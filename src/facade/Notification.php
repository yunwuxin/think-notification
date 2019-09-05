<?php

namespace yunwuxin\facade;

use think\Facade;
use yunwuxin\notification\Sender;

/**
 * Class Mail
 *
 * @package yunwuxin\facade
 * @mixin Sender
 */
class Notification extends Facade
{
    protected static function getFacadeClass()
    {
        return Sender::class;
    }
}
