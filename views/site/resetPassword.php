<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Cambiar contraseña';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor elija su nueva contraseña:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->widget(
                    PasswordInput::classname(), [
                        'pluginOptions' => [
//                            'language' => 'es',
//                            'showMeter' => false,
//                            'toggleMask' => false,
                            'verdictTitles' => [
                                0 => 'Vacío',
                                1 => 'Muy pobre',
                                2 => 'Pobre',
                                3 => 'Regular', 
                                4 => 'Bueno',
                                5 => 'Excelente'
                            ],
                            'verdictClasses' => [
                                0 => 'text-muted',
                                1 => 'text-danger',
                                2 => 'text-warning',
                                3 => 'text-info', 
                                4 => 'text-primary',
                                5 => 'text-success'
                            ],
                        ],
                        'options' => [
                            'autocomplete' => 'new-password',
                            'autofocus' => true,
                        ]
                    ]
                ); ?>
                <?= $form->field($model, 'verify_password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
