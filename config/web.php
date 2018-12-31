<?php
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;

use mdm\admin\components\AccessControl; 
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii', 'debug' , 'InitClass'],
    'language' => 'en',
    // set source language to be English
    'sourceLanguage' => 'en-US',
	'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
		'@uploads' => '@app/web/uploads/',
    ],
    'modules' => [
	   'treemanager' =>  [
			'class' => 'kartik\tree\Module',
			// other module settings, refer detailed documentation
		],
		'admin' => [
            'class' => 'mdm\admin\Module',
			
			/*start*/
            'controllerMap' => [
                 'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    /* 'userClassName' => 'app\models\User', */
                    'idField' => 'user_id',
                    'usernameField' => 'username',
                    'fullnameField' => 'profile.full_name',
                    'extraColumns' => [
                        [
                            'attribute' => 'full_name',
                            'label' => 'Full Name',
                            'value' => function($model, $key, $index, $column) {
                                return $model->profile->full_name;
                            },
                        ],
                        [
                            'attribute' => 'dept_name',
                            'label' => 'Department',
                            'value' => function($model, $key, $index, $column) {
                                return $model->profile->dept->name;
                            },
                        ],
                        [
                            'attribute' => 'post_name',
                            'label' => 'Post',
                            'value' => function($model, $key, $index, $column) {
                                return $model->profile->post->name;
                            },
                        ],
                    ],
                    'searchClass' => 'app\models\MembersSearch'
                ],
            ],	
        ],	
		'gii' => [
			'class' => 'yii\gii\Module',
			'allowedIPs' => [],//'127.0.0.1', '::1', '192.168.56.*'
		],
		'debug' => [
			'class' => 'yii\debug\Module',
			'allowedIPs' => [],//'127.0.0.1', '::1', '192.168.56.*'
			// uncomment and adjust the following to add your IP if you are not connecting from localhost.
			//'allowedIPs' => ['127.0.0.1', '::1'],
		],			/*end*/
	   'datecontrol' =>  [
			'class' => 'kartik\datecontrol\Module',
			
			// format settings for displaying each date attribute (ICU format example)
			'displaySettings' => [
				Module::FORMAT_DATE => 'dd-MM-yyyy',
				Module::FORMAT_TIME => 'HH:mm:ss a',
				Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss a', 
			],
		
			// format settings for saving each date attribute (PHP format example)
			'saveSettings' => [
				Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
				Module::FORMAT_TIME => 'php:H:i:s',
				Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
			],
		
			// set your display timezone
			'displayTimezone' => 'Asia/Kolkata',
	
			// set your timezone for date saved to db
			'saveTimezone' => 'UTC',
			
			// automatically use kartik\widgets for each of the above formats
			'autoWidget' => true,
			
			// use ajax conversion for processing dates from display format to save format.
			'ajaxConversion' => true,
	
			// default settings for each widget from kartik\widgets used when autoWidget is true
			'autoWidgetSettings' => [
				Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true]], // example
				Module::FORMAT_DATETIME => [], // setup if needed
				Module::FORMAT_TIME => [], // setup if needed
			],
			
			// custom widget settings that will be used to render the date input instead of kartik\widgets,
			// this will be used when autoWidget is set to false at module or widget level.
			'widgetSettings' => [
				Module::FORMAT_DATE => [
					'class' => 'yii\jui\DatePicker', // example
					'options' => [
						'dateFormat' => 'php:d-M-Y',
						'options' => ['class'=>'form-control'],
					]
				]
			]
			// other settings
		],	
		'gridview' => [
			'class' => '\kartik\grid\Module'
			// enter optional module parameters below - only if you need to
			// use your own export download action or custom translation
			// message source
			// 'downloadAction' => 'gridview/export/download',
			// 'i18n' => []
			],
		],
    'components' => [
		'assetManager' => [ 'class' => 'yii\web\AssetManager', 'forceCopy' => false, ], 		
		'InitClass' =>[
			'class' => 'app\components\InitClass'
		 ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],	
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '32wTBi11nWbvGiYra6Kfsdfsdbv!4RPsXNwZcLiOo',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Members',	//amend thie to members to use members model for login
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // '' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
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
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'rules' => array(
				  '<controller:\w+>/<id:\d+>' => '<controller>/view',
				  '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				  '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),			
        ],
		/**/
		'common'=>[
			'class'=>"app\components\Common",
		],
		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'en',	//en-US
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
			],
		],
			
    ],
	/**/
	'as beforeRequest' => [
		'class' 	=> 'app\components\checkIfInitialised',
	],
    /**/
	'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
			'units-booked-by-students/*',
			'book/*',
			'members/*',
            'admin/*',
			'student-games/*',
			'marks-master/*',
			'branches/*',
			'authors/*',
			'gii/*',
			'companies/*',
			'payments/*',
			'loans/*',
			'general/*',
			'loans-type/*',
			'adjustable-charges/*',
			'shares-transfer-master/*',
			'constituency/*',
			'departments/*',
			'states/*',
			'countys/*',
			'citys/*',
			'phone-makes/*',
			'phone-models/*',
			'units/*',
			'events/*',
			'images/*',
			'rbac/*',
			'test/*',
			'country/*',
			'settings/*',
			'games/*',
			'streams/*',
			'jobs/*',
			'jobs-members/*',
			'codes/*',
			'countrys/*',
			'debug/*',
			
			//'user/*',
            //'some-controller/some-action',
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ],
    ],
	
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
