<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan JiYuan Information Technology Co., Ltd.
 * @link https://www.yaoqiyuan.com/
 */

namespace yunwuxin\notification\channel;

use larva\sms\Sms;
use think\facade\Log;
use yunwuxin\Notification;
use yunwuxin\notification\Channel;
use yunwuxin\notification\Notifiable;

/**
 * 短信通知
 * @author Tongle Xu <xutongle@msn.com>
 * @date 2021/6/30
 */
class Mobile extends Channel
{
    /**
     * 发送短信通知
     * @param \yunwuxin\notification\Notifiable $notifiable
     * @param Notification $notification
     * @return array|\Overtrue\EasySms\EasySms|void
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $this->getMessage($notifiable, $notification);
        $to = $notifiable->prepareMobile();
        if ($to) {
            try {
                return Sms::send($to, $message);
            } catch (\Exception $exception) {
                foreach ($exception->getExceptions() as $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
