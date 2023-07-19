<?php

namespace ZhMead\XmnkBikeControl\Common;

interface ControlInterface
{

    /**
     * 寻铃
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function bell($box_no, $isSync);

    /**
     * 开锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function openLock($box_no, $cacheOtherData = [], $isSync);

    /**
     * 关锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function closeLock($box_no, $isSync);

    /**
     * 临时关锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function temporaryCloseLock($box_no, $isSync);

    /**
     * 临时开锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function temporaryOpenLock($box_no, $isSync);

    /**
     * 打开电池锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function openBatteryLock($box_no, $isSync);

    /**
     * 关闭电池锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function closeBatteryLock($box_no, $isSync);

    /**
     * 播放超区语音
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function outAreaPlayVideo($box_no, $isSync);

    /**
     * 播放语音
     * @param $box_no
     * @param $video_cmd
     * @param $isSync
     * @return mixed
     */
    public function playVideo($box_no, $video_cmd, $isSync);

    /**
     * 超区断电
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function outAreaLoseElectric($box_no, $isSync);

    /**
     * 进区供电
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function outAreaGetElectric($box_no, $isSync);

    /**
     * 关闭超区断电功能
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function closeOutAreaLoseElectric($box_no, $isSync);

    /**
     * 关闭低电锁车限制
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function closeLowElectricLimit($box_no, $isSync);

    /**
     * 车辆上线
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function bikeOnLine($box_no, $lat, $lng, $isSync);

    /**
     * 车辆下线
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function bikeOffLine($box_no, $isSync);

    /**
     * 重启中控
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function rebootBox($box_no, $isSync);

    /**
     * 打开头盔锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function openHelmet($box_no, $isSync);

    /**
     * 关闭头盔锁
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function closeHelmet($box_no, $isSync);

    /**
     * 查询头盔锁的状态
     * @param $box_no
     * @return mixed
     */
    public function selectHelmetStatus($box_no);

    /**
     * 查询车辆配置
     * @param $box_no
     * @param $setting
     * @param $isSync
     * @return mixed
     */
    public function selectBoxSetting($box_no, $setting = []);

    /**
     * 查询服务地址
     * @param $box_no
     * @return mixed
     */
    public function selectBoxServerUrl($box_no);

    /**
     * 查询车辆状态
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function selectBikeStatus($box_no, $isSync);

    /**
     * 立即定位
     * @param $box_no
     * @param $isSync
     * @return mixed
     */
    public function nowBikeLocation($box_no, $isSync);

    /**
     * 立即获取电量信息
     * @param $box_no
     * @param $isSoc
     * @param $isSync
     * @return mixed
     */
    public function nowBikeBatteryMSG($box_no, $isSoc = 0, $isSync);

    /**
     * 设置中控参数
     * @param $box_no
     * @param $setting
     * @param $isSync
     * @return mixed
     */
    public function setBoxSetting($box_no, $setting = [], $isSync);

    /**
     * 设置中控服务地址
     * @param $box_no
     * @param $server
     * @param $isSync
     * @return mixed
     */
    public function setBoxServerUrl($box_no, $server, $isSync);

    /**
     * 设置车辆速度
     * @param $box_no
     * @param $speed
     * @param $isSync
     * @return mixed
     */
    public function setBikeSpeedLimit($box_no, $speed, $isSync);

    /**
     * 获取骑行订单
     * @param $box_no
     * @return mixed
     */
    public function getRideBikeOrderInfo($box_no);

    /**
     * 获取最后定位信息
     * @param $box_no
     * @return mixed
     */
    public function byBoxNoGetLocation($box_no);

    /**
     * 自定义发送数据
     * @param $box_no
     * @param $cmd
     * @param $param
     * @param $isSync
     * @return mixed
     */
    public function sendCustomParam($box_no, $cmd, $param, $isSync);
}
