<?php
// 区域代理
if (!defined('IN_IA')) {
	exit('Access Denied');
}
class ZoneSet_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		if ($_W['ispost']) {
			$taocanprovince = $_GPC['taocanprovince']>=1?$_GPC['taocanprovince']/100.0:$_GPC['taocanprovince'];
			$taocancity = $_GPC['taocancity']>=1?$_GPC['taocancity']/100.0:$_GPC['taocancity'];
			$taocanzone = $_GPC['taocanzone']>=1?$_GPC['taocanzone']/100.0:$_GPC['taocanzone'];
			$untaocanprovince = $_GPC['untaocanprovince']>=1?$_GPC['untaocanprovince']/100.0:$_GPC['untaocanprovince'];
			$untaocancity = $_GPC['untaocancity']>=1?$_GPC['untaocancity']/100.0:$_GPC['untaocancity'];
			$untaocanzone = $_GPC['untaocanzone']>=1?$_GPC['untaocanzone']/100.0:$_GPC['untaocanzone'];
			pdo_update("ewei_shop_commission_glouns",array("taocanprovince"=>$taocanprovince,"taocancity"=>$taocancity,"taocanzone"=>$taocanzone,"untaocanprovince"=>$untaocanprovince,"untaocancity"=>$untaocancity,"untaocanzone"=>$untaocanzone),array("id"=>1));
		}
		$item = pdo_fetch("select * from " . tablename("ewei_shop_commission_glouns"));
		include $this->template();
	}
}