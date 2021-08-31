<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */

//$this->title = 'Crear Auth Assignment';
//$this->params['breadcrumbs'][] = ['label' => 'Auth Assignments', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-create">

    <div class='panel panel-warning'>
        <div class='panel-heading'>
            <h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class='panel-body'>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
