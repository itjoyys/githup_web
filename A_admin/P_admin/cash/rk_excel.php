<?php
header("content-type:text/html;charset=utf-8");
/** PHPExcel */
include_once '../common/PHPExcel.php';

/** PHPExcel_Writer_Excel2003用于创建xls文件 */
include_once '../common/PHPExcel/Writer/Excel5.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
 
// Set properties
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


//设置单元格宽度
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);    
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(90);    
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(90);    
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(90);    
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(70);    

$objPHPExcel->getActiveSheet()->SetCellValue('A1', '层级');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', '订单号');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', '代理商');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', '會員帳號');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', '會員銀行帳戶');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', '存入金額');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', '存入銀行帳戶');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', '狀態');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', '是否首存');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', '操縱者');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', '時間');

//从数据取值循环输出 
if(isset($_POST['intoStyle'])){
	$count = count($_POST['level_des']);
	for ($i=0;$i<$count;$i++){
		$n = $i+2;
		$total_crje +=$_POST['crje'][$i];
		$total_ckyh +=$_POST['ckyh'][$i];
		$total_qtyh +=$_POST['qtyh'][$i];
		$total_crzje +=$_POST['crzje'][$i];
		
		$objPHPExcel->getActiveSheet()->SetCellValue( 'A'.$n , $_POST['level_des'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'B'.$n , $_POST['order_num'][$i].' ' );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'C'.$n , $_POST['agent_user'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'D'.$n , $_POST['username'][$i] );
		if ($_POST['intoStyle'][$i] == 1) { 
			if ($_POST['in_type'][$i] ==2 || $_POST['in_type'][$i] == 3) {
				$atm_address = $_POST['atm_address'][$i];
			}
			$tex ="銀行：".$_POST['bank_style'][$i]."    存款人：".$_POST['in_name'][$i]."    存款時間：".$_POST['in_date'][$i]."    方式：".$_POST['ck_type'][$i]."    ".$atm_address;
		}
		$objPHPExcel->getActiveSheet()->SetCellValue( 'E'.$n , $tex );
		$tex1 = "存入金額：".$_POST['crje'][$i]."    存款優惠：".$_POST['ckyh'][$i]."    其他優惠：".$_POST['qtyh'][$i]."    存入總金額：".$_POST['crzje'][$i];
		$objPHPExcel->getActiveSheet()->SetCellValue( 'F'.$n , $tex1 );
		if ($_POST['intoStyle'][$i] == 1) {
			$tex2 = "銀行帳號：".$_POST['card_ID'][$i]."    銀行：".$_POST['bank_type'][$i]."    卡主姓名：".$_POST['card_userName'][$i];
		}else{
			$tex2 = "";
		}
		$objPHPExcel->getActiveSheet()->SetCellValue( 'G'.$n , $tex2 );
		if($_POST['make_sure'][$i] == 0){
			$tex3 = "取消";
		}else if($_POST['make_sure'][$i] == 1){
			$tex3 = "已确认";
		}
		$objPHPExcel->getActiveSheet()->SetCellValue( 'H'.$n , $tex3 );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'I'.$n , "否" );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'J'.$n , $_POST['admin_user'][$i] );
		if($_POST['intoStyle'][$i] ==1){
			$tex4 = "系統時間：".$_POST['log_time'][$i]."(美东)    操作時間：".$_POST['do_time'][$i]."(美东)";
		}else{
			$tex4 = "系統時間：".$_POST['log_time'][$i]."(北京)    操作時間：".$_POST['do_time'][$i]."(北京)";
		}
		$objPHPExcel->getActiveSheet()->SetCellValue( 'K'.$n , $tex4 );
	}
	
	//合并单元格
	$num = $count+2;
	$box = 'A'.$num.':K'.$num;
	$objPHPExcel->getActiveSheet()->mergeCells($box);
	//总计合算
	$text = "总计：笔数：".$count." 存入金額：".$total_crje." 存款優惠：".$total_ckyh." 其他優惠:".$total_qtyh." 存入總金額：".$total_crzje;
	$objPHPExcel->getActiveSheet()->SetCellValue( 'A'.$num , $text );

	// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle($_POST['tableTitel']);
 
   
// Save Excel 2007 file
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
 $filename = $_POST['tableTitel'];
 $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
 $find = array('.php','cash');
 $replace = array('.xls','excel');
 $objWriter->save(str_replace($find, $replace, __FILE__));
 header("Pragma: public");
 header("Expires: 0");
 header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
 header("Content-Type:application/force-download");
 header("Content-Type:application/vnd.ms-execl");
 header("Content-Type:application/octet-stream");
 header("Content-Type:application/download");
 header("Content-Disposition:attachment;filename=$filename.xls");
 header("Content-Transfer-Encoding:binary");
 $objWriter->save("php://output");
}else{
	echo "当前没有数据!!";
	
}


?>