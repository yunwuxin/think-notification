<?php

namespace yunwuxin\notification;

use yunwuxin\notification\command\NotificationTable;

class Service extends \think\Service
{

    public function boot()
    {
        $this->commands([
            NotificationTable::class,
        ]);
    }
}
