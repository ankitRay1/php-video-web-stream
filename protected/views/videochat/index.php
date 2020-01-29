<script>
$(document).ready(function(){
var Opts = {
	sUrl: '<?php echo $this -> createUrl("online/session") ?>',
	oUrl: '<?php echo $this -> createUrl("online/users") ?>',
	iUrl: '<?php echo $this -> createUrl("informer/index") ?>',
	riUrl: '<?php echo $this -> createUrl("informer/delete") ?>',
	syncUrl: '<?php echo $this -> createUrl("online/sync") ?>',
	sessInt: <?php echo Yii::app() -> params["sessIntTime"] ?>,
	dataSync: <?php echo Yii::app() -> params["dataSync"] ?>,
}

var sesIntID = 0,
		syncIntID = 0;

sesIntID = setInterval(function(){
	$.get(Opts.sUrl, function(){
	});/*.fail(function(xhr, ajaxOptions, thrownError, request, error) {
		console.log('<session-error>');
		console.log('xhr.status = ' + xhr.status);
		console.log('hrown error = ' + thrownError);
		console.log('xhr.statusText = ' + xhr.statusText);
		console.log('request = ' + request);
		console.log('error = ' + error);
		console.log('</session-error>');
		clearInterval(sesIntID);
	});*/
}, Opts.sessInt);

syncIntID = setInterval(function(){
	$.getJSON(Opts.syncUrl, function(data){
		$('#online-cnt').html(data.online_cnt);
		$('#informer-cnt').html(data.informer_cnt);

		$('#informer').html(data.informer);
		$('.close').on('click', function(){ removeTick($(this)); });

		$('#online').html(data.online);
	});/*.fail(function(xhr, ajaxOptions, thrownError, request, error){
		console.log('<sync-error>');
		console.log('xhr.status = ' + xhr.status);
		console.log('hrown error = ' + thrownError);
		console.log('xhr.statusText = ' + xhr.statusText);
		console.log('request = ' + request);
		console.log('error = ' + error);
		console.log('</sync-error>');
		//clearInterval(syncIntID);
	});*/
}, Opts.dataSync);


function removeTick(tick) {
	var id = tick.attr("id").replace(/^\D+/g, '');
	var cnt = parseInt($('#informer-cnt').html());
	$('#informer-cnt').html(cnt - 1);
	$('#informer-'+id).remove();
	$.get(Opts.riUrl, {id: id}, function(){});
}

});
</script>

<div class="col-lg-6">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo Yii::t("app", "User online") ?><span id="online-cnt" class="badge badge-panel badge-primary"></span></h3>
		</div>
		<div class="panel-body">
			<div id="online"><?php echo Yii::t("app", "Initializing") ?></div>
		</div>
	</div>
</div>

<div class="col-lg-6">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo Yii::t("app", "Notifications") ?><span id="informer-cnt" class="badge badge-panel badge-success"></span></h3>
		</div>
		<div class="panel-body">
			<div id="informer"><?php echo Yii::t("app", "Initializing") ?></div>
		</div>
	</div>
</div>