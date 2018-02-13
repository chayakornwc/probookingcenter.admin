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
    $wsfolder   = '/report'; //กำหนด Folder
    $wsfile     = '/select_invoice.php'; //กำหนด File
    $url        = $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data       = array(    
                                'date_from'         => Y_m_d($_REQUEST['date_from']),
                                'date_to'           => Y_m_d($_REQUEST['date_to']),
                                
                                'method'            => 'GET'
                            );


    $data_return =  json_decode( post_to_ws($url,$data),true );

    if($data_return['status'] == 200){
      $result = $data_return['results'];
     

    }

    $dateStr = thai_date_short(strtotime(Y_m_d($_REQUEST['date_from'])));
    if( $_REQUEST["date_from"] != $_REQUEST["date_to"] ){
        $dateStr = thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to'])));
    }
    
    # SET FILE TEMPLATE
    $objPHPExcel = PHPExcel_IOFactory::load('template/report_invoice.xlsx');
    $objPHPExcel->setActiveSheetIndex();
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A2','วันที่ : '.$dateStr);
    // $objPHPExcel->setActiveSheetIndex()->setCellValue('C3','ชื่อสมุดบัญชี : '.$bankname);
    

    $row = 5;
    $no = 1;
    $sumtotal = 0;
    for($i = 0 ; $i< count($result);$i++){
                $period = d_m_y($result[$i]['per_date_start']).'-'.d_m_y($result[$i]['per_date_end']);
                $sale = $result[$i]['user_fname'].' '.$result[$i]['user_lname'];
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row,$no);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,$result[$i]['invoice_code']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,$result[$i]['ser_code']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,$period);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,$result[$i]['agen_com_name']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,$sale);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,$result[$i]['qty']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format($result[$i]['book_total']));
                // $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,intval($result[$i]['pay_received']));
                
                $sumtotal = $sumtotal + intval($result[$i]['book_total']);  
        
        $row++;
        $no++;
    }
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,'รวม : ');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,number_format($sumtotal));



    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/report_invoice_'.thai_date_short(strtotime(Y_m_d($_REQUEST['date_from']))). ' - ' . thai_date_short(strtotime(Y_m_d($_REQUEST['date_to']))).'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>