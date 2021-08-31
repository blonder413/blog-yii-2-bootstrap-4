<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transmision */

$this->title = 'Actualizar Transmision: ' . $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Transmisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->titulo, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transmision-update">

    <div class='panel panel-warning'>
        <div class='panel-heading'>
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class='panel-body'>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
