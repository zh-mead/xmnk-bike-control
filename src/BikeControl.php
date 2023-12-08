<?php

namespace ZhMead\XmnkBikeControl;

use ZhMead\XmnkBikeControl\Common\BikeStatusSync;
use ZhMead\XmnkBikeControl\Common\Maps\DeviceMap;
use ZhMead\XmnkBikeControl\Common\Maps\UserRoleMap;


/**
 * 车辆控制
 * @method bool bell(string $box_no, bool $isSync = false)
 * @method bool openLock(string $box_no, $cacheOtherData = [], bool $isSync = false)
 * @method bool closeLock(string $box_no, bool $isSync = false)
 * @method bool temporaryCloseLock(string $box_no, bool $isSync = false)
 * @method bool temporaryOpenLock(string $box_no, bool $isSync = false)
 * @method bool openBatteryLock(string $box_no, bool $isSync = false)
 * @method bool closeBatteryLock(string $box_no, bool $isSync = false)
 * @method bool outAreaPlayVideo(string $box_no, bool $isSync = false)
 * @method bool playVideo(string $box_no, $video_cmd, bool $isSync = false)
 * @method bool outAreaLoseElectric(string $box_no, bool $isSync = false)
 * @method bool outAreaGetElectric(string $box_no, bool $isSync = false)
 * @method bool closeOutAreaLoseElectric(string $box_no, bool $isSync = false)
 * @method bool closeLowElectricLimit(string $box_no, bool $isSync = false)
 * @method bool bikeOnLine(string $box_no, $lat, $lng, bool $isSync = false)
 * @method bool bikeOffLine(string $box_no, bool $isSync = false)
 * @method bool rebootBox(string $box_no, bool $isSync = false)
 * @method bool openHelmet(string $box_no, bool $isSync = false)
 * @method bool cloneHelmet(string $box_no, bool $isSync = false)
 * @method mixed selectHelmetStatus(string $box_no, bool $isSync = false)
 * @method mixed selectBoxSetting(string $box_no, array $setting = [])
 * @method mixed selectBoxServerUrl(string $box_no)
 * @method mixed selectBikeStatus(string $box_no, bool $isSync = false)
 * @method bool nowBikeLocation(string $box_no, bool $isSync = false)
 * @method bool nowBikeBatteryMSG(string $box_no, bool $isSoc, bool $isSync = false)
 * @method bool setBoxSetting(string $box_no, array $setting = [], bool $isSync = false)
 * @method bool setBoxServerUrl(string $box_no, string $server, bool $isSync = false)
 * @method bool setBikeSpeedLimit(string $box_no, mixed $speed, bool $isSync = false)
 * @method mixed getRideBikeOrderInfo(string $box_no)
 * @method mixed byBoxNoGetLocation(string $box_no)
 * @method mixed sendCustomParam(string $box_no, string $cmd, array $param, bool $isSync = false)
 *
 */
class BikeControl
{
    /**
     * @var
     */
    protected $controls;
    protected $control;
    protected $controlKeys = [];
    protected $defaultGateway;
    protected $redis = false;
    protected $bikeStatusSyncModel = false;

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $configs = [])
    {
        if (count($configs)) $this->init($configs);
    }

    /**
     * 初始化配置
     * @param array $configs
     * @return $this
     * @throws \Exception
     */
    public function init(array $configs)
    {
        $this->controlKeys = [];
        //连接redis
        try {
            $this->redis = new \Redis();
            $this->redis->connect($configs['redis']['host'], $configs['redis']['port']);
            if (!empty($configs['redis']['password'])) {
                $this->redis->auth($configs['redis']['password']);
            }
            $this->redis->select($configs['redis']['database']);
        } catch (\Exception $exception) {
            throw new \Exception('Redis链接失败');
        }

        //是否同步获取结果
        $isSyncCmd = false;
        if (array_key_exists('isSyncCmd', $configs)) $isSyncCmd = $configs['isSyncCmd'];

        $userRoleTag = UserRoleMap::USER;
        if (array_key_exists('userRoleTag', $configs)) $userRoleTag = $configs['userRoleTag'];

        $isDev = false;
        if (array_key_exists('isDev', $configs)) $isDev = $configs['isDev'];

        if (!array_key_exists('registerGateways', $configs)) throw new  \Exception('registerGateways找不到该配置项');

        $bikeStatusSync = new BikeStatusSync($this->redis);
        $this->bikeStatusSyncModel = $bikeStatusSync;

        $otherConfig = [
            'isAutoBikeStatusSync' => true
        ];
        if (array_key_exists('isAutoBikeStatusSync', $configs)) $otherConfig['isAutoBikeStatusSync'] = $configs['isAutoBikeStatusSync'];

        $gateways = $configs['registerGateways'];
        if (array_key_exists(DeviceMap::TBit, $gateways)) {
            try {
                $this->controls[DeviceMap::TBit] = new \ZhMead\XmnkBikeControl\Tbit\Control($gateways[DeviceMap::TBit], $bikeStatusSync, $isSyncCmd, $userRoleTag, $otherConfig, $isDev);
                $this->controlKeys[] = DeviceMap::TBit;
            } catch (\Exception $exception) {
                $this->controls[DeviceMap::TBit] = $exception->getMessage();
                $this->controlKeys[] = DeviceMap::TBit;
            }
        }
        if (array_key_exists(DeviceMap::XiaoAn, $gateways)) {
            try {
                $this->controls[DeviceMap::XiaoAn] = new \ZhMead\XmnkBikeControl\Xiaoan\Control($gateways[DeviceMap::XiaoAn], $bikeStatusSync, $isSyncCmd, $userRoleTag, $otherConfig, $isDev);
                $this->controlKeys[] = DeviceMap::XiaoAn;
            } catch (\Exception $exception) {
                $this->controls[DeviceMap::XiaoAn] = $exception->getMessage();
                $this->controlKeys[] = DeviceMap::XiaoAn;
            }
        }
        if (array_key_exists(DeviceMap::WeiKeMu, $gateways)) {
            try {
                $this->controls[DeviceMap::WeiKeMu] = new \ZhMead\XmnkBikeControl\Xiaoan\Control($gateways[DeviceMap::WeiKeMu], $bikeStatusSync, $isSyncCmd, $userRoleTag, $otherConfig, $isDev);
                $this->controlKeys[] = DeviceMap::WeiKeMu;
            } catch (\Exception $exception) {
                $this->controls[DeviceMap::WeiKeMu] = $exception->getMessage();
                $this->controlKeys[] = DeviceMap::WeiKeMu;
            }
        }
        if (array_key_exists(DeviceMap::Yiqaing, $gateways)) {
            try {
                $this->controls[DeviceMap::Yiqaing] = new \ZhMead\XmnkBikeControl\Yiqiang\Control($gateways[DeviceMap::Yiqaing], $bikeStatusSync, $isSyncCmd, $userRoleTag, $otherConfig, $isDev);
                $this->controlKeys[] = DeviceMap::Yiqaing;
            } catch (\Exception $exception) {
                $this->controls[DeviceMap::Yiqaing] = $exception->getMessage();
                $this->controlKeys[] = DeviceMap::Yiqaing;
            }
        }
        if (array_key_exists(DeviceMap::Gemili, $gateways)) {
            try {
                $this->controls[DeviceMap::Gemili] = new \ZhMead\XmnkBikeControl\Gemili\Control($gateways[DeviceMap::Gemili], $bikeStatusSync, $isSyncCmd, $userRoleTag, $otherConfig, $isDev);
                $this->controlKeys[] = DeviceMap::Gemili;
            } catch (\Exception $exception) {
                $this->controls[DeviceMap::Gemili] = $exception->getMessage();
                $this->controlKeys[] = DeviceMap::Gemili;
            }
        }

        if (array_key_exists('numGatewayMaps', $configs)) $this->controlKeys = $configs['numGatewayMaps'];

        if (!count($this->controlKeys)) {
            throw new \Exception('必须配置一个中控');
        }

        if (!array_key_exists('defaultGateway', $configs)) $configs['defaultGateway'] = $this->controlKeys[0];
        $this->defaultGateway = $configs['defaultGateway'];

        return $this;
    }

    /**
     * 切换中控类型
     * @param $type
     * @return $this
     */
    public function device($type)
    {
        if (is_numeric($type)) {
            if (count($this->controlKeys) <= $type) throw new \Exception('匹配不到对应的厂商');
            $type = $this->controlKeys[$type];
        }

        if (!in_array($type, $this->controlKeys)) throw new \Exception('找不到该厂商');
        if ($type == DeviceMap::NO) throw new \Exception('没有匹配到高厂商');

        if (!count($this->controlKeys)) throw new \Exception('必须配置一个中控');

        $this->control = $this->controls[$type];
        return $this;
    }

    /**
     * 这是参数
     * @param $property
     * @param $val
     * @return $this
     * @throws \Exception
     */
    public function setControlProperty($property, $val)
    {
        if (!count($this->controlKeys)) throw new \Exception('必须配置一个中控');
        if ($this->control) {
            $this->control->{$property} = $val;
            return $this;
        }
        $this->controls[$this->defaultGateway]->$property = $val;
        return $this;
    }

    /**
     * @return BikeStatusSync
     */
    public function bikeStatusSyncModel()
    {
        return $this->bikeStatusSyncModel;
    }

    public function __call($method, $parameters)
    {
        if (!count($this->controlKeys)) throw new \Exception('必须配置一个中控');
        if ($this->control) {
            return $this->control->$method(...$parameters);
        }
        return $this->controls[$this->defaultGateway]->$method(...$parameters);
    }

    public function __get($property)
    {
        if (!count($this->controlKeys)) throw new \Exception('必须配置一个中控');
        if ($this->control) {
            return $this->control->$property;
        }
        return $this->controls[$this->defaultGateway]->$property;
    }
}
