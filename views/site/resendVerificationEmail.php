<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reenviar correo de verificación';
$this->params['breadcrumbs'][] = $this->title;

// parámetros para el sidebar
$this->params['categorias'] = $categorias;
?>
<div class="site-resend-verification-email">

    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title"><?= Html::encode($this->title) ?></h2>
        </div>


        <div class="panel-body">
            <p>Por favor escriba su correo electrónico.
                Se enviará un enlace de verificación allí.</p>

            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

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
