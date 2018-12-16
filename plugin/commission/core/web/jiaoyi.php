<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Jiaoyi_EweiShopV2Page extends PluginWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        //$member = $this->model->getInfo($_W['openid'], array('total', 'ordercount0', 'ok', 'ordercount', 'wait', 'pay'));

//        $list = pdo_fetchall('select * from ' . tablename('ewei_shop_zhuanzhang') );
        $list = pdo_fetchall('select a.formid ,a.toid , a.mon , a.time , b.realname , c.realname as cname from ' . tablename('ewei_shop_zhuanzhang').'a left join '. tablename('ewei_shop_member').'b on a.formid = b.id left join '. tablename('ewei_shop_member').'c on a.toid = c.id ');





        include $this->template();
    }


}