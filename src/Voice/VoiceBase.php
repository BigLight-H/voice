<?php


namespace Wisdom\CallVoice\Voice;


use GuzzleHttp\Client;
use Wisdom\CallVoice\Exceptions\InvalidArgumentException;

class VoiceBase
{

    protected $guzzleOptions = [];

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * @brief 检测函数必传参数是否存在
     * @param $params array 关联数组 要检查的参数
     * @param array $mod array 索引数组 要检查的字段
     * @param array $fields array 索引数组 额外要检查参数的字段
     * @return bool
     * @throws InvalidArgumentException
     */
    public function checkParamsExists($params, $mod = [], $fields = [])
    {
        if (empty($params)) {
            throw new InvalidArgumentException('检查参数数组不能为空');
        }
        $params = is_array($params) ? $params : [$params];

        if ($fields) {
            $fields = array_flip($fields);
            $params = array_merge($params, $fields);
        }

        foreach ($mod as $mod_key => $mod_value) {
            if (!array_key_exists($mod_value, $params) || !$params[$mod_value]) {
                throw new InvalidArgumentException('数组' . json_encode($params) . '参数(' . $mod_value . ')的值不存在');
            }
        }
        return true;
    }

    /**
     * 生成签名
     * @param $arr
     * @param $key
     * @return array
     */
    public function getSign($arr, $key)
    {
        $str = 'post&';
        $arr = array_filter($arr);
        foreach ($arr as $key=>$val) {
            $str .= $key.'='.$val.'&';
        }
        $str .= $key;
        $sign = md5($str);
        $arr['sign'] = $sign;
        return $arr;
    }

    public function taskTime()
    {
        $data['star'] = date('Y-m-d H:i:s', time());
        $data['end'] =  date('Y-m-d H:i:s', strtotime("+1 year"));
        return $data;
    }
}