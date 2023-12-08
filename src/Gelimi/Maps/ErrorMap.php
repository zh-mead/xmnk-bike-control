<?php

namespace ZhMead\XmnkBikeControl\Gemili\Maps;

class ErrorMap
{
    //操作成功
    const CODE_SUCCESS = 0;

    //请求范围过大
    const CODE_RANGE_TOO_LARGE = 105;

    //设备内存错误
    const CODE_MEM_NOT_ENOUGH = 110;

    //设备不支持该指令
    const CODE_CMD_NOT_SUPPORT = 111;

    //参数不合法
    const CODE_PARAM_INALID = 114;

    //参数缺失
    const CODE_PARAM_MISSING = 115;

    //HTTPCID错误
    const CODE_ERR_HTTP_CID = 116;

    //HTTPURL错误
    const CODE_ERR_HTTP_URL = 117;

    //HTTPUSERDATA错误
    const CODE_ERR_HTTP_USERDATA = 118;

    //post数据写入modem时错误
    const CODE_ERR_HTTP_WRITEDATA = 119;

    //HTTPACTION除去常301 500 604 603 404错误的其他错
    const CODE_ERR_HTTP_ACTION = 120;

    //post读取服务器返回数据失败
    const CODE_ERR_HTTP_POSTREAD = 121;

    //http中正在执行进程
    const CODE_ERR_HTTP_IS_PROCING = 122;

    //post中读取文件数据失败
    const CODE_ERR_HTTP_READFILE = 123;

    //301 error
    const CODE_ERR_HTTP_MOVEDPERMANENTLY = 124;

    //404 error
    const CODE_ERR_HTTP_NOTFOUND = 125;

    //408 error
    const CODE_ERR_HTTP_REQUESTTIMEOUT = 126;

    //500 error
    const CODE_ERR_HTTP_INTERNALSERVERERROR = 127;

    //504 error
    const CODE_ERR_HTTP_GATEWAYTIMEOUT = 128;

    //601 error
    const CODE_ERR_HTTP_NETWORKERROR = 129;

    //603 error
    const CODE_ERR_HTTP_DNSERROR = 130;

    //设备存储失败
    const CODE_ERR_FILE_ERROR = 131;

    //车辆未停止
    const CODE_ERR_CAR_NOT_STOP = 132;

    //crc校验失败
    const CODE_ERR_UPGRADE_CRCERROR = 133;

    //http操作失败
    const CODE_ERR_OPEN_GPRS_FAILED = 134;

    //设备操作超时
    const CODE_ERR_OPERATION_TIMEOUT = 135;

    //设备操作失败
    const CODE_ERR_OPERATION_ERROR = 136;

    //命令超时
    const CODE_ERR_OUT_OF_TIME = 137;

    //操作过去频繁
    const CODE_ERR_CONTINUALLY = 138;

    //超级锁车功能开启,无法操作
    const CODE_ERR_ACCESS_DENY = 139;

    //控制器或者BMS OTA升级中,无法操作
    const CODE_ERR_OTA_UPGRADING = 140;

    //大电不在,无法操作
    const CODE_ERR_NO_POWER = 141;

    //超载
    const CODE_ERR_OVERLOAD = 142;

    //角度不满足条件
    const CODE_ERR_ANGEL_DISSATISFY = 143;
}