<?php

use app\models\Articulo;
use app\models\Categoria;
use app\models\Curso;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchArticulo app\models\ArticuloSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
    <?= GridView::widget([
        'dataProvider' => $dataProviderArticulo,
        'filterModel' => $searchArticulo,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'numero',
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
//            'slug',
//            'tema',
            //'detalle:ntext',
            //'resumen',
            //'video',
            //'descarga',
            [
                'attribute' => 'categoria_id',
                'value'     => 'categoria.categoria',
                'format'    => 'raw',
                'filter'    => Select2::widget([
                    'model' => $searchArticulo,
                    'attribute' => 'categoria_id',
                    'data' => ArrayHelper::map(Categoria::find()->orderBy('categoria asc')->all(), 'id', 'categoria'),
                    'options' => ['placeholder' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            //'etiquetas',
            //'estado',
            [
                'attribute' => 'vistas',
//                'width' => '150px',
//                'hAlign' => 'right',
                'format' => ['decimal', 0],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_AVG
            ],
            'cantidadComentarios',
            [
                'attribute' => 'descargas',
//                'width' => '150px',
//                'hAlign' => 'right',
                'format' => ['decimal', 0],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_AVG
            ],
            [
                'attribute' => 'curso_id',
                'value'     => 'curso.curso',
                'format'    => 'raw',
                'filter'    => Select2::widget([
                    'model' => $searchArticulo,
                    'attribute' => 'curso_id',
                    'data' => ArrayHelper::map(Curso::find()->orderBy('curso asc')->all(), 'id', 'curso'),
                    'options' => ['placeholder' => 'Seleccione...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute'     => 'usuario_crea',
                'value'         => 'usuarioCrea.name',
            ],
            //'fecha_crea',
//            [
//                'attribute'     => 'usuario_modifica',
//                'value'         => 'usuarioModifica.name',
//            ],
            //'fecha_modifica',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {cambiar-estado}',
                'buttons' => [
                    'cambiar-estado' => function ($url, $model, $key) {
                        if ($model->estado == Articulo::ESTADO_INACTIVO) {
                            return Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', $url,
                                [ 'title' => Yii::t('app', 'Activar este artículo'), ]
                            );
                        } elseif ($model->estado == Articulo::ESTADO_ACTIVO) {
                            return Html::a('<span class="glyphicon glyphicon-thumbs-down"></span>', $url,
                                [ 'title' => Yii::t('app', 'Inactivar este artículo'), ]
                            );
                        }
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,
                            [ 'title' => Yii::t('app', 'Actualizar'), ]
                        );
                    },
//                    'delete' => function ($url, $model) {
//                          return Html::a(
//                              "<button type='button' class = 'btn btn-danger btn-circle' >
//                                <i class='fa fa-trash' aria-hidden='true'></i>
//                              </button>", ["delete", "id" => $model->id], [
//                              'title' => 'Eliminar',
//                              'data-method'   => 'POST',
//                              'data-confirm'  => 'Desea eliminar este registro?',
//                          ]);
//                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return yii\helpers\Url::to(['articulos/view', 'id' => $key]);
                    } elseif ($action === 'cambiar-estado') {
                        return yii\helpers\Url::to(['articulos/cambiar-estado', 'id' => $key]);
                    } elseif ($action == 'update') {
                        return yii\helpers\Url::to(['articulos/update/', 'id' => $key]);
                    } elseif ($action === 'delete') {
                        return yii\helpers\Url::to(['articulos/delete/', 'id' => $key]);
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
        'toolbar' => [
            '{toggleData}',
            '{export}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'responsive'    => true,
        'rowOptions'    => function($model){
            if ($model->estado == Articulo::ESTADO_INACTIVO) {
                return ['class' => 'danger'];
            } elseif ($model->estado == Articulo::ESTADO_ACTIVO) {
                return ['class' => 'success'];
            }
        },
        'panel'     => [
            'after'=>Html::a('<i class="fas fa-redo"></i> Limpiar Tabla', ['index'], ['class' => 'btn btn-info']),
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Crear', ['create'], ['class' => 'btn btn-success']),
            'heading' => 'Administrar Artículos',
            'type'  => GridView::TYPE_SUCCESS,
            
            
        ],
    ]); ?>
