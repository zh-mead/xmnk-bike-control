<?php

namespace ZhMead\XmnkBikeControl\Xiaoan;

use GatewayClient\Gateway;
use ZhMead\XmnkBikeControl\Common\ControlInterface;
use ZhMead\XmnkBikeControl\Common\Maps\UserRoleMap;
use ZhMead\XmnkBikeControl\Xiaoan\Maps\CmdMap;
use ZhMead\XmnkBikeControl\Xiaoan\Maps\VideoMap;

class Control implements ControlInterface
{
    //分割符
    const SPLIT_TAG = '';
    const START_TAG = 'aa55';
    const CMD_WILD = '00';

    private $registerAddress = '';
    private $bikeStatusSync = false;
    public $isSync = false;
    public $userRoleTag = 'user';
    public $isDev = false;
    public $isAutoBikeStatusSync = false;

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
     * 寻铃
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function bell($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_CONTROL_VOICE_BROADCAST;
        $param = [
            'idx' => VideoMap::CAR_SEARCH_SOUND,
            'volume' => 70
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 开锁
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function openLock($box_no, $cacheOtherData = [], $isSync = -1)
    {
        if (is_array($cacheOtherData) && $this->isAutoBikeStatusSync) $this->bikeStatusSync->toBikeRideStatus($this->userRoleTag, $box_no, $cacheOtherData);

        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 1,
            'volume' => 70
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 关锁
     * @param $box_no
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function closeLock($box_no, $isSync = -1)
    {
        if ($this->isAutoBikeStatusSync) {
            $location = $this->bikeStatusSync->byBoxNoGetLocation($box_no);
            $this->bikeStatusSync->toBikeWaitRideStatus($box_no, $location['lng'], $location['lat']);
        }

        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 1,
            'volume' => 70
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
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
        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 1,
            'force' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
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
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 打开电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function openBatteryLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_OPEN_BATTERY_COMPARTMENT_LOCK;
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 关闭电池锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function closeBatteryLock($box_no, $isSync = -1)
    {
        return true;
    }

    /**
     * 超出骑行区域播放音乐
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaPlayVideo($box_no, $isSync = -1)
    {
        return $this->playVideo($box_no, VideoMap::NEAR_SERVICE_AREA_SOUND, $isSync);
    }

    /**
     * 播放语音
     * @param $box_no
     * @param $video_cmd
     * @return bool
     * User: Mead
     */
    public function playVideo($box_no, $video_cmd, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_CONTROL_VOICE_BROADCAST;
        $param = [
            'idx' => $video_cmd
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 超出骑行区域失能
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaLoseElectric($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 0
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 超出区域后返回骑行区域加电
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function outAreaGetElectric($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
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
        $cmd = CmdMap::COMMAND_STARTORSTOP_VEHICLE;
        $param = [
            'acc' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
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
        $cmd = CmdMap::COMMAND_SWITCH_HELMET_LOCK;
        $param = [
            'sw' => 0
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
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
        $cmd = CmdMap::COMMAND_SWITCH_HELMET_LOCK;
        $param = [
            'sw' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 查询头盔状态
     * @param $box_no
     * @return bool|mixed
     * @throws \Exception
     */
    public function selectHelmetStatus($box_no)
    {
        $location = $this->bikeStatusSync->byBoxNoGetLocation($box_no);
        if (isset($location['isHelmetUnlock'])) return $location['isHelmetUnlock'];
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
     * 获取骑行订单信息
     * @param $box_no
     * @return mixed
     */
    public function getRideBikeOrderInfo($box_no)
    {
        return $this->bikeStatusSync->getRideBikeOrderInfo($box_no);
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
     * 查询车的配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBoxSetting($box_no, $setting = [])
    {
//        $cmd = CmdMap::COMMAND_OBTAIN_CONTROLLER_DATA;
        $cmd = CmdMap::COMMAND_QUERY_INTERNAL_PARAMETERS;
        $param = [];
        return $this->send($box_no, $cmd, $param, true);
    }

    /**
     * 查询车的服务器的地址
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function selectBoxServerUrl($box_no)
    {
        $cmd = CmdMap::COMMAND_QUERY_SERVER_ADDRESS;
        $param = [];
        return $this->send($box_no, $cmd, $param, true);
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
        $cmd = CmdMap::COMMAND_MODIFY_SERVER_ADDRESS;
        $param = [
            'server' => $server
        ];
        return $this->send($box_no, $cmd, $param, true);
    }

    /**
     * 配置车辆速度
     * @param $box_no
     * @param $setting
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    public function setBikeSpeedLimit($box_no, $speed = '100', $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_SET_CONTROLLER_SPEED_LIMIT;
        $param = [
            'speed' => $speed
        ];
        return $this->send($box_no, $cmd, $param, true);
    }

    /**
     * 查询车的状态
     * @param $box_no
     * @return
     * User: Mead
     *??
     */
    public function selectBikeStatus($box_no, $setting = [], $isSync = 1)
    {
        $cmd = CmdMap::COMMAND_QUERY_DEVICE_STATUS_INFO;
        $param = [];
        return self::send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 远程重启中控
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function rebootBox($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_DEVICE_RESTART;
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 立即定位
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeLocation($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_QUERY_DEVICE_STATUS_INFO;
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 立即上传电池信息
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function nowBikeBatteryMSG($box_no, $isSoc = 0, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_QUERY_DEVICE_STATUS_INFO;
        if ($isSoc) {
            $cmd = CmdMap::COMMAND_OBTAIN_BMS_REALTIME_DATA;
        }
        $param = [];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 参数配置
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function setBoxSetting($box_no, $setting = [], $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_SET_INTERNAL_PARAMETERS;

        $param = $setting;
        if (array_key_exists('freq', $setting)) {
            $param['freq_move'] = $setting['freq'];
            unset($param['freq']);
        }

        if (array_key_exists('server', $setting)) {
            $p['server'] = $setting['server'];
            unset($param['server']);
//            self::send($box_no, CmdMap::COMMAND_MODIFY_SERVER_ADDRESS, $p);
            $this->setBoxServerUrl($box_no, $p['server']);
        }

        if (array_key_exists('maxecuspeed', $setting)) {
            $index = 7;
            $p2['speed'] = 100 - ($index - $setting['maxecuspeed']) * 5;
            unset($param['maxecuspeed']);
            $this->setBikeSpeedLimit($box_no, $p2['speed']);
//            self::setBikeSpeedLimit($box_no, CmdMap::COMMAND_SET_CONTROLLER_SPEED_LIMIT, $p2);
        }

        if (count($param)) {
            return self::send($box_no, $cmd, $param);
        }
        return true;
    }

    /**
     * 远程撤防
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function cheFang($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 0
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 远程加锁
     * @param $box_no
     * @return bool
     * User: Mead
     */
    public function addLock($box_no, $isSync = -1)
    {
        $cmd = CmdMap::COMMAND_ANTITHEFT_SWITCH;
        $param = [
            'defend' => 1
        ];
        return $this->send($box_no, $cmd, $param, $isSync);
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
        return $this->send($box_no, $cmd, $param, $isSync);
    }

    /**
     * 发送数据包
     * @param $box_no
     * @param $cmd
     * @param $param
     * @param $isSync
     * @return bool|mixed
     * @throws \Exception
     */
    private function send($box_no, $cmd, $param, $isSync = -1)
    {
        $msg_id = self::getRandHex();
        $msg = self::encode($cmd, $param, $msg_id);

        Gateway::$registerAddress = $this->registerAddress;
        if (!Gateway::isUidOnline($box_no)) return 0;

        if ($isSync === -1) {
            $isSync = $this->isSync;
        } else {
            $isSync = (bool)$isSync;
        }

        try {
            if ($this->isDev) var_dump($msg);
            Gateway::sendToUid($box_no, hex2bin($msg));
            if ($isSync) {
                //是否获取相应
                $response = false;

                for ($i = 0; $i <= 30; $i++) {
                    sleep(1);
                    if ($this->isDev) var_dump($i . "==>cmd:{$box_no}:{$msg_id}");

                    $data = $this->bikeStatusSync->getBikeBoxInfo(":cmd:{$box_no}:{$msg_id}");
                    if ($data) {
                        $response = $this->decodeData($data);
                        break;
                    }
                    if (in_array($i, [5, 10, 15, 20])) {
                        //重试一次
                        Gateway::sendToUid($box_no, hex2bin($msg));
                    }
                }
                return $response;
            }
        } catch (\Exception $exception) {
            throw new \Exception('服务连接失败');
        }

        return true;
    }

    /**
     * 编码
     * @param $data
     * @return string
     * User: Mead
     */
    private function encode($cmd, $param = [], $randHex = false)
    {
        $body = [
            'c' => $cmd,
            'param' => $param
        ];

        $bodyStr = json_encode($body, true);
        $bodyArrHex = $this->str2arr(bin2hex($bodyStr));
        $bodyNumsHex = $this->byNumGetDataLength(count($bodyArrHex));

        if ($randHex === false) {
            $randHex = $this->getRandHex();
        }

        $msg = [
            self::START_TAG,
            self::CMD_WILD,
            $randHex,
            $bodyNumsHex,
            implode('', $bodyArrHex)
        ];

        $msg = implode('', $msg);
        return $msg;
    }

    /**
     * 格式数组
     * @param $arr
     * @return array
     * User: Mead
     */
    private function str2arr($str)
    {
        return str_split($str, 2);
    }

    /**
     * 获取数据包的长度
     * @param $num
     * @return string
     * User: Mead
     */
    private function byNumGetDataLength($num)
    {
        $length = dechex($num);
        return str_pad($length, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 获取数据包的随机码
     * @param $num
     * @return string
     * User: Mead
     */
    private function getRandHex()
    {
        $length = dechex(rand(0, 100));
        return str_pad($length, 2, '0', STR_PAD_LEFT);
    }

    /**
     * 解密信息
     * @param $str
     * @return mixed
     * Author: Mead
     */
    private function decodeData($str)
    {
        return json_decode($str, true);
    }

    /**
     * 删除缓存
     * @param $box_no
     * @param $type
     * User: Mead
     */
    public function delRedisCache($box_no, $types)
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
//        $this->redis->del($cacheNames);
        $this->bikeStatusSync->delKeys($cacheNames);
    }
}
