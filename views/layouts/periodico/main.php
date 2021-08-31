<?php
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use \kartik\icons\FontAwesomeAsset;
FontAwesomeAsset::register($this);
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$formatter = \Yii::$app->formatter;
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    
    <meta name='author' content='Jonathan Morales Salazar'>
    <meta name='copyright' content='blonder413.wordpress.com'>
    <meta name='designer' content='blonder413.wordpress.com'>
    <meta name='publisher' content='blonder413.wordpress.com'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="generator" content="Jekyll v4.1.1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="<?= Yii::$app->homeUrl ?>css/periodico/estilos.css" rel="stylesheet" type="text/css">
    <link href="<?= Yii::$app->homeUrl ?>img/favicon.png" rel="icon" type="image/vnd.microsoft.icon"/>

    <link rel="alternate" type="application/rss+xml" title="RSS feed" href="/rss.xml" />
    
    <link rel="canonical" href="/web/tweet-button">
    <link rel="me" href="https://twitter.com/blonder413">
    

    <!-- Bootstrap core CSS -->
<link href="/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Favicons -->
  <!--
    <link rel="apple-touch-icon" href="/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="/docs/4.5/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon.ico">
<meta name="msapplication-config" content="/docs/4.5/assets/img/favicons/browserconfig.xml">
<meta name="theme-color" content="#563d7c">
-->

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="blog.css" rel="stylesheet">
  </head>
  <body>
      
      <?php $this->beginBody() ?>
      
    <div class="container">
  <header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 pt-1">
        <?= Html::a(
            'Registrarse',
            ['/registro'],
            [
                'class' => 'text-muted',
                'title' => 'Registrarse',
            ]
        ); ?>
      </div>
      <div class="col-4 text-center">
            <?= Html::a(
                Yii::$app->name,
                Yii::$app->homeUrl,
                [
                    'class' => 'blog-header-logo text-dark',
                    'title' => Yii::$app->name,
                ]
            ); ?>
      </div>
      <div class="col-4 d-flex justify-content-end align-items-center">
<!--        <a class="text-muted" href="#" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </a>-->
        <?php if (Yii::$app->user->isGuest): ?>
        <?= Html::a(
            'Entrar',
            ['/login'],
            [
                'class'     => 'btn btn-sm btn-outline-secondary',
            ]
        ); ?>
        <?php else: ?>
        <?= Html::a(
            'Cerrar Sesión',
            ['/site/logout'],
            [
                'class'     => 'btn btn-sm btn-outline-secondary',
                'data-method' => 'post',
            ]
        ); ?>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <div class="nav-scroller py-1 mb-2">
    <nav class="nav d-flex justify-content-between">
      <a class="p-2 text-muted" href="<?= Yii::$app->homeUrl ?>">Inicio</a>
      <a class="p-2 text-muted" href="<?= Yii::$app->homeUrl ?>portafolio">Portafolio</a>
      <a class="p-2 text-muted" href="<?= Yii::$app->homeUrl ?>acerca">Acerca</a>
      <a class="p-2 text-muted" href="<?= Yii::$app->homeUrl ?>en-vivo">En vivo</a>
      <a class="p-2 text-muted" href="<?= Yii::$app->homeUrl ?>contacto">Contacto</a>
      <a class="p-2 text-muted" href="<?= Yii::$app->homeUrl ?>curso">Cursos</a>
      
      <?php if (!Yii::$app->user->isGuest): ?>
        <?= Html::a(
            'Admin',
            ['/site/admin'],
            ['class' => 'p-2 text-muted']
        ); ?>
      <?php endif ?>
    </nav>
  </div>

    <?php if( isset($this->params['mas_visitado']) ): ?>
  <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
    <div class="col-sm-12 px-0">
      <h1 class="display-4 font-italic">
        <?= $this->params['mas_visitado']->titulo; ?>
      </h1>
      <p class="lead my-3">
          <?= $this->params['mas_visitado']->resumen; ?>
      </p>
      <p class="lead mb-0">
            <?= Html::a(
                'Ver más', 
                "/articulo/" . $this->params['mas_visitado']->slug,
                [
                    'class' => 'text-white font-weight-bold',
                ]
            ); ?>
      </p>
    </div>
  </div>
    <?php endif; ?>

        <?php if( isset($this->params['aleatorios']) ): ?>
  <div class="row mb-2">
      <?php foreach($this->params['aleatorios'] as $key => $value): ?>
    <div class="col-md-6">
      <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <strong class="d-inline-block mb-2 text-primary"><?= $value->categoria->categoria; ?></strong>
          <h4 class="mb-0"><?= $value->titulo; ?></h4>
          <div class="mb-1 text-muted"><time datetime="<?= $value->fecha_crea; ?>"><?= $formatter->asRelativeTime($value->fecha_crea); ?></time></div>
<!--          <p class="card-text mb-auto"><?= $value->resumen; ?></p>-->
          <?= Html::a(
                'Ver más', 
                "/articulo/" . $value->slug,
                [
                    'class' => 'stretched-link',
                ]
            ); ?>
        </div>
        <div class="col-auto d-none d-lg-block m-auto">
            <?= Html::img(
                '@web/img/categorias/' . $value->categoria->imagen,
                    [
                        'focusable' => 'false',
//                        'preserveAspectRatio'   => 'xMidYMid slice',
//                        'role'  => 'img',
                        'width' => 200,
//                        'heigth'    => 250,
                    ]
            ); ?>
            <!--<svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/></svg>-->
        </div>
      </div>
    </div>
      <?php endforeach; ?>
  </div>
        <?php endif; ?>
</div>

<main role="main" class="container">
  <div class="row">
    <div class="col-md-8 blog-main">
        
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'float-xs-left breadcrumb'
            ]
        ]); ?>
          <?php //echo Html::encode($this->title) ?>
      

      <?= $content ?>
        <?php /* echo '
      <nav class="blog-pagination">
        <a class="btn btn-outline-primary" href="#">Older</a>
        <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Newer</a>
      </nav>
             '; */ ?>

    </div><!-- /.blog-main -->

    <aside class="col-md-4 blog-sidebar">
      <div class="p-4 mb-3 bg-light rounded">
        <h4 class="font-italic">Acerca</h4>
        <p class="mb-0">
            Blog dedicado a la enseñanza de <em>Software Libre</em> y <em>Desarrollo de Software</em>.
        </p>
      </div>

      <div class="p-4">
        <h4 class="font-italic">Categorías</h4>
        <ol class="list-unstyled mb-0">
            <?php foreach ($this->params['categorias'] as $key => $value): ?>
            <li>
                <?= Html::a(
                    $value->categoria,
                    ['/categoria/' . $value->slug],
                    [
                        'title' => $value->categoria,
                    ]
                ); ?>
            </li>
            <?php endforeach; ?>
        </ol>
      </div>

      <div class="p-4">
        <h4 class="font-italic">Redes Sociales</h4>
        <ol class="list-unstyled">
            <li>
                <a href="https://diasp.org/people/98b07b1ddf65e3e8" target="_blank">
                    Diaspora
                </a>
            </li>
            <li>
                <a href="https://gitlab.com/blonder413" target="_blank">
                    GitLab
                </a>
            </li>
            <li>
                <a href="https://mastodon.la/@blonder413" target="_blank">
                    Mastodon
                </a>
            </li>
            <li>
                <a href="https://pixelfed.social/blonder413" target="_blank">
                    Pixelfed
                </a>
            </li>
        </ol>
      </div>
    </aside><!-- /.blog-sidebar -->

  </div><!-- /.row -->

</main><!-- /.container -->

<footer class="blog-footer">
  <a rel="license" href="http://creativecommons.org/licenses/by-sa/2.5/co/">
                <img alt="Licencia Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/2.5/co/88x31.png" />
            </a>
            <br>

            <!--
            <a href="http://www.w3.org/html/logo/">
                    <img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-semantics.png" width="165" height="64" alt="HTML5 Powered with CSS3 / Styling, and Semantics" title="HTML5 Powered with CSS3 / Styling, and Semantics">
            </a>
            <br>
            -->

            <span xmlns:dct="http://purl.org/dc/terms/" property="dct:title" class="negrita">Blonder413 - Aprendizaje dinámico</span> por <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.blonder413.com" property="cc:attributionName" rel="cc:attributionURL">Jonathan Morales Salazar</a> <br>se encuentra bajo una Licencia <a rel="license" href="http://creativecommons.org/licenses/by-sa/2.5/co/">Creative Commons Atribución-CompartirIgual 2.5 Colombia</a>.
            <br>Desarrollado con Yii Framework <?= Yii::getVersion(); ?>
            <br><?php echo date("Y"); ?>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>