<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

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
            'id',
            'login',
            'billing.balance',
        ],
    ]) ?>
    
    <legend>My Billing Operations</legend>
    <?= GridView::widget([
        'dataProvider' => $dataProviderBillingOperations,
        // 'filterModel' => $searchModelBillingOperations,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
    <legend>My Invoices</legend>
    <?= GridView::widget([
        'dataProvider' => $dataProviderInvoiceSearch,
        // 'filterModel' => $searchModelInvoiceSearch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'owner_id',
            'for_user_id',
            'status',
            'amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>
