<?php
if (!(defined('IN_IA'))) {
	exit('Access Denied');
}


require_once IA_ROOT . '/addons/ewei_shopv2/version.php';
require_once IA_ROOT . '/addons/ewei_shopv2/defines.php';
require_once EWEI_SHOPV2_INC . 'functions.php';
class Ewei_shopv2Module extends WeModule
{
	public function welcomeDisplay()
	{
		header('location: ' . webUrl());
		exit();
	}
}
define("APP_ROOT",dirname(__FILE__));
    if(!file_exists(dirname(dirname(dirname(__FILE__))).'/web/52jscn.php')){
    echo'&#35813;&#27169;&#22359;&#20165;&#29992;&#20110;&#24494;&#20449;&#39764;&#26041;&#26694;&#26550;&#65292;&#35831;&#19979;&#36733;&#26368;&#26032;&#29256;&#30340;&#24494;&#20449;&#39764;&#26041;&#20351;&#29992;&#65306;&#104;&#116;&#116;&#112;&#58;&#47;&#47;&#98;&#98;&#115;&#46;&#53;&#50;&#106;&#115;&#99;&#110;&#46;&#99;&#111;&#109;&#47;&#102;&#111;&#114;&#117;&#109;&#45;&#50;&#50;&#53;&#45;&#49;&#46;&#104;&#116;&#109;&#108;';
    exit();
}
?>