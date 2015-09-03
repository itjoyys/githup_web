<?php
   
    Class ModulesAction extends CommonAction{
          //功能列表视图
      Public function index(){

         import('Class.Modules', APP_PATH);
         
         $modules= M('modules') ->order('sort ASC')-> select();
         $this->modules = Modules::unlimitedForLevel($modules); 
         $this->display(); // 输出模板
      
        
      }

          //添加功能视图
         
          Public function addmodules(){            
          
              $this->pid= I('pid', 0 , 'intval');
              $this-> display();
          }

          //添加分类表单处理

          Public function runAddmodules(){
         
           
             if (M('modules') -> add($_POST)){

               $this->success('添加成功' , U(GROUP_NAME. '/Modules/index'));

             }else{
              $this -> error('添加失败');
             }
          	
          }


          Public function sortCate(){
            $db =M('cate');
            foreach ($_POST as $id => $sort) {
              $db->where(array('id' => $id))->setField('sort' ,$sort);
            }
              $this ->redirect(GROUP_NAME . '/Category/index');
          }



    }



?>
 