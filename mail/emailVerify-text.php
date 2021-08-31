<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
Hola <?= $user->username ?>,

Siga el enlace a continuación para verificar su correo electrónico:

<?= $verifyLink ?>
