<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
//域名管理
class Server_domain extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('other/Server_domain_model');
	}

    //网站域名
    public function site_domain(){
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $is_host = $this->input->get('is_host');
        $domain = $this->input->get('domain');
        $page = $this->input->get('page');
        $page = empty($page)?1:$page;

        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['index_id'] = $index_id;
        if ($domain) {
            $map['domain'] = array('like','%'.$domain.'%');
        }

        //获取总数
        $count = $this->Server_domain_model->get_site_domain($map,'');

        $perNumber = 10;
        $totalPage=ceil($count/$perNumber);
        $page=isset($page)?$page:1;
        if($totalPage<$page){
          $page = 1;
        }
        $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
        $limit=$startCount.",".$perNumber;

        $sdata = $this->Server_domain_model->get_site_domain($map,$limit);

               //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.$this->Server_domain_model->select_sites());
        }
        //判断站点域名是否已满
        $domainnum = $this->Server_domain_model->get_site_domainnums($index_id);
        if ($count < $domainnum) {
            $this->add('is_domain',1);
        }
        $this->add('site_id',$_SESSION['site_id']);
        $this->add('sdata',$sdata);
        $this->add('index_id',$index_id);
        $this->add('page', $this->Server_domain_model->get_page('history_login',$totalPage,$page));

        $this->display('other/site_domain.html');
    }

    //支付域名
    public function pay_domain(){
        $domain = $this->input->get('domain');
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['index_id'] = $index_id;
        if ($domain) {
            $map['domain'] = array('like','%'.$domain.'%');
        }

                //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.$this->Server_domain_model->select_sites());
        }

        $sdata = $this->Server_domain_model->get_pay_domain($map);
        $this->add('sdata',$sdata);
        $this->add('index_id',$index_id);
        $this->display('other/pay_domain.html');
    }

    //域名删除
    public function site_domain_del(){
        $id = intval($this->input->get('id'));
        $type = intval($this->input->get('type'));
        $db_model = array();
        $db_model['type'] = 4;
        if ($type == 1) {
            $db_model['tab'] = 'server_domain';
            $url = URL.'/other/Server_domain/site_domain';
        }else{
            $db_model['tab'] = 'server_pay_domain';
            $url = URL.'/other/Server_domain/pay_domain';
        }
        if (!$id) {showmessage('参数错误',$url,0);}

        $log = $this->Server_domain_model->M($db_model)->where(array('id'=>$id,'site_id'=>$_SESSION['site_id']))->delete();

        if ($log) {
            showmessage('操作成功',$url);
        }else{
            showmessage('操作失败',$url,0);
        }
    }

     //域名添加
    public function site_domain_addo(){
        $id = intval($this->input->post('id'));
        $type = intval($this->input->post('type'));
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $arr = array();
        $arr['domain'] = trim($this->input->post('domain'));
        $db_model = array();
        $db_model['type'] = 4;
        if ($type == 1) {
            $db_model['tab'] = 'server_domain';
            $url = URL.'/other/Server_domain/site_domain?index_id='.$index_id;
        }else{
            $db_model['tab'] = 'server_pay_domain';
            $url = URL.'/other/Server_domain/pay_domain?index_id='.$index_id;
        }
        if (empty($arr['domain'])) {
            showmessage('请完善表单',$url,0);
        }

        if ($id) {
            //编辑
            $log = $this->Server_domain_model->M($db_model)->where(array('id'=>$id,'site_id'=>$_SESSION['site_id']))->update($arr);
        }else{
            $arr['site_id'] = $_SESSION['site_id'];
            $arr['index_id'] = $index_id;
            $arr['add_date'] = date('Y-m-d H:i:s');
            //获取cname值
            $cname = $this->Server_domain_model->M($db_model)->field("cname")->where(array('site_id'=>$_SESSION['site_id'],'state'=>1,'index_id'=>$index_id))->limit(1)->find();
            $arr['cname'] = $cname['cname'];
            if (empty($arr['cname'])) {
                showmessage('请先添加一个已经绑定的域名',$url,0);
            }
            $log = $this->Server_domain_model->M($db_model)->add($arr);
        }

        if ($log) {
            showmessage('操作成功',$url);
        }else{
            showmessage('操作失败',$url,0);
        }
    }


}