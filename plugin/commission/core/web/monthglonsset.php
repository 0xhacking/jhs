<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}
class Monthglonsset_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		if ($_W['ispost']) {
			$content = array();
			$bili = $_GPC['bili']>1?$_GPC['bili']/100.0:$_GPC['bili'];
			pdo_update("ewei_shop_commission_glouns",array("bili"=>$bili,"beishu"=>$_GPC['beishu'],"monthnum"=>date('n')),array("id"=>1));
			$this->calculateGlouns();
			// show_json(1,$_GPC['bili']);
			//m("commission")->
		}
		
		
		$item = pdo_fetch("select * from ". tablename("ewei_shop_commission_glouns"));
		$item["newmoney"] = $this->GetNewTotalformeByMonth();
		$item["theone"] = $this->GetTheOne();
		
		$row = pdo_fetchall("select * from " . tablename("ewei_shop_member") . " where level = 10 or level = 12 "); 
		
		
		if (intval($item["monthnum"])==intval(date('n'))) {
			// v1.004 start
			$item["enablenotice"] = "上月已经进行过分红";
			// v1.004 end
			// show_json(1,1);

		} else{
			// v1.004 start
			$item["enablenotice"] = "上月尚未进行分红";
			// v1.004 end
		}

		include $this->template();
	}
	//获取上个月整个系统的新增业绩
	//V1.004 修改了月分红的计算方式
	public function GetNewTotalformeByMonth(){
		$totle = pdo_fetch("select sum(og.realprice) as ordermoney from "
			. tablename("ewei_shop_order") . " o ".
			" left join " . tablename("ewei_shop_order_goods") . " og on og.orderid=o.id " .
			" left join " . tablename("ewei_shop_goods") . " oc on og.goodsid=oc.id " .
			" where o.status > 0 and oc.pcate = 1176 and year(from_unixtime(og.createtime)) = " . date('Y') .
			" and month(from_unixtime(og.createtime)) = " . (date('n')-1));
		if (empty($totle["ordermoney"])) {
			return 0;
		}
		return $totle["ordermoney"];
	}
	// v1.004 end
	
	//v1.003 start
	//获取平台所有的消费股东、合伙人
	public function GetTheOne(){
		
		$totle = pdo_fetch("select count(*) as onemon from " . tablename("ewei_shop_member") . " where level = 10 or level = 12 ");
		$totle += pdo_fetch("select count(*) as onegu from " . tablename("ewei_shop_member") . " where level = 10 ");
		$totle += pdo_fetch("select count(*) as onehe from " . tablename("ewei_shop_member") . " where level = 12 ");

		return $totle;

	}
	
	//v1.003 end
	
	
	
	
	//分配月分红的按钮触发计算函数
		// public function calculateGlouns(){
		// 	$totle = $this->GetNewTotalformeByMonth();
		// 	$set = pdo_fetch("select bili,beishu from " . tablename("ewei_shop_commission_glouns"));
		// 	$money = $totle * $set["bili"];
		// 	$countnum = 0;
		// 	$members = pdo_fetchall("select o.id as id, og.levelname as levelname from " . tablename("ewei_shop_member") . " o " .
		// 	" left join " . tablename("ewei_shop_commission_level") . " og on og.id = o.agentlevel " .
		//     " where og.levelname = '合伙人' or og.levelname = '高级分销商'");
		//     foreach ($members as $ts) {
		//    		if ($ts["levelname"=="合伙人"]) {
		//    			$countnum += $set["beishu"];
		//    		}
		//    		else{
		//    			$countnum++;
		//    		}
		//     }
		//     $pieceOfMoney = $countnum==0?0:$money/$countnum;
		//     foreach ($members as $tss) {
		//     	if ($tss["levelname"]=="合伙人") {
		//     		pdo_update("ewei_shop_member",array("monthmoney"=>$pieceOfMoney*$set["beishu"]),array("id"=>$tss["id"]));
		//     	}
		//     	if ($tss["levelname"=="高级分销商"]) {
		//     		pdo_update("ewei_shop_member",array("monthmoney"=>$pieceOfMoney),array("id"=>$tss["id"]));
		//     	}
		//     }
		// }





	public function calculateGlouns(){
			$totle = $this->GetNewTotalformeByMonth();
			$set = pdo_fetch("select bili,beishu from " . tablename("ewei_shop_commission_glouns"));
			$money = $totle * $set["bili"];
			$countnum = 0;
			$members = pdo_fetchall("select o.id , o.monthmoney_old , o.monthmoney_old_no , o.agentlevel from " . tablename("ewei_shop_member") . " o " .
		    " where o.agentlevel = 12 or o.agentlevel = 10 ");
			
		    foreach ($members as $tk) 
		    {
		    	//show_json(1,$tk["agentlevel"]);
		   		if ($tk["agentlevel"]==12) 
		   		{
		   			$countnum += $set["beishu"];
		   			//
		   		}
		   		else
		   		{
		   			$countnum+=1;
		   		}

		    }
		    $pieceOfMoney = $countnum==0?0:$money/$countnum;
		    //show_json(1,$pieceOfMoney);
			// $pieceOfMoney = round($pieceOfMoney,0,PHP_ROUND_HALF_DOWN);
            //v1.009 start
			//$pieceOfMoney = floor($pieceOfMoney);
            //v1.009 end
		   
		    foreach ($members as $tss)
		    {
					    // show_json(1,$tss['id']);
					    // show_json(1,$tss['monthmoney_old']);
		    	if ($tss["agentlevel"]==12)
		    	{
//        v1.009 start 定义月分红发放记录
                    $id = $tss["id"];                   //用户id
                    $monthnum_y = date('Y');   //月分红发放年数
                    $monthnum_m = date('m');   //月分红发放月数
                    $monthmoney = $pieceOfMoney*$set["beishu"];   //月分红发放金额
                    $monthdate = time();               //月分红发放时间
                    $is_ti = 0;

                    //获取值存入数组
                    $arr['uid'] = $id;
                    $arr['year_num'] = $monthnum_y;
                    $arr['month_num'] = $monthnum_m;
                    $arr['fa_money'] = $monthmoney;
                    $arr['fa_date'] = $monthdate;
                    $arr['is_ti'] = $is_ti;

//                   向记录表中存值
                    pdo_insert("ewei_shop_monthmoney",$arr);

//		   v1.009 end
		    		pdo_update("ewei_shop_member",array("monthmoney"=>$pieceOfMoney*$set["beishu"]),array("id"=>$tss["id"]));
		    		// v1.003 start
		    		pdo_update("ewei_shop_member",array("monthmoney_old"=>($pieceOfMoney*$set["beishu"]+$tss['monthmoney_old'])),array("id"=>$tss["id"]));
		    		pdo_update('ewei_shop_member',array('monthmoney_zhuangtai' => 0),array('id'=>$tss['id']));
                    //v1.009 start
					//pdo_update("ewei_shop_member",array("monthmoney_old_no"=>($pieceOfMoney*$set["beishu"]+$tss['monthmoney_old_no'])),array("id"=>$tss["id"]));
                    //v1.009 end
		    		// v1.003 end
		    	}
		    	if ($tss["agentlevel"]==10)
		    	{
//        v1.009 start 定义月分红发放记录
                    $id = $tss["id"];                   //用户id
                    $monthnum_y = date('Y');   //月分红发放年数
                    $monthnum_m = date('m');   //月分红发放月数
                    $monthmoney = $pieceOfMoney*$set["beishu"];   //月分红发放金额
                    $monthdate = time();               //月分红发放时间
                    $is_ti = 0;

                    //获取值存入数组
                    $arr['uid'] = $id;
                    $arr['year_num'] = $monthnum_y;
                    $arr['month_num'] = $monthnum_m;
                    $arr['fa_money'] = $monthmoney;
                    $arr['fa_date'] = $monthdate;
                    $arr['is_ti'] = $is_ti;

//                   向记录表中存值
                    pdo_insert("ewei_shop_monthmoney",$arr);

//		   v1.009 end
		    		pdo_update("ewei_shop_member",array("monthmoney"=>$pieceOfMoney),array("id"=>$tss["id"]));
		    		// v1.003 start
		    		pdo_update("ewei_shop_member",array("monthmoney_old"=>($pieceOfMoney+$tss['monthmoney_old'])),array("id"=>$tss["id"]));
		    		pdo_update('ewei_shop_member',array('monthmoney_zhuangtai' => 0),array('id'=>$tss['id']));
                    //v1.009 start
		    		//pdo_update("ewei_shop_member",array("monthmoney_old_no"=>($pieceOfMoney+$tss['monthmoney_old_no'])),array("id"=>$tss["id"]));
                    //v1.009 end
		    		// v1.003 end
		    	}
		    }

		}




}