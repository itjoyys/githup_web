<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bet extends MY_Controller {
    public function __construct() { 
        parent::__construct();
        $this->load->model('sports/Bet_model'); 
        $this->load->library('sportsbet');
        $this->config->load('sportsconfig'); 
        $this->load->model('sports/User_model');
        /*验证是否登录*/
        $this->user_data = $this->get_token_info_is_login(); 
        //echo $this->user_data['uid'];
 
    }

    public function index(){

    }
    public function makebetshow(){
        $post        =    $this->input->post(['matchid','sport_type','pk','oddpk','dsorcg','play_type']);
        $d=$this->makebet($post);
        echo json_encode($d);
    }

    public function makebet($post){

        $d['status']   =    0;
        $d['login']    =$echo['login']    =    1;//重置为登录状态
        $d['msg']      =    '';

        $matchid       =    intval($post['matchid']);
        $sport_type    =    $post['sport_type'];
        $pk            =    $post['pk'];
        if($matchid){
            if($post['oddpk']!='M' && $post['oddpk']!='I' && $post['oddpk']!='E' )$post['oddpk']='H';//默认香港盘
            if($post['dsorcg']!=2)    $post['dsorcg']=1; //单式
            else                      $post['dsorcg']=2;//串关
            
             $sprotsorderpktype=$this->config->item('sprotsorderpktype');//读取投注盘口玩法配置信息
             foreach ($sprotsorderpktype as $key => $value) {
                  $pk_key=$pk_array='';
                  $pkjc  =false;
                   if(array_key_exists($pk,$value)){//判断盘口玩法是否开放            
                     $pk_array=$value[$pk];//根据下注盘口类型获取配置信息 
                     $pk_key  =$key;
                     $pkjc    =true;
                       break;
                   }
             }

             if($pkjc==true){
                 /*限额*/ 
                $setdata=$this->sportsbet->checkxe($setdata_=$this->Bet_model->checkxe($this->user_data['uid'],$this->user_data['agentid']),$sport_type,$pk_array['pkinfo'][0]);

                if($sport_type=='FT')       $data=$this->Bet_model->get_ft_match($matchid,$pk_array['pkinfo'][0]); 
                elseif($sport_type=='FTP')  $data=$this->Bet_model->get_ftp_match($matchid,$pk_array['pkinfo'][0]); 
                elseif($sport_type=='BK')   $data=$this->Bet_model->get_bk_match($matchid);
                elseif($sport_type=='BKP')  $data=$this->Bet_model->get_bkp_match($matchid);
                elseif($sport_type=='VB')   $data=$this->Bet_model->get_vb_match($matchid);
                elseif($sport_type=='TN')   $data=$this->Bet_model->get_tn_match($matchid);
                elseif($sport_type=='BB')   $data=$this->Bet_model->get_bb_match($matchid);
                else $data='';
                if($data){
                    $this->load->library('sportsbet'); //调用体育公共类库 进行赔率算法计算
                     $team=0;
                     //根据玩法盘口选择赔率换算
                    if(($pk=='Match_Ho' || $pk=='Match_Ao') && $post['dsorcg']==1 ){//让球
                        $pk=='Match_Ho'?$team=0:$team=1;
                         $iorH=$data[0]['Match_Ho'];
                         $iorA=$data[0]['Match_Ao'];    
                         $ior=$this->sportsbet->chg_ior($post['oddpk'],$iorH,$iorA,3);
                     }elseif(($pk=='Match_DxDpl' || $pk=='Match_DxXpl') && $post['dsorcg']==1){//大小
                         $pk=='Match_DxDpl'?$team=0:$team=1;
                         $iorH=$data[0]['Match_DxDpl'];
                         $iorA=$data[0]['Match_DxXpl'];
                         $ior=$this->sportsbet->chg_ior($post['oddpk'],$iorH,$iorA,3);
                     }elseif(($pk=='Match_BHo' || $pk=='Match_BAo') && $post['dsorcg']==1){//让球
                         $pk=='Match_BHo'?$team=0:$team=1;
                         $iorH=$data[0]['Match_BHo'];
                         $iorA=$data[0]['Match_BAo'];
                         $ior=$this->sportsbet->chg_ior($post['oddpk'],$iorH,$iorA,3);
                     }elseif(($pk=='Match_Bdpl' || $pk=='Match_Bxpl') && $post['dsorcg']==1){//大小
                         $pk=='Match_Bdpl'?$team=0:$team=1;
                         $iorH=$data[0]['Match_Bdpl'];
                         $iorA=$data[0]['Match_Bxpl'];
                         $ior=$this->sportsbet->chg_ior($post['oddpk'],$iorH,$iorA,3);
                     }else {
                         //不是大小让球盘 默认地区盘口为 香港盘赔率
                         $post['oddpk']='H';
                         $ior[$team]=$data[0][$pk];
                     }
                     //欧美盘 水位 加1 
                    if($post['oddpk']=='E' && ($pk_array['pkinfo'][0]=='rq' || $pk_array['pkinfo'][0]=='dx'))$pk_array['ben_add']=$pk_array['ben_add']+1;
                    //印尼 马来盘 赔率小于1时 可赢 赔率为 本金
                    if(($post['oddpk']=='I' || $post['oddpk']=='M') && $ior[$team]<0) $d['data']['plwin'] =1;
                    else {
                        if(($pk_array['pkinfo'][0]=='rq' || $pk_array['pkinfo'][0]=='dx') && $post['dsorcg']!=1) {//如果串关是让球或者大小盘 赔率加1
                                $d['data']['plwin'] =$ior[$team]+1;
                        }
                        elseif(($pk_array['pkinfo'][0]=='dy' || $pk_array['pkinfo'][0]=='ds') && $post['dsorcg']!=1) {//如果串关是让球或者大小盘 赔率加1
                            $d['data']['plwin'] =$ior[$team];
                        }
                        else {
                            $d['data']['plwin'] =$ior[$team]-$pk_array['ben_add'];
                        }
                    }
                    if($pk_array['pkinfo'][0]=='rq'){
                        //让球半场全场MATCH_SHOWTYPE判断
                        if($pk_array['pkinfo'][1]==2) {$Match_ShowType='Match_ShowType';$match_rgg=$data[0]['Match_RGG'];}
                        else if($pk_array['pkinfo'][1]==1) {$Match_ShowType='Match_Hr_ShowType';$match_rgg=$data[0]['Match_BRpk'];}
                        else  $this->gojson($echo,'msg','02主客让分信息错误');
                        //异常赔率
                        if($sport_type=='BK' || $sport_type=='BKP' ){
                                if($data[0]['Match_Ho']>=1.5 || $data[0]['Match_Ao']>=1.5) $this->gojson($echo,'msg','');
                        }

                        $match_showtype=$data[0][$Match_ShowType];
                        //if($pd['Match_ShowType']!=$data[$Match_ShowType]) $this->gojson($echo,'msg','03盘口数据已变['.$data[$rqdxpk].']');
                        
                    }elseif($pk_array['pkinfo'][0]=='dx'){
                        if($pk_array['pkinfo'][1]==2) $Match_ShowType='Match_DxGG';
                        else if($pk_array['pkinfo'][1]==1) $Match_ShowType='Match_Bdxpk';
                        else  $this->gojson($echo,'msg','04大小盘信息错误');
                        //让分盘判断
                        $match_dxgg=$data[0][$Match_ShowType];
                    }
                    
                    $d['data']['pl']     =$ior[$team];
                    $d['data']['pk']     =[$pk_array,$pk,$pk_key,'xe'=>$setdata,'match_dxgg'=>$match_dxgg,'match_rgg'=>$match_rgg];
                    $d['data']['data']   =$data;
                    $d['data']['oddpk']  =$post['oddpk'];
                    $d['data']['dsorcg'] =$post['dsorcg'];
                    $d['data']['betinfo']=$this->sportsbet->write_bet_info($this->sporttypeetc($sport_type),$pk,$data[0]['Match_Master'].'VS'.$data[0]['Match_Guest'],$ior[$team],$match_showtype,$match_rgg,$match_dxgg,$data[0]['Match_NowScore'],$tid=0);

                    $d['status']=    1;
                }else{
                    $d['msg']='赛事已经关闭或移至滚球盘！';
                    $d['status']=    0;
                }

             }else{
                 $d['msg']='下注盘口关闭';
                 $d['status']=    0;
             }
        }else $d['status']=    0;

        return $d;
    }
    /*注单提交*/
    public function post_bet(){
        $msg=['系统余额不足','投注金额不正确','下注盘口未开放','体育过关至少选择3场赛事','体育过关最多10场赛事','下注成功','下注失败']; 
        $cd=false;
        $echo['status']=0;//初始验证为失败
        $echo['login'] =1;//初始登录为登录
        $dsorcg=intval($this->input->get('dsorcg'));
        $pd=$this->input->post(['Match_ID','Match_ShowType','Match_Type','Bet_PK','Bet_PL','Win_PL','bet_money','Sport_Type','Odd_PK']);
        //print_r($pd);

        $pd['bet_money']=intval($pd['bet_money']);
        //判断用户金额是否足够下注
            //获取用户金额
            if($pd['bet_money']<=0) $this->gojson($echo,'msg',$msg[1]);


            $this->load->model('User_model');
            $user=$this->user_data;
            $user_money=$this->User_model->get_user_money($user['uid']);
            $user_money=$user_money['money'];
            if($pd['bet_money']>$user_money || $user_money<=0) $this->gojson($echo,'msg',$msg[0]); 
            //如果负水盘 判断该扣的金额为赔率X下注金额
             
            if($pd['Bet_PL'][0]<0 && $pd['Bet_PL'][0]*$pd['bet_money']*(-1)>$user_money) $this->gojson($echo,'msg',$msg[0]); 
            //生成注单信息

               //判断用户投注限额是否满足

            //判断赛事是否已经结束 + 自定义关盘

        //判断盘口是否已经关闭

        //如果是足球滚球
        //记录当前主客队红牌数

        //审查注单盘口/赔率信息和数据库缓存数据是否正确
/*        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $foo = 'aaa';
        $this->cache->save('foo', $foo, 300);
        $this->cache->get('foo');*/

        if($dsorcg==1){//单式

              //$makebet (['matchid','sport_type','pk','oddpk','dsorcg']);
              $bet=$this->makebet(['matchid'=>$pd['Match_ID'][0],'sport_type'=>$pd['Sport_Type'][0],'pk'=>$pd['Match_Type'][0],'oddpk'=>$pd['Odd_PK'][0],'dsorcg'=>1]); 
              if($bet['status']==0) $this->gojson($echo,'msg',$bet['msg']); 
              $cd=$this->check_point(
                                    $bet,
                                    [
                                        'Sport_Type'=>$pd['Sport_Type'][0],
                                        'Match_ID'=>$pd['Match_ID'][0],
                                        'Match_ShowType'=>$pd['Match_ShowType'][0],
                                        'Match_Type'=>$pd['Match_Type'][0],
                                        'Bet_PK'=>$pd['Bet_PK'][0],
                                        'Win_PL'=>$pd['Win_PL'][0],
                                        'Bet_PL'=>$pd['Bet_PL'][0],
                                        'Sport_Type'=>$pd['Sport_Type'][0],
                                        'Odd_PK'=>$pd['Odd_PK'][0]
                                    ]);
              if($cd['status']==1){
                $addbet=$this->addbetds(
                                        $bet,
                                        [
                                            'bet_money'=>$pd['bet_money'],
                                            'Sport_Type'=>$pd['Sport_Type'][0]
                                        ],
                                        $user);      
                if($addbet==true)  {
                    $echo['status']=2;
                    $this->gojson($echo,'msg',$msg[5]); 
                }else $this->gojson($echo,'msg',$msg[6]); 
              }
        }elseif(count($pd['Match_ID'])>2){//串关   而且默认串关写死为香港盘
             if(count($pd['Match_ID'])>10) $this->gojson($echo,'msg',$msg[4]); 
             $betwin=1;
             $gg_matchid=[];
             $gg_matchname=[];
             foreach($pd['Match_ID'] as $k=>$v){
                //重复赛事判断
                if(in_array($v,$gg_matchid)) $this->gojson($echo,'msg','同场赛事不能进行赛事投注'); 
                else $gg_matchid[]=$v;
                

                //过关强制为香港盘
                if($pd['Odd_PK'][$k]!='H')  $this->gojson($echo,'msg','过关下注只支持香港盘'); 
                $bet=$this->makebet([
                                    'Sport_Type'=>$pd['Sport_Type'][$k],
                                    'matchid'=>$pd['Match_ID'][$k],
                                    'sport_type'=>$pd['Sport_Type'][$k],
                                    'pk'=>$pd['Match_Type'][$k],
                                    'oddpk'=>'H',
                                    'dsorcg'=>2
                                    ]); 
                //重复队名串关过滤
                $cgnameonly=str_replace(['-(上半)','-(第1节)','-(第2节)','-(第3节)'],['','',''],$bet['data']['data'][0]['Match_Master']);
                if(in_array($cgnameonly,$gg_matchname)) $this->gojson($echo,'msg','同一队名不能进行赛事投注'); 
                else $gg_matchname[]=$cgnameonly;
               
                if($bet['status']==0) $this->gojson($echo,'msg',$bet['msg']); 

                $cd=$this->check_point(
                    $bet,
                    [
                        'Match_ID'=>$pd['Match_ID'][$k],
                        'Match_ShowType'=>$pd['Match_ShowType'][$k],
                        'Match_Type'=>$pd['Match_Type'][$k],
                        'Bet_PK'=>$pd['Bet_PK'][$k],
                        'Win_PL'=>$pd['Win_PL'][$k],
                        'Bet_PL'=>$pd['Bet_PL'][$k],
                        'Sport_Type'=>$pd['Sport_Type'][$k],
                        'Odd_PK'=>'H'
                    ]);
                if($cd['status']!=1)  $this->gojson($echo,'msg','过关下注失败'); 
                $cgd[$k]=$bet;
                $betwin*=$bet['data']['plwin'];
              }
              $betwin-=1;
              $addbetcg=$this->addbetcg($cgd,$pd,$user,$betwin);
              if($addbetcg==true)  {
                    $echo['status']=2;
                    $this->gojson($echo,'msg',$msg[5]); 
              }else $this->gojson($echo,'msg',$msg[6]); 
        }else  $this->gojson($echo,'msg',$msg[3]); 
        $echo['status']=1;
        echo json_encode($echo);
    }
    /*水位盘口对比*/
    public function check_point($bet,$pd){
        //print_r($bet);
        //print_r($pd); 
        $echo['status']=0;
        $echo['data']  =$bet;
        $sprotsorderpktype=$bet['data']['pk'][0]['pkinfo'];
        $rqdxpk           =$bet['data']['pk'][0][2];//让球大小盘口字段名称
        $data             =$bet['data']['data'][0];


        //让球大小盘口检查
        if($sprotsorderpktype[0]=='rq' || $sprotsorderpktype[0]=='dx'){
            if($pd['Bet_PK']!=$data[$rqdxpk] || $data[$rqdxpk]=='') $this->gojson($echo,'msg','01盘口数据已变['.$data[$rqdxpk].']');
            if($sprotsorderpktype[0]=='rq'){
                //让球半场全场MATCH_SHOWTYPE判断
                if($sprotsorderpktype[1]==2) $Match_ShowType='Match_ShowType';
                else if($sprotsorderpktype[1]==1) $Match_ShowType='Match_Hr_ShowType';
                else  $this->gojson($echo,'msg','02主客让分信息错误');
                //让分盘判断
                if($pd['Match_ShowType']!=$data[$Match_ShowType]) $this->gojson($echo,'msg','03盘口数据已变['.$data[$rqdxpk].']');
           
            }else{
                //大小半场 全场 Match_DxGG  Match_Bdxpk 判断
                if($sprotsorderpktype[1]==2) $Match_ShowType='Match_DxGG';
                else if($sprotsorderpktype[1]==1) $Match_ShowType='Match_Bdxpk';
                else  $this->gojson($echo,'msg','04大小盘信息错误');
                //大小盘判断
                $match_dxgg=$data[0][$Match_ShowType];
            }
        }
        //赔率判断 
        $betpoint   =  sprintf('%.2f',$bet['data']['plwin']);
        $betpl      =  sprintf('%.2f',$bet['data']['pl']);
        $pdpoint    =  sprintf('%.2f',$pd['Win_PL']);
        $pdpl       =  sprintf('%.2f',$pd['Bet_PL']);

        if($pdpl!=$betpl)   $this->gojson($echo,'msg','06下注失败！'.chr(012).'当前赔率已变为['.$pdpl.'=>'.$betpl.']'.chr(012).'['.$bet['data']['data'][0]['Match_Master'].'VS'.$bet['data']['data'][0]['Match_Guest'].']');
        if($betpoint<=0.04) $this->gojson($echo,'msg','07下注失败！');
        if(abs($betpl)>=30 && ($bet['data']['oddpk']=='I' || $bet['data']['oddpk']=='M')) $this->gojson($echo,'msg','08下注失败！');

        return ['status'=>1];
            
    }
    public function oddtoktype($odd){
        switch ($odd) {
            case 'H':
                $d=0;
                break;
            case 'M':
                $d=1;
                break;
            case 'I':
                $d=2;
                break;
            case 'E':
                $d=3;
                break;
            default:
                 $d=0;
                break;
        }
        return $d; 
    }
    public function addbetds($bet,$pd,$userdata){
         
        $user_money=$this->User_model->get_user_money($userdata['uid']);
        $user_money=$user_money['money'];

        $sprotsorderpktype=$bet['data']['pk'][0]['pkinfo'];
        $rqdxpk           =$bet['data']['pk'][0][2];//让球大小盘口字段名称
        $data             =$bet['data']['data'][0];
        $ben_add          =$bet['data']['pk'][0]['ben_add'];
        if($ben_add==0)   $ben_add=1;
        else              $ben_add=0;


        // ball_sort  FT -> 足球单式  转换成中文
        $ball_sort=$this->sporttypeetc($pd['Sport_Type']);
        if($sprotsorderpktype[1]==2) $Match_ShowType='Match_ShowType';
        else  $Match_ShowType='Match_Hr_ShowType'; 
        $bet_info=$this->sportsbet->write_bet_info($ball_sort,
            $bet['data']['pk'][1],
            $data['Match_Master'].'VS'.$data['Match_Guest'],
            sprintf('%.2f',$bet['data']['pl']),
            $data[$Match_ShowType],
            $bet['data']['pk']['match_rgg'],
            $bet['data']['pk']['match_dxgg'],
            $data['Match_NowScore']);
        //盘口转化成数字
        $k_type=$this->oddtoktype($bet['data']['oddpk']);
       /* uid,            */  $d['uid']            =  $userdata['uid'];
       /* ball_sort,        */$d['ball_sort']      =  $ball_sort;
       /* point_column,*/     $d['point_column']   =  strtolower($bet['data']['pk'][1]);
       /* match_name,*/       $d['match_name']     =  $data['Match_Name'];
       /* master_guest,*/     $d['master_guest']   =  $data['Match_Master'].'VS'.$data['Match_Guest'];
       /* match_id,*/         $d['match_id']       =  $data['Match_ID'];
       /* bet_info,*/         $d['bet_info']       =  $bet_info;
       /* bet_money,*/        $d['bet_money']      =  $pd['bet_money'];
       /* bet_point,*/        $d['bet_point']      =  sprintf('%.2f',$bet['data']['pl']);//赔率 
       /* ben_add,*/          $d['ben_add']        =  $ben_add;
       /* bet_win,*/          $d['bet_win']        =  sprintf('%.2f',$pd['bet_money']*$bet['data']['plwin']);
       /* match_time,*/       $d['match_time']     =  $data['Match_Time'];//比赛时间
       /* bet_time,*/         $d['bet_time']       =  date('Y-m-d H:i:s');//下注时间
       /* match_endtime,*/    $d['match_endtime']  =  $data['Match_CoverDate'];//开赛时间  YOU  ZHENGYI
       /* lose_ok,*/          $d['lose_ok']        =  $data['lose_ok'];//是否确认审核注单 0是未审核 1是审核
       /* match_showtype,*/   $d['match_showtype'] =  $data[$Match_ShowType];
       /* match_rgg,*/        $d['match_rgg']      =  $bet['data']['pk']['match_rgg']; //让分盘口数据
       /* match_dxgg,*/       $d['match_dxgg']     =  $bet['data']['pk']['match_dxgg'];//大小盘口数据
       /* match_nowscore,*/   $d['match_nowscore'] =  $data['Match_NowScore'];
       /* match_type,*/       $d['match_type']     =  $data['Match_Type'];//2是滚球 1是单式
       /* balance,下注后余额*/$d['balance']        =  $user_money;//进去算了这里不算了
       /* assets,下注前金额*/ $d['assets']         =  $user_money;
       /* Match_HRedCard,*/   $d['Match_HRedCard'] =  intval($data['Match_HRedCard']);//主队红卡
       /* Match_GRedCard,*/   $d['Match_GRedCard'] =  intval($data['Match_GRedCard']);//客队红卡
       /* www,*/              $d['www']            =  $_SERVER['HTTP_HOST'];
       /* match_coverdate,*/  $d['match_coverdate']=  $data['Match_CoverDate']; 
       /* site_id,*/          $d['site_id']        =  $userdata['siteid'];
       /* agent_id,*/         $d['agent_id']       =  $userdata['agentid'];
       /* indexid,*/          $d['index_id']       =  $userdata['indexid'];
       /* k_type,*/           $d['k_type']         =  $k_type;
       /* ua_id,*/            $d['ua_id']          =  $userdata['ua_id'];
       /* ua_id,*/            $d['sh_id']          =  $userdata['sh_id'];
       /* username*/          $d['username']       =  $userdata['username'];
       return $this->Bet_model->addbetds($d);
 /* 
 print_r($d);
 print_r($bet);
 print_r($pd);
        */
    }
    public function sporttypeetc($Sport_Type){
        $ball_sort='';
        if($Sport_Type    =='FT')  $ball_sort='足球单式';
        elseif($Sport_Type=='FTP') $ball_sort='足球滚球';
        elseif($Sport_Type=='BK')  $ball_sort='篮球单式';
        elseif($Sport_Type=='BKP') $ball_sort='篮球滚球';
        elseif($Sport_Type=='VB')  $ball_sort='排球单式';
        elseif($Sport_Type=='TN')  $ball_sort='网球单式';
        elseif($Sport_Type=='BB')  $ball_sort='棒球单式';
        return $ball_sort;
    }
    public function addbetcg($bet,$pd,$userdata,$betwin){
        $user_money=$this->User_model->get_user_money($userdata['uid']);
        $user_money=$user_money['money'];
        
         /*uid,*/               $d['uid']            =  $userdata['uid'];
         /*cg_count,*/          $d['cg_count']       =  count($bet);
         /*bet_money,*/         $d['bet_money']      =  $pd['bet_money'];
         /*bet_win,*/           $d['bet_win']        =  sprintf('%.2f',$betwin*$pd['bet_money']);
         /*balance,*/           $d['balance']        =  $user_money-$pd['bet_money'];
         /*assets,*/            $d['assets']         =  $user_money;
         /*www,*/               $d['www']            =  $_SERVER["SERVER_NAME"];
         /*match_coverdate,*/   $d['match_coverdate']=  $bet[0]['data']['data'][0]['Match_CoverDate']; //默认取第一个
         /*site_id,*/           $d['site_id']        =  $userdata['siteid']; 
         /*index_id,*/          $d['index_id']       =  $userdata['indexid']; 
         /*bet_time,*/          $d['bet_time']       =  date('Y-m-d H:i:s');
         /*agent_id,*/          $d['agent_id']       =  $userdata['agentid'];
         /*username*/           $d['username']       =  $userdata['username'];
         /* ua_id,*/            $d['ua_id']          =  $userdata['ua_id'];
         /* ua_id,*/            $d['sh_id']          =  $userdata['sh_id'];
         //) values('$uid','$cg_count','$bet_money','$bet_win',$balance,$assets,'$conf_www','$ksTime','".SITEID."','$InsertTime','".$_SESSION["agent_id"]."','".$_SESSION['username']."')"; 
         foreach ($bet as $k => $v) {
            $sprotsorderpktype=$v['data']['pk'][0]['pkinfo'];
            $rqdxpk           =$v['data']['pk'][0][2];//让球大小盘口字段名称
            $data             =$v['data']['data'][0];
            $ben_add          =$v['data']['pk'][0]['ben_add'];
            if($ben_add==0)   $ben_add=1;
            else              $ben_add=0;
             // ball_sort  FT -> 足球单式  转换成中文
            $ball_sort=$this->sporttypeetc($pd['Sport_Type'][$k]);
            if($sprotsorderpktype[1]==2) $Match_ShowType='Match_ShowType';
            else  $Match_ShowType='Match_Hr_ShowType'; 
            $bet_info=$this->sportsbet->write_bet_info( $ball_sort,
                                                        $v['data']['pk'][1],
                                                        $data['Match_Master'].'VS'.$data['Match_Guest'],
                                                        sprintf('%.2f',$v['data']['pl']),
                                                        $data[$Match_ShowType],
                                                        $v['data']['pk']['match_rgg'],
                                                        $v['data']['pk']['match_dxgg'],
                                                        $data['Match_NowScore']
                                                        );

            /*uid,*/            $cd[$k]['uid']           =    $userdata['uid'];
            /*gid,*/
            /*ball_sort,*/      $cd[$k]['ball_sort']     =    $ball_sort;
            /*point_column,*/   $cd[$k]['point_column']  =    strtolower($v['data']['pk'][1]);
            /*match_name,*/     $cd[$k]['match_name']    =    $data['Match_Name'];
            /*master_guest,*/   $cd[$k]['master_guest']  =    $data['Match_Master'].'VS'.$data['Match_Guest'];
            /*match_id,*/       $cd[$k]['match_id']      =    $data['Match_ID'];
            /*bet_info,*/       $cd[$k]['bet_info']      =    $bet_info;
            /*bet_money,*/      $cd[$k]['bet_money']     =    $pd['bet_money'];
            /*bet_point,*/      $cd[$k]['bet_point']     =    sprintf('%.2f',$v['data']['pl']);//赔率 
            /*ben_add,*/        $cd[$k]['ben_add']       =    $ben_add;
            /*match_endtime,*/  $cd[$k]['match_endtime'] =   $data['Match_CoverDate'];//开赛时间  YOU  ZHENGYI
            /*match_showtype,*/ $cd[$k]['match_showtype']=   $data[$Match_ShowType];
            /*match_rgg,*/      $cd[$k]['match_rgg']     =   $v['data']['pk']['match_rgg']; //让分盘口数据
            /*match_dxgg,*/     $cd[$k]['match_dxgg']    =   $v['data']['pk']['match_dxgg'];//大小盘口数据
           // /*match_nowscore,*/ $cd['match_nowscore']=  $data['Match_NowScore'];
            /*site_id*/         $cd[$k]['site_id']       =  $userdata['siteid'];
         }
 
        return $this->Bet_model->addbetcg($d,$cd);
        //print_r($bet);
        //print_r($pd);
    }

}
