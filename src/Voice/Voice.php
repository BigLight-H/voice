<?php


namespace Wisdom\CallVoice\Voice;


use Wisdom\CallVoice\Exceptions\HttpException;
use Wisdom\CallVoice\Parameter\EssentialParameter;

class Voice extends VoiceBase
{
    protected $key;              //签名
    protected $url;              //API-URL
    protected $account;          //帐号(机器人平台分配)
    protected $compid;           //企业id
    protected $id;               //任务ID
    protected $task_name;        //任务名称 -Y
    protected $stime_dt;         //任务开始时间 -Y
    protected $etime_dt;         //任务结束时间 -Y
    protected $type;             //任务类型(2表示呼叫坐席分机，3表示机器人外呼) -Y
    protected $retry;            //重复次数 -Y
    protected $interval;         //重试间隔 -Y
    protected $max;              //最大并发数 -Y
    protected $status;           //任务状态(0停用，1启用，2暂停) -N
    protected $phone_url;        //号码获取url -Y
    protected $state_url;        //号码推送状态url -Y
    protected $task_state_url;   //任务状态url -Y
    protected $speed;            //外呼系数 -Y
    protected $prefix;           //外呼号码前辍 -N
    protected $route_num;        //转入智能路由号码(机器人外呼时为必传) -Y
    protected $total_robot_num;  //总机器人数 -Y

    public function __construct($data)
    {
        $taskTime = $this->taskTime();
        $this->key = env('VOICE_KEY', '');
        $this->url = env('VOICE_PHONE_URL', '');
        $this->account = env('VOICE_ACCOUNT', '');
        $this->compid = env('VOICE_COMPID', '');
        $this->task_name = 'qx'.time();
        $this->stime_dt = $taskTime['star'];
        $this->etime_dt = $taskTime['end'];
        $this->type = 3;
        $this->retry = 1;
        $this->interval = 1;
        $this->max = 1;
        $this->phone_url = $data['phone_url'];
        $this->state_url = $data['state_url'] ? $data['state_url'] : 'www.test.com';
        $this->task_state_url = $data['task_state_url'] ? $data['task_state_url'] : 'www.test.com';
        $this->speed = 1;
        $this->route_num = $data['route_num'];
        $this->total_robot_num = 1;
        $this->id = $id = $this->add();
    }

    /**
     * @return string
     * @throws HttpException
     * @throws \Wisdom\CallVoice\Exceptions\InvalidArgumentException
     */
    public function add()
    {
        $url = $this->url . 'task/add';
        $arr = [
            'account' => $this->account,
            'compid' => $this->compid,
            'task_name' => $this->task_name,
            'stime_dt' => $this->stime_dt,
            'etime_dt' => $this->etime_dt,
            'type' => $this->type,
            'retry' => $this->retry,
            'interval' => $this->interval,
            'max' => $this->max,
            'status' => $this->status,
            'phone_url' => $this->phone_url,
            'state_url' => $this->state_url,
            'task_state_url' => $this->task_state_url,
            'speed' => $this->speed,
            'prefix' => $this->prefix,
            'route_num' => $this->route_num,
            'total_robot_num' => $this->total_robot_num,
        ];
        $this->checkParamsExists($arr, EssentialParameter::$addParament);//检测必要参数是否齐全
        $form_params = $this->getSign($arr, $this->key);//签名生成
        try {
            $response = $this->getHttpClient()->post($url, [
                'form_params' => $form_params,
            ])->getBody()->getContents();
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        $response = json_decode($response, true);
        if ($response['code'] == 1) {
            $this->id = $response['id'];
        }
        $this->id = '';
    }

    /**
     * 启动机器人任务进行外呼
     * @return mixed
     * @throws HttpException
     * @throws \Wisdom\CallVoice\Exceptions\InvalidArgumentException
     */
    public function start()
    {
        $url = $this->url . 'task/start';
        $this->bind();
        $arr = [
            'account' => $this->account,
            'compid' => $this->compid,
            'id' => $this->id
        ];
        $form_params = $this->getSign($arr, $this->key);//签名生成
        $this->checkParamsExists($form_params, EssentialParameter::$starParament);
        try {
            $response = $this->getHttpClient()->post($url, [
                'form_params' => $form_params,
            ])->getBody()->getContents();
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        return json_decode($response);
    }

    /**
     * 绑定任务
     * @return string
     * @throws HttpException
     */
    public function bind()
    {
        $url = $this->url.'task/addtaskphone';
        $id = $this->id;
        $disphone_id = $this->getPhoneId();
        $arr = [
            'account' => $this->account,
            'compid' => $this->compid,
            'task_id' => $id,
            'disphone_id' => $disphone_id
        ];
        $form_params = $this->getSign($arr, $this->key);//签名生成
        try {
            $response = $this->getHttpClient()->post($url, [
                'form_params' => $form_params,
            ])->getBody()->getContents();
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
        return $response;
    }

    /**
     * 获取呼出电话ID
     * @return array
     * @throws HttpException
     */
    public function getPhoneId()
    {
        $url = $this->url.'task/getdisphone';
        $arr = [
            'account' => $this->account,
            'page' => 1,
            'page_size' => 1000,
        ];
        $form_params = $this->getSign($arr, $this->key);//签名生成
        try {
            $response = $this->getHttpClient()->post($url, [
                'form_params' => $form_params,
            ])->getBody()->getContents();
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        $arr = json_decode($response, true);
        $ids = [];
        foreach ($arr['data'] as $val) {
            array_push($ids, $val['id']);
        }

        return $ids;
    }
}