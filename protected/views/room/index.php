<script type="text/javascript">
var videoOpts = {
	apiKey: "<?php echo $apiKey ?>",
	token: "<?php echo $token ?>",
	sessionId: "<?php echo $opentokid ?>",
	autoResize: true,
	name: '<?php echo CHtml::encode($publisher -> name) ?>',
	lang: {
		unpublish_stream: '<?php echo Yii::t("app", "{name} finished stream publeshing") ?>',
		publish_stream: '<?php echo Yii::t("app", "{name} started stream publeshing") ?>',
		connection_destroyed: '<?php echo Yii::t("app", "{name} was disconnected") ?>',
		is_waiting: '<?php echo Yii::t("app", "{name} is waiting you") ?>',
		disconnect: '<?php echo Yii::t("app", "{name} was disconnected") ?>',
		disconnected_by_yourself: '<?php echo Yii::t("app", "You have been disconnected from the session") ?>',
		start_stream_publeshing: '<?php echo Yii::t("app", "You started video publishing") ?>',
		finish_stream_publeshing: '<?php echo Yii::t("app", "You finished video publishing") ?>',
		has_not_requirements: "<?php echo Yii::t("app", "Your browser doesn't support video streaming") ?>",
		error_connection: "<?php echo Yii::t("app", "There was an error connecting. Trying again") ?>",
        restricted_camera_permissions: "<?php echo Yii::t("app", "You've restricted audio and video permissions.") ?>"
	},
	noticeTmpl:  function(notice_counter, type, msg) {
		return '<div id="notice-'+ notice_counter +'" class="alert alert-dismissable alert-'+ type + ' video-notice">'+ msg + '</div>';
	}
}

var chatOpts = {
	recieveUri: '<?php echo $this -> createAbsoluteUrl("message/index") ?>',
	sendUri: '<?php echo $this -> createAbsoluteUrl("message/insert") ?>',
	activity: '<?php echo json_encode(Yii::app() -> params["chatActivity"]) ?>',
	mTimeout: <?php echo Yii::app() -> params["chatActivity"][0] ?>,
	template: function(message) {
		return '<div class="row">'+
		'<div class="col-lg-2">'+
		'<span class="'+ (message.self == true ? 'blue' : 'red') +'-color"><strong>'+ message.name +'</strong><br/><small>'+ message.created_at +'</small></span>' +
		'</div>' +
		'<div class="col-md-10">'+
		'<blockquote class="blue-color">'+
		'<small>' + message.message +'</small>'+
		'</blockquote>'+
		'</div>'+
		'</div>';
	}
};


$(document).ready(function(){
	new $.jOpenTok(videoOpts);
	new $.jOnlineChat(chatOpts);
});


</script>

<div class="wrapper">
<h2 class="white"><?php echo CHtml::encode($this -> title) ?></h2>

<div class="row">

<div class="col-md-8">

<div class="panel panel-primary">
<div class="panel-heading">
	<h3 class="panel-title"><?php echo CHtml::encode($subscriber -> username) ?></h3>
</div>
<div class="panel-body panel-video">
	<div id="subscriber"></div>
</div>
</div>


</div><!-- col-md-8 -->

<div class="col-md-4">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo CHtml::encode($publisher -> name) ?></h3>
		</div>
		<div class="panel-body panel-video">
			<div id="publisher"></div>
				<button type="button" class="btn btn-success btn-publish" id="publishVideo" style="display:none"><?php echo Yii::t("app" , "Publish video") ?></button>
				<button type="button" class="btn btn-warning btn-unpublish" id ="unpublishVideo" style="display:block"><?php echo Yii::t("app" , "Unpublish video") ?></button>
				<button type="button" class="btn btn-default btn-leave" id ="disconnectLink" style="display:block"><?php echo Yii::t("app" , "Leave room") ?></button>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo Yii::t("app", "Notifications") ?></h3>
		</div>
		<div class="panel-body">
			<div id="notification"></div>
		</div>
	</div>
</div><!--notice and publisher section -->

</div><!-- row -->


<div class="row">
<div class="col-md-12">
	<div class="panel panel-info">
		<div class="panel-heading purple-bg">
			<h3 class="panel-title"><?php echo Yii::t("app", "Messages") ?></h3>
		</div>
		<div class="panel-body">
			<div id="messages"></div>
			<form id="chat-form">
			<input type="hidden" name="roomid" value="<?php echo $roomid ?>" >
			<input type="hidden" name="to_sessionid" value="<?php echo $subscriber -> id ?>">
			<input type="hidden" name="lastid" value="0">
			<br/>			
				<div class="input-group has-success">
					<input autocomplete="off" type="text" id="message" name="message" class="form-control">
					<span class="input-group-btn">
						<input type="submit" class="btn btn-success btn-chat-submit" id="submit" value="<?php echo Yii::t("app", "Submit") ?>">
					</span>
				</div><!-- /input-group -->
			</form>
		</div>
	</div>
</div>
</div>

</div><!-- wrapper -->