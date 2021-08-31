<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transmision */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transmision-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'inicio')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Inicio de la transmisión ...'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]); ?>

    <?= $form->field($model, 'fin')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Fin de la transmisión ...'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
