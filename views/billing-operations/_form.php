<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BillingOperations */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="billing-operations-form">

    <?php $form = ActiveForm::begin([
        'id' => 'billing-operations-form',
    ]); ?>

    <?= $form->field($model, 'login')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
