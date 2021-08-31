<?php

use app\models\Helper;
use kartik\growl\Growl;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\bootstrap4\Breadcrumbs;

/* @var $this yii\web\View */

// Pone la fecha en español
//setlocale(LC_TIME, 'es_CO.UTF-8');

if (isset($_GET['page'])) {
    $this->title = "Blonder413 - Página " . $_GET['page'];
} else {
    $this->title = "Blonder413";
}

$this->params['breadcrumbs'][] = [
    'label' => $articulo->categoria->categoria, 
    'url' => ['categoria/' . $articulo->categoria->slug]
];

$this->params['breadcrumbs'][] = $articulo->titulo;

// parámetros para el sidebar
$this->params['categorias'] = $categorias;

$formatter = \Yii::$app->formatter;
?>

<article class="blog-post">
    <h2 class="blog-post-title"><?= $articulo->titulo; ?></h2>
    <p class="blog-post-meta">
        <?=
        Html::a(
                ucwords($articulo->usuarioCrea->name),
                ['autor/' . urlencode($articulo->usuarioCrea->name)],
                [
                    'rel' => 'author', 
                    'title' => 'Ver artículos del usuario ' . $articulo->usuarioCrea->name
                ]
        );
        ?> | 
        <?= $formatter->asDatetime($articulo->fecha_crea); ?> | 
        <?=
        Html::a(
                $articulo->categoria->categoria,
                ['categoria/' . urlencode($articulo->categoria->slug)],
                [
                    'title' => 'Ver artículos de la categoría ' . $articulo->categoria->categoria
                ]
        );
        ?> | 
        <?= $articulo->vistas; ?> visitas

        <?php if (\Yii::$app->user->can('articulo-admin') or \Yii::$app->user->can('articulo-actualizar', ['articulos' => $articulo])): ?>

            |

        <?=
        Html::a(
            '<i class="fas fa-pen"></i> Actualizar',
            ['articulos/update', 'id' => $articulo->id], 
            [
                'title' => 'Actualizar artículo',
            ]
        );
        ?>

    <?php endif; ?>

    </p>

                <?= $articulo->detalle; ?>

                <?php if (!empty($articulo->video)): ?>
        <div class="video-responsive text-center">
                    <?= $articulo->video; ?>
        </div>
                <?php endif; ?>

    <div class="container">
        <div class="row text-center">
        <div class="col-xs-4 col-sm-4 col-md-4">
            <?php if ($articuloAnterior): ?>
            <?=
            Html::a(
                    "<i class='fas fa-step-backward'></i><span class='d-none d-sm-inline'>&nbsp;Capítulo Anterior</span>",
                    ["/articulo/$articuloAnterior->slug"],
                    [
                        'title' => $articuloAnterior->tema,
                    ]
            );
            ?>
            <?php endif; ?>
        </div>

        <div class="col-xs-4 col-sm-4 col-md-4">
            
                <?php if ($curso): ?>
            <?= Html::a(
                "<i class='fas fa-list-ol'></i><span class='d-none d-sm-inline'>Todos los capítulos</span>",
                ["/curso/$curso->slug"],
                [
                    'title' => $curso->curso,
                ]
            ); ?>
                <?php endif; ?>
            
        </div>

        <div class="col-xs-4 col-sm-4 col-md-4">
            
            <?php if ($articuloSiguiente): ?>
            <?=
            Html::a(
                    "<span class='d-none d-sm-inline'>Capítulo Siguiente&nbsp;</span><i class='fas fa-step-forward'></i>",
                    ["/articulo/$articuloSiguiente->slug"],
                    [
                        'title' => $articuloSiguiente->tema,
                    ]
            );
            ?>
            <?php endif; ?>
            
        </div>
        </div>
    </div>

    <div class="col-sm-12">
        <p>
            <span class="glyphicon glyphicon-tags">&nbsp;Etiquetas:&nbsp;</span>
            <?php foreach ($etiquetas as $key => $value): ?>
                <span class="badge">
                <?= Html::a($value, ["etiqueta/$value"]) ?>
                </span>
            <?php endforeach; ?>
        </p>
    </div>

    <div class="col-md-12">
        <div class="col-md-3">
            <?php if (!empty($article->download)): ?>

                <?=
                Html::a(
                        "<span class='glyphicon glyphicon-floppy-save'></span> Descargar",
                        // $article->download,
                        'descarga/' . $article->slug,
                        [
                            "class" => "btn btn-primary btn",
                            // 'target'    => '_blank',
                            'title' => 'Descargar',
                        ]
                )
                ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3">
            <?=
            Html::a(
                    "<i class='fas fa-file-pdf'></i>&nbsp;Exportar",
                    ['/pdf/' . $articulo->slug],
                    [
                        'class' => 'btn btn-primary btn',
                        'target' => '_blank',
                        'title' => 'Exportar a PDF',
                    ]
            )
            ?>
        </div>
<!--        <div class="col-md-6">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_donations">
                <input type="hidden" name="business" value="blonder413@gmail.com">
                <input type="hidden" name="lc" value="ES">
                <input type="hidden" name="item_name" value="blonder413">
                <input type="hidden" name="no_note" value="0">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
                <input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
                <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
            </form>
        </div>-->
    </div>

    <div class="comment-post">
        <?php
        if (Yii::$app->session->getFlash('success')) {
            echo Growl::widget([
                'type' => Growl::TYPE_SUCCESS,
                'title' => 'Comentario registrado!',
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
        } elseif (Yii::$app->session->getFlash('error')) {
            echo Growl::widget([
                'type' => Growl::TYPE_DANGER,
                'title' => 'Error al registrar el comentario!',
                'icon' => 'glyphicon glyphicon-ok-sign',
                'body' => Yii::$app->session->getFlash('error'),
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
        } elseif (Yii::$app->session->getFlash('download_error')) {
            echo Growl::widget([
                'type' => Growl::TYPE_DANGER,
                'title' => 'Error de descarga!',
                'icon' => 'glyphicon glyphicon-ok-sign',
                'body' => Yii::$app->session->getFlash('download_error'),
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

        <h2>Comentar</h2>

<?php
echo $this->render('/comentario/_form', [
    'model' => $comentario,
])
?>

        <a name="comments"></a>

        <h2>Comentarios</h2>

        <!-- listado de comentarios -->
        <ul class="item-comment">
                    <?php foreach ($articulo->comentarios as $key => $value): ?>
                        <?php if ($value->estado == 1): ?>
                    <li>
                        <span class="item-comment-name">
                           <i class="fas fa-user"></i>&nbsp;
                            <?php
                            if (empty($value->web)) {
                                echo Html::encode("{$value->nombre}");
                            } else {
                                echo Html::a(
                                        Html::encode("{$value->nombre}"),
                                        Html::encode("{$value->web}"),
                                        [
                                            'target' => '_blank',
                                            'rel' => $value->rel,
                                            'title' => Html::encode("{$value->web}"),
                                        ]
                                );
                            }
                            ?>
                        </span>
                        &nbsp;
                        <i class="far fa-calendar-alt"></i> <?= $formatter->asDatetime($value->fecha); ?>
                        <p><?= "<i class='fas fa-comment-alt'></i> " . Html::encode("{$value->comentario}"); ?></p>
                    </li>
    <?php endif; ?>
<?php endforeach; ?>
        </ul>
        <!-- fin listado de comentarios -->
    </div>

</article><!-- /.blog-post -->
