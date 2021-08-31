[![Gitter](https://badges.gitter.im/Yii-Framework-2/blog-yii-2.svg)](https://gitter.im/Yii-Framework-2/blog-yii-2?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

# Manejo de sesiones en la base de datos

(https://www.yiiframework.com/doc/api/2.0/yii-web-dbsession) Por defecto la 
clase ```yii\web\Session``` almacena los datos de sesión como ficheros en el 
servidor. Para cambiarlo a base de datos debemos modificar ```config\web.php```
```
return [
    'components' => [
        'session' => [
            'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',  // el identificador del componente de aplicación DB connection. Por defecto'db'.
            // 'sessionTable' => 'my_session', // nombre de la tabla de sesión. Por defecto 'session'.
        ],
    ],
];
```

y crear la tabla de sessions
```
CREATE TABLE session
(
    id CHAR(40) NOT NULL PRIMARY KEY,
    expire INTEGER,
    data BLOB
)
```

Configurar authManager en config\web.php para crear la migración de RBAC
```
'authManager'       => [
            'class'         => 'yii\rbac\DbManager',
            'defaultRoles'  => ['guest'],
//            'itemTable'         => 'auth_item',         // tabla para guardar objetos de autorización
//            'itemChildTable'    => 'auth_item_child',   // tabla para almacenar jerarquía elemento autorización
//            'assignmentTable'   => 'auth_assignment',   // tabla para almacenar las asignaciones de elementos de autorización
//            'ruleTable'         => 'auth_rule',         // tabla para almacenar reglas
],
```

Agregarlo también a config\console.php
```
'authManager'       => [
	'class'         => 'yii\rbac\DbManager',
	'defaultRoles'  => ['guest'],
],
```

Ejecutar las migraciones para crear las tablas

```
./yii migrate
```

Ejecutar la migración para crear las tablas del RBAC
```
./yii migrate --migrationPath=@yii/rbac/migrations
```
Ejecutar el seeder para tener datos de prueba. La configuración se encuentra en commands\SeedController

```
./yii seed/user
./yii seed/categoria
./yii seed/curso
./yii seed/articulo
./yii seed/comentario
```

Para insertar roles y permisos ejecute el seed RBAC

```
./yii rbac/init
```

Insertar las reglas para que los usuarios solo puedan interactuar con sus propios registros

```
./yii rbac/reglas
```

Se debe crear un registro en auth_item que tenga la regla, por ejemplo
propio-registro y la ruleName = esAutor.

propio registro debe ser el padre del permiso que quiera restringir, por ejemplo
articulo-ver o articulo-actualizar

se debe asignar el permiso propio registro a los usuarios que quiera restringir
en lugar de asignar el permiso ver o eliminar

# Extensiones

- Imagine Extension for Yii 2 (https://github.com/yiisoft/yii2-imagine)
```
composer require yiisoft/yii2-imagine
```

- yii2-grid ( https://demos.krajee.com/grid )

```
composer require kartik-v/yii2-grid "dev-master"
```

- AdminLTE Asset Bundle ( https://github.com/dmstr/yii2-adminlte-asset#adminlte-plugins )

```
composer require dmstr/yii2-adminlte-asset "^2.1"
```

- hail812/yii2-adminlte3 ( https://www.yiiframework.com/extension/hail812/yii2-adminlte3 )

```
composer require --prefer-dist hail812/yii2-adminlte3 "*"
```

- yii2-password ( https://demos.krajee.com/password-details/password-input )

```
composer require kartik-v/yii2-password "dev-master"
```

- yii2-widget-datetimepicker ( https://github.com/kartik-v/yii2-widget-datetimepicker )

```
composer require kartik-v/yii2-widget-datetimepicker "*"
```

- Bootstrap DatePicker Widget for Yii2 ( https://github.com/2amigos/yii2-date-picker-widget )

```
composer require 2amigos/yii2-date-picker-widget:~1.0
```

- yii2-mpdf ( https://demos.krajee.com/mpdf )

```
composer require kartik-v/yii2-mpdf "dev-master"
```

- yii2-widget-select2 ( https://github.com/kartik-v/yii2-widget-select2 )

```
composer require kartik-v/yii2-widget-select2 "@dev"
```

- mihaildev/yii2-ckeditor ( https://www.yiiframework.com/extension/mihaildev/yii2-ckeditor )


```
composer require --prefer-dist mihaildev/yii2-ckeditor "*"
```


- yii2-widget-growl ( https://demos.krajee.com/widget-details/growl )

```
composer require kartik-v/yii2-widget-growl "dev-master"
```

- Yii2-widget-fileinput ( https://demos.krajee.com/widget-details/fileinput )

```
composer require kartik-v/yii2-widget-fileinput "dev-master"
```

- yii2-highcharts-widget ( https://www.yiiframework.com/extension/yii2-highcharts-widget )


```
composer require --prefer-dist miloschuman/yii2-highcharts-widget "*"
```

- yii2-widget-datepicker ( https://demos.krajee.com/widget-details/datepicker )


```
composer equire kartik-v/yii2-widget-datepicker "dev-master"
composer require kartik-v/yii2-field-range "*"
```


- yii2-lightbox ( https://github.com/yeesoft/yii2-lightbox )

```
composer require --prefer-dist yeesoft/yii2-lightbox "~0.1.0"
```


-  yii2-icons (https://github.com/kartik-v/yii2-icons)

```
composer require kartik-v/yii2-icons "@dev"
```
