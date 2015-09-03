<?php

set_include_path(get_include_path()
        . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\class'
        . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\cache'
        . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\include'
        . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\\'
);

class user {

    static function login($username, $passwrod, $login_url, $login_ip) { //登陆，进行验证，和信息更新,产生UID
        global $mysqlt;
        $sql = "select uid,is_daili,is_delete,reg_date,shiwan from k_user where username='$username' and password='" . md5($passwrod) . "' and site_id='" . SITEID . "' limit 1"; //print_r($sql);exit;
        $query = $mysqlt->query($sql);
        $t = $query->fetch_array();
        if ($t) {
            if ($t["is_delete"] == 1 || $t["is_delete"] == 2) {
                // echo '3';
                echo "<script>alert('账号异常！请联系管理员解决！');</script>";
                echo "<script>window.location.href='/'</script>";
                exit;
            }

            if ($t["is_stop"] == 1) {
                // echo '3';
                echo "<script>alert('账号异常！请联系管理员解决！');</script>";
                echo "<script>window.location.href='/'</script>";
                exit;
            }


            /* if(strtotime("2010-06-14 12:00:00")<=strtotime($t["reg_date"])){ //在 2010-06-14 12:00:00 之后注册的用户，要验证所属IP地址，中国境内，不给登陆
              include_once("ip.php");
              include_once("city.php");
              $ClientSity = iconv("GB2312","UTF-8",convertip($_SERVER["REMOTE_ADDR"]));   //取出客户端IP所在城市
              $bool		= false;
              foreach($city as $k=>$v){
              if(count(explode($v,$ClientSity))-1){
              $bool = true;
              break;
              }
              }

              if($bool){
              $db1 -> message("尊敬的客户，由于您所在的国家或地区的法律限制，我公司不能为您提供服务，谢谢您的支持！");
              exit;
              }
              } */

            $mysqlt->query("update k_user set login_time=now(),login_ip='" . $_SERVER['REMOTE_ADDR'] . "',lognum=lognum+1 where username='$username' and site_id='" . SITEID . "'");
            include_once("ip.php");

            include_once("include/private_config.php");
            $conf_www = $_SERVER['HTTP_HOST'];
            //$ClientSity = @iconv("GB2312", "UTF-8", convertip($_SERVER["REMOTE_ADDR"]));   //取出客户端IP所在城市
			$ClientSity = ipsetarea($_SERVER["REMOTE_ADDR"]);               //获取客户端IP所在的国家，省，市
            $ClientSity = $ClientSity.'('.$_SERVER["REMOTE_ADDR"].')';
            $time = time();
            $date = date('Y-m-d H:i:s');
            $rs = $mysqlt->query("select `uid`,`login_id` from `k_user_login` where uid=" . $t["uid"] . " limit 1");
            if ($row = $rs->fetch_array()) {
                //有登录的情况 强制退出上一次登录
                $arr = explode('_', $row["login_id"]);
                if (!empty($arr[0])) {
                    //取消上一个session
                    session_id($arr[0]);
                    session_regenerate_id(true);
                    $newSession = session_id();
                    session_write_close();
                    session_id($newSession);
                    session_start();
                }
                unset($arr);
                $user_login_id = session_id() . '_' . $t["uid"] . '_' . $username;
                $sql = "update `k_user_login` set `login_id`='$user_login_id',`login_time`='$date',`is_login`=1,www='$conf_www',ip = '$ClientSity' WHERE `uid`='" . $t["uid"] . "'";

                $mysqlt->query($sql);
                unset($user_login_id);
            } else {
                $user_login_id = session_id() . '_' . $t["uid"] . '_' . $username;
                $mysqlt->query("insert into `k_user_login` (`login_id`,`uid`,`login_time`,`is_login`,www,ip) VALUES ('$user_login_id','" . $t["uid"] . "','$date',1,'$conf_www','$ClientSity')");
                unset($user_login_id);
            }
           
            $mysqlt->query("insert into `history_login` (`uid`,`username`,`ip`,`ip_address`,`login_time`,`www`,`site_id`) VALUES (" . $t["uid"] . ",'$username','" . $_SERVER['REMOTE_ADDR'] . "','" . $ClientSity . "',now(),'$conf_www','" . SITEID . "')");

            $_SESSION["uid"] = $t["uid"];
            $_SESSION["is_daili"] = $t["is_daili"];
            //$_SESSION["gid"]			=	$t["gid"]; //所属权限组
            $_SESSION["username"] = $username;
            $_SESSION["denlu"] = "one";
            $_SESSION['user_login_id'] = session_id() . '_' . $t["uid"] . '_' . $username;
            if ($t["shiwan"] == 1) {
                $_SESSION["shiwan"] = 2;
            }

            return $t["uid"];
        } else {
            return false;
        }
    }

    static function islogin() { //验证是否登录
        return isset($_SESSION["uid"], $_SESSION["username"]);
    }

    static function getinfo($uid) {
        $uid = intval($uid);
        if ($uid < 1) {
            return 0;
        } else {
            global $mysqlt;
            $query = $mysqlt->query("select * from k_user u left join k_user_games g on u.uid=g.uid where u.uid='$uid' limit 1");
            $t1 = $query->fetch_array();

            return $t1;
        }
    }

    static function getusername($uid) {
        $uid = intval($uid);
        if ($uid < 1) {
            return false;
        } else {
            global $mysqlt;
            $query = $mysqlt->query("select username from k_user where uid='$uid' limit 1");
            $t = $query->fetch_array();

            return $t["username"];
        }
    }

    static function update_pwd($uid, $oldpwd, $newpwd, $type = 'password') {
        $uid = intval($uid);
        global $mysqlt;
        $sql = "update k_user set " . $type . "='" . md5($newpwd) . "' where uid='$uid' and " . $type . "='" . md5($oldpwd) . "'";
        $mysqlt->autocommit(FALSE);
        $mysqlt->query("BEGIN"); //事务开始
        try {
            $mysqlt->query($sql);
            $q1 = $mysqlt->affected_rows;
            if ($q1 == 1) {
                $mysqlt->commit(); //事务提交
                return true;
            } else {
                $mysqlt->rollback(); //数据回滚
                return false;
            }
        } catch (Exception $e) {
            $mysqlt->rollback(); //数据回滚
            return false;
        }
    }

    static function update_paycard($uid, $pay_card, $pay_num, $pay_address, $pay_name, $username) {
        $uid = intval($uid);
        global $mysqlt;
        $sql = "update k_user set pay_card='$pay_card',pay_num='$pay_num',pay_address='$pay_address' where uid='$uid'";
        $sql1 = "insert into history_bank (uid,username,pay_card,pay_num,pay_address,pay_name) values (" . $uid . ",'$username','$pay_card','$pay_num','$pay_address','$pay_name')";
        $mysqlt->autocommit(FALSE);
        $mysqlt->query("BEGIN"); //事务开始
        try {
            $mysqlt->query($sql);
            $q1 = $mysqlt->affected_rows;
            $mysqlt->query($sql1);
            $q2 = $mysqlt->affected_rows;
            if ($q1 == 1 && $q2 == 1) {
                $mysqlt->commit(); //事务提交
                return true;
            } else {
                $mysqlt->rollback(); //数据回滚
                return false;
            }
        } catch (Exception $e) {
            $mysqlt->rollback(); //数据回滚
            return false;
        }
    }

    static function msg_add($uid, $from, $title, $info) {
        $uid = intval($uid);
        global $mysqlt;
        $sql = "insert into k_user_msg(uid,msg_from,msg_title,msg_info,site_id) values ('$uid','$from','$title','$info','" . SITEID . "')";
        $mysqlt->autocommit(FALSE);
        $mysqlt->query("BEGIN"); //事务开始
        try {
            $mysqlt->query($sql);
            $q1 = $mysqlt->affected_rows;
            if ($q1 == 1) {
                $mysqlt->commit(); //事务提交
                return true;
            } else {
                $mysqlt->rollback(); //数据回滚
                return false;
            }
        } catch (Exception $e) {
            $mysqlt->rollback(); //数据回滚
            return false;
        }
    }

    static function is_daili($uid) {
        $uid = intval($uid);
        global $mysqlt;
        $query = $mysqlt->query("select is_daili from k_user where uid='$uid' and is_daili=1 and site_id='" . SITEID . "' limit 1");
        //echo "select is_daili from k_user where uid=$uid and is_daili=1 limit 1";exit;
        $t = $query->fetch_array();
        if ($t['is_daili'] == 1) {
            setcookie("is_daili", $uid, time() + 8 * 3600);
            return true;
        } else {
            setcookie("is_daili", "", time() - 3600, "/");
            return false;
        }
    }

}

?>