<?php
$_conf = require(dirname(__FILE__).'/main.php');
unset($_conf['defaultController']);
return $_conf;