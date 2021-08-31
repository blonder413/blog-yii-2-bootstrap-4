<?php

use app\models\AuthAssignment;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auth Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-index box box-primary">
    
    <div class="box-body table-responsive no-padding">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
        Modal::begin([
            'header'    => '<h3>Asignar permiso</h3>',
            'toggleButton'  => ['label' => '<i class="glyphicon glyphicon-plus"></i> Crear', 'class' => 'btn btn-success'],
        ]);

        echo $this->render('/auth-assignment/create', ['model' => new AuthAssignment()]);

        Modal::end();

        ?>
    </p>
<?php Pjax::begin(['id' => 'permisos']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_name',
            [
                'attribute'     => 'user_id',
                'value'         => 'user.name',
            ],
            'created_at',

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
//            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Crear', ['create'], ['class' => 'btn btn-success']),
            'heading' => 'Administrar Auth Assignment',
            'type'  => GridView::TYPE_SUCCESS,
            
            
        ],
    ]); ?>
<?php Pjax::end(); ?>

</div>
