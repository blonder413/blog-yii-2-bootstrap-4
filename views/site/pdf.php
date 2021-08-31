<?php
setlocale(LC_TIME, 'es_CO.UTF-8');
$formatter = \Yii::$app->formatter;
use yii\helpers\Html;
?>


<h1><?= $articulo->titulo ?></h1>

<p class='data-author'>
    Publicado por: <strong><?= $articulo->usuarioCrea->name ?></strong>
    el día <strong><?= $formatter->asDatetime($articulo->fecha_crea); ?></strong>
</p>

<?= $articulo->detalle ?>

<?php if ($articulo->video and $articulo->descarga): ?>
    <div class='alert-warning'>
        Este artículo contiene material y un video que puede ver y descargar desde 
        <?= Html::a(
            Yii::$app->homeUrl . "articulo/$articulo->slug",
            Yii::$app->homeUrl . "articulo/$articulo->slug"
        ) ?>
    </div>


<?php elseif ($articulo->video): ?>
    <div class='alert-warning'>
        Este artículo contiene un video que puede ver desde 
        <?= Html::a(
            Yii::$app->homeUrl . "articulo/$articulo->slug",
            Yii::$app->homeUrl . "articulo/$articulo->slug"
        ) ?>
    </div>


<?php elseif ($articulo->descarga): ?>
    <div class='alert-warning'>
        Este artículo contiene material que puede descargarse desde 
        <?= Html::a(
            Yii::$app->homeUrl . "articulo/$articulo->slug",
            Yii::$app->homeUrl . "articulo/$articulo->slug"
        ) ?>
    </div>
<?php endif; ?>