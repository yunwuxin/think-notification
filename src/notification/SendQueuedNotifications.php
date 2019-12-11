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

use yunwuxin\Notification;

class SendQueuedNotifications
{
    /** @var Notifiable[] */
    protected $notifiables;

    /** @var Notification */
    protected $notification;

    /** @var array */
    protected $channels = null;

    public function __construct($notifiables, Notification $notification, array $channels = null)
    {
        $this->notifiables  = $notifiables;
        $this->notification = $notification;
        $this->channels     = $channels;
    }

    public function handle(Sender $sender)
    {
        $sender->sendNow($this->notifiables, $this->notification, $this->channels);
    }
    
    
    /**
     * 队列任务失败回调
     * @return void
     */
    public function failed(){
        if (method_exists($this->notification, 'failed')) {
            $this->notification->failed($this->notifiables);
        }
    }
}
