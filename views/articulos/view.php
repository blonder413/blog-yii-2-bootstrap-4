<?php

use app\models\Articulo;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Articulo */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Articulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="articulo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Seguro quiere eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'numero',
            [
                'attribute' => 'titulo',
                'format'    => 'raw',
                'value'     => function($data){
                    return Html::a(
                        $data->titulo,
                        Yii::getAlias('@web') . '/articulo/' . $data->slug,
                        [
                            'target'    => '_blank',
                            'title'     => 'Ver post',
                        ]
                    );
                }
            ],
            'slug',
            'tema',
            'detalle:ntext',
            'resumen',
            [
                'attribute' =>'video',
                'value'     => $model->video,
                'format'    => ['raw'],
            ],
            'descarga',
            'categoria.categoria',
            'etiquetas',
            [
                'attribute' => 'estado',
                //'format'      => 'raw',
                'value'     => function ($data) {
                    if ($data->estado == Articulo::ESTADO_ACTIVO) {
                        return "ACTIVO";
                    } elseif ($data->estado == Articulo::ESTADO_INACTIVO) {
                        return "INACTIVO";
                    }
                }
            ],
            'vistas',
            'descargas',
            'curso.curso',
            'usuarioCrea.name',
            'fecha_crea:datetime',
            'usuarioModifica.name',
            'fecha_modifica:datetime',
        ],
    ]) ?>

</div>
