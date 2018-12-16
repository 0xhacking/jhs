<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Level_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		global $_S;
		$set = $_S['commission'];
		$leveltype = $set['leveltype'];
		$default = array('id' => 'default', 'levelname' => empty($set['levelname']) ? '默认等级' : $set['levelname'], 'commission1' => $set['commission1'], 'commission2' => $set['commission2'], 'commission3' => $set['commission3']);
		$others = pdo_fetchall('SELECT * FROM ' . tablename('ewei_shop_commission_level') . (' WHERE uniacid = \'' . $_W['uniacid'] . '\' ORDER BY commission1 asc'));
		$list = array_merge(array($default), $others);
		include $this->template();
	}

	public function add()
	{
		$this->post();
	}

	public function edit()
	{
		$this->post();
	}

	protected function post()
	{
		global $_W;
		global $_GPC;
		global $_S;
		$set = $_S['commission'];
		$leveltype = $set['leveltype'];
		$id = trim($_GPC['id']);

		if ($id == 'default') {
			$level = array('id' => 'default', 'levelname' => empty($set['levelname']) ? '默认等级' : $set['levelname'], 'commission1' => $set['commission1'], 'commission2' => $set['commission2'], 'commission3' => $set['commission3']);
		}
		else {
			$level = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_commission_level') . ' WHERE id=:id and uniacid=:uniacid limit 1', array(':id' => intval($id), ':uniacid' => $_W['uniacid']));
		}

		if ($_W['ispost']) {
			$data = array('uniacid' => $_W['uniacid'],
				'levelname' => trim($_GPC['levelname']),
				'commission1' => trim(trim($_GPC['commission1']), '%'),
				'commission2' => trim(trim($_GPC['commission2']), '%'),
				'commission3' => trim(trim($_GPC['commission3']), '%'),
				'commissionmoney' => trim($_GPC['commissionmoney'], '%'),
				'ordermoney' => $_GPC['ordermoney'],
				'ordercount' => intval($_GPC['ordercount']),
				'downcount' => intval($_GPC['downcount']),
				'jian' => trim(trim($_GPC['jian']), '%'),
				'ling' => trim(trim($_GPC['ling']), '%'),
				'ling1money' => trim(trim($_GPC['ling1money']), '%'),
				'ling1up' => trim(trim($_GPC['ling1up']), '%'),
				'ling1down' => trim(trim($_GPC['ling1down']), '%'),
				'ling2money' => trim(trim($_GPC['ling2money']), '%'),
				'ling2down' => trim(trim($_GPC['ling2down']), '%'),
				'ling2up' => trim(trim($_GPC['ling2up']), '%'),
				'ling3money' => trim(trim($_GPC['ling3money']), '%'),
				'ling3down' => trim(trim($_GPC['ling3down']), '%'),
				'ling3up' => trim(trim($_GPC['ling3up']), '%'),
				'ling4money' => trim(trim($_GPC['ling4money']), '%'),
				'ling4down' => trim(trim($_GPC['ling4down']), '%'),
				'ling4up' => trim(trim($_GPC['ling4up']), '%'),
				'ping1money' => trim(trim($_GPC['ping1money']), '%'),
				'ping1down' => trim(trim($_GPC['ping1down']), '%'),
				'ping1up' => trim(trim($_GPC['ping1up']), '%'),
				'ping2money' => trim(trim($_GPC['ping2money']), '%'),
				'ping2down' => trim(trim($_GPC['ping2down']), '%'),
				'ping2up' => trim(trim($_GPC['ping2up']), '%'),
				'ping3money' => trim(trim($_GPC['ping3money']), '%'),
				'ping3down' => trim(trim($_GPC['ping3down']), '%'),
				'ping3up' => trim(trim($_GPC['ping3up']), '%'),
				'ping4money' => trim(trim($_GPC['ping4money']), '%'),
				'ping4down' => trim(trim($_GPC['ping4down']), '%'),
				'ping4up' => trim(trim($_GPC['ping4up']), '%'),
				'ping1money2' => trim(trim($_GPC['ping1money2']), '%'),
				'ping1money3' => trim(trim($_GPC['ping1money3']), '%'),
				'ping2money2' => trim(trim($_GPC['ping2money2']), '%'),
				'ping2money3' => trim(trim($_GPC['ping2money3']), '%'),
				'ping3money2' => trim(trim($_GPC['ping3money2']), '%'),
				'ping3money3' => trim(trim($_GPC['ping3money3']), '%'),
				'ping4money2' => trim(trim($_GPC['ping4money2']), '%'),
				'ping4money3' => trim(trim($_GPC['ping4money3']), '%'),
				'ntc1' => trim(trim($_GPC['ntc1']), '%'),
				'ntc2' => trim(trim($_GPC['ntc2']), '%'),
				'ntc3' => trim(trim($_GPC['ntc3']), '%'),
				'ntc4' => trim(trim($_GPC['ntc4']), '%'),
				'ntc5' => trim(trim($_GPC['ntc5']), '%'),
				'ntc6' => trim(trim($_GPC['ntc6']), '%'),
				'ntc7' => trim(trim($_GPC['ntc7']), '%'),
				'ntc8' => trim(trim($_GPC['ntc8']), '%')

	);

			if (!empty($id)) {
				if ($id == 'default') {
					$updatecontent = '<br/>等级名称: ' . $set['levelname'] . '->' . $data['levelname'] . ('<br/>一级佣金比例: ' . $set['commission1'] . '->' . $data['commission1']) . ('<br/>二级佣金比例: ' . $set['commission2'] . '->' . $data['commission2']) . ('<br/>三级佣金比例: ' . $set['commission3'] . '->' . $data['commission3']);
					$set['levelname'] = $data['levelname'];
					$set['commission1'] = $data['commission1'];
					$set['commission2'] = $data['commission2'];
					$set['commission3'] = $data['commission3'];
					$this->updateSet($set);
					plog('commission.level.edit', '修改分销商默认等级' . $updatecontent);
				}
				else {
					$updatecontent = '<br/>等级名称: ' . $level['levelname'] . '->' . $data['levelname'] . ('<br/>一级佣金比例: ' . $level['commission1'] . '->' . $data['commission1']) . ('<br/>二级佣金比例: ' . $level['commission2'] . '->' . $data['commission2']) . ('<br/>三级佣金比例: ' . $level['commission3'] . '->' . $data['commission3']);
					pdo_update('ewei_shop_commission_level', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
                    //show_json(0,$data['ping1money'].'-'.$data['ping1money2'].'-'.$data['ping1money3']);
					plog('commission.level.edit', '修改分销商等级 ID: ' . $id . $updatecontent);
				}
			}
			else {
				pdo_insert('ewei_shop_commission_level', $data);
				$id = pdo_insertid();
				plog('commission.level.add', '添加分销商等级 ID: ' . $id);
			}

			show_json(1, array('url' => webUrl('commission/level')));
		}

		include $this->template();
	}

	public function delete()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
		}

		$items = pdo_fetchall('SELECT id,levelname FROM ' . tablename('ewei_shop_commission_level') . (' WHERE id in( ' . $id . ' ) AND uniacid=') . $_W['uniacid']);

		foreach ($items as $item) {
			pdo_delete('ewei_shop_commission_level', array('id' => $item['id']));
			plog('commission.level.delete', '删除分销商等级 ID: ' . $id . ' 等级名称: ' . $level['levelname']);
		}

		show_json(1);
	}
}

?>
