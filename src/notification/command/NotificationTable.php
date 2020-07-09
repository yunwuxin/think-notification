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
namespace yunwuxin\notification\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class NotificationTable extends Command
{

    protected function configure()
    {
        $this->setName('notification:table')->setDescription("Create a migration for the notification table");
    }

    protected function execute(Input $input, Output $output)
    {
        $this->createMigration($output);
    }

    protected function createMigration($output)
    {
        $configFilePath = APP_PATH.'..'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'
            .DIRECTORY_SEPARATOR.date("YmdHis").'_notification.php';

        if (is_file($configFilePath)) {
            $output->writeln('Migration file is exist');
            return;
        }

        $res = copy(__DIR__."./stubs/notification.stub", $configFilePath);
        if ($res) {
            $output->writeln('Migration file success:'.$configFilePath);
        } else {
            $output->writeln('Migration file error');
        }
    }
}