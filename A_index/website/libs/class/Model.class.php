<?php

/**
 * @mebar update 2015-03-19 17:54:00
 * 支持commit
 *
 * $mysql_session = M("a_test", $db_config);
 *
 * $mysql_session->begin();
 * $data = array(
 * "name" => "1111",
 * "time"=>date("Y-m-d H:i:s")
 * );
 * try{
 * $mysql_session->setTable("a_test")->add($data);
 * }catch (Exception $e) {
 * $mysql_session->rollback();
 * }
 * $mysql_session->commit();
 *
 */
Class Model {

    /**
     * 数据库操作类
     * 模糊查询  $condition = "uid like ".'"%'.$account.'%"';
     */
    Public $join;
    Public $field;
    Public $tab; //当前表名
    Public $where;
    Public $order;
    Public $limit;
    Public $group;
    //当前操作的数据库连接
    var $link = null;
    //读写数据库连接
    var $link_rw = null;
    //只读数据库连接 read_only
    var $link_ro = null;
    var $ro_exist = false;
    //当前数据库
    var $cur_db;
    //是否开启事物
    var $autocommit = false;

    //构造函数
    public function __construct($tab, $db_rw) {
        //初始化数据连接
        if (!empty($db_rw)) {
            //连接主数据库
            $this->connect($db_rw['host'], $db_rw['user'], $db_rw['pass'], $db_rw['dbname']);

            //设置一系列只读数据库并且连接其中一个
            $db_ro = $db_rw['db_ro'];
            if (is_array($db_ro)) {
                print_r($db_ro);
                //随机选择其中一个
                $link_ro = $db_ro[array_rand($db_ro)];
                
                print_r($link_ro);
                $this->connect_ro($link_ro['dbhost'], $link_ro['dbuser'], $link_ro['dbpw']);
            }
            //选择表
            $this->tab = $tab;
        }
    }

    function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE) {
        if ($pconnect) {
            if (!$this->link = @mysql_pconnect($dbhost, $dbuser, $dbpw)) {
                $halt && $this->halt('Can not connect to MySQL server');
            }
        } else {
            if (!$this->link = @mysql_connect($dbhost, $dbuser, $dbpw)) {
                $halt && $this->halt('Can not connect to MySQL server');
            }
        }

        //只读连接失败
        if (!$this->link && !$halt) {
            return false;
        }

        //未初始化rw时，第一个连接作为rw
        if ($this->link_rw == null) {
            $this->link_rw = $this->link;
        }

        mysql_query("set names utf8", $this->link);

        if ($dbname) {
            $this->select_db($dbname);
        }
    }

    //连接一个只读的mysql数据库
    function connect_ro($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0) {
        if ($this->link_rw == null) {
            $this->link_rw = $this->link;
        }
        $this->link = null;
        $this->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, false);
        if ($this->link) {
            $this->ro_exist = true;
            $this->link_ro = $this->link;
            if ($this->cur_db) {
                //如果已经选择过数据库则需要操作一次
                mysql_select_db($this->cur_db, $this->link_ro);
            }
        } else {
            //连接失败
            $this->link = &$this->link_rw;
        }
    }

    //选择数据库
    function select_db($dbname) {
        //同时操作两个数据库连接
        $this->cur_db = $dbname;
        if ($this->ro_exist) {
            @mysql_select_db($dbname, $this->link_ro);
        }
        return @mysql_select_db($dbname, $this->link_rw);
    }
    //选择数据库连接
    function select_link($param = "w") {
        $this->link = &$this->link_rw;
        //判断是否select语句
        if ($this->ro_exist && "select" == $param) {
            $this->link = &$this->link_ro;
        }
    }

    function begin() {
        $this->select_link();
        $this->autocommit = true;
        mysql_query("SET AUTOCOMMIT=0", $this->link);
    }

    function rollback() {
        $this->select_link();
        mysql_query("ROLLBACK", $this->link);
        mysql_query("SET AUTOCOMMIT=1", $this->link);
        $this->autocommit = false;
    }

    function commit() {
        $this->select_link();
        mysql_query("COMMIT", $this->link);
        mysql_query("SET AUTOCOMMIT=1", $this->link);
        $this->autocommit = false;
    }

    function field($field) {
        $this->field = $field;
        return $this;
    }

    function where($where) {
        if (is_array($where)) {
            $this->where = 'where ' . $this->condition_check($where);
        } else {
            if (!empty($where)) {
                $this->where = 'where ' . $where;
            }
        }
        return $this;
    }

    function setTable($name = "") {
        $this->tab = $name;
        return $this;
    }

    function order($order) {
        $this->order = 'order by ' . $order;
        return $this;
    }

    function group($group) {
        $this->group = 'group by ' . $group;
        return $this;
    }

    function limit($limit) {
        $this->limit = 'limit ' . $limit;
        return $this;
    }

    /**
     * 指定查询数量
     * @access public
     * @param mixed $offset 起始位置
     * @param mixed $length 查询数量
     * @return Model
     */
    // public function limit($offset,$length=null){
    //     $this->options['limit'] =   is_null($length)?$offset:$offset.','.$length;
    //     return $this;
    // }
    //查询
    function select($key_name = '') {

        if (isset($this->field)) {
            $sql = "select {$this->field} from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit} {$this->group}";
        } else {
            $sql = "select * from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit} {$this->group}";
        }
        //  return $sql;
        // echo $sql;echo "</br>";
        $this->clearAtrr();
        $this->select_link("select");
        $result = mysql_query($sql, $this->link);
        if ($result == false && $this->autocommit) {
            throw new mysqlException(mysql_error($this->link));
        }
        $rows = array();
        while ($row = mysql_fetch_assoc($result)) {
            if (!empty($key_name)) {
                $rows[$row[$key_name]] = $row;
            } else {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    //查单条
    function find() {
        if (isset($this->field)) {
            $sql = "select {$this->field} from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit}";
        } else {
            $sql = "select * from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit}";
        }
        // return $sql;
        $this->clearAtrr();
        $this->select_link("select");
        $result = mysql_query($sql, $this->link);
        if ($result == false && $this->autocommit) {
            throw new mysqlException(mysql_error($this->link));
        }
        if (!empty($result)) {
            while ($row = mysql_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }

        return $rows[0];
    }

    //查询总数
    function count() {
        if (isset($this->field)) {
            $sql = "select count({$this->field}) from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit}";
        } else {
            $sql = "select count(*) from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit}";
        }
        // return $sql;
        $this->clearAtrr();
        $this->select_link("select");
        $result = mysql_query($sql, $this->link);
        if ($result == false && $this->autocommit) {
            throw new mysqlException(mysql_error($this->link));
        }
        while ($row = mysql_fetch_array($result)) {
            $rows = $row;
        }

        return $rows[0];
    }

    //插入
    function add($data) {
        is_array($data) ? null : $data = array();
        foreach ($data as $key => $val) {
            $keys[] = "`" . $key . "`";
            $vals[] = "'" . $val . "'";
        }
        $keystr = join(",", $keys);
        $valstr = join(",", $vals);
        $sql = "insert into {$this->tab}($keystr) values($valstr)";
        $this->clearAtrr();
        $this->select_link();
        $result = mysql_query($sql, $this->link);
        if ($result == false && $this->autocommit) {
            throw new mysqlException(mysql_error($this->link));
        } else {
            return mysql_insert_id();
        }
        return false;
    }
    /**
     * 设置记录的某个字段值
     * 支持使用数据库字段和方法
     * @access public
     * @param string|array $field  字段名
     * @param string $value  字段值
     * @return boolean
     */
    public function setField($field,$value='') {
        if(is_array($field)) {
            $data           =   $field;
        }else{
            $data[$field]   =   $value;
        }
        return $this->update($data);
    }
    

    //更新
    function update($data = array()) {
        is_array($data) ? null : $data = array();
        foreach ($data as $key => $val) {
            $sets[] = "{$key}='{$val}'";
        }

        $setstr = join(",", $sets);
        $sql = "update {$this->tab} set $setstr {$this->where}";
        $this->clearAtrr();
        $this->select_link();
        $result = mysql_query($sql, $this->link);
        if ($result == false && $this->autocommit) {
            throw new mysqlException(mysql_error($this->link));
        } else {
            return mysql_affected_rows();
        }
        return false;
    }

    //删除
    function delete() {
        $sql = "delete from {$this->tab} {$this->where}";
        $this->clearAtrr();
        $this->select_link();
        $result = mysql_query($sql, $this->link);
        if ($result == false && $this->autocommit) {
            throw new mysqlException(mysql_error($this->link));
        } else {
            return $sql;
        }
        return false;
    }

    //计算总和
    function sum($sum) {
        //判断是否同时计算多个类别
        if (is_array($sum)) {
            $j = count($sum);
            foreach ($sum as $key => $val) {
                if ($key == $j - 1) {
                    $sum_str .= "Sum($val)";
                } else {
                    $sum_str .= "Sum($val)" . ',';
                }
            }
            $sql = "select $sum_str from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit}";
        } else {
            $sql = "select sum($sum) from {$this->tab} {$this->join} {$this->where} {$this->order} {$this->limit}";
        }
        $this->clearAtrr();
        $this->select_link("select");
        $result = mysql_query($sql, $this->link);
        if ($result == false && $this->autocommit) {
            throw new mysqlException(mysql_error($this->link));
        }
        while ($row = mysql_fetch_array($result)) {
            $rows = $row;
        }
        return $rows;
    }

    //条件分析
    /*     * *
      EQ  等于（=）
      GT  大于（>）
      EGT 大于等于（>=）
      LT  小于（<）
      ELT 小于等于（<=）
      LIKE  模糊查询
      [NOT] BETWEEN （不在）区间查询
      [NOT] IN  （不在）IN 查询
     * */
    function condition_check($arr) {
        $count = count($arr) - 1;
        $i = 0;
        if ($arr['_logic'] == 'or') {
            foreach ($arr as $key => $val) {
                if ($i == $count) {
                    $where .= "$key = $val";
                } else {
                    $where .= "$key = $val or ";
                }
                $i++;
            }
        } else {
            foreach ($arr as $key => $val) {
                if ($i == $count) {
                    if (is_array($val)) {
                        if (is_array($val[0])) {
                            foreach ($val as $key_v => $val_v) {
                                if (is_array($val_v)) {
                                    if ($key_v == count($val) - 1) {
                                        $where .= "$key $val_v[0] '$val_v[1]'";
                                    } else {
                                        $where .= "$key $val_v[0] '$val_v[1]' and ";
                                    }
                                } else {
                                    if ($val[0] == 'in') {
                                        $where .= "$key $val[0] $val[1]";
                                    } else {
                                        $where .= "$key $val[0] '$val[1]'";
                                    }
                                }
                            }
                        } else {
                            if ($val[0] == 'in') {
                                $where .= "$key $val[0] $val[1]";
                            } else {
                                $where .= "$key $val[0] '$val[1]'";
                            }
                        }
                    } else {
                        $where .= "$key = '$val'";
                    }
                } else {
                    if (is_array($val)) {
                        if (is_array($val[0])) {
                            foreach ($val as $key_v => $val_v) {
                                if (is_array($val_v)) {
                                    $where .= "$key $val_v[0] '$val_v[1]' and ";
                                } else {
                                    $where .= "$key $val[0] '$val[1]'";
                                }
                            }
                        } else {
                            if ($val[0] == 'in') {
                                $where .= "$key $val[0] $val[1] and ";
                            } else {
                                $where .= "$key $val[0] '$val[1]' and ";
                            }
                        }
                    } else {
                        $where .= "$key = '$val' and ";
                    }
                }
                $i++;
            }
        }
        return $where;
    }

    //join方法联表
    /*     * *
      join('left join k_user_login on k_user.uid = k_user_login.uid left join k_user_games on k_user.uid = k_user_games.uid')
     * */
    public function join($join) {
        if (!empty($join)) {
            $this->join = $join;
        }
        return $this;
    }

    function clearAtrr() {
        unset($this->field);
        unset($this->join);
        unset($this->where);
        unset($this->order);
    }

    function close() {
        unset($this->field);
        unset($this->join);
        unset($this->where);
        unset($this->order);
        mysql_close($this->link_rw);
        mysql_close($this->link_ro);
        unset($this);
    }

    //输出页面样式
    public function showPage($totalPage, $page) {
        $strPage = "頁數：<select id=\"page\" name=\"page\" class=\"za_select\"> ";
        for ($i = 1; $i <= $totalPage; $i++) {
            //循环显示出页面
            $strPage .= '<option value="' . $i . '"';
            if ($page == $i) {
                $strPage .= ' selected';
            }
            $strPage .= '>' . $i . '</option>';
        }
        $strPage .= '</select> ' . $totalPage . ' 頁';
        return $strPage;
    }

    function error() {
        return (($this->link) ? mysql_error($this->link) : mysql_error());
    }

    function errno() {
        return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
    }

    function halt($message = '', $sql = '') {
        $dberror = $this->error();
        $dberrno = $this->errno();
        echo "<div style=\"position:absolute;font-size:11px;font-family:verdana,arial;background:#EBEBEB;padding:0.5em;\">
        <b>MySQL Error</b><br>
        <b>Message</b>: $message<br>
        <b>SQL</b>: $sql<br>
        <b>Error</b>: $dberror<br>
        <b>Errno.</b>: $dberrno<br>
        </div>";
        exit();
    }

}

class mysqlException extends Exception {

    public function errorMessage() {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
                . ':' . $this->getMessage();
        return $errorMsg;
    }

}

//M方法
function M($tab, $db_config) {
    return new Model($tab, $db_config);
}
?>

