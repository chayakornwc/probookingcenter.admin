<?php
include_once('../unity/post_to_ws/post_to_ws.php');
include_once '../unity/PHPExcel-1.8/Classes/PHPExcel.php';
include_once('../unity/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');

    #ws
    $list = array();

    for ( $i = 1 ; $i < 100; $i++){
        
        $list[] = array($i,'Code'.$i,'Description'.$i,'Unit_price'.$i,'Unit'.$i,'Qty'.$i,'Total'.$i);
    }




    # SET FILE TEMPLATE
    $objPHPExcel = PHPExcel_IOFactory::load('template/excel_test.xlsx');
	$objPHPExcel->setActiveSheetIndex();


    

    #
    $row = 2;
    
    for($i = 0; $i< count($list); $i++){
        
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row,$list[$i][0]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row,$list[$i][1]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$row,$list[$i][2]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,$list[$i][3]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$row,$list[$i][4]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,$list[$i][5]);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,$list[$i][6]);

    
        $row++;
    }




    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/report_'.substr(uniqid(),0,5).'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>