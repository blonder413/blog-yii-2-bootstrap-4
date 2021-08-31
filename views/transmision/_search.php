<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransmisionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transmision-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'video') ?>

    <?= $form->field($model, 'inicio') ?>

    <?php // echo $form->field($model, 'fin') ?>

    <?php // echo $form->field($model, 'usuario_crea') ?>

    <?php // echo $form->field($model, 'fecha_crea') ?>

    <?php // echo $form->field($model, 'usuario_modifica') ?>

    <?php // echo $form->field($model, 'fecha_modifica') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
