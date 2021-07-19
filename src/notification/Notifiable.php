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

use think\helper\Str;
use yunwuxin\facade\Notification;

/**
 * Trait Notifiable
 * @method string prepareMobile()
 */
trait Notifiable
{
    /**
     * @param \yunwuxin\Notification $notification
     */
    public function notify(\yunwuxin\Notification $notification)
    {
        Notification::send($this, $notification);
    }

    /**
     * 准备渠道收件人
     * @param string $channel
     * @return mixed|void
     */
    public function getPreparedData(string $channel)
    {
        $method = 'prepare' . Str::studly($channel);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
    }
}
