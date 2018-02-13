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
	$wsfile		= '/select_booking_sort_country.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'datefrom'       => $_REQUEST['datefrom'],
								'dateto'     => $_REQUEST['dateto'],
								'countryfrom'     => $_REQUEST['countryfrom'],
								'countryto'     => $_REQUEST['countryto'],
								'serfrom'     => $_REQUEST['serfrom'],
								'serto'     => $_REQUEST['serto'],
								'userfrom'     => $_REQUEST['userfrom'],
								'userto'     => $_REQUEST['userto'],
								'agenfrom'     => $_REQUEST['agenfrom'],
								'agento'     => $_REQUEST['agento'],
                                
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	if($data_return['status'] == 200){
	  $result = $data_return['results'];
	 

	}
    
    # SET FILE TEMPLATE
    $objPHPExcel = PHPExcel_IOFactory::load('template/report_booking_sort_country.xlsx');
	$objPHPExcel->setActiveSheetIndex();



    $row = 5;
	$no = 1;
	$sumtotal = 0;
    for($i = 0 ; $i< count($result);$i++){
		if ($i > 0){
			if ($result[$i]['country_id'] != $result[$i-1]['country_id']){
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,'ประเทศ : '.$result[$i]['country_name']);
				$row++;
				$no = 1;

			}
		}else{
				$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,'ประเทศ : '.$result[$i]['country_name']);
				$row++;
		}
				$perioddate = d_m_y($result[$i]['per_date_start']) .' - '.d_m_y($result[$i]['per_date_end']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row,$no);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,$result[$i]['book_code']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,d_m_y($result[$i]['book_date']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,$result[$i]['agenname']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,$result[$i]['username']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,$result[$i]['ser_code']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,$perioddate);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,intval($result[$i]['QTY']));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,intval($result[$i]['book_amountgrandtotal']));
		
				$sumtotal = $sumtotal + intval($result[$i]['book_amountgrandtotal']);	
				if ($i > 0){
					if ($result[$i]['country_id'] != $result[$i-1]['country_id']){
					$row++;
        			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,'รวม : ');
        			$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,intval($sumtotal));
					$sumtotal = 0;
					}
					else if ($result[$i]['country_id'] != $result[$i+1]['country_id']){
					$row++;
        			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,'รวม : ');
        			$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,intval($sumtotal));
					$sumtotal = 0;

					}
				}	
        $row++;
        $no++;
    }




    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/report_booking_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>