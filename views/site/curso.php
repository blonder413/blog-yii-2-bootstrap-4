<?php

use app\models\Helper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\LinkPager;

/* @var $this yii\web\View */
setlocale(LC_TIME, 'es_CO.UTF-8');
$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if (isset($_GET['page'])) {
    $this->title = "Curso " . Html::encode("{$curso->curso}") . " - Página " . $_GET['page'] . " | Blonder413";
} else {
    $this->title = "Curso " . Html::encode("{$curso->curso}") . " | Blonder413";
}
$this->params['description'] = $curso->descripcion;

$this->params['breadcrumbs'][] = [
    'label' => 'curso', 
    'url' => ['curso/']
];
$this->params['breadcrumbs'][] = Html::encode("{$curso->curso}");
// parámetros para el sidebar
$this->params['categorias'] = $categorias;
$this->params['mas_visitados'] = $mas_visitados;
?>

<div class="row">
    <br>
    <div class="col-sm-12 col-md-3">
        <?=
        Html::img(
                '@web/img/cursos/' . $curso->imagen,
                [
                    "alt" => $curso->curso,
                    "class" => "img-fluid"
                ]
        )
        ?>
    </div>

    <div class="col-sm-12 col-md-9">
        <h2>
            <span class="glyphicon glyphicon-education small"></span>
            Curso: <?= Html::encode("{$curso->curso}") ?>
        </h2>
        <p>
            <?= $curso->descripcion ?>
        </p>
    </div>
</div>

<div class="row">

            <?php if (sizeof($articulos) === 0): ?>
        <div class='alert alert-danger'>Aún no existe material para este curso.</div>
            <?php endif; ?>

            <?php foreach ($articulos as $key => $value): ?>
        <article class="clear-fix">
            <div>
                <?=
                Html::a(
                        Html::encode("{$value->numero}") . " - " . Html::encode("{$value->tema}"),
                        [
                            "/articulo/" . Html::encode("{$value->slug}")
                        ],
                        [
                            "class" => "course-title",
                            'title' => Html::encode("{$value->tema}"),
                        ]
                )
                ?>

            </div>
        </article>
<?php endforeach; ?>
</div>

<div class="d-flex justify-content-center">
        <?= LinkPager::widget([
            'linkContainerOptions'   => [
                'href'  => '/inicio/pagina/'
            ],
            'pagination'=>$pagination
        ]); ?>
    </div>

<?php
if ($pagination->totalCount < 15) {
    $marginTop = "1em";
} else {
    $marginTop = 0;
}
?>

<div class="row" style="margin-top: <?= $marginTop; ?>">
    <div class="col-md-12">
        <!--
        <a class="twitter-share-button"
                data-counturl="https://dev.twitter.com/web/tweet-button" data-count="horizontal">
              Tweet
            </a>
        <div class="fb-like"></div>
        -->
<?php /* echo \ijackua\sharelinks\ShareLinks::widget(
  [
  'viewName' => '/site/shareLinks.php'   //custom view file for you links appearance
  ]
  ); */ ?>

        Compartir:
        <!-- Go to www.addthis.com/dashboard to generate a new set of sharing buttons -->

        <a href="https://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=http%3A%2F%2Fwww.blonder413.com%2Farticulo%2F<?= $curso->curso; ?>&pubid=ra-55d90fcb1d66e601&title=<?= $curso->curso; ?>&nbsp;|&nbsp;Blonder413" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/facebook.png" border="0" alt="Facebook"/></a>
        <a href="https://api.addthis.com/oexchange/0.8/forward/twitter/offer?url=http%3A%2F%2Fwww.blonder413.com%2Farticulo%2F<?= $curso->curso; ?>&pubid=ra-55d90fcb1d66e601&title=<?= $curso->curso; ?>&nbsp;|&nbsp;Blonder413" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/twitter.png" border="0" alt="Twitter"/></a>
        <a href="https://api.addthis.com/oexchange/0.8/forward/linkedin/offer?url=http%3A%2F%2Fwww.blonder413.com%2Farticulo%2F<?= $curso->curso; ?>&pubid=ra-55d90fcb1d66e601&title=<?= $curso->curso; ?>&nbsp;|&nbsp;Blonder413" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/linkedin.png" border="0" alt="LinkedIn"/></a>
        <a href="https://api.addthis.com/oexchange/0.8/forward/delicious/offer?url=http%3A%2F%2Fwww.blonder413.com%2Farticulo%2F<?= $curso->curso; ?>&pubid=ra-55d90fcb1d66e601&title=<?= $curso->curso; ?>&nbsp;|&nbsp;Blonder413" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/delicious.png" border="0" alt="Delicious"/></a>
        <a href="https://www.addthis.com/bookmark.php?source=tbx32nj-1.0&v=300&url=http%3A%2F%2Fwww.blonder413.com%2Farticulo%2F<?= $curso->curso; ?>&pubid=ra-55d90fcb1d66e601&title=<?= $curso->curso; ?>&nbsp;|&nbsp;Blonder413" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/addthis.png" border="0" alt="Addthis"/></a>

    </div>
</div>

    <hr>
    <h3 class="text-center">Otros cursos que podría interesarle</h3>
    
    <div class="row">
    <?php foreach ($cursos as $key => $value): ?>
        
            <div class="col-sm col-md-4">
                <?=
                Html::a(
                        Html::img(
                                "@web/img/cursos/$value->imagen",
                                [
                                    // 'class' => 'img-circle',
                                    'alt' => $value->curso,
                                    'class' => 'img-thumbnail',
                                ]
                        ),
                        "@web/curso/$value->slug",
                        [
                            //                        'target'    => '_blank',
                            //                        'rel'       => 'nofollow',
                            'title' => $value->descripcion,
                        ]
                )
                ?>
            </div>
            <div class="col-sm col-md-8">
                <h3><?= $value->curso ?></h3>
                <p><?= $value->descripcion ?></p>
                <p>
    <?=
    Html::a(
            'Ver Curso',
            ["/curso/$value->slug"],
            ['title' => 'Ver todos los capítulos del curso', 'class' => 'btn btn-primary']
    );
    ?>
                </p>
            </div>    
<?php endforeach; ?>
</div>