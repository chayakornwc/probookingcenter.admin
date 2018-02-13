<?php
include_once('../unity/post_to_ws/post_to_ws.php');
include_once '../unity/PHPExcel-1.8/Classes/PHPExcel.php';
include_once('../unity/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');
include_once('../unity/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

    #ws

    $wsserver   = URL_WS;
	$wsfolder	= '/report'; //กำหนด Folder
	$wsfile		= '/select_tag_bag_by_perid.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'per_id'        => $_REQUEST['per_id'],
								'bus_no'        => $_REQUEST['bus_no'],
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );


    $data_return_result = $data_return['results']['period'];
    
    $arr_passenger = array();
    for($i = 0; $i < count($data_return_result); $i++){

        for($j = 0; $j < count($data_return_result[$i]['room_detail']);$j++){
            $arr_passenger[] = $data_return_result[$i]['room_detail'][$j];
        }

    }

    $result = $data_return['results']['period'][0];

    $img_country = $result['country_img'];
    $period_name    = $result['ser_code'].' '.$result['ser_name'];
    $period_date    = date('d',strtotime($result['per_date_start'])).' - '.date('d-M-Y',strtotime($result['per_date_end']));
    $period_air     = $result['ser_go_flight_code'].'('.$result['ser_go_time'].') - '.$result['ser_return_flight_code'].'('.$result['ser_return_time'].')';
    $period_country = '';
    $logo_air       = $result['air_url_img'];
    $logo_country   = $img_country;

  
    $set_data = array();

    for ( $i = 0 ; $i < count($arr_passenger); $i++){
        
        $full_name_eng  = $arr_passenger[$i]['room_prename'].''.$arr_passenger[$i]['room_fname'].' '.$arr_passenger[$i]['room_lname'];
        $full_name_thai = $arr_passenger[$i]['room_name_thai'];
        

        $set_data[] = array(
                                0 => ($i+1),
                                1 => $full_name_eng,
                                2 => $full_name_thai,
                                );
    }

    # set_data array สุดท้ัายจะเป็นของหัวหน้านำเที่ยว

    $full_name_eng  = $arr_passenger[$i]['leader_room_prename'].''.$arr_passenger[$i]['leader_room_fname'].' '.$arr_passenger[$i]['leader_room_lname'];
    $full_name_thai = $result['leader_room_name_thai'];

    $set_data[] = array(
                                0 => 'LT',
                                1 => $full_name_eng,
                                2 => $full_name_thai,
                                );

    # คน / 12 เพื่อหา sheet
    $sheet_qty = ceil(count($set_data)/12);
    
    
    # SET FILE TEMPLATE
    $objPHPExcel = PHPExcel_IOFactory::load('template/tag_bag.xlsx');
	$objPHPExcel->setActiveSheetIndex();



    

    $position_number = array(

        0 => A1,
        1 => G1,
        2 => A15,
        3 => G15,
        4 => A29,
        5 => G29,
        6 => A43,
        7 => G43,
        8 => A57,
        9 => G57,
        10 => A71,
        11 => G71,

    );

    $position_img_country = array(
        0 => A9,
        1 => G9,
        2 => A23,
        3 => G23,
        4 => A37,
        5 => G37,
        6 => A51,
        7 => G51,
        8 => A65,
        9 => G65,
        10 => A79,
        11 => G79,
    );

    $position_img_air = array(
        0 => F9,
        1 => L9,
        2 => F23,
        3 => L23,
        4 => F37,
        5 => L37,
        6 => F51,
        7 => L51,
        8 => F65,
        9 => L65,
        10 => F79,
        11 => L79,
    );

    $position_full_name_eng = array(
        0 => B1,
        1 => H1,
        2 => B15,
        3 => H15,
        4 => B29,
        5 => H29,
        6 => B43,
        7 => H43,
        8 => B57,
        9 => H57,
        10 => B71,
        11 => H71,
    );

    $position_full_name_thai = array(
        0 => B4,
        1 => H4,
        2 => B18,
        3 => H18,
        4 => B32,
        5 => H32,
        6 => B46,
        7 => H46,
        8 => B60,
        9 => H60,
        10 => B74,
        11 => H74,
    );
    
    $position_period_name = array(
        0 => A7,
        1 => G7,
        2 => A21,
        3 => G21,
        4 => A35,
        5 => G35,
        6 => A49,
        7 => G49,
        8 => A63,
        9 => G63,
        10 => A77,
        11 => G77,
    );

    $position_period_date = array(
        0 => A9,
        1 => G9,
        2 => A23,
        3 => G23,
        4 => A37,
        5 => G37,
        6 => A51,
        7 => G51,
        8 => A65,
        9 => G65,
        10 => A79,
        11 => G79,
    );


    $position_period_country = array(
        0 => A11,
        1 => G11,
        2 => A25,
        3 => G25,
        4 => A39,
        5 => G39,
        6 => A53,
        7 => G53,
        8 => A67,
        9 => G67,
        10 => A81,
        11 => G81,
    );

    $position_period_air = array(
        0 => A13,
        1 => G13,
        2 => A27,
        3 => G27,
        4 => A41,
        5 => G41,
        6 => A55,
        7 => G55,
        8 => A69,
        9 => G69,
        10 => A83,
        11 => G83,
    );


    #add sheet
    for($i = 1; $i < $sheet_qty; $i++){

        //$objPHPExcel->createSheet();
        $objPHPExcel_template = PHPExcel_IOFactory::load('template/tag_bag.xlsx');
        $sheet = $objPHPExcel_template->getSheetByName('Sheet1');
        $sheet->setTitle('Sheet'.($i+1));
        $objPHPExcel->addExternalSheet($sheet);
      
    }


    #add logo and data
    $index_sheet    = 0;
    $index_data     = 0;
    for($i = 0; $i < $sheet_qty; $i++){

        $objPHPExcel->setActiveSheetIndex($index_sheet);
            
        for($j = 0; $j < count($position_img_country); $j++){

            # add data to cell
            
            $objPHPExcel->getActiveSheet()->setCellValue($position_number[$j],$set_data[$index_data][0]);
            $objPHPExcel->getActiveSheet()->setCellValue($position_full_name_eng[$j],$set_data[$index_data][1]);
            $objPHPExcel->getActiveSheet()->setCellValue($position_full_name_thai[$j],$set_data[$index_data][2]); 
            $objPHPExcel->getActiveSheet()->setCellValue($position_period_name[$j],$period_name);
            $objPHPExcel->getActiveSheet()->setCellValue($position_period_date[$j],$period_date);
            $objPHPExcel->getActiveSheet()->setCellValue($position_period_country[$j],$period_country);
            $objPHPExcel->getActiveSheet()->setCellValue($position_period_air[$j],$period_air);


            #logo country
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setDescription('Logo');
            $logo = $logo_country; // Provide path to your logo file
            $objDrawing->setPath($logo);  //setOffsetY has no effect
            $objDrawing->setOffsetX(3); // padding cell right 
            $objDrawing->setCoordinates($position_img_country[$j]);
            $objDrawing->setHeight(52); // logo height
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet('Sheet'.$i)); 

            #logo country
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setDescription('Logo');
            $logo = $logo_air; // Provide path to your logo file
            $objDrawing->setPath($logo);  //setOffsetY has no effect
            $objDrawing->setOffsetX(3); // padding cell right 
            $objDrawing->setCoordinates($position_img_air[$j]);
            $objDrawing->setWidth(50); // logo width
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet('Sheet'.$i)); 

            
            
            $index_data++;

        }

        $index_sheet++;

    }
    $objPHPExcel->setActiveSheetIndex(0);



    # WRITE FILE & DOWLAND    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $strFileName = 'tmp/tag_bag_'.substr(uniqid(),0,5).'_'.date('d-m-Y_His',time()).'.xlsx';
    $objWriter->save($strFileName);
    header( "location: $strFileName" );



?>