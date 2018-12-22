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
        $g_m = $this -> get_member_id();
        $g_m_all  = $this -> get_member_id("get_all");
        //定义一级模板数组
        /*
         * item
         * down_num 下级人数
         * 获取每个id自身产生的业绩、上周累计业绩与累计业绩
         * down_total_new_week 上周业绩
         * total_new_week 上周产生
         * one_week_total 周分红
         * total 总业绩
        **/
        $item = Array();
        //定义二级模板数据
        /*
         * id
         * total_by_new_week
         *
         * */
        $item_down = Array();
        //获取各种信息
        $they_one = $this->get_member_id();//获取全部的消费股东与公司合伙人
        $they_all_down = $this -> get_member_down($g_m_all,$_GPC['id'],0);//获取所有用户的下级并按接点人顺序分级
        $they_down = $this -> get_member_down($g_m_all,$_GPC['id'],0);
        $they_total = "0";
//        show_json(1,$_GPC['id']);
        //show_json(1,$this ->get_member_down($g_m_all,'6565',0)['1']);
//
//        foreach($array as $value){
//               echo str_repeat('--', $value['level']), $value['id'].'<br />';
//            }
        //show_json(1,$this -> get_member_id("down_num"));
        //组织一级模板数组
        for($i = 0 ;$i<count($they_one); $i++){
            $item[$i] = $they_one[$i];
            $item[$i]['level'] = $they_all_down[$i]['level'];
            $item[$i]['total'] = $this -> get_member_total()[$i];
            $item[$i]['total_new_week'] = $this -> get_member_oneweek_total_all()[$i];
            }
        //组织二级模板数组
        for($p = 0 ;$p<count($they_all_down); $p++){
            $item_down[$p] = $they_all_down[$p];
            //show_json(1,$this -> get_member_id("info","6572"));
            $item_down[$p]['nickname'] = $this -> get_member_id("info",$they_all_down[$p]['id'])[0]['nickname'];
            $item_down[$p]['realname'] = $this -> get_member_id("info",$they_all_down[$p]['id'])[0]['realname'];
            $item_down[$p]['level'] = $this -> get_member_id("info",$they_all_down[$p]['id'])[0]['level'];
        }
        //show_json(1,$item);
        include $this->template();
    }
    //获取全部符合要求的消费股东与合伙人
    public function get_member_id($who,$id){
        global $_W;
        global $_GPC;
        if($who == "get_all"){
            $g_m = pdo_fetchall('select id,jiedianrenid,openid from '.tablename('ewei_shop_member').' order by id');
        }else if($who == "info"){
            $g_m = pdo_fetchall('select * from '.tablename('ewei_shop_member').' where id ='.$id);
            //show_json(1,$g_m);
        }else{
            $g_m = pdo_fetchall('select * from '.tablename('ewei_shop_member').' where agentlevel = 10 or agentlevel = 12');
        }

        return $g_m;
    }
    //获取每个人的下级
    public function get_member_down($data,$jdr=0,$level=0){
        static $list = Array();
        //递归获取
        foreach ($data as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['jiedianrenid']==$jdr){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                //array_push($list,$value);
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($data[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                this.$this->get_member_down($data, $value['id'], $level+1);
            }
        }
        return $list;
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