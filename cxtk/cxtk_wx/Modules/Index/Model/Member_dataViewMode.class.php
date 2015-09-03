<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-8-14
 * Time: 上午9:50
 * 会员表 会员积分表
 */


class Member_dataViewModel extends ViewModel{

    Protected $viewFields=array(

        'member_data'=>array('id','openid','card_id','mid','siteid','name',
            '_type'=>'INNER'),
        'member_integral'=>array('card_id','total_integral','in_integral','xiaofei_integral','date',
            '_on'=>'member_data.card_id=member_integral.card_id')
    );

} 