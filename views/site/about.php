<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\models\Helper;
use yeesoft\lightbox\Lightbox;

/* @var $this yii\web\View */
$this->title = 'Acerca';
$this->params['breadcrumbs'][] = $this->title;
// parámetros para el sidebar
$this->params['categorias'] = $categorias;
$this->params['mas_visitados'] = $mas_visitados;
?>

<?php
$script = <<< JS
JS;
$this->registerJs($script);
?>

<div class="col-md-12">
    
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    
    <div class="d-flex justify-content-center text-center">
        <figure>

                <?= Lightbox::widget([
                    'options' => [
                        'fadeDuration' => '2000',
    //                    'albumLabel' => "Image %1 of %2",
                    ],
                    'linkOptions' => ['class' => 'btn'],
                    'imageOptions' => [
                        'class' => 'text-center thumbnail',
                        'alt' => 'Jonathan Morales Salazar',
                    ],
                    'items' => [
                        [
                            'thumb' => '@web/img/foto_thumbnail.jpg',
                            'image' => '@web/img/foto.jpg',
                            'title' => 'Jonathan Morales Salazar',
    //                        'group' => 'image-set1',
                        ],
                    ],
                ]);
                ?>
            <figcaption>
                
                <div class="vcard">
                    <div class="fn n">
                        <span class="given-name">Jonathan</span> <span class="family-name">Morales Salazar</span><br />
                        <!-- <span class="org">El Ejemplo S. A.</span> -->
                    </div>
                    <div>
                        <span class="nickname">Blonder413</span>
                    </div>
                    <div class="adr">
                    <!-- <span class="street-address">Calle falsa 1</span><br /> -->
                        <span class="locality" title="La Dorada">La Dorada</span>,
                        <abbr class="region" title="Caldas">Caldas</abbr>,
                        <!-- <span class="postal-code">94301</span>, -->
                        <abbr class="country-name" title="Colombia">Colombia</abbr>
                    </div>
                    <!-- <li class="tel"><strong class="type" title="Teléfono del trabajo">Work</strong>: <span class="value">604-555-1234</span></li> -->
                        <!-- <li class="url"><strong class="type" title="Sitio web oficial del trabajo">Work</strong>: <a href="http://ejemplo.com/" title="Ejemplo.com" class="value">http://ejemplo.com/</a></li> -->
                </div>
                <span class="negrita">Edad</span>: <?= Helper::calculaEdad("1985-04-13"); ?> años
                <br />
                <span class="negrita">Ocupación</span>: Ingeniero sistemas
                
            </figcaption>
        </figure>
    </div>
        <h3>Otros títulos</h3>
        <ul class="list-style-none">
            <li>
                Emprendimiento Empresarial
            </li>
            <li>
                Programación de páginas Web con HTML y Javascript
            </li>
            <li>
                Linux: Sistema Operativo, comandos y utilidad
            </li>
        </ul>

        <h3>Software utilizado para el desarrollo</h3>
        <ul class="list-style-none">
            <li>
                <a href="https://netbeans.apache.org/" title="NetBeans IDE" target="_blank">
                    NetBeans IDE
                </a>
            </li>
            <li>
                <a href="http://www.mozilla.org/es-ES/firefox/fx/" title="Mozilla Firefox" target="_blank">
                    Mozilla Firefox
                </a>
            </li>
            <li>
                <a href="https://blonder413.blogspot.com/2020/05/instalar-lampp-en-ubuntu-2004.html" title="LAMP" target="_blank">
                    LAMPP
                </a>
            </li>
            <li>
                <a href="http://www.gimp.org/" title="GIMP" target="_blank">
                    Gimp
                </a>
            </li>
            <li>
                <a href="http://inkscape.org/" title="Inkscape" target="_blank">
                    Inkscape
                </a>
            </li>
            <li>
                <a href="https://getcomposer.org/" title="Composer" target="_blank">
                    Composer
                </a>
            </li>
            <li>
                <a href="https://www.yiiframework.com/" title="Composer" target="_blank">
                    Yii framework
                </a>
            </li>
            <li>
                <a href="https://git-scm.com/" title="Composer" target="_blank">
                    Git
                </a>
            </li>
            <li>
                <a href="https://www.mysql.com/products/workbench/" title="Composer" target="_blank">
                    MySQL Workbench
                </a>
            </li>
            <li>
                <a href="https://www.phpmyadmin.net/" title="Composer" target="_blank">
                    phpMyAdmin
                </a>
            </li>
        </ul>

        <hr>
        <div class="row">
            <iframe width="100%" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=-74.74908828735353%2C5.427265668386307%2C-74.60575103759767%2C5.5045038058395015&amp;layer=mapnik" style="border: 1px solid black"></iframe>
            <br/><small><a href="https://www.openstreetmap.org/#map=14/5.4659/-74.6774" target='_blank'>Ver mapa más grande</a></small>
        </div>
</div>