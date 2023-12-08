<?php

namespace ZhMead\XmnkBikeControl\Gemili\Maps;

class CameraErrorMap
{
    //成功
    const DVR_ERROR_SUCCESS = 0;

    //拍照失败
    const DVR_ERROR_CAMERA_CAPTURE_FAILED = 1;

    //拍照检测文件坏了
    const DVR_ERROR_CHECK_FILEFAILED = 10;

    //拍照检测失败1
    const DVR_ERROR_CHECK_YELLOW = 11;

    //拍照检测失败2
    const DVR_ERROR_CHECK_WHITE = 12;

    //拍照检测距离1失败
    const DVR_ERROR_CHECK_DISTANCE = 13;

    //拍照检测距离2失败
    const DVR_ERROR_CHECK_DISTANCE2 = 14;

    //拍照检测点比较少
    const DVR_ERROR_CHECK_POINTLOW = 15;

    //运行中
    const DVR_ERROR_IS_RUNING = 100;
}