<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="login-form">
    <div class="form-container">
        <?php $form = ActiveForm::begin()?>
        <?= $form->field($model, 'username')->textInput()?>
        <?= $form->field($model, 'password')->passwordInput()?>
        <?=Html::submitButton('Login', ['class' => 'btn-primary btn login-button'])?>
        <?php ActiveForm::end()?>

        <p class="go-home">
            <?=Html::a('Go to public part of site', [Yii::$app->homeUrl])?>
        </p>
    </div>

</div>
