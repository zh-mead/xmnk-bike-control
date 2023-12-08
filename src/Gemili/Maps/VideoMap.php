<?php

namespace ZhMead\XmnkBikeControl\Gemili\Maps;

class VideoMap
{
    //车辆撤防提示音
    const VOICE_VEHICLE_DISARM = 1;

    //低电量提醒
    const VOICE_LOW_BATTERY_REMINDER = 2;

    //出圈提示音
    const VOICE_RING_OUT_PROMPT = 3;

    //车辆告警提示音
    const VOICE_VEHICLE_ALARM = 4;

    //工作人员找车
    const VOICE_STAFF_SEARCH_CAR = 5;

    //车辆设防提示音
    const VOICE_VEHICLE_DEFENSE = 6;

    //寻车提示音
    const VOICE_CAR_SEARCH_PROMPT = 7;

    //临时锁车提示音
    const VOICE_TEMPORARY_LOCK_PROMPT = 8;

    //站点外停车提示
    const VOICE_PARK_REMINDER_OUTSIDE = 9;

    //电池锁打开
    const VOICE_BATTERY_LOCK_OPEN = 10;

    //电池锁关闭
    const VOICE_BATTERY_LOCK_CLOSD = 11;

    //离开服务器断电提示
    const VOICE_POWER_OFF_PROMPT_LEAV_SERVER = 12;

    //定制预留
    const VOICE_CUSTOMIZED_RESERVATION = 13;

    //超速提示
    const VOICE_OVERSPEED_REMINDER = 14;

    //车铃音
    const VOICE_CAR_RINGTONE = 15;

    //超运营区
    const VOICE_SUPER_OPERATIONAL_AREA = 16;

    //进入停车区
    const VOICE_ENTER_PARK_AREA = 17;

    //离开停车区
    const VOICE_LEAVE_PARK_AREA = 18;

    //低电量
    const VOICE_LOWBATTERY = 19;

    //电量耗尽
    const VOICE_DEPLETED_BATTERY = 20;

    //禁止载人骑行
    const VOICE_PROHIBIT_RID_PEOPLE = 21;

    //车辆恢复供电
    const VOICE_VEHIVLE_RESTORATION_POWER = 22;

    //请把车头朝马路，垂直马路90度，规范停好后再还车
    const VOICE_CAR_TOWARD_ROAD_PARK_CAR = 23;

    //请佩戴头盔
    const VOICE_WEAR_HELMET = 24;

    //头盔已佩戴
    const VOICE_HELMET_WORN = 25;

    //您已进入RFID测试站点
    const VOICE_ENTER_RFID_TEST_SITE = 26;

    //离开RFID测试站点
    const VOICE_LEAVE_RFID_TEST_SITE = 27;

    //靠近运营区边界，请及时返回
    const VOICE_RETURN_TIMELY_MANNER = 28;

    //沙滩车剩余不足5分钟合理规划时间
    const VOICE_PLAN_5MINUTES_TIME_REASONABLY = 29;

    //沙滩车不足3分钟及时返航
    const VOICE_3MINUTES_TIMELY_MANNER = 30;

    //沙滩车不足8分钟合理规划时间
    const VOICE_PLAN_8MINUTES_TIME_REASONABLY = 31;

    //沙滩车不足5分钟及时返航
    const VOICE_5MINUTES_TIMELY_MANNER = 32;

    //车辆被暂用
    const VOICE_VEHICLE_TEMPORARILY_USED = 33;

    //进入RFID识别区
    const VOICE_ENTER_RFID_IDENTIFICATION_AREA = 34;

    //请先锁好头盔再还车
    const VOICE_LOCK_HELMET_RETURN_CAR = 35;

    //头盔锁已打开
    const VOICE_HELMET_LOCK_OPEN = 36;

    //头盔锁已关闭
    const VOICE_HELMET_LOCK_CLOSE = 37;

    //头盔未锁到位，请取出头盔重新锁好头盔再还车
    const VOICE_HELMET_UNLOCK_PLACE = 38;

    //卡片操作相关语音50-70
    const VOICE_CARD_OPERATION_RELAETD = 50;
}