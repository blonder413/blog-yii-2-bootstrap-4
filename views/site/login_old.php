<?php

use kartik\growl\Growl;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

$this->title = 'Login';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

if (Yii::$app->session->getFlash('signup')) {
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'title' => 'Usuario registrado!',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('signup'),
        'showSeparator' => true,
        'delay' => 0, // time before display
        'pluginOptions' => [
            'placement' => [
                'from' => 'top',
                'align' => 'center',
            ],
            'showProgressbar' => false,
            'timer' => 3000, // screen time
        ]
    ]);
} else if (Yii::$app->session->getFlash('request')) {
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'title' => 'Solicitud recibida!',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('request'),
        'showSeparator' => true,
        'delay' => 0, // time before display
        'pluginOptions' => [
            'placement' => [
                'from' => 'top',
                'align' => 'center',
            ],
            'showProgressbar' => false,
            'timer' => 3000, // screen time
        ]
    ]);
} else if (Yii::$app->session->getFlash('new-password')) {
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'title' => 'Contraseña modificada!',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('new-password'),
        'showSeparator' => true,
        'delay' => 0, // time before display
        'pluginOptions' => [
            'placement' => [
                'from' => 'top',
                'align' => 'center',
            ],
            'showProgressbar' => false,
            'timer' => 3000, // screen time
        ]
    ]);
}
?>

<div class="login-box">
    <div class="login-logo">
        <a href="<?= Yii::$app->homeUrl ?>"><b>Blonder</b>413</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Por favor complete los siguientes campos para iniciar sesión</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?=
                $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')])
        ?>

        <?=
                $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
        ?>

        <div class="row">
            <div class="col-xs-8">
<?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
        <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


<?php ActiveForm::end(); ?>

    <p>
        Si olvidó su contraseña, puede 
        <?= Html::a(
            'restablecerla', 
            ['site/request-password-reset'],
            [
                'title' => 'Restablecer contraseña',
            ]
        ) ?>.
    </p>
    <p>
        ¿Necesita un nuevo correo electrónico de verificación? 
        <?= Html::a(
            'Reenviar', 
            ['site/resend-verification-email'],
            [
                'title' => 'Reenviar correo de verificación',
            ]
        ) ?>
    </p>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->