<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Categoria */

$this->title = $model->categoria;
$this->params['breadcrumbs'][] = ['label' => 'Categorias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categoria-view">

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
            'categoria',
            'slug',
            [
                'attribute'=>'imagen',
                'value' => Url::to('@web/img/categorias/'. $model->imagen, true),
                'format' => ['image',['width'=>'70']],
            ],
            'descripcion',
            'usuarioCrea.name',
            'fecha_crea:datetime',
            'usuarioModifica.name',
            'fecha_modifica:datetime',
        ],
    ]) ?>

        <?= $this->renderAjax('/articulos/_index', [
            'dataProviderArticulo'  => $dataProviderArticulo,
            'searchArticulo'        => $searchArticulo,
        ]); ?>

</div>
