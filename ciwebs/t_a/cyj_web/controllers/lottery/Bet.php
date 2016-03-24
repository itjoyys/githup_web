<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bet extends MY_Controller
{

  public function __construct()
  {
      parent::__construct();
      //$this->load->model('Login_model');
      $config_css = $this->config->item('css');
      $this->add('config_css',$config_css);
  }

  public function index(){
    $type = $this->input->get('type');
    $this->load->view($type.'.html');
  }

  public function bet(){
    echo "string";
    $post = $this->input->post();
    $get = $this->input->get();
    p($post);
    p($get);
  }


}
