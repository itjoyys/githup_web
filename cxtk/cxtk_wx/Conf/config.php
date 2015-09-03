<?php
return array(
	//数据库配置信息
        'DB_TYPE'   => 'mysql', // 数据库类型
        'DB_HOST'   => '127.0.0.1', // 服务器地址
        'DB_NAME'   => 'cxtk_wx', // 数据库名
        'DB_USER'   => 'root', // 用户名
        'DB_PWD'    => 'zqchyj2014', // 密码
        'DB_PORT'   => 3306, // 端口
        'DB_PREFIX' => 'wx_', // 数据库表前缀 

        'APP_GROUP_LIST' =>'Index,Admin',
        'DEFAULT_GROUP'=>'Index',
        'APP_GROUP_MODE'=>'1',
        'APP_GROUP_PATH'=>'Modules',
        'APP_STATUS' => 'debug', //应用调试模式状态

        'TMPL_l_DELIM'=>'<{',
        'TMPL_R_DELIM'=>'}>',
        'TMPL_CACHE_ON' => false, //缓存关闭

        'URL_ROUTER_ON'=>true,//路由定义
        'URL_MODEL'=>1,//使用PATHINFO模式
           // 'URL_ROUTE_RULES'=> array(
           //           '/^c_(\d+)$/'=>'Index/List/Index?id=:1', //规则路由
           //       )

         'image_url'=> 'http://localhost:8077/weipan',//图片地址附加
         'url_url'=> 'http://localhost:8077/weipan/index.php/Index/',//生成url地址附加
    
);
?>