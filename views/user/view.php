<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Invoice;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->login;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--
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
    -->
    
    <?= Html::a('Pass Amount', ['billing-operations/create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Pass Invloce', ['invoice/create'], ['class' => 'btn btn-success']) ?>
    
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
            // 'user_id',
            [
                'attribute' => 'Receiver',
                'value' => 'reciverUser.login',
            ],
            [
                'attribute' => 'Sender',
                'value' => 'senderUser.login',
            ],
            // 'reciver_id',
            // 'reciverUser.login',
            'amount',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
    <legend>My Invoices</legend>
    <?= GridView::widget([
        'dataProvider' => $dataProviderInvoiceSearch,
        // 'filterModel' => $searchModelInvoiceSearch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'owner_id',
            [
                'attribute' => 'Initiator',
                'value' => 'owner.login',
            ],
            // 'for_user_id',
            [
                'attribute' => 'Receiver',
                'value' => 'forUser.login',
            ],
            'amount',
            // 'status',
            [
                'format' => 'raw',
                'value'=>function ($data) {
                    $labelStatus = '';
                    switch($data->status) {
                        case Invoice::STATUS_NEW :
                            $labelStatus = 'New';
                            break;
                        case Invoice::STATUS_ACCEPTED :
                            $labelStatus = 'Accepted';
                            break;
                        case Invoice::STATUS_REJECTED_BY_SENDER :
                            $labelStatus = 'Rejected by sender';
                            break;
                        case Invoice::STATUS_REJECTED_BY_RECEIVER :
                            $labelStatus = 'Rejected by receiver';
                            break;
                    }
                    return $labelStatus;
                },
                'attribute' => 'status'
            ],
            [
                'format' => 'raw',
                'value'=>function ($data) {
                    $result = '';
                    if($data->status === Invoice::STATUS_NEW ) {
                        if($data->owner_id !== Yii::$app->user->id) {
                            $result .= Html::a(Html::encode("Accept Invoice"), ['invoice/accept', 'id'=>$data->id]);
                            $result .= '&nbsp;/&nbsp;';
                        }
                        $result .= Html::a(Html::encode("Reject Invlice"), ['invoice/reject', 'id'=>$data->id]);
                    }
                    return $result;
                    
                },
            ],
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>
