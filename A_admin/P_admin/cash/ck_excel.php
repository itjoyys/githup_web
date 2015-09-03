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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);    
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);    
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);    
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10); 

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', '層級');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', '代理商');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', '會員帳號');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', '出款狀況');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', '提出額度');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', '手續費');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', '優惠金額');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', '行政費');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', '出款金額');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', '账户余额');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', '優惠扣除');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', '出款日期');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', '已出款');
$objPHPExcel->getActiveSheet()->SetCellValue('O1', '操作者');
$objPHPExcel->getActiveSheet()->SetCellValue('P1', '備註');

//从数据取值循环输出 
if(isset($_POST['uid'])){
	$count = count($_POST['uid']);
	for ($i=0;$i<$count;$i++){
		$n = $i+2;
		$total_sx +=$_POST['sx_money'][$i];
		$total_yh +=$_POST['yh_money'][$i];
		$total_xz +=$_POST['xz_money'][$i];
		$total_ck +=$_POST['ck_money'][$i];
		
		$objPHPExcel->getActiveSheet()->SetCellValue( 'A'.$n , $_POST['uid'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'B'.$n , $_POST['user_leve'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'C'.$n , $_POST['agent_user'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'D'.$n , $_POST['username'][$i] );
		if($_POST['ifpay'][$i] == 1){
			$v = "首次出款";
		}
		$objPHPExcel->getActiveSheet()->SetCellValue( 'E'.$n , $v );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'F'.$n , $_POST['getmoney'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'G'.$n , $_POST['sx_money'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'H'.$n , $_POST['yh_money'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'I'.$n , $_POST['xz_money'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'J'.$n , $_POST['ck_money'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'K'.$n , $_POST['zhye_monye'][$i] );
		if($_POST['yhkc_money'][$i] == 1){
			$v1 = "是";
		}else{
			$v1 = "否";
		}
		$objPHPExcel->getActiveSheet()->SetCellValue( 'L'.$n , $v1 );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'M'.$n , $_POST['ck_date'][$i] );
		if($_POST['yck'][$i] == 4){
			$v2 = "未处理";
		}else if($_POST['yck'][$i] == 0){
			$v2 = "未处理";
		}else{
			$v2 = "已出款";
		}
		$objPHPExcel->getActiveSheet()->SetCellValue( 'N'.$n , $v2 );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'O'.$n , $_POST['makeuser'][$i] );
		$objPHPExcel->getActiveSheet()->SetCellValue( 'P'.$n , $_POST['bz'][$i] );
	}
	
	//合并单元格
	$num = $count+2;
	$box = 'A'.$num.':P'.$num;
	$objPHPExcel->getActiveSheet()->mergeCells($box);
	//总计合算
	$text = "总计：笔数：".$count." 手续费：".$total_sx." 優惠金額：".$total_yh." 行政费:".$total_xz." 总出款金額：".$total_ck;
	$objPHPExcel->getActiveSheet()->SetCellValue( 'A'.$num , $text );

	// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Csat');
 
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