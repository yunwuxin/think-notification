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

use yunwuxin\model\SerializesModel;
use yunwuxin\Notification;

class SendQueuedNotifications
{
    use SerializesModel;

    /** @var Notifiable */
    protected $notifiable;

    /** @var Notification */
    protected $notification;

    /** @var array */
    protected $channels = null;

    public function __construct($notifiable, Notification $notification, array $channels = null)
    {
        $this->notifiable   = $notifiable;
        $this->notification = $notification;
        $this->channels     = $channels;
    }

    public function handle(Sender $sender)
    {
        $sender->sendNow($this->notifiable, $this->notification, $this->channels);
    }

    /**
     * 队列任务失败回调
     * @return void
     */
    public function failed()
    {
        if (method_exists($this->notification, 'failed')) {
            $this->notification->failed($this->notifiable);
        }
    }
}
