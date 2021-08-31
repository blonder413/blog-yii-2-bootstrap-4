<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransmisionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transmisiones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transmision-index box box-primary">
    
    <div class="box-body table-responsive no-padding">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'titulo',
            'descripcion:ntext',
            'video',
            [
                'attribute' => 'inicio',
                'filter'    => DatePicker::widget([
                    'model' => $searchModel, 
                    'attribute' => 'inicio',
    //                'type' => DatePicker::TYPE_BUTTON,
                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
                    'pluginOptions' => [
                        'autoclose'=> true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]),
                'format'    => 'datetime',
            ],
            [
                'attribute' => 'fin',
                'filter'    => DatePicker::widget([
                    'model' => $searchModel, 
                    'attribute' => 'fin',
    //                'type' => DatePicker::TYPE_BUTTON,
                    'options' => ['placeholder' => 'Seleccione una fecha ...'],
                    'pluginOptions' => [
                        'autoclose'=> true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]),
                'format'    => 'datetime',
            ],
            [
                'attribute' => 'usuario_crea',
                'value'     => 'usuarioCrea.name',
            ],
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
            'heading' => 'Administrar Transmision',
            'type'  => GridView::TYPE_SUCCESS,
            
            
        ],
    ]); ?>


</div>
