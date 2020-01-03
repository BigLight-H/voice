## 智能语音推送说明

### 添加配置文件
>在根目录的.env文件写入推送相关的配置文件
```shell
VOICE_KEY=语音key
VOICE_PHONE_URL=Api连接前缀,例如:http://127.0.0.42:8066/v1/post/
VOICE_ACCOUNT=帐号(机器人平台分配)
VOICE_COMPID=企业id
VOICE_STATE_URL=号码推送状态url
VOICE_TASK_STATE_URL=任务状态url
```

### 1.推送方法1

```php
$data['phone_url'] = 需要拨打电话语音的电话获取连接url,例如:'https://www.baidu.com/tests';
$data['route_num'] = 指定此批电话所执行的行为ID,例如:'52000700002';
(new Voice($data))->start();
```
#### 参数说明
$data['phone_url'] 返回格式为：
```php
$data = [
            'code' => 200,
            'reason' => 'ok',
            'isend' => 'true',
            'nums' => [
                [
                    'id'=>1,
                    'phone'=>[
                        '18655555555',
                        '18666666666'
                    ],
                    'param'=>1,
                ]
            ],
        ];
return response()->json($data, 200);
```
#### 传输电话号码的url的响应格式
```
code	        int	是	200: 成功，其他失败
reason	        string	是	失败的具体原因
isend	        string	是	true表示没有号码再返回；false表示还有号码未呼，请继续获取号码
nums[].id	string	是	手机或者坐席的唯一标识
nums[].phone	array	是	号码数组
nums[].param	string	是	随路数据id
{
    "code": 200,
    "reason": "ok",
    "isend": "false",
    "nums":[
        {"id":"1", "phone":["13611111111"],"param":"1"},
        {"id":"2", "phone":["13600000000"],"param":"2"}
    ]
}
```
