<?php
use yii\helpers\Html;
$this->title = 'Página no encontrada';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="clearfix"><p></p></div>

<div class="row">
    <div class="alert alert-danger text-center">
        <?= Html::img(
            '@web/img/404.png',
            [
                'alt'   => 'Página No Encontrada'
            ]
        ) ?>
        <p><small>
            <a href="https://es.vecteezy.com/vectores-gratis/mono" target="_blank">
                Mono Vectores por Vecteezy
            </a>
        </small></p>
        <h2><?= nl2br(Html::encode($message)) ?></h2>
        <p>
            Ha encontrado a <strong>Monker</strong>, lo que significa que al igual que él usted se encuentra perdido.
            <br>
            Si ha accedido desde un enlace de este sitio, por favor envíeme un correo indicando el enlace erróneo para corregirlo.
            <br>
            Si ha accedido desde un enlace externo, por favor use el buscador para encontrar lo que 
            desea o navegue a través del menú superior o el panel de la derecha.
        </p>
    </div>
</div>