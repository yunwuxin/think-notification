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
use think\View;
use yunwuxin\mail\Mailable;
use yunwuxin\Notification;
use yunwuxin\notification\message\Mail;

class MailableMessage extends Mailable
{
    /** @var Mail */
    protected $message;

    /** @var Notification */
    protected $notification;

    public function __construct(Mail $message, Notification $notification)
    {
        $this->message      = $message;
        $this->notification = $notification;
    }

    protected function buildTwigLoader()
    {
        $loader = parent::buildTwigLoader();

        $loader->addPath(dirname(__FILE__) . DS . 'view', 'notification');

        return $loader;
    }

    protected function build()
    {
        parent::build();

        $message = $this->message;

        $this->markdown($message->view ?: '@notification/mail', $message->data());

        if (!empty($message->from)) {
            $this->from($message->from[0], isset($message->from[1]) ? $message->from[1] : null);
        }

        if (is_array($message->to)) {
            $this->bcc($message->to);
        } else {
            $this->to($message->to);
        }

        $this->subject($message->subject ?: Str::title(
            Str::snake(class_basename($this->notification), ' ')
        ));

        foreach ($message->attachments as $attachment) {
            $this->attach($attachment['file'], $attachment['options']);
        }

        foreach ($message->rawAttachments as $attachment) {
            $this->attachData($attachment['data'], $attachment['name'], $attachment['options']);
        }

    }

    protected function afterBuild(\Swift_Message $message)
    {
        parent::afterBuild($message);

        if (!is_null($this->message->priority)) {
            $message->setPriority($this->message->priority);
        }
    }
}