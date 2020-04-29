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
namespace yunwuxin\notification\message;

class Mail
{

    /** @var string 通知等级 */
    public $level = 'info';

    /** @var string 标题 */
    public $subject;

    /** @var string 问候语 */
    public $greeting = null;

    public $introLines = [];

    public $outroLines = [];

    /** @var string 操作按钮的文字 */
    public $actionText;

    /** @var string 操作按钮的链接 */
    public $actionUrl;

    /** @var string  提示语 */
    public $subcopy;

    /** @var string 视图模板 */
    public $view;

    /** @var array 模板数据 */
    public $viewData = [];

    /** @var array 发信人 */
    public $from = [];

    /** @var array 收信人 */
    public $to = [];

    /** @var array 附件 */
    public $attachments = [];

    /** @var array 附件(数据) */
    public $rawAttachments = [];

    /** @var null 优先级 */
    public $priority = null;

    /**
     * 设置模板及数据
     * @param       $view
     * @param array $data
     * @return $this
     */
    public function view($view, array $data = [])
    {
        $this->view     = $view;
        $this->viewData = $data;

        return $this;
    }

    /**
     * 设置发信人
     * @param      $address
     * @param null $name
     * @return $this
     */
    public function from($address, $name = null)
    {
        $this->from = [$address, $name];

        return $this;
    }

    /**
     * 设置收信人
     * @param $address
     * @return $this
     */
    public function to($address)
    {
        $this->to = $address;

        return $this;
    }

    /**
     * 设置附件
     * @param       $file
     * @param array $options
     * @return $this
     */
    public function attach($file, array $options = [])
    {
        $this->attachments[] = compact('file', 'options');

        return $this;
    }

    /**
     * 设置附件(数据)
     * @param       $data
     * @param       $name
     * @param array $options
     * @return $this
     */
    public function attachData($data, $name, array $options = [])
    {
        $this->rawAttachments[] = compact('data', 'name', 'options');

        return $this;
    }

    /**
     * 设置优先级
     * @param $level
     * @return $this
     */
    public function priority($level)
    {
        $this->priority = $level;

        return $this;
    }

    /**
     * 设置通知等级
     * @return $this
     */
    public function success()
    {
        $this->level = 'success';

        return $this;
    }

    /**
     * 设置通知等级
     * @return $this
     */
    public function error()
    {
        $this->level = 'error';

        return $this;
    }

    /**
     * 设置通知等级
     * @param $level
     * @return $this
     */
    public function level($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * 设置标题
     * @param $subject
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * 设置问候语
     * @param $greeting
     * @return $this
     */
    public function greeting($greeting)
    {
        $this->greeting = $greeting;

        return $this;
    }

    /**
     * 添加一行文字
     * @param $line
     * @return Mail
     */
    public function line($line)
    {
        if (!$this->actionText) {
            $this->introLines[] = $this->formatLine($line);
        } else {
            $this->outroLines[] = $this->formatLine($line);
        }

        return $this;
    }

    /**
     * 格式化文字内容
     * @param $line
     * @return string
     */
    protected function formatLine($line)
    {
        if (is_array($line)) {
            return implode(' ', array_map('trim', $line));
        }

        return trim(implode(' ', array_map('trim', preg_split('/\\r\\n|\\r|\\n/', $line))));
    }

    /**
     * 设置操作按钮
     *
     * @param string $text
     * @param string $url
     * @return $this
     */
    public function action($text, $url = null)
    {
        $this->actionText = $text;
        $this->actionUrl  = $url;

        return $this;
    }

    /**
     * 设置提示文字
     * @param $subcopy
     * @return $this
     */
    public function subcopy($subcopy)
    {
        $this->subcopy = $subcopy;
        return $this;
    }

    /**
     * 获取视图数据
     * @return array
     */
    public function data()
    {
        return array_merge([
            'level'      => $this->level,
            'subject'    => $this->subject,
            'greeting'   => $this->greeting,
            'introLines' => $this->introLines,
            'outroLines' => $this->outroLines,
            'actionText' => $this->actionText,
            'actionUrl'  => $this->actionUrl,
            'subcopy'    => $this->subcopy,
        ], $this->viewData);
    }
}
