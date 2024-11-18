<?php

namespace ZhMead\XmnkBikeControl\Weikemu\Maps;

class CmdMap
{
    // 控制指令
    const CMD_REMOTE_CONTROL = '06';
    // 远程查询
    const CMD_REMOTE_SELECT = '07';
    // 播放语言指令
    const CMD_REMOTE_VOICE = '0C';
    //远程设置参数
    const CMD_REMOTE_CONFIG = '08';

    /**
     * 控制指令（服务器下发）
     */
    //保留
//    const CONTROL_DEFAULT = '00';
    //远程设防(业务关锁)
    const CONTROL_REMOTE_CLOSE_LOCK = '01';
    // 远程撤防
    const CONTROL_REMOTE_CHE_FANG = '02';
    // 远程重启
    const CONTROL_REMOTE_CHONG_QI = '03';
    // 远程关机
    const CONTROL_REMOTE_GUAN_JI = '04';
    // 恢复出差设置
    const CONTROL_HUI_FU_CHU_CHANG = '05';
    // 保留
//    const CONTROL_REMOTE_NO = '06';
    // 保留
//    const CONTROL_REMOTE_NO = '07';
    // 立即定位
    const CONTROL_REMOTE_LOCATION = '08';
    //远程寻车(带寻车提示音)
    const CONTROL_REMOTE_FIND_BIKE = '09';
    // 远程重启蓝牙模块
    const CONTROL_REMOTE_REBOOT_LAN_YA = '0A';
    //远程开锁(业务开锁)
    const CONTROL_REMOTE_UNLOCK = '0B';
    //远程加锁
    const CONTROL_REMOTE_SHUT_UNLOCK = '0C';
    // 主动查询终端电池信息
    const CONTROL_GET_BATTERY_INFO = '0D';
    //主动查询控制器信息
    const CONTROL_GET_BOX_INFO = '0E';
    //远程打开电池锁
    const CONTROL_REMOTE_OPEN_BATTERY_LOCK = '0F';
    //远程关闭电池锁
    const CONTROL_REMOTE_CLOSE_BATTERY_LOCK = '10';
    //关闭远程寻车（待寻车提示音）
    const CONTROL_REMOTE_CLOSE_BELL = '11';
    //远程重启整个系统
    const CONTROL_REMOTE_REBOOT_SYSTEM = '12';
    //远程格式化主控制器
    const CONTROL_REMOTE_FORMAT_BOX = '13';
    //外部USB供电使能
    const CONTROL_REMOTE_USB_OK = '14';
    //外部USB供电失能
    const CONTROL_REMOTE_USB_NO = '15';
    //远程打开轮毂锁
    const CONTROL_REMOTE_OPEN_HUB_LOCK = '16';
    //远程关闭轮毂锁
    const CONTROL_REMOTE_CLOSE_HUB_LOCK = '17';

//    const CONTROL_REMOTE_CLOSE_LOCK = '18';
    //提示终端超出运营区域进行语音播报
    const CONTROL_OUT_AREA_PLAY_VOICE = '19';
    //提示终端进入运营区域关闭语音播放
    const CONTROL_OUT_AREA_CLOSE_VOICE = '1A';
    //出电子围栏直接失能ACC(仅限制在借车状态使用)
    const CONTROL_OUT_AREA_LOST_ELECTRIC = '1B';
    //入电子围栏直接使能ACC(仅限制在借车状态使用)
    const CONTROL_OUT_AREA_OPEN_ELECTRIC = '1C';
    // 打开后座
    const CONTROL_REMOTE_OPEN_BACKSEAT = '20';
    //关闭后座锁
    const CONTROL_REMOTE_CLOSE_BACKSEAT = '21';
    //远程临时关锁
    const CONTROL_REMOTE_TEMPORARY_CLOSE_LOCK = '30';
    //远程临时开锁
    const CONTROL_REMOTE_TEMPORARY_UNLOCK = '31';
    //进入运输模式
    const CONTROL_TRANSPORTATION_MODE = '32';
    //立即上传融合定位包
    const CONTROL_NOW_UP_LOCATION = '33';
}