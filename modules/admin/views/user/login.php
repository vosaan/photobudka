<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h1>login</h1>

<?php $form = ActiveForm::begin()?>
<?= $form->field($model, 'username')->textInput()?>
<?= $form->field($model, 'password')->passwordInput()?>
<?=Html::submitButton('Login', ['class' => 'btn-primary btn'])?>
<?php ActiveForm::end()?>
