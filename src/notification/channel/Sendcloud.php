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

namespace yunwuxin\notification\channel;

use yunwuxin\Notification;
use yunwuxin\notification\Channel;
use yunwuxin\notification\Notifiable;

class Sendcloud extends Channel
{

    /**
     * 发送通知
     * @param Notifiable   $notifiable
     * @param Notification $notification
     * @return mixed
     */
    public function send(Notifiable $notifiable, Notification $notification)
    {
        $message = $this->getMessage($notifiable, $notification);

        if ($message instanceof \yunwuxin\notification\message\Sendcloud) {
            $message->send();
        }

    }
}