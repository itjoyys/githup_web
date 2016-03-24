<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('sports/Match_model');
    }
    //整
    public function double_format($double_num){
        return $double_num>0 ? sprintf("%.2f",$double_num) : $double_num<0 ? sprintf("%.2f",$double_num) : 0;
    }

    public function FootballGG(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 
        $post['oddpk']='H';
        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->FootballGG($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
         
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];  
            $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];  
            $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];   
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];    
            $mdate    =    $rows["Match_Date"]."<br/>".$rows["Match_Time"];
        if ($rows["Match_IsLose"]==1){
            $mdate.= "<br><font color=red>滾球</font>";
        }
            $json["db"][$i]["Match_Date"]        =    $mdate; 
             $rows["Match_BzM"]<>""?$a=$rows["Match_BzM"]:$a=0;
            $json["db"][$i]["Match_BzM"]        =    $a;
            $this->double_format($rows["Match_Ho"])<>""?$b=$this->double_format($rows["Match_Ho"]):$b=0;
            $json["db"][$i]["Match_Ho"]            =    $b;    
            $rows["Match_DxDpl"]<>""?$c=$rows["Match_DxDpl"]:$c=0;
            $json["db"][$i]["Match_DxDpl"]        =    $c;   
            $rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
            $json["db"][$i]["Match_DsDpl"]        =    $d;    
            $rows["Match_BzG"]<>""?$e=$rows["Match_BzG"]:$e=0;
            $json["db"][$i]["Match_BzG"]        =    $e;   
            $rows["Match_Ao"]<>""?$f=$rows["Match_Ao"]:$f=0;
            $json["db"][$i]["Match_Ao"]            =    $f;   
            $rows["Match_DxXpl"]<>""?$g=$rows["Match_DxXpl"]:$g=0;
            $json["db"][$i]["Match_DxXpl"]        =    $g;   
            $rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
            $json["db"][$i]["Match_DsSpl"]        =    $h;    
            $rows["Match_BzH"]<>""?$k=$rows["Match_BzH"]:$k=0;
            $json["db"][$i]["Match_BzH"]        =    $k;     
            $rows["Match_RGG"]<>""?$j=$rows["Match_RGG"]:$j=0;
            $json["db"][$i]["Match_RGG"]        =    $j;   
            $rows["Match_DxGG"]<>""?$m=$rows["Match_DxGG"]:$m=0;
            $json["db"][$i]["Match_DxGG1"]        =    $m;    
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $rows["Match_DxGG"]<>""?$n=$rows["Match_DxGG"]:$n=0;
            $json["db"][$i]["Match_DxGG2"]        =    $n; 
            $match1=$rows["Match_BHo"]+$rows["Match_BAo"];
            $match2=$rows["Match_Bdpl"]+$rows["Match_Bxpl"];//print_r($rows);exit;
        if(($match1!=0||$match2!=0)&&$rows["Match_IsShowb"]==1){
            $json["db"][$i]["Match_Bmdy"]        =    $rows["Match_Bmdy"];
            $json["db"][$i]["Match_BHo"]        =    $rows["Match_BHo"];
            $json["db"][$i]["Match_Bdpl"]        =    $rows["Match_Bdpl"]; 
            $json["db"][$i]["Match_Bgdy"]        =    $rows["Match_Bgdy"]; 
            $json["db"][$i]["Match_BAo"]        =    $rows["Match_BAo"];  
            $json["db"][$i]["Match_Bxpl"]        =    $rows["Match_Bxpl"];  
            $json["db"][$i]["Match_Bhdy"]        =    $rows["Match_Bhdy"];
            $json["db"][$i]["Match_BRpk"]        =    $rows["Match_BRpk"];
            $json["db"][$i]["Match_Bdxpk1"]        =    $rows["Match_Bdxpk"];
            $json["db"][$i]["Match_Hr_ShowType"]=    $rows["Match_Hr_ShowType"];
            $json["db"][$i]["Match_Bdxpk2"]        =    $rows["Match_Bdxpk"];
        }else{
            $json["db"][$i]["Match_Bmdy"]        =    '0';
            $json["db"][$i]["Match_BHo"]        =    '0';
            $json["db"][$i]["Match_Bdpl"]        =    '0'; 
            $json["db"][$i]["Match_Bgdy"]        =    '0'; 
            $json["db"][$i]["Match_BAo"]        =    '0';  
            $json["db"][$i]["Match_Bxpl"]        =    '0';  
            $json["db"][$i]["Match_Bhdy"]        =    '0'; 
            $json["db"][$i]["Match_BRpk"]        =    '';
            $json["db"][$i]["Match_Bdxpk1"]        =    '';
            $json["db"][$i]["Match_Hr_ShowType"]=    '';
            $json["db"][$i]["Match_Bdxpk2"]        =    '';
        }
         
        }
        echo json_encode($json);
    }
    public function Footballtoday(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']  =$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->Footballtoday($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
        
        foreach($d['d'] as $i=>$rows){
            
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];  
            $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];  
            $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];   
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];    
            $mdate    =    $rows["Match_Date"]."<br/>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_Date"]        =    $mdate; 
            
        
             
            $rows["Match_BzM"]<>""?$a=$rows["Match_BzM"]:$a=0;
            $json["db"][$i]["Match_BzM"]        =    $a;
            $this->double_format($rows["Match_Ho"])<>""?$b=$this->double_format($rows["Match_Ho"]):$b=0;
            $json["db"][$i]["Match_Ho"]            =    $b;    
            $rows["Match_DxDpl"]<>""?$c=$rows["Match_DxDpl"]:$c=0;
            $json["db"][$i]["Match_DxDpl"]        =    $c;   
            $rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
            $json["db"][$i]["Match_DsDpl"]        =    $d;    
            $rows["Match_BzG"]<>""?$e=$rows["Match_BzG"]:$e=0;
            $json["db"][$i]["Match_BzG"]        =    $e;   
            $rows["Match_Ao"]<>""?$f=$rows["Match_Ao"]:$f=0;
            $json["db"][$i]["Match_Ao"]            =    $f;   
            $rows["Match_DxXpl"]<>""?$g=$rows["Match_DxXpl"]:$g=0;
            $json["db"][$i]["Match_DxXpl"]        =    $g;   
            $rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
            $json["db"][$i]["Match_DsSpl"]        =    $h;    
            $rows["Match_BzH"]<>""?$k=$rows["Match_BzH"]:$k=0;
            $json["db"][$i]["Match_BzH"]        =    $k;     
            $rows["Match_RGG"]<>""?$j=$rows["Match_RGG"]:$j=0;
            $json["db"][$i]["Match_RGG"]        =    $j;   
            $rows["Match_DxGG"]<>""?$m=$rows["Match_DxGG"]:$m=0;
            $json["db"][$i]["Match_DxGG1"]        =    $m;    
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $rows["Match_DxGG"]<>""?$n=$rows["Match_DxGG"]:$n=0;
            $json["db"][$i]["Match_DxGG2"]        =    $n; 
            $match1=$rows["Match_BHo"]+$rows["Match_BAo"];
            $match2=$rows["Match_Bdpl"]+$rows["Match_Bxpl"];//print_r($rows);exit;
        if(($match1!=0||$match2!=0)&&$rows["Match_IsShowb"]==1){
            $json["db"][$i]["Match_Bmdy"]        =    $rows["Match_Bmdy"];
            $json["db"][$i]["Match_BHo"]         =    $rows["Match_BHo"];
            $json["db"][$i]["Match_Bdpl"]        =    $rows["Match_Bdpl"]; 
            $json["db"][$i]["Match_Bgdy"]        =    $rows["Match_Bgdy"]; 
            $json["db"][$i]["Match_BAo"]         =    $rows["Match_BAo"];  
            $json["db"][$i]["Match_Bxpl"]        =    $rows["Match_Bxpl"];  
            $json["db"][$i]["Match_Bhdy"]        =    $rows["Match_Bhdy"];
            $json["db"][$i]["Match_BRpk"]        =    $rows["Match_BRpk"];
            $json["db"][$i]["Match_Bdxpk1"]        =    $rows["Match_Bdxpk"];
            $json["db"][$i]["Match_Hr_ShowType"]   =    $rows["Match_Hr_ShowType"];
            $json["db"][$i]["Match_Bdxpk2"]        =    $rows["Match_Bdxpk"];
        }else{
            $json["db"][$i]["Match_Bmdy"]        =    '0';
            $json["db"][$i]["Match_BHo"]         =    '0';
            $json["db"][$i]["Match_Bdpl"]        =    '0'; 
            $json["db"][$i]["Match_Bgdy"]        =    '0'; 
            $json["db"][$i]["Match_BAo"]         =    '0';  
            $json["db"][$i]["Match_Bxpl"]        =    '0';  
            $json["db"][$i]["Match_Bhdy"]        =    '0'; 
            $json["db"][$i]["Match_BRpk"]        =    '';
            $json["db"][$i]["Match_Bdxpk1"]      =    '';
            $json["db"][$i]["Match_Hr_ShowType"] =    '';
            $json["db"][$i]["Match_Bdxpk2"]      =    '';
        }
         
        }
         
        echo json_encode($json);
    }
    
    public function FootballPlaying(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']  =$post['p'];
        //$r['leg']=trim('|',$post['leg']);
        $legarr=@explode('|',$post['leg']);
        $d = $this->Match_model->FootballPlaying($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
        /*$dd=file_get_contents('D:\Web\wwwroot_new\ciwebs_\t_a\cyj_web\models\sports\1.txt');
        //echo $dd;exit;
        $dd=json_decode($dd,true); 
        //print_r($dd);
        $d['d']=$dd['db'];*/
        $i=0;
        //print_r($legarr);
        if($d['d']){
            foreach($d['d'] as $ii=>$rows){
                $leg=false;
                if(strpos($rows["Match_Name"],'测试')===false){
                       
                    if($post['leg']){
                        if(in_array($rows['Match_Name'],$legarr)) {
                            $leg=true;
                            //echo $rows['Match_Name'];
                        }
                    }else  {
                        //echo $rows['Match_Name'];
                        $leg=true;
                    }
                    if($leg){

                        $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
                        $rows["Match_DxDpl"]=$ior[0];
                        $rows["Match_DxXpl"]=$ior[1];
                        $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
                        $rows["Match_Ho"]=$ior[0];
                        $rows["Match_Ao"]=$ior[1];
                        $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
                        $rows["Match_BHo"]=$ior[0];
                        $rows["Match_BAo"]=$ior[1];
                        $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
                        $rows["Match_Bdpl"]=$ior[0];
                        $rows["Match_Bxpl"]=$ior[1];
                        $json["db"][$i]["Match_ID"]          =    $rows["Match_ID"];  
                        $json["db"][$i]["Match_Master"]      =    $rows["Match_Master"];  
                        $json["db"][$i]["Match_Guest"]       =    $rows["Match_Guest"];   
                        $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];    
                        $mdate    =    $rows["Match_Time"];
                        if ($rows["Match_IsLose"]==1){
                            $mdate.= "<br><font color=red>滾球</font>";
                        }
                        $json["db"][$i]["Match_Date"]        =    $mdate; 
                        
                    
                         
                        $rows["Match_BzM"]<>""?$a=$rows["Match_BzM"]:$a=0;
                        $json["db"][$i]["Match_BzM"]        =    $a;
                        $this->double_format($rows["Match_Ho"])<>""?$b=$this->double_format($rows["Match_Ho"]):$b=0;
                        $json["db"][$i]["Match_Ho"]            =    $b;    
                        $rows["Match_DxDpl"]<>""?$c=$rows["Match_DxDpl"]:$c=0;
                        $json["db"][$i]["Match_DxDpl"]        =    $c;   
                        $rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
                        $json["db"][$i]["Match_DsDpl"]        =    $d;    
                        $rows["Match_BzG"]<>""?$e=$rows["Match_BzG"]:$e=0;
                        $json["db"][$i]["Match_BzG"]        =    $e;   
                        $rows["Match_Ao"]<>""?$f=$rows["Match_Ao"]:$f=0;
                        $json["db"][$i]["Match_Ao"]            =    $f;   
                        $rows["Match_DxXpl"]<>""?$g=$rows["Match_DxXpl"]:$g=0;
                        $json["db"][$i]["Match_DxXpl"]        =    $g;   
                        $rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
                        $json["db"][$i]["Match_DsSpl"]        =    $h;    
                        $rows["Match_BzH"]<>""?$k=$rows["Match_BzH"]:$k=0;
                        $json["db"][$i]["Match_BzH"]        =    $k;     
                        $rows["Match_RGG"]<>""?$j=$rows["Match_RGG"]:$j=0;
                        $json["db"][$i]["Match_RGG"]        =    $j;   
                        $rows["Match_DxGG"]<>""?$m=$rows["Match_DxGG"]:$m=0;
                        $json["db"][$i]["Match_DxGG1"]        =    $m;    
                        $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
                        $rows["Match_DxGG"]<>""?$n=$rows["Match_DxGG"]:$n=0;
                        $json["db"][$i]["Match_DxGG2"]        =    $n; 
                        $match1=$rows["Match_BHo"]+$rows["Match_BAo"];
                        $match2=$rows["Match_Bdpl"]+$rows["Match_Bxpl"];//print_r($rows);exit;
                        $json["db"][$i]["Match_NowScore"]        =    $rows["Match_NowScore"];
                        $json["db"][$i]["Match_HRedCard"]        =    $rows["Match_HRedCard"];    
                        $json["db"][$i]["Match_GRedCard"]        =    $rows["Match_GRedCard"];    
                        
                   
                        if(($match1!=0||$match2!=0)&&$rows["Match_IsShowb"]==1){
                            $json["db"][$i]["Match_Bmdy"]        =    $rows["Match_Bmdy"];
                            $json["db"][$i]["Match_BHo"]         =    $rows["Match_BHo"];
                            $json["db"][$i]["Match_Bdpl"]        =    $rows["Match_Bdpl"]; 
                            $json["db"][$i]["Match_Bgdy"]        =    $rows["Match_Bgdy"]; 
                            $json["db"][$i]["Match_BAo"]         =    $rows["Match_BAo"];  
                            $json["db"][$i]["Match_Bxpl"]        =    $rows["Match_Bxpl"];  
                            $json["db"][$i]["Match_Bhdy"]        =    $rows["Match_Bhdy"];
                            $json["db"][$i]["Match_BRpk"]        =    $rows["Match_BRpk"];
                            $json["db"][$i]["Match_Bdxpk1"]        =    $rows["Match_Bdxpk"];
                            $json["db"][$i]["Match_Hr_ShowType"]   =    $rows["Match_Hr_ShowType"];
                            $json["db"][$i]["Match_Bdxpk2"]        =    $rows["Match_Bdxpk"];
                        }else{
                            $json["db"][$i]["Match_Bmdy"]        =    '0';
                            $json["db"][$i]["Match_BHo"]         =    '0';
                            $json["db"][$i]["Match_Bdpl"]        =    '0'; 
                            $json["db"][$i]["Match_Bgdy"]        =    '0'; 
                            $json["db"][$i]["Match_BAo"]         =    '0';  
                            $json["db"][$i]["Match_Bxpl"]        =    '0';  
                            $json["db"][$i]["Match_Bhdy"]        =    '0'; 
                            $json["db"][$i]["Match_BRpk"]        =    '';
                            $json["db"][$i]["Match_Bdxpk1"]      =    '';
                            $json["db"][$i]["Match_Hr_ShowType"] =    '';
                            $json["db"][$i]["Match_Bdxpk2"]      =    '';
                        }
                        $i++;
                    }
                    
                }
                
            }
        }else $json["db"]='';

         
        echo json_encode($json);
    }
    public function BasketballPlaying(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 
       
        $r['p']=$post['p'];
        $r['matchname']=$this->input->get('matchname');
        $d = $this->Match_model->BasketballPlaying($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
        $json["db"]='';
        ////
        $legarr=@explode('|',$post['leg']);

       /* $dd=file_get_contents('D:\Web\wwwroot_new\ciwebs_\t_a\cyj_web\models\sports\1.txt');

        //echo $dd;exit;
        $dd=json_decode($dd,true); 
        $json['legname']=$dd['legname'];
        //print_r($dd);
        $d['d']=$dd['db'];*/
        $i=0;
        ////
        if($d['d']){ 
            foreach($d['d'] as $ii=>$rows){
                $leg=false;
                //异常赔率判断
                if($post['leg']){
                        if(in_array($rows['Match_Name'],$legarr)) {
                            $leg=true;
                            //echo $rows['Match_Name'];
                        }
                    }else  {
                        //echo $rows['Match_Name'];
                        $leg=true;
                    }
                if($leg){
                    if($rows["Match_Ho"]>1.4 || $rows["Match_Ao"]>1.4){
                    $rows["Match_Ho"]=$rows["Match_Ao"]=0;
                    }
                    $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
                    $rows["Match_DxDpl"]=$ior[0];
                    $rows["Match_DxXpl"]=$ior[1];
                    $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
                    $rows["Match_Ho"]=$ior[0];
                    $rows["Match_Ao"]=$ior[1];
                    $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
                    $rows["Match_BHo"]=$ior[0];
                    $rows["Match_BAo"]=$ior[1];
                    $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
                    $rows["Match_Bdpl"]=$ior[0];
                    $rows["Match_Bxpl"]=$ior[1];
                    
                    
                    $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
                    $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
                    $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
                    $json["db"][$i]["Match_Name"]     = $rows["Match_Name"]; 
                    $json["db"][$i]["Match_BzM"]        =0;
                    $json["db"][$i]["Match_BzG"]        =0;
                    $json["db"][$i]["Match_BzH"]        =0;
                    $json["db"][$i]["Match_Date"]       =    $rows["Match_Time"];
                    $json["db"][$i]["Match_NowScore"]   =    $rows["Match_NowScore"];
                    $json["db"][$i]["Match_Ho"]         =    $rows["Match_Ho"];
                    $json["db"][$i]["Match_DxDpl"]      =    $rows["Match_DxDpl"];
                    $json["db"][$i]["Match_DsDpl"]      =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
                    $json["db"][$i]["Match_Ao"]         =    $rows["Match_Ao"];
                    $json["db"][$i]["Match_DxXpl"]      =    $rows["Match_DxXpl"];
                    $json["db"][$i]["Match_DsSpl"]      =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
                    $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
                    $json["db"][$i]["Match_DxGG1"]      =    $rows["Match_DxGG"];
                    $json["db"][$i]["Match_ShowType"]   =    $rows["Match_ShowType"];
                    $json["db"][$i]["Match_DxGG2"]      =    $rows["Match_DxGG"];
                    $i++; 
                }
                
             
            }
        }
        echo json_encode($json);
    }
        //足球总入球
    public function FootballZRQ(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        
         
        $d = $this->Match_model->FootballZRQ($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
        // print_r($d);die;
        foreach($d['d'] as $i=>$rows){
        $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];     ///////////  0
        $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];     ///////////   1
        $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];     ///////////    2
        $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];     ///////////     3
        $mdate    =    $rows["Match_Date"]."<br>".$rows["Match_Time"];
        if ($rows["Match_IsLose"]==1){
            $mdate.= "<br><font color=red>滾球</font>";
        }
        $json["db"][$i]["Match_Date"]        =    $mdate;     ///////////               4
        $json["db"][$i]["Match_BzM"]        =    $rows["Match_BzM"];     ///////////  5
        $json["db"][$i]["Match_Total01Pl"]    =    $rows["Match_Total01Pl"];     ///////////   6
        $json["db"][$i]["Match_Total23Pl"]    =    $rows["Match_Total23Pl"];     ///////////    7
        $json["db"][$i]["Match_Total46Pl"]    =    $rows["Match_Total46Pl"];     ///////////     8
        $json["db"][$i]["Match_Total7upPl"]    =    $rows["Match_Total7upPl"];     ///////////   9
        $json["db"][$i]["Match_BzG"]        =    $rows["Match_BzG"];     ///////////    10
        $json["db"][$i]["Match_BzH"]        =    $rows["Match_BzH"];     ///////////     11
        
        }
        echo json_encode($json);
        }
        //足球波胆
    public function FootballBD(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p']; 
        $r['leg']=trim($post['leg'],'|'); 
        $d = $this->Match_model->FootballBD($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        // print_r($d);die;
        foreach($d['d'] as $i=>$rows){
            
        $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];     ///////////  0
        $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];     ///////////   1
        $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];     ///////////    2
        $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];     ///////////     3
        $mdate    =    $rows["Match_Date"]."<br>".$rows["Match_Time"];
        if ($rows["Match_IsLose"]==1){
            $mdate.= "<br><font color=red>滾球</font>";
        }
        $json["db"][$i]["Match_Date"]        =    $mdate;     ///////////               4
        $json["db"][$i]["Match_Bd10"]        =    $rows["Match_Bd10"];     ///////////     5
        $json["db"][$i]["Match_Bd20"]        =    $rows["Match_Bd20"];     ///////////     6
        $json["db"][$i]["Match_Bd21"]        =    $rows["Match_Bd21"];     ///////////     7
        $json["db"][$i]["Match_Bd30"]        =    $rows["Match_Bd30"];     ///////////     8
        $json["db"][$i]["Match_Bd31"]        =    $rows["Match_Bd31"];     ///////////     9
        $json["db"][$i]["Match_Bd32"]        =    $rows["Match_Bd32"];     ///////////     10
        $json["db"][$i]["Match_Bd40"]        =    $rows["Match_Bd40"];     ///////////     11
        $json["db"][$i]["Match_Bd41"]        =    $rows["Match_Bd41"];     ///////////     12
        $json["db"][$i]["Match_Bd42"]        =    $rows["Match_Bd42"];     ///////////     13
        $json["db"][$i]["Match_Bd43"]        =    $rows["Match_Bd43"];     ///////////     14
        $json["db"][$i]["Match_Bd00"]        =    $rows["Match_Bd00"];     ///////////     15
        $json["db"][$i]["Match_Bd11"]        =    $rows["Match_Bd11"];     ///////////     16
        $json["db"][$i]["Match_Bd22"]        =    $rows["Match_Bd22"];     ///////////     17
        $json["db"][$i]["Match_Bd33"]        =    $rows["Match_Bd33"];     ///////////     18
        $json["db"][$i]["Match_Bd44"]        =    $rows["Match_Bd44"];     ///////////     19
        $json["db"][$i]["Match_Bdup5"]        =    $rows["Match_Bdup5"];     ///////////     20
        $json["db"][$i]["Match_Bdg10"]        =    $rows["Match_Bdg10"];     ///////////     21
        $json["db"][$i]["Match_Bdg20"]        =    $rows["Match_Bdg20"];     ///////////     22
        $json["db"][$i]["Match_Bdg21"]        =    $rows["Match_Bdg21"];     ///////////     23
        $json["db"][$i]["Match_Bdg30"]        =    $rows["Match_Bdg30"];     ///////////     24
        $json["db"][$i]["Match_Bdg31"]        =    $rows["Match_Bdg31"];     ///////////     25
        $json["db"][$i]["Match_Bdg32"]        =    $rows["Match_Bdg32"];     ///////////     26
        $json["db"][$i]["Match_Bdg40"]        =    $rows["Match_Bdg40"];     ///////////     27
        $json["db"][$i]["Match_Bdg41"]        =    $rows["Match_Bdg41"];     ///////////     28
        $json["db"][$i]["Match_Bdg42"]        =    $rows["Match_Bdg42"];     ///////////     29
        $json["db"][$i]["Match_Bdg43"]        =    $rows["Match_Bdg43"];     ///////////     30
        
         
        }
        echo json_encode($json);
        }
    public function Fbnews(){
            $post=$this->input->post('type');
            $d = $this->Match_model->Fbnews($r);
            if($post==2){
                $notice=array();
                $notice=$d;
            }else{
                $notice='';
                foreach($d as $k=>$v){
                    $notice.=$v['notice_content'].'<br>';
                }
            }
            echo json_encode($notice);
    }
    
    public function BasketballToday(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->BasketballToday($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            //异常赔率判断
                if($rows["Match_Ho"]>1.4 || $rows["Match_Ao"]>1.4){
                    $rows["Match_Ho"]=$rows["Match_Ao"]=$rows["Match_RGG"]=0;
                }
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
    public function BaseballToday(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->BaseballToday($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
    public function BaseballMorning(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->BaseballMorning($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
    public function BasketballGG(){
        $post=$this->input->post(['p','oddpk']);
        $this->load->library('sportsbet'); 
        $post['oddpk']='H';
        $r['p']=$post['p'];
        $r['matchname']=$this->input->get('matchname');
        $d = $this->Match_model->BasketballGG($r);
        $json['page']=$d['page'];
         
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }    
    public function VolleyballToday(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->VolleyballToday($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
        public function TennisToday(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->TennisToday($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
    public function FootballMorning(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']  =$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->FootballMorning($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
        
        foreach($d['d'] as $i=>$rows){
            
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];  
            $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];  
            $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];   
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];    
            $mdate    =    $rows["Match_Date"]."<br/>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_Date"]        =    $mdate; 
            
        
             
            $rows["Match_BzM"]<>""?$a=$rows["Match_BzM"]:$a=0;
            $json["db"][$i]["Match_BzM"]        =    $a;
            $this->double_format($rows["Match_Ho"])<>""?$b=$this->double_format($rows["Match_Ho"]):$b=0;
            $json["db"][$i]["Match_Ho"]            =    $b;    
            $rows["Match_DxDpl"]<>""?$c=$rows["Match_DxDpl"]:$c=0;
            $json["db"][$i]["Match_DxDpl"]        =    $c;   
            $rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
            $json["db"][$i]["Match_DsDpl"]        =    $d;    
            $rows["Match_BzG"]<>""?$e=$rows["Match_BzG"]:$e=0;
            $json["db"][$i]["Match_BzG"]        =    $e;   
            $rows["Match_Ao"]<>""?$f=$rows["Match_Ao"]:$f=0;
            $json["db"][$i]["Match_Ao"]            =    $f;   
            $rows["Match_DxXpl"]<>""?$g=$rows["Match_DxXpl"]:$g=0;
            $json["db"][$i]["Match_DxXpl"]        =    $g;   
            $rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
            $json["db"][$i]["Match_DsSpl"]        =    $h;    
            $rows["Match_BzH"]<>""?$k=$rows["Match_BzH"]:$k=0;
            $json["db"][$i]["Match_BzH"]        =    $k;     
            $rows["Match_RGG"]<>""?$j=$rows["Match_RGG"]:$j=0;
            $json["db"][$i]["Match_RGG"]        =    $j;   
            $rows["Match_DxGG"]<>""?$m=$rows["Match_DxGG"]:$m=0;
            $json["db"][$i]["Match_DxGG1"]        =    $m;    
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $rows["Match_DxGG"]<>""?$n=$rows["Match_DxGG"]:$n=0;
            $json["db"][$i]["Match_DxGG2"]        =    $n; 
            $match1=$rows["Match_BHo"]+$rows["Match_BAo"];
            $match2=$rows["Match_Bdpl"]+$rows["Match_Bxpl"];//print_r($rows);exit;
        if(($match1!=0||$match2!=0)&&$rows["Match_IsShowb"]==1){
            $json["db"][$i]["Match_Bmdy"]        =    $rows["Match_Bmdy"];
            $json["db"][$i]["Match_BHo"]         =    $rows["Match_BHo"];
            $json["db"][$i]["Match_Bdpl"]        =    $rows["Match_Bdpl"]; 
            $json["db"][$i]["Match_Bgdy"]        =    $rows["Match_Bgdy"]; 
            $json["db"][$i]["Match_BAo"]         =    $rows["Match_BAo"];  
            $json["db"][$i]["Match_Bxpl"]        =    $rows["Match_Bxpl"];  
            $json["db"][$i]["Match_Bhdy"]        =    $rows["Match_Bhdy"];
            $json["db"][$i]["Match_BRpk"]        =    $rows["Match_BRpk"];
            $json["db"][$i]["Match_Bdxpk1"]        =    $rows["Match_Bdxpk"];
            $json["db"][$i]["Match_Hr_ShowType"]   =    $rows["Match_Hr_ShowType"];
            $json["db"][$i]["Match_Bdxpk2"]        =    $rows["Match_Bdxpk"];
        }else{
            $json["db"][$i]["Match_Bmdy"]        =    '0';
            $json["db"][$i]["Match_BHo"]         =    '0';
            $json["db"][$i]["Match_Bdpl"]        =    '0'; 
            $json["db"][$i]["Match_Bgdy"]        =    '0'; 
            $json["db"][$i]["Match_BAo"]         =    '0';  
            $json["db"][$i]["Match_Bxpl"]        =    '0';  
            $json["db"][$i]["Match_Bhdy"]        =    '0'; 
            $json["db"][$i]["Match_BRpk"]        =    '';
            $json["db"][$i]["Match_Bdxpk1"]      =    '';
            $json["db"][$i]["Match_Hr_ShowType"] =    '';
            $json["db"][$i]["Match_Bdxpk2"]      =    '';
        }
         
        }
         
        echo json_encode($json);
    }
    
            //早餐-足球波胆
    public function FootballMBD(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p']; 
        $r['leg']=trim($post['leg'],'|'); 
        $d = $this->Match_model->FootballMBD($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        // print_r($d);die;
        foreach($d['d'] as $i=>$rows){
            
        $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];     ///////////  0
        $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];     ///////////   1
        $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];     ///////////    2
        $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];     ///////////     3
        $mdate    =    $rows["Match_Date"]."<br>".$rows["Match_Time"];
        if ($rows["Match_IsLose"]==1){
            $mdate.= "<br><font color=red>滾球</font>";
        }
        $json["db"][$i]["Match_Date"]        =    $mdate;     ///////////               4
        $json["db"][$i]["Match_Bd10"]        =    $rows["Match_Bd10"];     ///////////     5
        $json["db"][$i]["Match_Bd20"]        =    $rows["Match_Bd20"];     ///////////     6
        $json["db"][$i]["Match_Bd21"]        =    $rows["Match_Bd21"];     ///////////     7
        $json["db"][$i]["Match_Bd30"]        =    $rows["Match_Bd30"];     ///////////     8
        $json["db"][$i]["Match_Bd31"]        =    $rows["Match_Bd31"];     ///////////     9
        $json["db"][$i]["Match_Bd32"]        =    $rows["Match_Bd32"];     ///////////     10
        $json["db"][$i]["Match_Bd40"]        =    $rows["Match_Bd40"];     ///////////     11
        $json["db"][$i]["Match_Bd41"]        =    $rows["Match_Bd41"];     ///////////     12
        $json["db"][$i]["Match_Bd42"]        =    $rows["Match_Bd42"];     ///////////     13
        $json["db"][$i]["Match_Bd43"]        =    $rows["Match_Bd43"];     ///////////     14
        $json["db"][$i]["Match_Bd00"]        =    $rows["Match_Bd00"];     ///////////     15
        $json["db"][$i]["Match_Bd11"]        =    $rows["Match_Bd11"];     ///////////     16
        $json["db"][$i]["Match_Bd22"]        =    $rows["Match_Bd22"];     ///////////     17
        $json["db"][$i]["Match_Bd33"]        =    $rows["Match_Bd33"];     ///////////     18
        $json["db"][$i]["Match_Bd44"]        =    $rows["Match_Bd44"];     ///////////     19
        $json["db"][$i]["Match_Bdup5"]        =    $rows["Match_Bdup5"];     ///////////     20
        $json["db"][$i]["Match_Bdg10"]        =    $rows["Match_Bdg10"];     ///////////     21
        $json["db"][$i]["Match_Bdg20"]        =    $rows["Match_Bdg20"];     ///////////     22
        $json["db"][$i]["Match_Bdg21"]        =    $rows["Match_Bdg21"];     ///////////     23
        $json["db"][$i]["Match_Bdg30"]        =    $rows["Match_Bdg30"];     ///////////     24
        $json["db"][$i]["Match_Bdg31"]        =    $rows["Match_Bdg31"];     ///////////     25
        $json["db"][$i]["Match_Bdg32"]        =    $rows["Match_Bdg32"];     ///////////     26
        $json["db"][$i]["Match_Bdg40"]        =    $rows["Match_Bdg40"];     ///////////     27
        $json["db"][$i]["Match_Bdg41"]        =    $rows["Match_Bdg41"];     ///////////     28
        $json["db"][$i]["Match_Bdg42"]        =    $rows["Match_Bdg42"];     ///////////     29
        $json["db"][$i]["Match_Bdg43"]        =    $rows["Match_Bdg43"];     ///////////     30
        
         
        }
        echo json_encode($json);
        }
        //早餐-足球总入球
    public function FootballMZRQ(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        
         
        $d = $this->Match_model->FootballMZRQ($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
        // print_r($d);die;
        foreach($d['d'] as $i=>$rows){
        $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];     ///////////  0
        $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];     ///////////   1
        $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];     ///////////    2
        $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];     ///////////     3
        $mdate    =    $rows["Match_Date"]."<br>".$rows["Match_Time"];
        if ($rows["Match_IsLose"]==1){
            $mdate.= "<br><font color=red>滾球</font>";
        }
        $json["db"][$i]["Match_Date"]        =    $mdate;     ///////////               4
        $json["db"][$i]["Match_BzM"]        =    $rows["Match_BzM"];     ///////////  5
        $json["db"][$i]["Match_Total01Pl"]    =    $rows["Match_Total01Pl"];     ///////////   6
        $json["db"][$i]["Match_Total23Pl"]    =    $rows["Match_Total23Pl"];     ///////////    7
        $json["db"][$i]["Match_Total46Pl"]    =    $rows["Match_Total46Pl"];     ///////////     8
        $json["db"][$i]["Match_Total7upPl"]    =    $rows["Match_Total7upPl"];     ///////////   9
        $json["db"][$i]["Match_BzG"]        =    $rows["Match_BzG"];     ///////////    10
        $json["db"][$i]["Match_BzH"]        =    $rows["Match_BzH"];     ///////////     11
        
        }
        echo json_encode($json);
        }
        //早餐-足球综合过关
        public function FootballMGG(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 
        $post['oddpk']='H';
        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->FootballMGG($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname'];
         
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            $json["db"][$i]["Match_ID"]            =    $rows["Match_ID"];  
            $json["db"][$i]["Match_Master"]        =    $rows["Match_Master"];  
            $json["db"][$i]["Match_Guest"]        =    $rows["Match_Guest"];   
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"];    
            $mdate    =    $rows["Match_Date"]."<br/>".$rows["Match_Time"];
        if ($rows["Match_IsLose"]==1){
            $mdate.= "<br><font color=red>滾球</font>";
        }
            $json["db"][$i]["Match_Date"]        =    $mdate; 
             $rows["Match_BzM"]<>""?$a=$rows["Match_BzM"]:$a=0;
            $json["db"][$i]["Match_BzM"]        =    $a;
            $this->double_format($rows["Match_Ho"])<>""?$b=$this->double_format($rows["Match_Ho"]):$b=0;
            $json["db"][$i]["Match_Ho"]            =    $b;    
            $rows["Match_DxDpl"]<>""?$c=$rows["Match_DxDpl"]:$c=0;
            $json["db"][$i]["Match_DxDpl"]        =    $c;   
            $rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
            $json["db"][$i]["Match_DsDpl"]        =    $d;    
            $rows["Match_BzG"]<>""?$e=$rows["Match_BzG"]:$e=0;
            $json["db"][$i]["Match_BzG"]        =    $e;   
            $rows["Match_Ao"]<>""?$f=$rows["Match_Ao"]:$f=0;
            $json["db"][$i]["Match_Ao"]            =    $f;   
            $rows["Match_DxXpl"]<>""?$g=$rows["Match_DxXpl"]:$g=0;
            $json["db"][$i]["Match_DxXpl"]        =    $g;   
            $rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
            $json["db"][$i]["Match_DsSpl"]        =    $h;    
            $rows["Match_BzH"]<>""?$k=$rows["Match_BzH"]:$k=0;
            $json["db"][$i]["Match_BzH"]        =    $k;     
            $rows["Match_RGG"]<>""?$j=$rows["Match_RGG"]:$j=0;
            $json["db"][$i]["Match_RGG"]        =    $j;   
            $rows["Match_DxGG"]<>""?$m=$rows["Match_DxGG"]:$m=0;
            $json["db"][$i]["Match_DxGG1"]        =    $m;    
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $rows["Match_DxGG"]<>""?$n=$rows["Match_DxGG"]:$n=0;
            $json["db"][$i]["Match_DxGG2"]        =    $n; 
            $match1=$rows["Match_BHo"]+$rows["Match_BAo"];
            $match2=$rows["Match_Bdpl"]+$rows["Match_Bxpl"];//print_r($rows);exit;
        if(($match1!=0||$match2!=0)&&$rows["Match_IsShowb"]==1){
            $json["db"][$i]["Match_Bmdy"]        =    $rows["Match_Bmdy"];
            $json["db"][$i]["Match_BHo"]        =    $rows["Match_BHo"];
            $json["db"][$i]["Match_Bdpl"]        =    $rows["Match_Bdpl"]; 
            $json["db"][$i]["Match_Bgdy"]        =    $rows["Match_Bgdy"]; 
            $json["db"][$i]["Match_BAo"]        =    $rows["Match_BAo"];  
            $json["db"][$i]["Match_Bxpl"]        =    $rows["Match_Bxpl"];  
            $json["db"][$i]["Match_Bhdy"]        =    $rows["Match_Bhdy"];
            $json["db"][$i]["Match_BRpk"]        =    $rows["Match_BRpk"];
            $json["db"][$i]["Match_Bdxpk1"]        =    $rows["Match_Bdxpk"];
            $json["db"][$i]["Match_Hr_ShowType"]=    $rows["Match_Hr_ShowType"];
            $json["db"][$i]["Match_Bdxpk2"]        =    $rows["Match_Bdxpk"];
        }else{
            $json["db"][$i]["Match_Bmdy"]        =    '0';
            $json["db"][$i]["Match_BHo"]        =    '0';
            $json["db"][$i]["Match_Bdpl"]        =    '0'; 
            $json["db"][$i]["Match_Bgdy"]        =    '0'; 
            $json["db"][$i]["Match_BAo"]        =    '0';  
            $json["db"][$i]["Match_Bxpl"]        =    '0';  
            $json["db"][$i]["Match_Bhdy"]        =    '0'; 
            $json["db"][$i]["Match_BRpk"]        =    '';
            $json["db"][$i]["Match_Bdxpk1"]        =    '';
            $json["db"][$i]["Match_Hr_ShowType"]=    '';
            $json["db"][$i]["Match_Bdxpk2"]        =    '';
        }
         
        }
        echo json_encode($json);
    }
    //早餐-篮球，独赢等
    public function BasketballMorning(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->BasketballMorning($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
        //早餐-篮球综合过关
        public function BasketballMGG(){
        $post=$this->input->post(['p','oddpk']);
        $this->load->library('sportsbet'); 
        $post['oddpk']='H';
        $r['p']=$post['p'];
        $r['matchname']=$this->input->get('matchname');
        $d = $this->Match_model->BasketballMGG($r);
        $json['page']=$d['page'];
         
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
    //早餐-排球
    public function VolleyballMorning(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->VolleyballMorning($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
    //早餐-排球
    public function TennisMorning(){
        $post=$this->input->post(['p','oddpk','leg']);
        $this->load->library('sportsbet'); 

        $r['p']=$post['p'];
        $r['leg']=$post['leg'];
        $d = $this->Match_model->TennisMorning($r);
        $json['page']=$d['page'];
        $json['legname']=$d['legname']; 
        foreach($d['d'] as $i=>$rows){
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_DxDpl"],$rows["Match_DxXpl"],2);
            $rows["Match_DxDpl"]=$ior[0];
            $rows["Match_DxXpl"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Ho"],$rows["Match_Ao"],2);
            $rows["Match_Ho"]=$ior[0];
            $rows["Match_Ao"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_BHo"],$rows["Match_BAo"],2);
            $rows["Match_BHo"]=$ior[0];
            $rows["Match_BAo"]=$ior[1];
            $ior=$this->sportsbet->chg_ior($post['oddpk'],$rows["Match_Bdpl"],$rows["Match_Bxpl"],2);
            $rows["Match_Bdpl"]=$ior[0];
            $rows["Match_Bxpl"]=$ior[1];
            
            $json["db"][$i]["Match_ID"]     = $rows["Match_ID"];
            $json["db"][$i]["Match_Master"] = $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]     = $rows["Match_Guest"];
            $json["db"][$i]["Match_Name"]     = $rows["Match_Name"];
            $mdate = $rows["Match_Date"]."<br>".$rows["Match_Time"];
            if ($rows["Match_IsLose"]==1){
                $mdate.= "<br><font color=red>滾球</font>";
            }
            $json["db"][$i]["Match_BzM"]        =0;
            $json["db"][$i]["Match_BzG"]        =0;
            $json["db"][$i]["Match_BzH"]        =0;
            $json["db"][$i]["Match_Date"]        =    $mdate;
            $json["db"][$i]["Match_Ho"]            =    $rows["Match_Ho"];
            $json["db"][$i]["Match_DxDpl"]        =    $rows["Match_DxDpl"];
            $json["db"][$i]["Match_DsDpl"]        =    $rows["Match_DsDpl"]!=''?$rows["Match_DsDpl"]:0;
            $json["db"][$i]["Match_Ao"]            =    $rows["Match_Ao"];
            $json["db"][$i]["Match_DxXpl"]        =    $rows["Match_DxXpl"];
            $json["db"][$i]["Match_DsSpl"]        =    $rows["Match_DsSpl"]!=''?$rows["Match_DsSpl"]:0;
            $json["db"][$i]["Match_RGG"]        =    $rows["Match_RGG"];
            $json["db"][$i]["Match_DxGG1"]        =    $rows["Match_DxGG"];
            $json["db"][$i]["Match_ShowType"]    =    $rows["Match_ShowType"];
            $json["db"][$i]["Match_DxGG2"]        =    $rows["Match_DxGG"]; 
         
        }
        echo json_encode($json);
    }
    
    //足球-赛果
    public function FBRresults(){
        $time = intval($this->input->post("time"));
        $time=($time>7)?0:$time;
        $this->load->library('sportsbet'); 
        $d = $this->Match_model->FBRresults($time);
        
        foreach($d as $i=>$rows){
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"]; 
            $json["db"][$i]["Match_MatchTime"]   =    $rows["Match_MatchTime"]; 
            $json["db"][$i]["Match_Master"]      =    $rows["Match_Master"]; 
            $json["db"][$i]["Match_Guest"]       =    $rows["Match_Guest"]; 
            $json["db"][$i]["MB_Inball_HR"]      =    $rows["MB_Inball_HR"]; 
            $json["db"][$i]["MB_Inball"]         =    $rows["MB_Inball"]; 
            $json["db"][$i]["TG_Inball_HR"]      =    $rows["TG_Inball_HR"]; 
            $json["db"][$i]["TG_Inball"]         =    $rows["TG_Inball"];
        }
        echo json_encode($json);
    }
    //篮球-赛果
    public function BKRresults(){
        $time = intval($this->input->post("time"));
        $time=($time>7)?0:$time;
        $this->load->library('sportsbet'); 
        $d = $this->Match_model->BKRresults($time);
        
        foreach($d as $i=>$rows){
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"]; 
            $json["db"][$i]["Match_Date"]        =    $rows["Match_Date"];
            $json["db"][$i]["Match_Time"]        =    $rows["Match_Time"]; 
            $json["db"][$i]["Match_Master"]      =    $rows["Match_Master"];
            $json["db"][$i]["Match_Guest"]       =    $rows["Match_Guest"]; 
            $json["db"][$i]["MB_Inball_1st"]     =    $rows["MB_Inball_1st"] >= 0 ? $rows["MB_Inball_1st"]: '无效';
            $json["db"][$i]["MB_Inball_2st"]     =    $rows["MB_Inball_2st"] >= 0 ? $rows["MB_Inball_2st"]: '无效';
            $json["db"][$i]["MB_Inball_3st"]     =    $rows["MB_Inball_3st"] >= 0 ? $rows["MB_Inball_3st"]: '无效';
            $json["db"][$i]["MB_Inball_4st"]     =    $rows["MB_Inball_4st"] >= 0 ? $rows["MB_Inball_4st"]: '无效';
            $json["db"][$i]["MB_Inball_HR"]      =    $rows["MB_Inball_HR"]  >= 0 ? $rows["MB_Inball_HR"]: '无效'; 
            $json["db"][$i]["MB_Inball_ER"]      =    $rows["MB_Inball_ER"]  >= 0 ? $rows["MB_Inball_ER"]: '无效';
            $json["db"][$i]["MB_Inball_Add"]     =    $rows["MB_Inball_Add"] <  0 ? "无效":($rows["MB_Inball_Add"]>0 ? $rows["MB_Inball_Add"] : ''); 
            $json["db"][$i]["MB_Inball"]         =    $rows["MB_Inball"]     >= 0 ? $rows["MB_Inball"] : '无效';
            $json["db"][$i]["TG_Inball_1st"]     =    $rows["TG_Inball_1st"] >= 0 ? $rows["TG_Inball_1st"]: '无效';
            $json["db"][$i]["TG_Inball_2st"]     =    $rows["TG_Inball_2st"] >= 0 ? $rows["TG_Inball_2st"]: '无效';
            $json["db"][$i]["TG_Inball_3st"]     =    $rows["TG_Inball_3st"] >= 0 ? $rows["TG_Inball_3st"]: '无效';
            $json["db"][$i]["TG_Inball_4st"]     =    $rows["TG_Inball_4st"] >= 0 ? $rows["TG_Inball_4st"]: '无效';
            $json["db"][$i]["TG_Inball_HR"]      =    $rows["TG_Inball_HR"]  >= 0 ? $rows["TG_Inball_HR"]: '无效'; 
            $json["db"][$i]["TG_Inball_ER"]      =    $rows["TG_Inball_ER"]  >= 0 ? $rows["TG_Inball_ER"]: '无效';
            $json["db"][$i]["TG_Inball_Add"]     =    $rows["TG_Inball_Add"] <  0 ? "无效":($rows["TG_Inball_Add"]>0 ? $rows["TG_Inball_Add"] : ''); 
            $json["db"][$i]["TG_Inball"]         =    $rows["TG_Inball"]     >= 0 ? $rows["TG_Inball"] : '无效';
        }
        echo json_encode($json);
    }
    //网球-赛果
    public function TNRresults(){
        $time = intval($this->input->post("time"));
        $time=($time>7)?0:$time;
        $this->load->library('sportsbet'); 
        $d = $this->Match_model->TNRresults($time);
        
        foreach($d as $i=>$rows){
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"]; 
            $json["db"][$i]["Match_Date"]        =    $rows["Match_Date"]; 
            $json["db"][$i]["Match_Time"]        =    $rows["Match_Time"]; 
            $json["db"][$i]["Match_Master"]      =    $rows["Match_Master"]; 
            $json["db"][$i]["Match_Guest"]       =    $rows["Match_Guest"]; 
            $json["db"][$i]["MB_Inball"]         =    $rows["MB_Inball"]; 
            $json["db"][$i]["TG_Inball"]         =    $rows["TG_Inball"];
        }
        echo json_encode($json);
    }
    
    //排球-赛果
    public function VBRresults(){
        $time = intval($this->input->post("time"));
        $time=($time>7)?0:$time;
        $this->load->library('sportsbet'); 
        $d = $this->Match_model->VBRresults($time);
        
        foreach($d as $i=>$rows){
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"]; 
            $json["db"][$i]["Match_Date"]        =    $rows["Match_Date"]; 
            $json["db"][$i]["Match_Time"]        =    $rows["Match_Time"]; 
            $json["db"][$i]["Match_Master"]      =    $rows["Match_Master"]; 
            $json["db"][$i]["Match_Guest"]       =    $rows["Match_Guest"]; 
            $json["db"][$i]["MB_Inball"]         =    $rows["MB_Inball"]; 
            $json["db"][$i]["TG_Inball"]         =    $rows["TG_Inball"];
        }
        echo json_encode($json);
    }
    
    //棒球-赛果
    public function BBRresults(){
        $time = intval($this->input->post("time"));
        $time=($time>7)?0:$time;
        $this->load->library('sportsbet'); 
        $d = $this->Match_model->BBRresults($time);
        
        foreach($d as $i=>$rows){
            $json["db"][$i]["Match_Name"]        =    $rows["Match_Name"]; 
            $json["db"][$i]["Match_Date"]        =    $rows["Match_Date"]; 
            $json["db"][$i]["Match_Time"]        =    $rows["Match_Time"]; 
            $json["db"][$i]["Match_Master"]      =    $rows["Match_Master"]; 
            $json["db"][$i]["Match_Guest"]       =    $rows["Match_Guest"]; 
            $json["db"][$i]["MB_Inball"]         =    $rows["MB_Inball"]; 
            $json["db"][$i]["TG_Inball"]         =    $rows["TG_Inball"];
            $json["db"][$i]["MB_Inball_HR"]      =    $rows["MB_Inball_HR"]; 
            $json["db"][$i]["TG_Inball_HR"]      =    $rows["TG_Inball_HR"];
        }
        echo json_encode($json);
    }
    //网站信息
    public function JsonCopyRight(){
        $d = $this->Match_model->JsonCopyRight();
        $data['CopyRight']='PkBet';
        if($d){
            $data['CopyRight']=$d['copy_right'];
            $data['WebName']=$d['web_name'];
        }
        echo json_encode($data);
    } 
}
