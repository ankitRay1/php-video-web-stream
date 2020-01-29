<?php if($informs): ?>
<?php foreach($informs as $inform): ?>
<div id="informer-<?php echo $inform -> id ?>" class="alert alert-dismissable alert-info <?php echo $inform -> status == "new" ? "yellow-bg" : "gray-bg" ?>">
<button type="button" class="close" data-dismiss="alert" id="tick-<?php echo $inform -> id ?>">×</button>
<?php echo Yii::t("app", "{Name} sent you an invite. Click {Here} to join", array(
	'{Name}' => '<strong>'.CHtml::encode($inform -> informator -> username).'</strong>',
	'{Here}' => '<a href="'. $this -> createAbsoluteUrl("room/join", array("id" => $inform -> informator -> id)) .'" target="_blank" class="alert-link">'. Yii::t("app", "here") .'</a>',
)); ?>
</div>
<?php endforeach; ?>
<?php else: ?>
<?php echo Yii::t("app", "You haven't any notifications") ?>
<?php endif; ?>