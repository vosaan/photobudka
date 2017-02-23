<?php

//use Yii;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Album */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Albums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/ekko-lightbox.min.css');
$this->registerCssFile('@web/css/gallery.css');

$this->registerJsFile('@web/js/ekko-lightbox.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@web/js/gallery.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="album-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'title',
            'slug',
            'password',
            'description:ntext',
            'create_date',
            'edit_date',
            //'is_active',
            [
                'attribute' => 'is_active',
                'value' => !$model->is_active ? "Not active" : "Active",
                'format' => 'html'
            ],
            //'is_private',
            [
                'attribute' => 'is_private',
                'value' => !$model->is_private ? "Public" : "Private",
                'format' => 'html'
            ],
        ],
    ]) ?>

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
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

</div>
