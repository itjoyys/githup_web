<?php
include_once ("../../include/config.php");
include_once ("../common/login_check.php");
include ("../../include/public_config.php");
include ("../../lib/class/model.class.php");
$value = $_GET['value'];
$caid = $_GET['caid'];
$name = $_GET['name'];
$type = $_GET['type'];
if (isset($value) && isset($caid) && isset($name) && isset($type)) {
    if (strpos($type, 'ball') !== false) {
        $data = M("c_odds_" . $name, $db_config)->where('type="' . $type . '"')->setField($caid, $value);
    } else {
        if ($type == 'array') {
            $array = explode(',', $caid);
            $data = M("c_odds_" . $name, $db_config)->where('type="' . $array[0] . '"')->setField($array[1], $value);
        } else {
            $data = M("c_odds_" . $name, $db_config)->where('type="' . $caid . '"')->setField($type, $value);
        }
    }
    if ($data > 0) {
        echo "yes";
    } else {
        echo 'no';
    }
}