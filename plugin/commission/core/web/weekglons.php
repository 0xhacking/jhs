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
//        $c = $this -> get_member_oneweek_total();
        $c = $this -> get_member_down();
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
    //获取每个人的下级
    public function get_member_down($id){
        global $_W;
        global $_GPC;
        //获取初始符合条件的所有人
        $g_m = $this -> get_member_id();

        $a = Array();

        //定义数组存储下级
        $members_down = Array();
        //判断此函数是否是第一次调取
        if(empty($id)){
            //此处做第一次计算，如果此函数为第一次调取
            //获取id对应的一级下级
            for($i = 0 ; $i<count($g_m);$i++){
//               c empty(pdo_fetchall("select id from ".tablename('ewei_shop_member')." where jiedianrenid = ".$g_m[$i]["id"]))?0:1;
                //$a[$i] = $g_m[$i]['id'];
                $doen_cache = pdo_fetchall("select id from ".tablename('ewei_shop_member')." where jiedianrenid = ".$g_m[$i]["id"]);
                if(!empty($doen_cache)){
                    //$a[$i] = pdo_fetchall("select id from ".tablename('ewei_shop_member')." where jiedianrenid = ".$g_m[$i]["id"]);
                    //查询到下级，拆分组装进新的数组
                    for($w = 0 ;$w<count($doen_cache);$w++){
                        $a[$g_m[$i]['id']][$w]["id"] = $doen_cache[$w]["id"];
                        //$a[$i][$w]["p"] = $doen_cache[$w]["id"];
                    }
                }else{
                    //无下级的id填0补位
                    $a[$g_m[$i]['id']] = "0";
                }
            }
        }else{
            //此处做递归计算
        }
        //print_r($a);
        show_json(1,$a);
        return $a;

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
    //获取某人最近一周的业绩(当前用户)
    public function get_member_oneweek_total($id){
        global $_W;
        global $_GPC;

        $g_m = $this -> get_member_id();
        $g_m_w_t = Array();

        //show_json(1,$g_m[2]['id']);
        return $g_m_w_t;
    }

    //获取每个人最近一周产生的业绩(非当前用户)(自购)
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