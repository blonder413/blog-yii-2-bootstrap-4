<?php
use app\models\Comentario;
use app\models\Seguridad;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ComentarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comentarios';
$this->params['breadcrumbs'][] = $this->title;
$ruta = Yii::$app->homeUrl . 'comentario/index-ajax';

$script = <<< JS
$(document).ready(function() {
//    setInterval(function() {
//      $.pjax.reload({container:'#result_ajax'});
//    }, 450000);
    setInterval(function() {
//        var id = $("#id").val();
        var pendientes = $pendientes;
        $("#resultado_ajax").load("$ruta",{pendientes:pendientes},function(){});
    }, 1000 * 60 * 1); // 1000ms * 60seg * 10min = 10min
});
JS;
$this->registerJs($script);
?>
<div class="comentario-index box box-primary">
    
    <div class="box-body table-responsive no-padding">

    <?php Pjax::begin(); ?>
        <div id="resultado_ajax">
            <!-- Muestro algo cuando carga la página por primera vez -->
            <?php $pendientes = Comentario::find()->where(['estado' => Comentario::ESTADO_INACTIVO])->count(); ?>
            <?= $this->renderAjax('_index',
                [
                    'searchModel'   => $searchModel,
                    'dataProvider'  => $dataProvider,
                    'pendientes'    => $pendientes,
                ], null
            ); ?>
            <!-- /Muestro algo cuando carga la página por primera vez -->
        </div>
    <?php Pjax::end(); ?>
    
    </div>

</div>
