<?php

namespace ZhMead\XmnkBikeControl\Gemili\Maps;

class VideoMap
{
//    开锁成功
    const OPEN1 = 1;
    //电量低换车
    const VOICE_LOWBATTERY = 2;
    //您在骑行区边缘，出区断电
    const VOICE_AREA = 3;
    //车辆未解锁
    const OPEN4 = 4;
    //寻车
    const OPEN5 = 5;
    //还车成功
    const OPEN6 = 6;
    //寻车
    const VOICE_CAR_RINGTONE = 7;
    //临时锁车成功
    const OPEN8 = 8;
    //不在还车点
    const VOICE_NO_PARKING = 9;

    //电车锁一打开
    const OPEN10 = 10;
    //电车锁以关闭
    const OPEN11 = 11;
//超区断电
    const VOICE_SUPER_OPERATIONAL_AREA = 12;

    //超速
    const VOICE_SPEED = 14;
    //警告
    const VOICE_WARNING = 15;
}