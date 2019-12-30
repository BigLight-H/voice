<?php
/**
 * sign：签名
 * url：请求url前缀
 * account：帐号(机器人平台分配)
 * compid：企业id
 */
return [
    'key' => env('VOICE_KEY', ''),
    'url' => env('VOICE_PHONE_URL', ''),
    'account' => env('VOICE_ACCOUNT', ''),
    'compid' => env('VOICE_COMPID', '')
];