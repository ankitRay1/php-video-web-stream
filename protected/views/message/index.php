<?php $max = 1; ?>
<?php foreach($messages as $message): ?>
<div style="border: 1px solid #808080; padding:5px">

<?php if($message -> user -> username == $publisher -> name): ?>
<span>
<?php echo $message -> user -> username ?>
</span>
<?php else: ?>
<span><strong>
<?php echo $message -> user -> username ?>
</strong></span>
<?php endif; ?>

<span>
<?php echo $message -> created_at ?>
</span>

<p>
<?php echo $message -> message ?>
</p>
</div>
<?php if($message -> id > $max) { $max = $message -> id; } ?>
<?php endforeach; ?>
<input type="hidden" name="lastid" value="<?php echo $max ?>">