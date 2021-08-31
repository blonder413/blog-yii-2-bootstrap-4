<?php
use app\models\Comentario;
use app\models\Seguridad;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title =  Html::encode( '(' . $pendientes . ') Comentarios');

echo "Última actualización a las " . date("g:i:s a");

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
//            'id',
        'nombre',
        [
            'attribute' => 'correo',
            'format' => 'email',
            'value' => function ($searchModel) {
                return Seguridad::desencriptar($searchModel->correo);
            }
        ],
        'web:url',
//        'rel',
        'comentario:ntext',
//        [
//            'attribute' => 'fecha',
//            'format' => ['date', 'php:D d M Y g:i:s a'],
//        ],
        [
            'attribute' => 'fecha',
            'filter'    => DatePicker::widget([
                'model' => $searchModel, 
                'attribute' => 'fecha',
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
            'attribute' => 'articulo_id',
            'format' => 'raw',
            'value' => function ($searchModel) {
                return Html::a(
                    $searchModel->articulo->titulo, 
                    "@web/articulo/" . $searchModel->articulo->slug
                );
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'articulo_id',
                'initValueText' => $searchModel->articulo_id != '' ? $searchModel->articulo->titulo : '',
                'options' => ['placeholder' => 'Busque el artículo'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Cargando Resultados...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['articulos/buscar']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(articulo) { return articulo.text; }'),
                    'templateSelection' => new JsExpression('function (articulo) { return articulo.text; }'),
                ],
            ]),
        //'options' => ['width' => '10%'],
        ],
        /*
        [
            'attribute' => 'estado',
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'estado',
                'value' => $searchModel->estado,
                'data' => [0 => 'INACTIVO', 1 => 'ACTIVO'],
                'options' => ['multiple' => false, 'placeholder' => 'Seleccione ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]),
            'format' => 'raw',
            'value' => function($searchModel) {
                if ($searchModel->estado === Comentario::ESTADO_INACTIVO) {
                    return "<span class='glyphicon glyphicon-remove'></span>";
                } else {
                    return "<span class='glyphicon glyphicon-ok'></span>";
                }
            }
        ],
        */
        //'ip',
        //'puerto',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete} {aprobar}',
            'buttons' => [
                'aprobar' => function ($url, $model, $key) {
                    if ($model->estado == Comentario::ESTADO_INACTIVO) {
                        return Html::a(
                            '<i class="far fa-thumbs-up"></i>',
                            $url,
                            [
                                'class' => 'btn btn-success',
                                'title' => Yii::t('app', 'Aprobar comentario'),
                            ]
                        );
                    }
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        "<i class='fas fa-trash' aria-hidden='true'></i>",
                        ["delete", "id" => $model->id], 
                        [
                            'class' => 'btn btn-danger',
                            'title' => 'Eliminar',
                            'data-method'   => 'POST',
                            'data-confirm'  => 'Desea eliminar este registro?',
                        ]
                    );
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(
                        '<i class="fas fa-pen"></i>',
                        $url,
                        [
                            'class' => 'btn btn-primary',
                            'title' => Yii::t('app', 'Actualizar'),
                        ]
                    );
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'aprobar') {
                    return yii\helpers\Url::to(['comentario/aprobar', 'id' => $key]);
                } elseif ($action == 'update') {
                    return yii\helpers\Url::to(['comentario/update/', 'id' => $key]);
                } elseif ($action === 'delete') {
                    return yii\helpers\Url::to(['comentario/delete/', 'id' => $key]);
                }
            }
        ],
    ],
    'export' => [
        'label' => 'Exportar',
        'messages' => [
            'confirmDownload' => 'De acuerdo para proceder',
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
    'hover' => true,
    'toolbar' => [
        '{toggleData}',
        '{export}',
    ],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'responsive' => true,
    'rowOptions' => function($model) {
        if (!$model->estado) {
            return ['class' => 'danger'];
        } else {
            return ['class' => 'success'];
        }
    },
    'panel' => [
        'after' => Html::a('<i class="fas fa-redo"></i> Limpiar Tabla', ['index'], ['class' => 'btn btn-info']),
//        'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Crear', ['create'], ['class' => 'btn btn-success']),
        'heading' => 'Administrar Comentario',
        'type' => GridView::TYPE_SUCCESS,
    ],
]);