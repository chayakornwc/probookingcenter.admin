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
	$wsfile		= '/select_grosssale.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
                                'date_from'         => Y_m_d($_REQUEST['date_from']),
                                'date_to'           => Y_m_d($_REQUEST['date_to']),
								'country_id'     => $_REQUEST['country_id'],
								'ser_id'     => $_REQUEST['ser_id'],
								'sort'     => $_REQUEST['sort'],
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	if($data_return['status'] == 200){
      $result = $data_return['results'];
      

	}
        $objPHPExcel = PHPExcel_IOFactory::load('template/report_grosssale.xlsx');
  
    # SET FILE TEMPLATE
	$objPHPExcel->setActiveSheetIndex();

    $country_name = '';
    $ser_code = '';
    if ($_REQUEST['country_id'] != ''){
        $country_name = 'ประเทศ : '.$result[0]['country_name'] ;
    }else {
       $country_name = 'ประเทศ : ทั้งหมด';
    }
    if ($_REQUEST['ser_id'] != ''){
        $ser_code = 'ซี่รี่ทัวร์ : '.$result[0]['ser_code'] ;
    }else {
       $ser_code = 'ซี่รี่ทัวร์ : ทั้งหมด';
    }
           $objPHPExcel->setActiveSheetIndex()->setCellValue('A2','วันที่ : '.thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to']))));
           $objPHPExcel->setActiveSheetIndex()->setCellValue('C3',$country_name.' '.$ser_code);

    $row = 6;
    $no = 1;
    $sumsale = 0;
    $sumcom = 0;
    $sumtotal = 0;
    $sumcost = 0;
    $sumgross = 0;

    $sumtotal_sale = 0;
    $sumtotal_com = 0;
    $sumtotal_total = 0;
    $sumtotal_cost = 0;
    $sumtotal_gross = 0;
    
    $row_room = 8;
    $row_no = 8;
     for($i = 0 ; $i< count($result);$i++){
        if ($i == 0){
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C4',$result[$i]['country_name']);
            }else{
                if ($result[$i]['country_name'] != $result[$i - 1]['country_name']){
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,'รวม');
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,number_format(intval($sumsale),2));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumcom),2));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format(intval($sumtotal),2));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumcost),2));
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$row,number_format(intval($sumgross),2));
                    $sumsale = 0;
                    $sumcom = 0;
                    $sumtotal = 0;
                    $sumcost = 0;
                    $sumgross = 0;
                    $row++;
                    $objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$row.':E'.$row);
                    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,$result[$i]['country_name']);
                    $row++;
                    $no = 1;
                }
            }
                $period = date('d',strtotime($result[$i]['per_date_start'])).'-'.thai_date_short(strtotime($result[$i]['per_date_end']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row,$result[$i]['ser_code']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,$period);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,intval($result[$i]['per_qty_seats']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,intval($result[$i]['qty_book']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,text_status_period_report($result[$i]['status']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,number_format(intval($result[$i]['amounttotal']),2));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($result[$i]['amountcom']),2));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format($result[$i]['amountgrandtotal'],2));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format($result[$i]['amountcost'],2));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$row,number_format($result[$i]['grosstotal'],2));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$row,number_format($result[$i]['percent'],2));
                
				$sumsale = $sumsale + intval($result[$i]['amounttotal']);	
				$sumcom = $sumcom + intval($result[$i]['amountcom']);	
				$sumtotal = $sumtotal + intval($result[$i]['amountgrandtotal']);	
				$sumcost = $sumcost + intval($result[$i]['amountcost']);	
				$sumgross = $sumgross + intval($result[$i]['grosstotal']);	
                
        	
				$sumtotal_sale = $sumtotal_sale + intval($result[$i]['amounttotal']);	
				$sumtotal_com = $sumtotal_com + intval($result[$i]['amountcom']);	
				$sumtotal_total = $sumtotal_total + intval($result[$i]['amountgrandtotal']);	
				$sumtotal_cost = $sumtotal_cost + intval($result[$i]['amountcost']);	
				$sumtotal_gross = $sumtotal_gross + intval($result[$i]['grosstotal']);	
		
        $row++;
        $no++;
    }
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,'รวม');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,number_format(intval($sumsale),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumcom),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format(intval($sumtotal),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumcost),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$row,number_format(intval($sumgross),2));
    $row++;
    

    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,'รวมทั้งสิ้น : ');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,number_format(intval($sumtotal_sale),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumtotal_com),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format(intval($sumtotal_total),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumtotal_cost),2));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$row,number_format(intval($sumtotal_gross),2));


    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/report_grosssale_'.thai_date_short(strtotime($result[0]['per_date_start']))
    .' - '.thai_date_short(strtotime($result[0]['per_date_end'])).'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>