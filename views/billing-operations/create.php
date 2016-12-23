<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BillingOperations */

$this->title = 'Create Billing Operations';
$this->params['breadcrumbs'][] = ['label' => 'Billing Operations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-operations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
