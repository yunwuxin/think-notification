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
namespace yunwuxin\notification\model;

use think\Model;

/**
 * Class Notification
 * @package yunwuxin\notification\model
 *
 * @property integer $id
 * @property string  $type
 * @property string  $data
 * @property string  $read_time
 */
class Notification extends Model
{

    protected $type = [
        'data'      => 'array'
    ];
    protected $autoWriteTimestamp = "timestamp";

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        if (is_null($this->getData('read_time'))) {
            $this->save(['read_time' => (new \DateTime("now"))->format("Y-m-d H:i:s.u")]);
        }
    }

    public function toCollection($collection)
    {
        return new NotificationCollection($collection);
    }
}