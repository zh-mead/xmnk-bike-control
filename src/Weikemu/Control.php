<?php

namespace ZhMead\XmnkBikeControl\Weikemu;

use GatewayClient\Gateway;
use Illuminate\Support\Facades\Redis;
use ZhMead\XmnkBikeControl\Common\ControlInterface;
use ZhMead\XmnkBikeControl\Common\Maps\UserRoleMap;
use ZhMead\XmnkBikeControl\Weikemu\Maps\VideoMap;

class Control implements ControlInterface
{
    //分割符
    const SPLIT_TAG = 'bbbb';
    const START_TAG = '42 44';
    public $isSync = false;
    public $userRoleTag = 'user';
    public $isDev = false;
    public $isAutoBikeStatusSync = false;
    private $registerAddress = '';
    private $bikeStatusSync = false;

    public function __construct($config, $bikeStatusSync, $isSync = false, $userRoleTag = UserRoleMap::USER, $otherConfig = [], $isDev = false)
    {
        $this->registerAddress = $config['registerAddress'];
        $this->isSync = $isSync;
        $this->userRoleTag = $userRoleTag;
        $this->bikeStatusSync = $bikeStatusSync;
        $this->isDev = $isDev;
        $this->isAutoBikeStatusSync = $otherConfig['isAutoBikeStatusSync'];
    }

    /**
     * 数组转字符
     * @return string
     * User: Mead
     */
    private static function arr2str()
    {
        $args = func_get_args();
        $arr = '';
        foreach ($args as $arg) {
            $arr .= implode('', $arg);
        }
        return implode(' ', str_split($arr, 2));
    }

    /**
     * 寻车响铃1
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function bell($box_no, $isSync = -1)
    {
        return $this->playVoice($box_no, VideoMap::VIDEO_BIKE_HERE, $isSync);
    }

    /**
     * 开车
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function openLock($box_no, $cacheOtherData = [], $isSync = -1)
    {
        if (is_array($cacheOtherData) && $this->isAutoBikeStatusSync) $this->bikeStatusSync->toBikeRideStatus($this->userRoleTag, $box_no, $cacheOtherData);

        $re = $this->electricSwitchControl($box_no, '01');
        if ($re) {
            $this->orderStatus($box_no, '01');
            $this->playVoice($box_no, VideoMap::VIDEO_OPEN_LOCK);
            return true;
        }
        return false;
    }

    /**
     * electricSwitchControl  电门控制
     *
     * @param $box_no
     * @param $control 01 电门开启; 00 电门关闭(关闭防盗开关)；02 电门关闭(开启防盗开关)；
     * @return bool
     * @author Fx
     *
     */
    private function electricSwitchControl($box_no, $control = "01")
    {
        $cmd = "2D";
        $msg_id = self::getMsgId();
        $body = [
            $msg_id,
            $control,// 01 电门开启; 00 电门关闭(关闭防盗开关)；02 电门关闭(开启防盗开关)；
        ];

        $send_msg = self::makeSendMsg($cmd, $body);

        return $this->send($box_no, $send_msg, false);
    }

    /**
     * 获取序号（msg_id）
     * @return string
     * Author: Mead
     */
    private static function getMsgId()
    {
        $msg_id = str_pad(dechex(rand(0, 65535)), 4, "0", STR_PAD_LEFT);
        return $msg_id;
    }

    /**
     * 组装命令
     * @param $cmd
     * @param $msgID
     * @return string
     * User: Mead
     */
    private static function makeSendMsg($cmd, $body, $msg_id = false, $length = false)
    {
        if (is_array($body)) {
            $body = self::arr2arr($body);
        }
        if (is_string($body)) {
            $body = str_split($body, 2);
        }


        if ($length !== false) {
            $length = str_pad($length, 4, '0', STR_PAD_LEFT);
        } else {
            $length = str_pad(dechex(count($body)), 4, '0', STR_PAD_LEFT);
        }

        if ($msg_id === false) {
            $msg_id = self::getMsgId();
        }
        $header = [
            $cmd,
            $msg_id,
            '00',
            $length
        ];
        $response = self::formatArr($header, $body);
        $response[] = self::verification($response);

        return self::format(self::START_TAG . implode('', $response));
    }

    /**
     * 格式数组
     * @param $arr
     * @return array
     * User: Mead
     */
    private static function arr2arr($arr)
    {
        $arr = implode('', $arr);
        return str_split(str_replace(' ', '', $arr), 2);
    }

    /**
     * 合并数组
     * @return array
     * User: Mead
     */
    private static function formatArr()
    {
        $args = func_get_args();
        $arr = '';
        foreach ($args as $arg) {
            $arr .= implode('', $arg);
        }
        return str_split($arr, 2);
    }

    /**
     * 校检
     * @param $data
     * @param $verification
     * @return bool
     * Author: Mead
     */
    protected static function verification($data)
    {
        $a = $b = 0;
        for ($i = 0; $i < count($data); $i++) {
            $a = $a + hexdec($data[$i]);
            $b = $a + $b;
        }
        return str_pad(substr(dechex($a), -2), 2, 0, STR_PAD_LEFT) . str_pad(substr(dechex($b), -2), 2, 0, STR_PAD_LEFT);
    }

    /**
     * 格式字符
     * @param $str
     * @return string
     * User: Mead
     */
    private static function format($str)
    {
        return strtoupper(implode('', str_split(str_replace(' ', '', $str), 2)));
    }

    /**
     * 发送数据
     * @param $box_no
     * @param $msg
     * @return bool
     * User: Mead
     */
    private function send($box_no, $msg, $isSync = -1, $msgId = false)
    {
        Gateway::$registerAddress = $this->registerAddress;
        if (!Gateway::isUidOnline($box_no)) return 0;

        if ($isSync === -1) {
            $isSync = $this->isSync;
        } else {
            $isSync = (bool)$isSync;
        }

        try {
            if ($this->isDev) var_dump($msg);
            Gateway::sendToUid($box_no, '+NNMI,' . hex2bin(str_replace(' ', '', $msg . '0D0A')));

            if ($isSync && $msgId) {
                //是否获取相应
                $response = false;

                for ($i = 0; $i <= 30; $i++) {
                    sleep(1);
                    if ($this->isDev) var_dump($i);
                    $data = $this->bikeStatusSync->getBikeBoxInfo(':' . $msgId);
                    if ($data) {
                        $response = $this->decodeData($data);
                        break;
                    }
                    if (in_array($i, [5, 10, 15, 20])) {
                        //重试一次
                        Gateway::sendToUid($box_no, '+NNMI,' . hex2bin(str_replace(' ', '', $msg . '0D0A')));
                    }
                }
                return $response;
            }
            return true;
        } catch (\Exception $exception) {
            throw new \Exception('服务连接失败');
        }
    }

    /**
     * 解析车辆返回数据
     * @param $data
     * @return mixed
     */
    private function decodeData($data, $decode = true)
    {
        return json_decode($data, true);
    }

    /**
     * orderStatus
     *
     * @param $box_no
     * @param $control string 01：接单开启；00：接单关闭
     * @return bool
     * @author Fx
     *
     */
    private function orderStatus($box_no, $control)
    {
        $cmd = "35";
        $body = [
            self::getMsgId(),
            $control,
        ];

        $send_msg = self::makeSendMsg($cmd, $body);

        return $this->send($box_no, $send_msg, false);
    }

    /**
     * 关锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function closeLock($box_no, $isSync = -1)
    {
        if ($this->isAutoBikeStatusSync) {
            $location = $this->bikeStatusSync->byBoxNoGetLocation($box_no);
            $this->bikeStatusSync->toBikeWaitRideStatus($box_no, $location['lng'], $location['lat']);
        }

        $re = $this->electricSwitchControl($box_no, '02');
        if ($re) {
            $this->orderStatus($box_no, '00');
            $this->playVoice($box_no, VideoMap::VIDEO_RETURN_BIKE);
            return true;
        }
        return false;
    }

    /**
     * 获取最后位置
     * @param $box_no
     * @return mixed
     */
    public function byBoxNoGetLocation($box_no)
    {
        return $this->bikeStatusSync->byBoxNoGetLocation($box_no);
    }

    /**
     * 临时关锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function temporaryCloseLock($box_no, $isSync = -1)
    {
        if ($this->isAutoBikeStatusSync) $this->bikeStatusSync->toBikeTemporaryWaitRideStatus($box_no);

        $re = $this->electricSwitchControl($box_no, '02');
        if ($re) {
            $this->playVoice($box_no, VideoMap::VIDEO_FIND_BIKE);
            return true;
        }
        return false;
    }

    /**
     * 临时开锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function temporaryOpenLock($box_no, $isSync = -1)
    {
        if ($this->isAutoBikeStatusSync) $this->bikeStatusSync->toBikeTemporaryRideStatus($box_no);

        $re = $this->electricSwitchControl($box_no, '01');
        if ($re) {
            $this->playVideo($box_no, VideoMap::VIDEO_KEEP_GOING);
            return true;
        }
        return false;
    }

    /**
     * 播放语音
     * @param $box_no
     * @param $video_cmd
     * @return bool
     * User: Mead
     */
    public function playVideo($box_no, $voice_no, $isSync = -1)
    {
        $cmd = "2A";
        $msgId = self::getMsgId();
        $body = [
            $msgId,
            '01',
            $voice_no,
            'FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF',
            'FF FF FF FF',
            'FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF',
            'FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF',
        ];

        $send_msg = self::makeSendMsg($cmd, $body);
        return $this->send($box_no, $send_msg, $isSync, $msgId);
    }

    /**
     * 打开电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function openBatteryLock($box_no, $isSync = -1)
    {
        $cmd = "2F";
        $msg_id = self::getMsgId();
        $body = [
            $msg_id,
            '01',
        ];

        $send_msg = self::makeSendMsg($cmd, $body);

        return $this->send($box_no, $send_msg, $isSync, $msg_id);
    }

    /**
     * 关闭电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function closeBatteryLock($box_no, $isSync = -1)
    {
        return false;
    }

    /**
     * 超出骑行区域播放音乐
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaPlayVideo($box_no, $isSync = -1)
    {
        return $this->playVideo($box_no, VideoMap::VIDEO_GO_BEYOND, $isSync);
    }

    /**
     * 超出骑行区域失能
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaLoseElectric($box_no, $isSync = -1)
    {
        $re = $this->electricSwitchControl($box_no, '00');
        if ($re) {
            $this->playVideo($box_no, VideoMap::VIDEO_GO_BEYOND);
            return true;
        }
        return false;
    }

    /**
     * 关闭超区失去电能
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function closeOutAreaLoseElectric($box_no, $isSync = -1)
    {
        if ($this->isAutoBikeStatusSync) $this->bikeStatusSync->toBikeGetElectric($box_no);
        return $this->outAreaGetElectric($box_no, $isSync);
    }

    /**
     * 超出区域后返回骑行区域加电
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaGetElectric($box_no, $isSync = -1)
    {
        $re = $this->electricSwitchControl($box_no, '01');
        if ($re) {
            $this->playVideo($box_no, VideoMap::VIDEO_KEEP_GOING);
            return true;
        }
        return false;
    }

    /**
     * 打开头盔锁
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function openHelmet($box_no, $isSync = -1)
    {
        return false;
    }

    /**
     * 关闭头盔锁
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function closeHelmet($box_no, $isSync = -1)
    {
        return false;
    }

//    /**
//     * 融合定位包
//     * @param $box_no
//     * @return bool
//     * Author: Mead
//     */
//    public function nowBikeUpLocation($box_no, $isSync = false)
//    {
//        $msg_id = $this->makeMsgId($box_no,$this->userRoleTag, CmdMap::CONTROL_NOW_UP_LOCATION);
//        //删除ridis位置缓存
//        $str = $this->makeSendMsg(CmdMap::CONTROL_NOW_UP_LOCATION, $msg_id);
//        return $this->send($box_no, $str, $isSync, $msg_id);
//    }

    /**
     * 查询头盔状态
     * @param $box_no
     * @return bool|mixed
     * @throws \Exception
     */
    public function selectHelmetStatus($box_no)
    {
        return 0;
    }

    /**
     * 关闭对车辆低电骑行限制
     * @param $box_no
     * @param $isSync
     * @return bool
     */
    public function closeLowElectricLimit($box_no, $isSync = -1)
    {
        $this->bikeStatusSync->toBikeNoElectric($box_no);
        return true;
    }

    /**
     * 车辆上线
     * @param $box_no
     * @param $isSync
     * @return bool
     */
    public function bikeOnLine($box_no, $lat = 0, $lng = 0, $isSync = -1)
    {
        $this->bikeStatusSync->toBikeOnLineStatus($box_no, $lng, $lat);
        return true;
    }

    /**
     * 车辆上线
     * @param $box_no
     * @param $isSync
     * @return bool
     */
    public function bikeOffLine($box_no, $isSync = -1)
    {
        $this->bikeStatusSync->toBikeOffLineStatus($box_no);
        return true;
    }

    /**
     * 查询车的状态?
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBikeStatus($box_no, $isSync = 1)
    {
        return $this->selectBoxSetting($box_no, '00001000');
    }

    /**
     * 查询车的配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBoxSetting($box_no, $str2 = '00000100')
    {
        $cmd = "2B";
        $msgId = self::getMsgId();
        $body = [
            $msgId,
            str_pad(base_convert($str2, 2, 16), 2, 0, STR_PAD_LEFT)
        ];

        $send_msg = self::makeSendMsg($cmd, $body);

        return $this->send($box_no, $send_msg, true, $msgId);
    }

    /**
     * 远程重启中控
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function rebootBox($box_no, $isSync = -1)
    {
        $cmd = "25";
        $msg_id = self::getMsgId();
        $body = [
            $msg_id,
            '01',// 0X02:重启升级;0X01:重启不升级
            'FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF',// ip  20 bytes
            'FF FF FF FF FF FF FF FF FF FF ', // PORT 10 BYTES
            'FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF', // 重启升级路径
            'FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF FF', // 重启升级文件名称
        ];

        $send_msg = self::makeSendMsg($cmd, $body);

        return $this->send($box_no, $send_msg, $isSync, $msg_id);
    }

    /**
     * 获取骑行订单信息
     * @param $box_no
     * @return mixed
     */
    public function getRideBikeOrderInfo($box_no)
    {
        return $this->bikeStatusSync->getRideBikeOrderInfo($box_no);
    }

    /**
     * 组装命令
     * @param $cmd
     * @param $msgID
     * @return string
     * User: Mead
     */

    /**
     * 立即上传电池信息
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeBatteryMSG($box_no, $isSoc = false, $isSync = -1)
    {
        return $this->nowBikeLocation($box_no, $isSync);
    }

    /**
     * 立即定位
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeLocation($box_no, $isSync = -1)
    {
        //todo:删除ridis位置缓存
        $this->delRedisCache($box_no, 'update_bike_location');

        //00000001
        return $this->selectBoxSetting($box_no, '00000001');
    }

    /**
     * 删除缓存
     * @param $box_no
     * @param $type
     * User: Mead
     */
    private function delRedisCache($box_no, $types)
    {
        $cacheNames = [];
        if (is_array($types)) {
            foreach ($types as $type) {
                $cacheNames[] = "cache:min:{$type}:{$box_no}";
            }
        } else {
            $cacheNames[] = "cache:min:{$types}:{$box_no}";
        }
        if (!count($cacheNames)) return false;
        $this->bikeStatusSync->delKeys($cacheNames);
    }

    /**
     * 参数配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function setBoxSetting($box_no, $setting = [], $isSync = -1)
    {
        $cmd = "2C";
        $auto_lock_time = str_pad(base_convert($setting['auto_lock_time'], 10, 2), 7, 0, STR_PAD_LEFT); // 自动落锁时长，量化单位1分钟

        $msg_id = self::getMsgId();
        $body = [
            $msg_id,
            'FF',// 运动状态，位置采样间隔设置(默认1秒)
            str_pad(base_convert($setting['motion_position_reporting_interval'], 10, 16), 2, 0, STR_PAD_LEFT),// 运动状态，位置上报间隔设置
            str_pad(base_convert($setting['static_position_reporting_interval'], 10, 16), 4, 0, STR_PAD_LEFT),// 静止状态，位置上报间隔设置
            'FF',// 位移告警
            'FF',// 震动告警
            'FF',// 温度告警
            'FF',// 超速告警
            str_pad(base_convert($auto_lock_time . '1', 2, 16), 2, 0, STR_PAD_LEFT),// 自动落锁
            'FF FF',
            //str_pad(base_convert($arr['port'], 10, 16), 4, 0, STR_PAD_LEFT),// port
            'FF FF FF FF',
            // str_pad(base_convert($arr['ip'], 10, 16), 8, 0, STR_PAD_LEFT),// ip
            'FF FF FF FF FF FF FF FF FF FF',// 20位
            'FF FF FF FF ',//
            'FF FF FF FF FF FF'
        ];

        $send_msg = self::makeSendMsg($cmd, $body);
        return $this->send($box_no, $send_msg, $isSync, $msg_id);
    }

    /**
     * 查询车的服务器的地址
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBoxServerUrl($box_no)
    {
        $cmd = "2B";

        $msg_id = self::getMsgId();
        $body = [
            $msg_id,
            str_pad(base_convert('01000000', 2, 16), 2, 0, STR_PAD_LEFT)
        ];

        $send_msg = self::makeSendMsg($cmd, $body, $msg_id);
        return $this->send($box_no, $send_msg, true, $msg_id);
    }

    /**
     * 配置服务器的地址
     * @param $box_no
     * @param $setting
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function setBoxServerUrl($box_no, $server = '', $isSync = -1)
    {
        $serverArr = explode(':', $server);
        $url = $serverArr[0];
        $port = $serverArr[1];
        $cmd = "3D";
        $msg_id = self::getMsgId();
        $body = [
            $msg_id,
            str_pad(base_convert($port, 10, 16), 4, 0, STR_PAD_LEFT),
            bin2hex($url)
        ];
        $send_msg = self::makeSendMsg($cmd, $body);
        return $this->send($box_no, $send_msg, true);
    }

    /**
     * 配置车辆速度
     * @param $box_no
     * @param $setting
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function setBikeSpeedLimit($box_no, $speed = 7, $isSync = -1)
    {
        return false;
    }

    /**
     * 发送自定义数据
     * @param $box_no
     * @param $cmd
     * @param $param
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function sendCustomParam($box_no, $cmd, $param, $isSync = -1)
    {
        $msg_id = self::getMsgId();
        $send_msg = self::makeSendMsg($cmd, $param, $msg_id);
        return $this->send($box_no, $send_msg, $isSync, $msg_id);
    }
}
