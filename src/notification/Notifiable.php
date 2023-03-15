<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace yunwuxin\notification;

use Exception;
use think\facade\Log;
use think\helper\Str;
use yunwuxin\facade\Notification;

trait Notifiable
{
    public function notify($instance)
    {
        try {
            Notification::send($this, $instance);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function mustNotify($instance)
    {
        Notification::send($this, $instance);
    }

    public function getPreparedData($channel)
    {
        if (method_exists($this, $method = 'prepare' . Str::studly($channel))) {
            return $this->{$method}();
        }
    }

}
