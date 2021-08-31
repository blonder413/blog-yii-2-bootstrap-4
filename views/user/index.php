<?php

use app\models\User;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-primary">
    
    <div class="box-body table-responsive no-padding">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
            'username',
//            'auth_key',
            'email:email',
            [
                'attribute' => 'photo',
                'format'    => 'raw',
                'value'     => function ($data) {
                    return Html::img(
                        Url::to('@web/img/users/'. $data['photo'], true),
                        ['width' => '70px', 'alt' => $data['username']]
                    );
                }
            ],
            [
                'attribute' => 'status',
                'filter'    => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'value' => $searchModel->status,
                    'data'  => [0 => 'DELETED', 9 => 'INACTIVE', 10 => 'ACTIVE'],
                    'options' => ['multiple' => false, 'placeholder' => 'Select status ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'format'    => 'raw',
                'value'     => function($searchModel) {
                    if ($searchModel->status === 0) {
                        return "<i class='fas fa-minus-circle'></i>";
                    } elseif ($searchModel->status === 9) {
                        return "<i class='fas fa-clock'></i>";
                    } elseif ($searchModel->status === 10) {
                        return "<i class='fas fa-check-circle'></i>";
                    }
                }
            ],
            //'verification_token',
            //'password_hash',
            //'password_reset_token',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {cambiar-estado}',
                'buttons' => [
                    'cambiar-estado' => function ($url, $model, $key) {
                        if ($model->status == User::STATUS_INACTIVE) {
                            return Html::a(
                                '<i class="far fa-thumbs-up"></i>', 
                                $url,
                                [
                                    'class' => 'btn btn-sm btn-success',
                                    'title' => Yii::t('app', 'Activar este usuario'),
                                    'data-confirm'  => "Desea activar a $model->username?",
                                ]
                            );
                        } elseif ($model->status == User::STATUS_ACTIVE) {
                            return Html::a(
                                '<i class="far fa-thumbs-down"></i>',
                                $url,
                                [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => Yii::t('app', 'Desactivar este usuario'),
                                    'data-confirm'  => "Desea desactivar a $model->username?",
                                ]
                            );
                        }
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a(
                            '<i class="fas fa-pencil-alt"></i>', 
                            $url,
                            [ 
                                'class' => 'btn btn-sm btn-primary',
                                'title' => Yii::t('app', 'Actualizar'), 
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                          return Html::a(
                              "<button type='button' class = 'btn btn-sm btn-danger btn-circle' >
                                <i class='fa fa-trash' aria-hidden='true'></i>
                              </button>", ["delete", "id" => $model->id], [
                              'title' => 'Eliminar',
                              'data-method'   => 'POST',
                              'data-confirm'  => 'Desea eliminar este registro?',
                          ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return yii\helpers\Url::to(['user/view', 'id' => $key]);
                    } elseif ($action === 'cambiar-estado') {
                        return yii\helpers\Url::to(['user/cambiar-estado', 'id' => $key]);
                    } elseif ($action == 'update') {
                        return yii\helpers\Url::to(['user/update/', 'id' => $key]);
                    } elseif ($action === 'delete') {
                        return yii\helpers\Url::to(['user/delete/', 'id' => $key]);
                    }
                }
            ],
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
        'rowOptions'    => function($model){
            if ($model->status == User::STATUS_INACTIVE) {
                return ['class' => 'bg-gradient-warning'];
            } elseif ($model->status == User::STATUS_ACTIVE) {
                return ['class' => 'bg-gradient-success'];
            } else {
                return ['class' => 'bg-gradient-danger'];
            }
        },
        'toolbar' => [
            '{toggleData}',
            '{export}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'responsive'    => true,
        'panel'     => [
            'after'=>Html::a('<i class="fas fa-redo"></i> Limpiar Tabla', ['index'], ['class' => 'btn btn-info']),
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Crear', ['/site/signup'], ['class' => 'btn btn-success']),
            'heading' => 'Administrar User',
            'type'  => GridView::TYPE_SUCCESS,
            
            
        ],
    ]); ?>


</div>
