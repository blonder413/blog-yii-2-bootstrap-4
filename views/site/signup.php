<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use kartik\file\FileInput;
use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


$this->title = 'Signup';
$this->params['categorias'] = $categorias;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup'], ['options' => ['enctype' => 'multipart/form-data']]); ?>
            
                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->widget(
                    PasswordInput::class, [
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
                        ]
                    ]
                ); ?>
            
                <?= $form->field($model, 'verify_password')->passwordInput(['autocomplete' => 'new-password']); ?>
            
                <?= $form->field($model, 'file')->widget(FileInput::class, [
                    'pluginOptions' => [
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' =>  'Seleccione una foto'
                    ],
                    'options' => ['accept' => 'image/*']
                ]); ?>

                <div class="form-group">
                    <?= Html::submitButton('Registrarse', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
