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
	$wsfile		= '/select_grosssale_sale.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
                                'date_from'         => Y_m_d($_REQUEST['date_from']),
                                'date_to'           => Y_m_d($_REQUEST['date_to']),
								'user_id'     => $_REQUEST['user_id'],
								'sort'     => $_REQUEST['sort'],
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	if($data_return['status'] == 200){
      $result = $data_return['results'];
      

	}
        $objPHPExcel = PHPExcel_IOFactory::load('template/report_grosssale_sale.xlsx');
  
    # SET FILE TEMPLATE
	$objPHPExcel->setActiveSheetIndex();

    $com_name = '';
    $agentname = '';
    if ($_REQUEST['user_id'] != ''){
        $com_name = 'ชื่อพนักงาน : '.$result[0]['username'] ;
    }else {
       $com_name = 'ชื่อพนักงาน : ทั้งหมด';
    }
  
           $objPHPExcel->setActiveSheetIndex()->setCellValue('A2','วันที่ : '.thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to']))));
           $objPHPExcel->setActiveSheetIndex()->setCellValue('C3',$com_name.' '.$agentname);

    $row = 6;
    $no = 1;
    $sumsale = 0;
    $sumcom = 0;
    $sumtotal = 0;

    $sumtotal_sale = 0;
    $sumtotal_com = 0;
    $sumtotal_total = 0;
    
    $row_room = 8;
    $row_no = 8;
     for($i = 0 ; $i< count($result);$i++){
        if ($i == 0){
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C4',$result[$i]['username']);
            }else{
                if ($result[$i]['username'] != $result[$i - 1]['username']){
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,'รวม');
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumsale),2));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format(intval($sumcom),2));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumtotal),2));
                    $sumsale = 0;
                    $sumcom = 0;
                    $sumtotal = 0;
                    $row++;
                  //  $objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$row.':E'.$row);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,$result[$i]['username']);
                    $row++;
                    $no = 1;
                }
            }
                $period = date('d',strtotime($result[$i]['per_date_start'])).'-'.thai_date_short(strtotime($result[$i]['per_date_end']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row,$result[$i]['ser_code']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,$period);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,$result[$i]['ser_name']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,intval($result[$i]['per_qty_seats']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,intval($result[$i]['qty_book']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,text_status_period_report($result[$i]['status']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($result[$i]['amounttotal']),2));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format(intval($result[$i]['amountcom']),2));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format($result[$i]['amountgrandtotal'],2));
                
				$sumsale = $sumsale + intval($result[$i]['amounttotal']);	
				$sumcom = $sumcom + intval($result[$i]['amountcom']);	
				$sumtotal = $sumtotal + intval($result[$i]['amountgrandtotal']);	
                
        	
				$sumtotal_sale = $sumtotal_sale + intval($result[$i]['amounttotal']);	
				$sumtotal_com = $sumtotal_com + intval($result[$i]['amountcom']);	
				$sumtotal_total = $sumtotal_total + intval($result[$i]['amountgrandtotal']);	
		
        $row++;
        $no++;
    }
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,'รวม');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumsale),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format(intval($sumcom),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumtotal),2));
    $row++;
    

    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,'รวมทั้งสิ้น : ');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumtotal_sale),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format(intval($sumtotal_com),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumtotal_total),2));


    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/report_grosssale_sale_'.thai_date_short(strtotime($result[0]['per_date_start']))
    .' - '.thai_date_short(strtotime($result[0]['per_date_end'])).'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>