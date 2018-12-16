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
        $item["theone"] = $this->get_member();
        show_json(1,$item["theone"]);
        include $this->template();
    }
    //获取全部符合要求的消费股东与合伙人
    public function get_member(){

        $g_m = pdo_fetchall('select * from '.tablename('ewei_shop_member').' where agentlevel = 10 or agentlevel = 12');
        return $g_m;
    }
    //查询这个人的周分红
    public function get_member_zhou(){

    }


}