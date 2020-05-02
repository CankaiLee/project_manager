<?php
namespace App\Enums;

abstract class TaskStatus
{
    // 状态。0：未完成；1：已完成；2：已逾期
    const NOT_YET = 0;
    const COMPLETED = 1;
    const EXPIRED = 2;
}
