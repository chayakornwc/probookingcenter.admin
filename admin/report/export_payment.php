<?php
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once '../unity/PHPExcel-1.8/Classes/PHPExcel.php';
include_once('../unity/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');
include_once('../unity/php_script.php');



	#set var
	$result = array();

 	#WS
	$wsserver   = URL_WS;
	$wsfolder	= '/report'; //กำหนด Folder
	$wsfile		= '/select_payment.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'date_from'         => Y_m_d($_REQUEST['date_from']),
								'date_to'           => Y_m_d($_REQUEST['date_to']),
								'bankbook_id'       => $_REQUEST['bankbook_id'],
                                
								'method'            => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	if($data_return['status'] == 200){
	  $result = $data_return['results'];
	 

	}
    
    # SET FILE TEMPLATE
    $objPHPExcel = PHPExcel_IOFactory::load('template/report_payment.xlsx');
	$objPHPExcel->setActiveSheetIndex();
    $bankname = '';
    if ($_REQUEST['bankbook_id'] != ''){
             $bankname = $result[0]['bankbook_name'] ;
    }else {
            $bankname = 'ทั้งหมด';
    }
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A2','วันที่ : '.thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to']))));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('C3','ชื่อสมุดบัญชี : '.$bankname);
    

    $row = 5;
	$no = 1;
	$sumtotal = 0;
    for($i = 0 ; $i< count($result);$i++){
                $period = $result[$i]['ser_code'].' '.$result[$i]['per_date_start'].'-'.d_m_y($result[$i]['per_date_end']).' '.$result[$i]['qty'].'P';
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row,$no);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,d_m_y($result[$i]['pay_date']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,$result[$i]['pay_time']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,$result[$i]['book_code']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,$period);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,$result[$i]['agen_com_name']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,$result[$i]['bankbook_name']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,text_status_book_report($result[$i]['book_status']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,intval($result[$i]['pay_received']));
                
				$sumtotal = $sumtotal + intval($result[$i]['pay_received']);	
		
        $row++;
        $no++;
    }
	    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,'รวม : ');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,intval($sumtotal));



    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/report_payment_'.thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to']))).'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>