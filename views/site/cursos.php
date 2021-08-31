<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Cursos';
$this->params['breadcrumbs'][] = $this->title;
// parámetros para el sidebar
$this->params['categorias'] = $categorias;
$this->params['mas_visitados'] = $mas_visitados;
?>

<div class="col-md-12">
    <h1 class="text-center">
        <span class="glyphicon glyphicon-blackboard small"></span> <?= Html::encode($this->title) ?>
    </h1>
</div>

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