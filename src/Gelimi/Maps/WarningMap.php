<?php

namespace ZhMead\XmnkBikeControl\Gemili\Maps;

class WarningMap
{
    //出围栏告警（出服务区但是没有出断电区，
    //在语音提示区域,一直在该区域每隔30秒上报一次）
    const WARNING_FENCING = 1;

    //入围栏告警（从提示区回到服务区）
    const WARNING_ENTER_FENCE = 2;

    //非法移动告警
    const WARNING_LLLEGAL_MOVEMENT = 3;

    //低电压通知
    const WARNING_LOW_VOLTAGE_MSG = 4;

    //严重低电压告警
    const WARNING_SEVERE_LOW_VOLTAGE = 5;

    //电源断电通知
    const WARNING_POWER_OUTAGE_MSG = 6;

    //电门打开通知
    const WARNING_SWITCH_ON_MSG = 7;

    //电门关闭通知
    const WARNING_SWITCH_OFF_MSG = 8;

    //电池仓锁打开通知
    const WARNING_BATTERY_LOCK_OPEN_MSG = 9;

    //电池仓锁关闭通知
    const WARNING_BATTERY_LOCK_CLOSE_MSG = 10;

    //电源接通通知
    const WARNING_POWER_MSG = 11;

    //震动告警
    const WARNING_PULSATOR = 12;

    //自动落锁
    const WARNING_AUTO_LOCK = 13;

    //出断围栏电圈报警
    //（一直在断电圈时每隔30秒上报一次）
    const WARNING_BREACK_FENCE_COIL = 14;

    //头盔锁打开
    const WARNING_HELMET_LOCK_OPEN = 15;

    //头盔锁关闭
    const WARNING_HELMET_LOCK_CLOSE = 16;

    //头盔取出
    const WARNING_HELMET_REMOVEL = 17;

    //头盔锁入
    const WARNING_HELMET_LOCK = 18;

    //检测到载人骑行
    const WARNING_DETECTED_MANNED_CYCLING = 19;

    //载人骑行断电后车辆恢复供电
    const WARNING_RESTORED_VEHICLES_POWER = 20;

    //沙滩车打开补电通知
    const WARNING_CAR_POWER_MSG = 21;

    //沙滩车关闭补电通知
    const WARNING_CAR_CLOSE_POWER_MSG = 22;

    //imu角度异常变化进行纠正
    const WARNING_iMU_ANGLE = 23;

    //进入运输模式
    const WARNING_ENTER_TRANSPORTAION_MODE = 24;

    //陀螺仪完成零偏校正
    const WARNING_COMPLSTE_ZERO_DEVIATION_CORRECTION = 25;
}