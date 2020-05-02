<?php
namespace App\Enums;


abstract class ProjectStatus
{
    // 项目状态。0：未启用；1：进行中；2：已完成；3：暂缓处理；4：已取消；5：已逾期。
    const NOT_ACTIVATED = 0;
    const PROCESSING = 1;
    const COMPLETED = 2;
    const HOLD_OFF = 3;
    const CANCELED = 4;
    const EXPIRED = 5;
}
