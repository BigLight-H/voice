## 智能语音推送说明

### 添加配置文件
>在根目录的.env文件写入推送相关的配置文件
```shell
VOICE_KEY=语音key
VOICE_PHONE_URL=Api连接前缀,例如:http://101.201.196.42:8066/v1/post/
VOICE_ACCOUNT=帐号(机器人平台分配)
VOICE_COMPID=企业id
VOICE_STATE_URL=号码推送状态url
VOICE_TASK_STATE_URL=任务状态url
```

### 1.推送方法1

```php
$data['phone_url'] = 需要拨打电话语音的电话获取连接url,例如:'https://enjoycartask-feature-voice-call.enjoyapi.cn/tests';
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
                        '18682283663',
                        '18682286776'
                    ],
                    'param'=>1,
                ]
            ],
        ];
return response()->json($data, 200);
```