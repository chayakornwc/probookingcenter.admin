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
	$wsfile		= '/select_com_sale.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'date_from'         => Y_m_d($_REQUEST['date_from']),
								'date_to'           => Y_m_d($_REQUEST['date_to']),
								'user_id'       => $_REQUEST['user_id'],
                                
								'method'            => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	if($data_return['status'] == 200){
	  $result = $data_return['results'];
	 

	}
    
    # SET FILE TEMPLATE
    $objPHPExcel = PHPExcel_IOFactory::load('template/report_com_sale.xlsx');
	$objPHPExcel->setActiveSheetIndex();
	$styleArray_red = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => 'FF0000'),
			'size'  => 10,
			'name'  => 'Tahoma'
		));
	$styleArray_black = array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
				'name'  => 'Tahoma'
	));
    $user_fname = '';
    $user_lname = '';
    if ($_REQUEST['user_id'] != ''){
             $user_fname = $result[0]['user_fname'] ;
             $user_lname = $result[0]['user_lname'] ;
    }
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A2','วันที่ : '.thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to']))));
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A3','ชื่อพนักงาน : '.$user_fname.' '.$user_lname);
    
				
    $row = 6;
	$no = 1;
	$sumtotal_pax = 0;
	$sumpax = 0;
	$sumqty = 0;
	$x = 0;
    for($i = 0 ; $i< count($result);$i++){
		$pax = 0;
				if ($i == 0){
				$objPHPExcel->setActiveSheetIndex()->setCellValue('D4',$result[$i]['country_name']);
				$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleArray_black);
				}else{
					if ($result[$i]['country_name'] != $result[$i - 1]['country_name']){
						$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,'รวม');
						$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumqty)));
						$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumpax)));
						$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($styleArray_black);
						$sumtotal_pax = $sumtotal_pax + $sumpax;
						$sumqty = 0;
						$sumpax = 0;
						$row++;
						$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->applyFromArray($styleArray_black);
						$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,$result[$i]['country_name']);
						$row++;
						$no = 1;
					}
				}
				$period = date('d',strtotime($result[$i]['per_date_start'])).'-'.thai_date_short(strtotime($result[$i]['per_date_end']));
				if($result[$i]['Situation'] == 'Com'){
					$pax = number_format(intval($result[$i]['QTY']));
				}else{
					$pax = 0;
					$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($styleArray_red);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->applyFromArray($styleArray_red);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($styleArray_red);
					
				}
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row,$no);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,$result[$i]['ser_code']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,$period);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,$result[$i]['agen_com_name']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($result[$i]['book_receipt'])));
				$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,$result[$i]['invoice_code']);
        		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($result[$i]['QTY'])));
				$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,$result[$i]['Situation']);
				$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,$pax);
                
				$sumqty = $sumqty + intval($result[$i]['QTY']);	
				$sumpax = $sumpax + $pax;	

        $row++;
		$no++;
		

    }
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,'รวม');
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($sumqty)));
		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumpax)));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($styleArray_black);
		$sumtotal_pax = $sumtotal_pax + $sumpax;
        $row++;
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,'รวมทั้งหมด');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,number_format(intval($sumtotal_pax)));
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($styleArray_black);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->applyFromArray($styleArray_black);


        $row++;
		$row++;
		$qty1_200 = 0;
		$qty201_400 = 0;
		$qty401 = 0;
		$total1_200 = 0;
		$total201_400 = 0;
		$total401 = 0;

		if (intval($sumtotal_pax) > 0) {
			if (intval($sumtotal_pax) > 400) {
				$qty1_200 = 200;
				$total1_200 = 200*20;
				$qty201_400 = 400;
				$total201_400 = 200*40;
				$qty401 = $sumtotal_pax - 400;
				$total401 = $qty401 * 50;
			}else if(intval($sumtotal_pax) > 200){
				$qty1_200 = 200;
				$total1_200 = 200*20;
				$qty201_400 = $sumtotal_pax - 200;
				$total201_400 = $qty201_400*40;
			}else if(intval($sumtotal_pax) <= 200){
				$qty1_200 = $sumtotal_pax;
				$total1_200 = $sumtotal_pax*20;
			}				
		}
		if (intval($sumtotal_pax) <= 200) {
			$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,'1-200 Pax (com20/Pax)');
			$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($qty1_200)));
			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($total1_200)));
			$row++;
		}else if (intval($sumtotal_pax) > 200){
			$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,'1-200 Pax (com20/Pax)');
			$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($qty1_200)));
			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($total1_200)));
			$row++;
			$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,'201-400 Pax (com40/Pax)');
			$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($qty201_400)));
			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($total201_400)));
			$row++;
		}else if (intval($sumtotal_pax) > 400){
			$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,'1-200 Pax (com20/Pax)');
			$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($qty1_200)));
			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($total1_200)));
			$row++;
			$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,'201-400 Pax (com40/Pax)');
			$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($qty201_400)));
			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($total201_400)));
			$row++;
			$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,'401+ Pax (com50/Pax)');
			$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($qty401)));
			$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($total401)));
			$row++;
		}
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,number_format(intval($sumtotal_pax)));
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,number_format(intval($total1_200 + $total201_400 + $total401)));
		$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->applyFromArray($styleArray_black);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->applyFromArray($styleArray_black);
    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/report_com_sale_'.thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to']))).'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>