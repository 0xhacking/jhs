<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'commission/core/page_login_mobile.php';
class Zhou_EweiShopV2Page extends CommissionMobileLoginPage
{
    public function main()
    {
        $r=$_POST['a'];
        global $_W;
        global $_GPC;
        $member = $this->model->getInfo($_W['openid']);
        $user_zhoumoney = pdo_fetchall("select * from ".tablename("ewei_shop_zhou")." where uid = ".$member['id']);
        $tt = '';
        for($r = 0 ; $r < count($user_zhoumoney)  ; $r++ ){
            $tt = $tt.'
            <div class="userblock">
            <div class="line total new" style="height: 3.15rem;padding: 0">
                <div style="text-align: center;font-size: 60px;font-family: one;color: rgba(93, 93, 93, 0.44);line-height: 3.15rem;letter-spacing:40px;font-style: italic ">'.$user_zhoumoney[count($user_zhoumoney)-1-$r]["year_num"].'</div>
            </div>
            <div style="display: flex;">
                <!--分红金额-->
                <div class="zhou">
                '.$user_zhoumoney[count($user_zhoumoney)-1-$r]["fa_money"]. '
                </div>
                 <!--分红周-->
                <div class="zhou">
                    '.$user_zhoumoney[count($user_zhoumoney)-1-$r]["zhou_num"].'
                </div>
                <!--分红月份-->
                <div class="zhou">
                    '.$user_zhoumoney[count($user_zhoumoney)-1-$r]["month_num"].'
                </div>
                <!--是否提现-->
                <div class="zhou">
                    '.($user_zhoumoney[count($user_zhoumoney)-1-$r]["is_ti"] == 0 ? 未提现 : 已提现 ).'
                </div>
                 <!--详情-->
                <div class="zhou">
                    '.($user_zhoumoney[count($user_zhoumoney)-1-$r]["is_ti"] == 0 ? 详情 : 详情 ).'
                </div>
            </div>

        </div>';
        }
        include $this->template();
    }

}

?>
