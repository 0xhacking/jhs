<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Weekglons_EweiShopV2Page extends PluginWebPage
{
//    v1.011 周分红事宜
    public function main()
    {
        global $_W;
        global $_GPC;
        //拆分组装信息
        $item = Array();
        $a = $this->get_member_id();
        $b = $this -> get_member_total();
        $c = $this -> get_member_oneweek_total();
        for($i = 0 ; $i<count($this->get_member_id()) ; $i++){
            $item[$i] = $a[$i];
            $item[$i]["one_total"] = $b[$i];
            $item[$i]["one_week_total"] = $c[$i];
        }


        //$item['one_week_total'] = $this -> get_member_oneweek_total();
        //$item = $this->model->GetNewTotalformeByWeekByID('6572');
        //$item = $this->model->GetWeekGlons('6565');
        //show_json(1,$item);
        //show_json(1,$item["theone"]);GetWeekGlons
        // $this->mod
        //$this->get_member_zhou();
        include $this->template();
    }
    //获取全部符合要求的消费股东与合伙人
    public function get_member_id(){
        global $_W;
        global $_GPC;
        $g_m = pdo_fetchall('select id from '.tablename('ewei_shop_member').' where agentlevel = 10 or agentlevel = 12');
        return $g_m;
    }
    //获取每个人的总业绩(当前用户)
    public function get_member_total(){
        global $_W;
        global $_GPC;
        $g_m = $this -> get_member_id();
        $g_m_t = Array();
        for($i = 0 ; $i<count($g_m) ;$i++){
            $total = $this -> model->GetTotalforme($g_m[$i]['id']);
            array_push($g_m_t,$total);
        }
        //show_json(1,$g_m_t);
        return $g_m_t;
    }
    //获取每个人最近一周的业绩(当前用户)
    public function get_member_oneweek_total(){
        global $_W;
        global $_GPC;
        $g_m = $this -> get_member_id();
        $g_m_w_t = Array();
        $total = Array();
        //
//        for($i = 0 ; $i<count($g_m) ;$i++){
//            $total = $this ->GetNewTotalformeByWeek($g_m[$i]['id']);
//            array_push($g_m_w_t,$g_m[$i]['id']);
//        }
        //$r = $this->model->GetNewTotalformeByWeekByID('6565');
        //获取当前用户的下级
        $allmember = pdo_fetchall("select id from ".tablename("ewei_shop_member"). " where jiedianrenid = 6565 and isagent=1 and status=1 ");
        //获取每个下级产生的业绩
        for($t = 0 ; $t<count($allmember);$t++){

        }

        show_json(1,$this -> model->GetNewTotalformeByWeekByID('6572'));
        return $g_m_w_t;
    }

    public function GetNewTotalformeByWeek($angentid){
        global $_W;
        $totalforme = 0;
        $member = m("member")->getMember($agentid);
        if($member["weeknum"] == date('w')) return $member["weeksummoney"];
        $allmember = pdo_fetchall("select openid from " .
            tablename("ewei_shop_member") .
            " where jiedianrenid =:agid and isagent=1 and status=1 ",array( ":agid" => $agentid));
        $totalforme += $this->GetNewTotalformeByWeekByID($agentid);
        	foreach ($allmember as $onemember) {
        		$totalforme += this.GetNewTotalformeByWeek($onemember["id"]);
        	}
        pdo_update("ewei_shop_member",array("weekaddsum"=>$totalforme,"weeknum"=>date('w')),array("id"=>$agentid));
        return $totalforme;
    }

    //获取每个人最近一周的业绩(非当前用户)
    public function get_member_oneweek_total_all(){
        global $_W;
        global $_GPC;
        $g_m = $this -> get_member_id();
        $g_m_w_t_a = Array();
        $total = Array();
        //GetNewTotalformeByWeekByID
        for($i = 0 ; $i<count($g_m) ;$i++){
            $total = $this -> model->GetNewTotalformeByWeekByID($g_m[$i]['id']);
            array_push($g_m_w_t_a,$total);
        }
        //$r = $this->model->GetNewTotalformeByWeekByID('6565');
        //show_json(1,$g_m_w_t);
        return $g_m_w_t_a;
    }

    //计算当前用户上周周分红
    public function get_member_zhou(){
        global $_W;
        global $_GPC;
    $zhou = $this->model->getInfo($_W['openid'], array('total', 'ordercount0'));
    return $zhou;
    }
}