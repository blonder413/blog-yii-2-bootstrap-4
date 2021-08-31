<?php

use app\models\Categoria;
use app\models\Curso;
use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Articulo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="articulo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero')->textInput(['inputType' => 'number']) ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tema')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'detalle')->widget(CKEditor::class,[
        'editorOptions' => [
            'preset' => 'basic', // basic, standard, full
            'inline' => false, // false
        ],
    ]); ?>

    <?= $form->field($model, 'resumen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descarga')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categoria_id')->widget(Select2::classname(), [
        'model' => $model,
        'initValueText' => isset($model->categoria_id) ? $model->categoria->categoria : '',
//                'attribute' => 'categoria_id',
        'options' => ['placeholder' => 'Busque la categoría'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Cargando Resultados...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['categorias/buscar']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function (categoria) { return categoria.text; }'),
            'templateSelection' => new JsExpression('function (categoria) { return categoria.text; }'),
        ],
    ]); ?>

    <?= $form->field($model, 'etiquetas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'curso_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Curso::find()->orderBy('curso asc')->all(), 'id', 'curso'),
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

</div>
