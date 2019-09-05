<?php

namespace yunwuxin\notification;

use Ramsey\Uuid\Uuid;
use think\Collection;
use think\Manager;
use think\Queue;
use think\queue\Queueable;
use think\queue\ShouldQueue;
use yunwuxin\Notification;

class Sender extends Manager
{
    protected $namespace = "\\yunwuxin\\notification\\channel\\";

    /**
     * 发送通知
     * @param Notifiable[]|Notifiable $notifiables
     * @param Notification            $notification
     */
    public function send($notifiables, Notification $notification)
    {
        $notifiables = $this->formatNotifiables($notifiables);

        if ($notification instanceof ShouldQueue) {
            $this->sendQueue($notifiables, $notification);
        } else {
            $this->sendNow($notifiables, $notification);
        }
    }

    /**
     * 发送通知(立即发送)
     * @param Notifiable[]|Notifiable $notifiables
     * @param Notification            $notification
     * @param array                   $channels
     */
    public function sendNow($notifiables, Notification $notification, array $channels = null)
    {
        $notifiables = $this->formatNotifiables($notifiables);

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

                $this->channel($channel)->send($notifiable, $notification);
            }
        }
    }

    /**
     * 发送通知(队列发送)
     * @param Notifiable[]|Notifiable $notifiables
     * @param Notification            $notification
     */
    public function sendQueue($notifiables, Notification $notification)
    {
        $notifiables = $this->formatNotifiables($notifiables);

        $sender = function ($job) {
            $this->app->make(Queue::class)->push($job);
        };

        if (in_array(Queueable::class, class_uses_recursive($notification))) {
            $sender = function ($job) use ($notification) {
                /** @var Queue $queue */
                $queue = $this->app->make(Queue::class);

                $queue = $queue->connection($notification->connection);

                if ($notification->delay > 0) {
                    $queue->later($notification->delay, $job, '', $notification->queue);
                } else {
                    $queue->push($job, '', $notification->queue);
                }
            };
        }

        foreach ($notifiables as $notifiable) {

            $channels = $notification->channels($notifiable);

            if (empty($channels)) {
                continue;
            }

            foreach ($channels as $channel) {
                $job = new SendQueuedNotifications($notifiable, $notification, [$channel]);

                $sender($job);
            }
        }
    }

    /**
     * 获取通知渠道
     * @param string $name
     * @return Channel
     */
    protected function channel($name)
    {
        return $this->driver($name);
    }

    /**
     * 转数组
     * @param $notifiables
     * @return array
     */
    protected function formatNotifiables($notifiables)
    {
        if (!$notifiables instanceof Collection && !is_array($notifiables)) {
            return [$notifiables];
        }

        return $notifiables;
    }

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return null;
    }
}
