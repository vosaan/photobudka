<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Albums';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Album', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            'slug',
            'password',
            'description:ntext',
            // 'create_date',
            // 'edit_date',
            // 'is_active',
            [
                'attribute' => 'is_active',
                'value' => function($data){
                    return !$data->is_active ? "Not active" : "Active";
                }
            ],
            // 'is_private',
            [
                'attribute' => 'is_private',
                'value' => function($data){
                    return !$data->is_private ? "Public" : "Private";
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
