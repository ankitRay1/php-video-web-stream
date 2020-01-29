<?php if($online): ?>
<ul class="online">
<?php foreach($online as $o) : ?>
<?php if($o -> id != $user -> id) : ?>
<li>
<a href="<?php echo $this -> createAbsoluteUrl("room/create", array("id" => $o -> id)); ?>" target="_blank">
<span class="glyphicon glyphicon-user"></span><?php echo Yii::t("app", "Start video chat with {Name}", array("{Name}" => CHtml::encode($o -> username))); ?>
</a>
</li>
<?php else: ?>
<li>
<span class="glyphicon glyphicon-user"></span><?php echo CHtml::encode($user -> name); ?>
</li>
<?php endif; ?>
<?php endforeach; ?>
<ul>
<?php else: ?>
<?php echo Yii::t("app", "No user online") ?>
<?php endif; ?>
