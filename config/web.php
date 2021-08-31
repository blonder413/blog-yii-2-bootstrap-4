<?php
use kartik\mpdf\Pdf;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/mailer.php';

$config = [
//    'catchAll' => ['site/offline'],
//    'defaultRoute' => 'site/about',
    'id' => 'basic',
    'language'  => 'es',
    'layout'    => 'periodico/main',
    'name'  => 'Blonder413',
    'sourceLanguage'    => 'es-CO',
    'version'           => '2.0',
    'timeZone'          => 'America/Bogota',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@facebook'     => 'https://www.facebook.com/Blonder413-111821686918726',
        '@github'       => 'https://github.com/blonder413/',
        '@twitter'      => 'https://twitter.com/blonder413',
        '@youtube'      => 'https://www.youtube.com/channel/UCOBMvNSxe08V5E9qExfFt4Q',
    ],
    'components' => [
        'assetManager' => [
            'linkAssets' => true,   // make simbolic link in linux insteed copy
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
/*                    "skin-blue",
                    "skin-black",
                    "skin-red",
                    "skin-yellow",
                    "skin-purple",
                    "skin-green",
                    "skin-blue-light",
                    "skin-black-light",
                    "skin-red-light",
                    "skin-yellow-light",
                    "skin-purple-light",
                    "skin-green-light"
*/
                    'skin' => 'skin-blue',
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '2AYqoWPlSdU65zoMfUh6p56fAefztJyo',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,  // Si habilitar el inicio de sesión basado en cookies
//            'loginUrl' => ['login'],
            'authTimeout' => 60 * 30,   // 30 minutos de inactividad para cierre de sesión automático
//            'returnUrl' => '/',   // ruta a la que redirije al iniciar sesión
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => $mailer,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'defaultTimeZone'   => 'America/Bogota',
//            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat'    => 'php:D j \d\e M \d\e Y g:i a',
            'locale'    => 'es_ES',
//            'language'  => 'es',
            'thousandSeparator' => '.',
            'decimalSeparator' => ',',
            'currencyCode' => '$'
        ],
        // https://demos.krajee.com/mpdf
        // setup Krajee Pdf component
        'pdf' => [
            'class'             => Pdf::classname(),
            'format'            => Pdf::FORMAT_LETTER,
            'mode'              => Pdf::MODE_CORE, 
            'orientation'       => Pdf::ORIENT_PORTRAIT,
            'destination'       => Pdf::DEST_BROWSER,
//            'cssFile'           => 'css/azul/pdf.css',
            'defaultFont'       => 'Roboto',
//            'marginTop'         => 13,
//            'marginBottom'      => 13,
//            'marginLeft'        => 13,
//            'marginRight'       => 13,
            'options'   => [
                'showWatermarkText' => true,
                'showWatermarkImage' => true,
            ],
            
            'defaultFontSize'   => 12,
            // refer settings section for all configuration options
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',  // el identificador del componente de aplicación DB connection. Por defecto'db'.
            // 'sessionTable' => 'my_session', // nombre de la tabla de sesión. Por defecto 'session'.
            // 'timeout' => 10, segundos de inactividad para expirar la sesión
        ],
        'authManager'       => [
            'class'         => 'yii\rbac\DbManager',
            'defaultRoles'  => ['guest'],
//            'itemTable'         => 'auth_item',         // tabla para guardar objetos de autorización
//            'itemChildTable'    => 'auth_item_child',   // tabla para almacenar jerarquía elemento autorización
//            'assignmentTable'   => 'auth_assignment',   // tabla para almacenar las asignaciones de elementos de autorización
//            'ruleTable'         => 'auth_rule',         // tabla para almacenar reglas
        ],
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'articulo-rest'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'apirest/articulo'],
                'articulo/<slug>'                   => 'site/articulo',
                'articulo/descarga/<slug>'          => 'site/descarga',
                'autor/<autor>/pagina/<page>'       => 'site/autor',
                'autor/<autor>'                     => 'site/autor',
                'categoria/<slug>/pagina/<page>'    => 'site/categoria',
                'categoria/<slug>'                  => 'site/categoria',
                'curso'                             => 'site/cursos',
                'curso/<slug>/pagina/<page>'        => 'site/curso',
                'curso/<slug>'                      => 'site/curso',
                'etiqueta/<etiqueta>/pagina/<page>' => 'site/etiqueta',
                'etiqueta/<etiqueta>'               => 'site/etiqueta',
                'acerca'                            => 'site/about',
                'inicio/pagina/<page>'              => 'site/index',
                'inicio'                            => 'site/index',
                'contacto'                          => 'site/contact',
                'login'                             => 'site/login',
                "registro"                          => "site/signup",
                'pdf/<slug>'                        => 'site/pdf',
                'portafolio'                        => 'site/portafolio',
                'en-vivo'                           => 'site/transmision',
                'DELETE <controller:\w+>/<id:\d+>'  => '<controller>/delete',
            ],
        ],
    ],
    'modules'   => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
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
        'generators' => [ // HERE
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'yii2-adminlte3' => '@vendor/hail812/yii2-adminlte3/src/gii/generators/crud/default', // template name => path to template
                    'myCrud' => '@app/myTemplates/crud/default',
                ]
            ]
        ],
    ];
}

return $config;
