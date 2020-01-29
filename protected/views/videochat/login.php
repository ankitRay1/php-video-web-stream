<div class="login-wrapper">
<h2 class="form-signin-heading white"><?php echo Yii::t("app", "Please sign in") ?></h2><br/>

<?php foreach($loginForm -> getErrors() as $errors) : ?>
<?php foreach($errors as $error): ?>
<div class="alert alert-dismissable alert-warning red-bg">
<button type="button" class="close" data-dismiss="alert">×</button>
<?php echo $error; ?>
</div>
<?php endforeach; ?>
<?php endforeach; ?>

<?php echo CHtml::beginForm(CHtml::normalizeUrl(''), 'post', array(
	'class' => 'form-horizontal form-signin',
	'role' => 'form',
)); ?>

<div class="form-group">
<?php echo CHtml::activeTextField($loginForm, 'username', array(
	'class' => 'form-control',
	'placeholder' => Yii::t("app", "Username"),
)); ?>
</div>

<?php if(CCaptcha::checkRequirements()): ?>
<div class="form-group">
<?php $this->widget('CCaptcha', array(
	'buttonType' => 'button',
	'buttonOptions' => array(
		'class' => 'btn btn-success refresh-button',
	),
	'imageOptions' => array(
		//'class' => 'captcha-img'
		'height' => '51px',
	),
)); ?>
</div>
<div class="form-group">
<?php echo CHtml::activeTextField($loginForm, 'verifyCode', array(
	'class' => 'form-control',
	'placeholder' => Yii::t("app", "CAPTCHA"),
)); ?>
</div>
<?php endif; ?>

<div class="form-group submit">
<?php echo CHtml::submitButton(Yii::t("app", "Join"), array(
	'class' => 'btn btn-lg btn-primary btn-block',
)); ?>
</div>

<?php echo CHtml::endForm(); ?>
</div>