<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    //设置时区:
    'timeZone' => 'PRC',   //中国时区
//设置字符集:
    'charset' => 'UTF-8',   //默认, 万国码
//设置语言:
    'language' => 'zh-CN',   //简体中文
    'modules' => [
        //用户相关信息
        'home' => [
            'class' => 'backend\module\home\Module',
        ],
    ],
    'components' => [
//        邮箱发送配置
        'mailer' =>[
            'class'=>'yii\swiftmailer\Mailer',
            'viewPath'=>'@common/mail',
            'useFileTransport'=>false,
            'transport'=>[
                'class'=>'Swift_SmtpTransport',
                'host'=>'smtp.qq.com',
                'username'=>'2016253847@qq.com',
                'password'=>'ofvozpcqylqqdjah',
//                nxlriysuxfmubhea
                'port'=>'25',
                'encryption'=>'tls',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['2016253847@qq.com'=>'文件流转系统']
            ],
        ],
        'request' => [
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                //default为gii默认的控制器名称
                if(Yii::$app->controller->id!='default'){
                    if($response->getStatusCode()==200){
                        $response->data = [
                            'code' => $response->getStatusCode(),
                            'data' => is_array($response->data)?$response->data['data']:null,
                            'message' =>is_array($response->data)?$response->data['msg']:$response->data
                        ];
                    }else{
                        $response->data = [
                            'code' => $response->getStatusCode(),
                            'data' => $response->data,
                            'message' => "服务器错误"
                        ];
                    }

                }
            },
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false
            // 'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'index',
                ],

            ],
        ],

    ],
    'params' => $params,
    'defaultRoute' => 'index',//设置默认的控制器
];
