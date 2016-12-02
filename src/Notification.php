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
namespace yunwuxin;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use think\Collection;
use think\helper\Str;
use think\Queue;
use think\queue\Queueable;
use think\queue\ShouldQueue;
use yunwuxin\notification\Channel;
use yunwuxin\notification\SendQueuedNotifications;
use yunwuxin\notification\Notifiable;

/**
 * Class Notification
 * @package yunwuxin
 *
 * @property string  $queue
 * @property integer $delay
 */
abstract class Notification
{

    public $id;

    /** @var Channel[] */
    protected static $channels = [];

    /**
     * 发送渠道
     * @param $notifiable
     * @return array
     */
    abstract public function channels(Notifiable $notifiable);

    /**
     * 发送通知
     * @param Notifiable[]|Notifiable $notifiables
     * @param Notification            $notification
     */
    public static function send($notifiables, Notification $notification)
    {
        $notifiables = self::formatNotifiables($notifiables);

        if ($notification instanceof ShouldQueue) {
            self::queue($notifiables, $notification);
        } else {
            self::sendNow($notifiables, $notification);
        }
    }

    /**
     * 发送通知(立即发送)
     * @param Notifiable[]|Notifiable $notifiables
     * @param Notification            $notification
     * @param array                   $channels
     */
    public static function sendNow($notifiables, Notification $notification, array $channels = null)
    {
        $notifiables = self::formatNotifiables($notifiables);

        $original = clone $notification;

        foreach ($notifiables as $notifiable) {
            $notificationId = (string) Uuid::uuid4();

            $channels = $channels ?: $notification->channels($notifiable);

            if (empty($channels)) {
                continue;
            }

            foreach ($channels as $channel) {
                $notification = clone $original;

                $notification->id = $notificationId;

                self::channel($channel)->send($notifiable, $notification);
            }
        }
    }

    /**
     * 发送通知(队列发送)
     * @param Notifiable[]|Notifiable $notifiables
     * @param Notification            $notification
     */
    public static function queue($notifiables, Notification $notification)
    {
        $notifiables = self::formatNotifiables($notifiables);

        $delay = 0;
        $queue = null;
        if (in_array(Queueable::class, class_uses_recursive($notification))) {
            $delay = $notification->delay;
            $queue = $notification->queue;
        }

        foreach ($notifiables as $notifiable) {

            $channels = $notification->channels($notifiable);

            if (empty($channels)) {
                continue;
            }

            foreach ($channels as $channel) {
                $job = new SendQueuedNotifications($notifiable, $notification, [$channel]);

                if ($delay > 0) {
                    Queue::later($delay, $job, '', $queue);
                } else {
                    Queue::push($job, '', $queue);
                }
            }
        }
    }

    /**
     * 获取通知渠道
     * @param string $name
     * @return Channel
     */
    protected static function channel($name)
    {
        $name = strtolower($name);
        if (!isset(self::$channels[$name])) {
            self::$channels[$name] = self::buildChannel($name);
        }

        return self::$channels[$name];
    }

    /**
     * 创建渠道
     * @param string $name
     * @return Channel
     */
    protected static function buildChannel($name)
    {
        $className = false !== strpos($name, '\\') ? $name : "\\yunwuxin\\notification\\channel\\" . Str::studly($name);

        if (class_exists($className)) {
            return new $className;
        }
        throw new InvalidArgumentException("Channel [{$name}] not supported.");
    }

    /**
     * 转数组
     * @param $notifiables
     * @return array
     */
    protected static function formatNotifiables($notifiables)
    {
        if (!$notifiables instanceof Collection && !is_array($notifiables)) {
            return [$notifiables];
        }

        return $notifiables;
    }
}