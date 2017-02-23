<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCssFile('@web/css/ekko-lightbox.min.css');
$this->registerCssFile('@web/css/gallery.css');

$this->registerJsFile('@web/js/slug.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/ekko-lightbox.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/gallery.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/delete-image.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Album */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="album-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelAlbum, 'title')->textInput(['maxlength' => true, 'id' => 'album-title']) ?>

    <?= $form->field($modelAlbum, 'slug')->textInput(['maxlength' => true, 'id' => 'album-slug']) ?>

    <?= $form->field($modelAlbum, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelAlbum, 'description')->textarea(['rows' => 6]) ?>

    <?//= $form->field($model, 'create_date')->textInput() ?>

    <?//= $form->field($model, 'edit_date')->textInput() ?>

    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($modelAlbum, 'is_active')->dropDownList([ 1 => 'Active', 0 => 'Not active']) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($modelAlbum, 'is_private')->dropDownList([ 1 => 'Private', 0 => 'Public']) ?>
        </div>
    </div>

    <?php if (isset($imagesList)) : ?>
        <div class="thumbs-container">
            <ul>
                <?php foreach ($imagesList as $image) : ?>
                    <?php $middle = '/web/uploads/' . $image['album_id'] . '/' . $image['path']; ?>
                    <?php $thumbnail = '/web/uploads/' . $image['album_id'] . '/thumbnails/' . $image['path']; ?>
                    <li>
                        <div class="thumbnail-wrapper">
                            <div class="thumbnail-container">
                                <a href="<?=$middle?>" data-toggle="lightbox" data-gallery="example-gallery">
                                    <img src="<?=$thumbnail?>" class="img-fluid">
                                </a>
                            </div>

                            <input type="checkbox" name="delImages[]" class="del-check" value="<?= $image['id']?>"><span class="delete-label">Delete image</span>

                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $form->field($modelImage, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::$app->controller->action->id == 'create' ? 'Create' : 'Update', ['class' => Yii::$app->controller->action->id == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
