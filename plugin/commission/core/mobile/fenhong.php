<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'commission/core/page_login_mobile.php';
class Fenhong_EweiShopV2Page extends CommissionMobileLoginPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$member = $this->model->getInfo($_W['openid']);
		$levelcount1 = $member['level1'];
		$levelcount2 = $member['level2'];
		$levelcount3 = $member['level3'];
		$level1 = $level2 = $level3 = 0;
		$level1 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where agentid=:agentid and uniacid=:uniacid limit 1', array(':agentid' => $member['id'], ':uniacid' => $_W['uniacid']));
		if (2 <= $this->set['level'] && 0 < $levelcount1) {
			$level2 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where agentid in( ' . implode(',', array_keys($member['level1_agentids'])) . ') and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
		}

		if (3 <= $this->set['level'] && 0 < $levelcount2) {
			$level3 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where agentid in( ' . implode(',', array_keys($member['level2_agentids'])) . ') and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
		}

		$total = $level1 + $level2 + $level3;
		
		
		
		include $this->template();
	}

	public function get_list()
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$member = $this->model->getInfo($openid);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		
		

		$list = pdo_fetchall('select * from ' . tablename('ewei_shop_member_fenhong') . ' where mid = '.$member['id'].'  ORDER BY id desc ' );


		unset($row);
		show_json(1, array('list' => $list,'pagesize' => $psize));
	}
}

?>
