<?php

namespace ZhMead\XmnkBikeControl\Weikemu\Maps;

class VideoMap
{
    //    还车成功，请规范停车
    const VIDEO_RETURN_BIKE = '01';
//   开锁成功
    const VIDEO_OPEN_LOCK = '02';
//    请文明骑行规范停车
    const VIDEO_CIVILIZATION = '03';
//    车辆已断电请回到服务区
    const VIDEO_POWER_FAILURE = '04';
//    车辆已下线
    const VIDEO_OFFLINE = '05';
//    非法移动
    const VIDEO_ILLEGAL_MOVE = '06';
//    临停成功将持续计费
    const VIDEO_FIND_BIKE = '07';
//    向着诗和远方我们继续出发吧
    const VIDEO_KEEP_GOING = '08';
//    小喵在这里
    const VIDEO_BIKE_HERE = '09';
//    靠近服务区边缘，服务区外将断电 10
    const VIDEO_BATTERY_EDGE = '0A';
//    换电完成您辛苦了 11
    const VIDEO_POWER_CHANGE = '0B';
//    您已出服务区请尽快骑回 12
    const VIDEO_GO_BEYOND = '0C';
//     夜间出行注意安全 13
    const VIDEO_NIGHT_SAFETY = '0D';
//    当前为禁停区禁止停车 14
    const VIDEO_NO_STOPPING = '0E';
    // 空 15

    // 小喵生病了试试别的车吧 16
    const VIDEO_USE_OTHER = '10';

    //电量过低 17
    const VIDEO_LOW_POWER = '11';
    // 主人即将回来试试别的车吧 18
    const VIDEO_OCCUPIED = '12';
    //小猫被预约了试试别的车吧 19
    const VIDEO_RESERVED = '13';
    // (带告警音)请停止偷窃行为 20
    const VIDEO_WARNING = '14';
    // 您已进入禁停区 21
    const VIDEO_NO_STOP_ZONE = '15';
    // 车辆已报警请勿移动 22
    const VIDEO_ALARMED = '16';
    //请停止偷窃行为 23
    const VIDEO_NO_STEALING = '17';
    // 设备已绑定 24
    const VIDEO_BOUND = '18';
    // 小喵摔倒了
    const VIDEO_FALL = '19';
}