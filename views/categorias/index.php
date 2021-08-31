<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categorias';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
<?php
    echo Html::beginForm(['procesar'], 'post');

    echo Html::submitButton(
        'Procesar imágenes', 
        ['value'=> 'img','class' => 'btn btn-primary','name'=>'submit']
    );
    ?>
</p>

<div class="categoria-index box box-primary">
    
    <div class="box-body table-responsive no-padding">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
//                if ( $model->estadoid ) {
                    return ['value' => $model->id];
//                } else {
//                    return ['style' => ['display' => 'none']]; // OR ['disabled' => true]
//                    }

                },
            ],

//            'id',
            'categoria',
//            'slug',
            [
                'attribute' => 'imagen',
                'format'    => 'raw',
                'value'     => function ($data) {
                    return Html::img(
                        Url::to('@web/img/categorias/'. $data['imagen'], true),
                        ['width' => '70px', 'alt' => $data['categoria']]
                    );
                }
            ],
            'descripcion',
            //'usuario_crea',
            //'fecha_crea',
            //'usuario_modifica',
            //'fecha_modifica',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'export'    => [
            'label'     => 'Exportar',
            'messages'  => [
                'confirmDownload'   => 'De acuerdo para proceder',
            ],
//            'showConfirmAlert'  => false,
        ],
        'exportConfig' => [
//            GridView::HTML => [
//            ],
            GridView::CSV => [
            ],
//            GridView::TEXT => [
//            ],
            GridView::EXCEL => [
                'label' => ( 'XLS'),
                'iconOptions' => ['class' => 'text-success'],
                'showHeader' => true,
                'showPageSummary' => true,
                'showFooter' => true,
                'showCaption' => true,
                'filename' => ('archivoDeBlonder413'),
                'alertMsg' => ( 'El archivo de excel se va descargar.'),
                'options' => ['title' => ( 'Excel')],
                'mime' => 'application/vnd.ms-excel',
                'config' => [
                    'worksheet' => ( 'ExportWorksheet'),
                    'cssFile' => '',
                ]
            ],
            GridView::PDF => [
            ],
//            GridView::JSON => [
//            ],
        ],
        'hover'         => true,
        'toolbar' => [
            '{toggleData}',
            '{export}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'responsive'    => true,
        'panel'     => [
            'after'=>Html::a('<i class="fas fa-redo"></i> Limpiar Tabla', ['index'], ['class' => 'btn btn-info']),
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Crear', ['create'], ['class' => 'btn btn-success']),
            'heading' => 'Administrar Categoria',
            'type'  => GridView::TYPE_SUCCESS,
            
            
        ],
    ]); ?>


</div>
