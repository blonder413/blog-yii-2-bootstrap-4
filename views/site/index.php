<?php
use kartik\growl\Growl;
use yii\helpers\Html;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\LinkPager;
/* @var $this yii\web\View */

// Pone la fecha en español
//setlocale(LC_TIME, 'es_CO.UTF-8');

if (isset($_GET['page'])) {
    $this->title = "Blonder413 - Página " . $_GET['page'];
} else {
    $this->title = "Blonder413";
}

// parámetros para el sidebar
$this->params['categorias'] = $categorias;
$this->params['mas_visitado'] = $mas_visitado;
$this->params['aleatorios'] = $aleatorios;

$formatter = \Yii::$app->formatter;
?>

    <?= Breadcrumbs::widget([
      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?php if (sizeof($articulos) == 0): ?>
    <div class='no-content'>
        <div class='alert alert-danger'>
            No existen registros disponibles
        </div>
    </div>
    <?php endif; ?>

    <?php foreach ($articulos as $key => $value): ?>
    <article class="blog-post">
        <h2 class="blog-post-title"><?= $value->titulo; ?></h2>
        <p class="blog-post-meta">
            <time datetime="<?= $value->fecha_crea; ?>"><?= $formatter->asRelativeTime($value->fecha_crea); ?></time> por 
            <?= Html::a(
                $value->usuarioCrea->name,
                ['autor/' . urlencode($value->usuarioCrea->name)]
            ) ;?>
        </p>

        <?= $value->resumen; ?>
        
        <p>
            <?= Html::a(
                "Comentarios <span class='badge badge-light'>$value->cantidadComentarios</span>",
                ['/articulo/' . Html::encode("{$value->slug}") . '#comments'],
                [
                    'class' => 'btn btn-secondary',
                    'title' => 'Ver Comentarios',
                ]
            ) ?>

            <?= Html::a(
                'Ver más &raquo;', 
                ["/articulo/$value->slug"],
                ['class' => 'btn btn-secondary']
            ); ?>
            
        </p>
    </article><!-- /.blog-post -->
    <?php endforeach; ?>

    <div class="d-flex justify-content-center">
        <?= LinkPager::widget([
            'linkContainerOptions'   => [
                'href'  => '/inicio/pagina/'
            ],
            'pagination'=>$pagination
        ]); ?>
    </div>

<?php
if (Yii::$app->session->getFlash('success')) {
    echo Growl::widget([
        'type' => Growl::TYPE_SUCCESS,
        'title' => 'Usuario registrado!',
        'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => Yii::$app->session->getFlash('success'),
        'showSeparator' => true,
        'delay' => 0, // time before display
        'pluginOptions' => [
            'placement' => [
                'from' => 'top',
                'align' => 'center',
            ],
            'showProgressbar' => false,
            'timer' => 3000, // screen time
        ]
    ]);
}
?>