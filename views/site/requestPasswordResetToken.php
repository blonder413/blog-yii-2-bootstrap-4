<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Solicitud para cambiar contraseña';
$this->params['breadcrumbs'][] = $this->title;

// parámetros para el sidebar
$this->params['categorias'] = $categorias;
?>
<div class="site-request-password-reset">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title"><?= Html::encode($this->title) ?></h2>
        </div>

        <div class="panel-body">
            <p>Por favor ingrese su correo electrónico, allí se le enviará un enlace para cambiar su contraseña.</p>

            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
