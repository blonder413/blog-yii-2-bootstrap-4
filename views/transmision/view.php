<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transmision */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Transmisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transmision-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'titulo',
            'descripcion:ntext',
            [
                'attribute' =>'video',
                'value'     => $model->video,
                'format'    => ['raw'],
            ],
            'inicio',
            'fin',
            'usuarioCrea.name',
            'fecha_crea:datetime',
            'usuarioModifica.name',
            'fecha_modifica:datetime',
        ],
    ]) ?>

</div>
