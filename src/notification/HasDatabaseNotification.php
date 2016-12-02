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

use think\Model;
use yunwuxin\notification\model\Notification;

/**
 * Class HasDatabaseNotification
 * @package yunwuxin\notification
 *
 * @mixin Model
 */
trait HasDatabaseNotification
{
    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->order('created_time', 'desc');
    }

    /**
     * Get the entity's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->where('read_at', null)
            ->order('created_time', 'desc');
    }

    public function prepareDatabase()
    {
        return $this->notifications();
    }

}