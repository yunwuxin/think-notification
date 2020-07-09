# ThinkPHP5 消息通知扩展

支持`mail` `sendcloud` `database`等驱动

## 应用场景

> 发送手机验证码
> 发送验证邮件，找回密码邮件
> 订单状态变更
> 站内消息通知
> ...

## 安装
```
composer require yunwuxin/think-notification
```

## 使用说明
### 数据库通知
1. 用命令`php think notification:table`，或者手动创建数据库通知表,迁移脚本如下

```
<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Notification extends Migrator
{
    public function change()
    {
        $this->table('notification', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'uuid')
            ->addColumn('type', 'string')
            ->addColumn('notifiable_id', 'integer')
            ->addColumn('notifiable_type', 'string')
            ->addColumn('data', 'text')
            ->addColumn('read_time', 'timestamp', [
                'null'    => true,
                'default' => null
            ])
            ->addTimestamps()
            ->create();
    }
}

```
2. 比如通知给后台用户,用户模型里引入`Notifiable`、`HasDatabaseNotification`，建立关系

```
<?php
namespace app\index\model;


use think\Model;
use yunwuxin\notification\HasDatabaseNotification;
use yunwuxin\notification\Notifiable;

class SystemUser  extends Model
{
    use Notifiable;
    use HasDatabaseNotification;
}
```
3. 实现通知类，如下

```
<?php
namespace app\index\notification;


use yunwuxin\Notification;

class TestNotification extends Notification
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function channels($notifiable)
    {
        return ['database'];
    }

    public function toDatabase(){
        return [
            'data' => $this->data,
        ];
    }
}
```
4. 单发和群发

```
//单发
$user = SystemUser::get(1);
$user->notify(new TestNotification(["message"=>'消息内容']));

//群发
$users = SystemUser::all([1,2]);
Notification::send($users, new TestNotification([
    "message" =>"假消息"
]));
```

5. 更新未读消息的读取时间

```
$user = SystemUser::get(1);
foreach ($user->unreadNotifications as $notification){
    $notification->markAsRead();
}
```
