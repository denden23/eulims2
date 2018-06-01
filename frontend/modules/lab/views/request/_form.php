<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\lab\Lab;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\lab\Request */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="request-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'rstl_id')->hiddenInput(['value'=>$GLOBALS['rstl_id']])->label(false) ?>
    <?= $form->field($model, 'request_ref_num')->hiddenInput(['maxlength' => true])->label(false) ?>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'lab_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Lab::find()->all(),'lab_id','labname'),
                'language' => 'en',
                'options' => ['placeholder' => 'Select Lab'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
    ])->label('Laboratory'); ?>
    </div>
    <div class="col-md-6">
    <label class="control-label">Request Date</label>
    <?php echo DateTimePicker::widget([
	'model' => $model,
	'attribute' => 'request_datetime',
	'options' => ['placeholder' => 'Enter Date'],
        'value'=>'05/31/2018 3:26 PM',
	'pluginOptions' => [
            'autoclose' => true,
            'format' => 'mm/dd/yyyy g:ii P'
	]
    ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'customer_id')->textInput()->label('Customer') ?>
    </div>
    <div class="col-md-6">
        <label class="control-label">Payment Type</label>
        <div class="col-md-12">
        <label>Unpaid <input type="radio" name="PaymentType" value="0"/></label>
        <label>Paid <input type="radio" name="PaymentType" value="1" checked="checked" /></label>
        <label>Subsidized <input type="radio" name="PaymentType" value="2"/></label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'modeofrelease_id')->textInput() ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'discount_id')->textInput() ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'purpose_id')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'or_id')->textInput() ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'report_due')->textInput() ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'conforme')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'receivedBy')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'created_at')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'posted')->textInput() ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'status_id')->textInput() ?>
    </div>
</div>
<div class="row">
    <div class="row" style="float: right;padding-right: 30px">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-danger']) ?>
        <?= Html::Button('Cancel', ['class' => 'btn btn-default', 'id' => 'modalCancel', 'data-dismiss' => 'modal']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
