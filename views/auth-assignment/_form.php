<?php

use app\models\AuthItem;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use Yii;
/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */

$this->registerJS(
    '$("documento").ready(function(){
        $("#nuevo_permiso").on("pjax:end", function(){
            $.pjax.reload({container: "#permisos"});
        })
    });'
);

?>

<div class="auth-assignment-form">

<?php Pjax::begin(['id' => 'nuevo_permiso']); ?>

<?php
foreach (Yii::$app->session->getAllFlashes() as $key => $value) {
    echo '<div class="alert alert-' . $key . '">' . $value . '</div>';
}
?>

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

    <?= $form->field($model, 'item_name')->widget(Select2::class, [
        'data' => ArrayHelper::map(AuthItem::find()->orderBy('name asc')->all(), 'name', 'name'),
        'language' => 'es',
        'options' => ['placeholder' => 'Seleccione ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'user_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(User::find()->orderBy('name asc')->all(), 'id', 'name'),
        'language' => 'es',
        'options' => ['placeholder' => 'Seleccione ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php Pjax::end(); ?>

</div>
