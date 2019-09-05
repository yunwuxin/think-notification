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

use RuntimeException;
use yunwuxin\Notification;

abstract class Channel
{

    /**
     * 发送通知
     * @param Notifiable   $notifiable
     * @param Notification $notification
     */
    abstract public function send($notifiable, Notification $notification);

    /**
     * 获取通知数据
     * @param Notifiable   $notifiable
     * @param Notification $notification
     * @return mixed
     */
    protected function getMessage($notifiable, Notification $notification)
    {
        $toMethod = 'to' . class_basename($this);

        if (method_exists($notification, $toMethod)) {
            return $notification->$toMethod($notifiable);
        }

        throw new RuntimeException(
            "Notification is missing {$toMethod} method."
        );
    }

}
