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

use yunwuxin\notification\Channel;
use yunwuxin\notification\Notifiable;

/**
 * Class Notification
 * @package yunwuxin
 * @property string  $queue
 * @property integer $delay
 * @property string  $connection
 */
abstract class Notification
{

    public $id;

    /**
     * 发送渠道
     * @param Notifiable $notifiable
     * @return array
     */
    abstract public function channels($notifiable);

}
