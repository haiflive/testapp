<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if(!Yii::$app->user->isGuest): ?>
            <?= Html::a('Pass Amount', ['billing-operations/create'], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a('Registration', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'login',
            'billing.balance', // my be add order, if time exists

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
