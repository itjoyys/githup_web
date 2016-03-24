<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Go extends MY_Controller {
    public function __construct() {
        parent::__construct();
         
    }
    public function index(){
       // echo 'uid='.$_SESSION['uid'].'&token='.$_SESSION['token'];
        echo json_encode(['uid'=>$_SESSION['uid'],'token'=>$_SESSION['token']]);
    }
}