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

use GuzzleHttp\Client;

class Sendcloud
{
    protected $user;
    protected $key;

    protected $host = "http://www.sendcloud.net/";

    protected $template;

    protected $msgType = 0;

    protected $to;

    protected $data;

    protected $isVoice = false;

    public function __construct($user, $key)
    {
        $this->user = $user;
        $this->key  = $key;
    }

    /**
     * 短信模板
     * @param $template
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * 设置为彩信
     * @return $this
     */
    public function isMultimedia()
    {
        $this->msgType = 1;
        return $this;
    }

    /**
     * 收信人
     * @param $to
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * 替换变量或者语音短信里的验证码
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * 设为语音短信
     * @return $this
     */
    public function isVoice()
    {
        $this->isVoice = true;
        return $this;
    }

    protected function signature(&$params)
    {
        $sParamStr = "";
        ksort($params);
        foreach ($params as $sKey => $sValue) {
            if (is_array($sValue)) {
                $value     = implode(";", $sValue);
                $sParamStr .= $sKey . '=' . $value . '&';
            } else {
                $sParamStr .= $sKey . '=' . $sValue . '&';
            }
        }
        $sParamStr           = trim($sParamStr, '&');
        $sSignature          = md5($this->key . "&" . $sParamStr . "&" . $this->key);
        $params['signature'] = $sSignature;
    }

    /**
     * @internal
     */
    public function send()
    {
        $client = new Client();

        if ($this->isVoice) {
            $params = [
                'phone'   => $this->to,
                'code'    => $this->data,
                'smsUser' => $this->user
            ];

            $url = $this->host . 'smsapi/sendVoice';
        } else {
            $params = [
                'templateId' => $this->template,
                'msgType'    => $this->msgType,
                'phone'      => $this->to,
                'vars'       => json_encode($this->data),
                'smsUser'    => $this->user
            ];

            $url = $this->host . 'smsapi/send';
        }

        $this->signature($params);

        $response = $client->post($url, [
            'form_params' => $params
        ]);

        $result = json_decode($response->getBody(), true);

        if (!$result['result'] || $result['statusCode'] != 200) {
            throw new \RuntimeException($result['message'], $result['statusCode']);
        }

    }

}