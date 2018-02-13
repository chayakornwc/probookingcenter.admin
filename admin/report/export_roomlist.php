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
	$wsfolder	= '/manage_period'; //กำหนด Folder
	$wsfile		= '/select_list_roomlist.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'per_id'       => $_REQUEST['per_id'],
								'bus_no'     => $_REQUEST['bus_no'],
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	if($data_return['status'] == 200){
	  $result = $data_return['results']['period'];
	 

	}
    switch ($result[0]['country_id']) {
    case '1': // japan
        $objPHPExcel = PHPExcel_IOFactory::load('template/report_roomlist_japan.xlsx');
        break;
    case '2': // myanmar
        $objPHPExcel = PHPExcel_IOFactory::load('template/report_roomlist_myanmar.xlsx');
        break;
    case '3': // vietnam
        $objPHPExcel = PHPExcel_IOFactory::load('template/report_roomlist_vietnam.xlsx');
        break;
    case '4': // maldives
        $objPHPExcel = PHPExcel_IOFactory::load('template/report_roomlist_maldives.xlsx');
        break;
    default: // other
        $objPHPExcel = PHPExcel_IOFactory::load('template/report_roomlist_japan.xlsx');
    }
    # SET FILE TEMPLATE
	$objPHPExcel->setActiveSheetIndex();

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A2',$result[0]['ser_code']. ' '.$result[0]['ser_name']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D3',thai_date_short(strtotime($result[0]['per_date_start'])). ' - '.thai_date_short(strtotime($result[0]['per_date_end'])).' BUS : '.$result[0]['bus_no'] );

    $objPHPExcel->setActiveSheetIndex()->setCellValue('C4',thai_date_short(strtotime($result[0]['per_date_start'])));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E4',$result[0]['ser_go_flight_code']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F4',$result[0]['ser_go_route']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G4',$result[0]['ser_go_time']);
    
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C5',thai_date_short(strtotime($result[0]['per_date_end'])));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E5',$result[0]['ser_return_flight_code']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F5',$result[0]['ser_return_route']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G5',$result[0]['ser_return_time']);

  

    $row = 8;
    $no = 1;
    $sum_twin = 0;
    $sum_double = 0;
    $sum_triple = 0;
    $sum_single = 0;
    $row_room = 8;
    $row_no = 8;
    foreach($result as $key => $value){
        $room_twin = intval($value['book_room_twin']);
        $room_double = intval($value['book_room_double']);
        $room_triple = intval($value['book_room_triple']);
        $room_single = intval($value['book_room_single']);

        if ($room_twin > 0 ){
            for($i = 0 ; $i < intval($room_twin);$i++){
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);
                $no++;
                $row_no++;
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);

                $row_room_2 = $row_room + 1;
                $objPHPExcel->setActiveSheetIndex()->mergeCells('B'.$row_room.':B'.$row_room_2);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row_room,'TWN');
                if ($no == 2) {
                    $no = 0;
                }
                $row_no++;
                $row_room = $row_room_2 + 1;
                $no++;
            }
            $no = 1;
        }
        if ($room_double > 0 ){
            for($i = 0 ; $i < intval($room_double);$i++){
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);
                $no++;
                $row_no++;
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);

                $row_room_2 = $row_room + 1;
                $objPHPExcel->setActiveSheetIndex()->mergeCells('B'.$row_room.':B'.$row_room_2);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row_room,'DBL');
                if ($no == 2) {
                    $no = 0;
                }
                $row_no++;
                $row_room = $row_room_2 + 1;
                $no++;
            }
            $no = 1;
        }
        if ($room_triple > 0 ){
            for($i = 0 ; $i < intval($room_triple);$i++){
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);
                $no++;
                $row_no++;
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);
                $no++;
                $row_no++;
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);

                $row_room_2 = $row_room + 2;
                $objPHPExcel->setActiveSheetIndex()->mergeCells('B'.$row_room.':B'.$row_room_2);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row_room,'TRP');
                if ($no == 3) {
                    $no = 0;
                }
                $row_no++;
                $row_room = $row_room_2 + 1;
                $no++;
            }
            $no = 1;
        }
        if ($room_single > 0 ){
            for($i = 0 ; $i < intval($room_single);$i++){
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,$no);

                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row_room,'SGL');
                if ($no == 1) {
                    $no = 0;
                }
                $row_no++;
                $row_room = $row_room + 1;
                $no++;
            }
            $no = 1;
        }      


            foreach($value['room_detail'] as $key_2 => $value_2){
              

                $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row,$value_2['room_name_thai']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row,$value_2['room_fname']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row,$value_2['room_lname']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row,$value_2['room_passportno']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row,d_m_y($value_2['room_birthday']));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$row,d_m_y($value_2['room_expire']));
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$row,$value_2['room_nationality']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$row,$value_2['room_remark']);

                $row++;
            }





        $sum_twin = $sum_twin + intval($value['book_room_twin']);
        $sum_double = $sum_double + intval($value['book_room_double']);
        $sum_triple = $sum_triple + intval($value['book_room_triple']);
        $sum_single = $sum_single + intval($value['book_room_single']);
    }
    if($result[0]['leader_room_name_thai'] != ''){
    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$row_no,'1');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$row_no,'SGL');

    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$row_no,$result[0]['leader_room_name_thai']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$row_no,$result[0]['leader_room_fname']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$row_no,$result[0]['leader_room_lname']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$row_no,$result[0]['leader_room_passportno']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$row_no,d_m_y($result[0]['leader_room_birthday']));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$row_no,d_m_y($result[0]['leader_room_expire']));
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$row_no,$result[0]['leader_room_nationality']);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$row_no,$result[0]['leader_room_remark']);
    $sum_single = $sum_single + 1;
    }
  
    

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A6','TWN: '.$sum_twin.' ROOM');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E6','DBL: '.$sum_double.' ROOM');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G6','TRP: '.$sum_triple.' ROOM');
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H6','SGL: '.$sum_single.' ROOM');
    $sum_qty_room = intval($sum_twin) +  intval($sum_double) + intval($sum_triple) + intval($sum_single);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K6','TOTAL: '.$sum_qty_room.' ROOM');
    




    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/ROOM_'.$result[0]['ser_code']. '_'.thai_date_short(strtotime($result[0]['per_date_start']))
    .' - '.thai_date_short(strtotime($result[0]['per_date_end'])).'_'.' BUS_'.$result[0]['bus_no'].'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>