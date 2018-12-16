<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'commission/core/page_login_mobile.php';
class Zhuan_EweiShopV2Page extends CommissionMobileLoginPage
{
    public function main()
    {
        $r=$_POST['a'];
        global $_W;
        global $_GPC;
        $member = $this->model->getInfo($_W['openid'], array('total', 'ordercount0', 'ok', 'ordercount', 'wait', 'pay'));
        include $this->template();
    }
    public function MoneyTo(){
        //        当前id
        $fromid = $_POST['fromid'];
        //        当前uid
        $fromuid = $_POST['fromuid'];
        //        当前余额
        $frommon = $_POST['frommon'];
        //        转账金额
        $mon = $_POST['mon'];
        //        转账id
        $toid = $_POST['toid'];
        //        转账uid
        $touid = $_POST['touid'];
        //        转账余额
        $tomon = $_POST['tomon'];
        //        存库信息
        $frominmon = $frommon - $mon;
        $toinmon = $tomon + $mon;

//返回信息
        $arr[fromid]=$fromid;
        $arr[fromuid]=$fromuid;
        $arr[frommon]=$frommon;
        $arr[mon]=$mon;
        $arr[toid]=$toid;
        $arr[touid]=$touid;
        $arr[tomon]=$tomon;

//        更新操作
        pdo_update("mc_members",array("credit2" => $toinmon),array("uid"=>$touid));
        pdo_update("mc_members",array("credit2" => $frominmon),array("uid"=>$fromuid));
//        日志操作
        $date = Date('Y-m-d');
        $data = array( "formid" => $fromid, "toid" => $toid, "mon" => $mon, "time" => $date );
        pdo_insert('ewei_shop_zhuanzhang', $data);

        show_json(1 ,$arr);
    }
    public function tot(){
        $r=$_POST['a'];
        //echo $r;

        //$sql = pdo_fetch("select * from".tablename('ewei_shop_member')." where id =  '".$r."' or mobile = '".$r."' or realname = '".$r."' or nickname = '".$r."'");

        $sql = pdo_fetch("select a.id,b.* from".tablename('ewei_shop_member')."a left join ".tablename('mc_members')." b on a.uid=b.uid where a.id =  '".$r."' or b.mobile = '".$r."' or b.realname = '".$r."' or b.nickname = '".$r."'");

//        return $sql['id'];

        //$o = $sql['level'];
        show_json(1,$sql);
//        杜尔克斯大公爵

    }


}

?>
