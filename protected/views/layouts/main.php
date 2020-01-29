<!DOCTYPE html>
<html xml:lang="<?php echo Yii::app() -> language ?>" lang="<?php echo Yii::app() -> language ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="<?php echo Yii::app() -> language ?>" />
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
<?php Yii::app() -> clientScript -> registerCoreScript('jquery') ?>
<?php Yii::app() -> clientScript -> registerScriptFile(Yii::app() -> baseUrl . '/static/js/bootstrap.min.js', CClientScript::POS_END); ?>
<title><?php echo CHtml::encode($this -> title) ?></title>
</head>
<body>
<?php echo $content; ?>
</body>
</html>