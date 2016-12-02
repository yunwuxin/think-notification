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
        'read_time' => 'datetime',
        'data'      => 'array'
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        if (is_null($this->data['read_time'])) {
            $this->save(['read_time' => time()]);
        }
    }
}