<?php
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/tcpdf/tcpdf.php');
include_once('../unity/fpdi/fpdi.php');
include_once('../unity/php_script.php');
  
   

   
	#set var
	$result = array();

 	#WS
	$wsserver   = URL_WS;
	$wsfolder	= '/report'; //กำหนด Folder
	$wsfile		= '/select_tm_by_perid.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'per_id'        => $_REQUEST['per_id'],
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	
// create new PDF document

        #  เริ่มสร้างไฟล์ pdf 
		$pdf = new FPDI(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
		
		#  กำหนดรายละเอียดของไฟล์ pdf 
		$pdf->SetCreator(PDF_CREATOR);   
		$pdf->SetTitle('');  

		# กำหนดสี 
		$pdf->setFooterData(  
		    array(0,64,0),  // กำหนดสีของข้อความใน footer rgb   
		    array(0,0,0)   // กำหนดสีของเส้นคั่นใน footer rgb   
		);

		# กำหนดฟอนท์ของ header และ footer  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php 
		//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		# กำหนดฟอนท์ของ monospaced  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php   //
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);  

		# กำหนดขอบเขตความห่างจากขอบ  กำหนดเพิ่มเติมในไฟล์  tcpdf_config.php   //
		$pdf->SetMargins(15, PDF_MARGIN_TOP, 0);  
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		#  กำหนดแบ่่งหน้าอัตโนมัติ 
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 

		# กำหนด ฟอนท์  
		$pdf->SetFont('angsanaupc', '', 16, '', true);  

		# กำหนดแบ่่งหน้าอัตโนมัติ  //
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);  

		# กำหนด ฟอนท์ 
		$pdf->SetFont('angsanaupc', '', 12, '', true);  
        
        if($data_return['status'] == 200){
            $result = $data_return['results'];

        }
        else{
            # เพิ่มหน้า  
            $pageCount = $pdf->setSourceFile('template/japan/declare_japan.pdf');
            $tplIdx    = $pdf->importPage(1);
            $pdf->addPage();
            $pdf->useTemplate($tplIdx);  
            
        }


        foreach ($result as $key => $value) {
            
            # เพิ่มหน้า  
            $pageCount = $pdf->setSourceFile('template/japan/declare_japan.pdf');
            $tplIdx    = $pdf->importPage(1);
            $pdf->addPage();
            $pdf->useTemplate($tplIdx);  
            
            $border = 0; // เส้นกรอบข้อมูล

            # Set ข้อมูล

                $no                             = '';
                $falmily_name                   = $value['room_lname'];
                $first_name                     = $value['room_fname']; 
                $middle_name                    = '';
                $sex                            = $value['room_sex'];


                $date_of_birth                  = m_d_y($value['room_birthday']);
                
                $day_of_birth           = '';
                $month_of_birth         = '';
                $year_of_birth          = '';

                if($date_of_birth!=''){
                    $day_of_birth = substr($date_of_birth,3,2);
                    $month_of_birth   = substr($date_of_birth,0,2);
                    $year_of_birth  = substr($date_of_birth,6,4);
                }

                $place_of_birth                 = $value['room_placeofbirth'];
                $nationality                    = $value['room_nationality'];
                $occupation                     = $value['room_career'];
                $passport_no                    = $value['room_passportno'];
                $passport_no_place_of_issue     = $value['room_place_pp'];
                $passport_no_date_of_issue      = m_d_y($value['room_date_pp']);
                $visa_no                        = '';
                $visa_no_place_of_issue         = '';
                $visa_no_date_of_issue          = '';
                
                

                $by_air                         = '';
                $filght_no                      = $value['ser_go_flight_code'];

                $city_state                     = 'BANGKOK';
                $country                        = $value['room_country'];
                
                
                $point_of_embarkation           = 'BKK';

                $period_date_start              = m_d_y($value['arrival_date']);
                $period_date_end                = m_d_y($value['per_date_end']);

                if($period_date_start!=''){
                    $day_of_period_start    = substr($period_date_start,3,2);
                    $month_of_period_start  = substr($period_date_start,0,2);
                    $year_of_period_start   = substr($period_date_start,6,4);
                }

                $date_stay                      = count(dateRange($value['arrival_date'], $value['per_date_end']));
                if($date_stay > 0){
                    $date_stay .= ' DAYS';
                }

                $per_hotel                      = $value['per_hotel'];
                $per_hotel_tel                  = $value['per_hotel_tel'];

                if($per_hotel_tel!=''){
                    $per_hotel_tel_format = $per_hotel_tel[0].$per_hotel_tel[1].'     ';
                    $per_hotel_tel_format .= $per_hotel_tel[2].$per_hotel_tel[3].'              ';
                    $per_hotel_tel_format .= $per_hotel_tel[4].'  '.$per_hotel_tel[5].'  '.$per_hotel_tel[6].'                     ';
                    $per_hotel_tel_format .= $per_hotel_tel[7].'  '.$per_hotel_tel[8].'  '.$per_hotel_tel[9].'  '.$per_hotel_tel[10];
                }

            # เขียนข้อมูล
            
                $pdf->SetXY(95,22.5);
                $pdf->Cell(20, 5, $filght_no, $border, 1, 'L', 0, '', 1);
                $pdf->SetXY(128,22.5);
                $pdf->Cell(20, 5, $point_of_embarkation, $border, 1, 'L', 0, '', 1);


                $pdf->SetFont('angsanaupc', '', 10, '', true);  
                $pdf->SetXY(98,28.5);
                $pdf->Cell(10, 5, $year_of_period_start, $border, 1, 'L', 0, '', 1);
                $pdf->SetXY(120,28.5);
                $pdf->Cell(10, 5, $month_of_period_start, $border, 1, 'L', 0, '', 1);
                $pdf->SetXY(138,28.5);
                $pdf->Cell(10, 5, $day_of_period_start, $border, 1, 'L', 0, '', 1);

                $pdf->SetFont('angsanaupc', '', 12, '', true);  
                $pdf->SetXY(83,37);
                $pdf->Cell(26, 5, $falmily_name, $border, 1, 'L', 0, '', 1);
                $pdf->SetXY(112,37);
                $pdf->Cell(30, 5, $first_name, $border, 1, 'L', 0, '', 1);

                $pdf->SetXY(83,44);
                $pdf->Cell(70, 5, $per_hotel, $border, 1, 'L', 0, '', 1);

                $pdf->SetFont('angsanaupc', '', 12, '', true);  
                $pdf->SetXY(89,49);
                $pdf->Cell(70, 5, '+'.$per_hotel_tel_format, $border, 1, 'L', 0, '', 1);
                
                $pdf->SetFont('angsanaupc', '', 12, '', true);
                $pdf->SetXY(83,55);
                $pdf->Cell(40, 5, $nationality, $border, 1, 'L', 0, '', 1);
                $pdf->SetXY(125,55);
                $pdf->Cell(40, 5, $occupation, $border, 1, 'L', 0, '', 1);



                $pdf->SetFont('angsanaupc', '', 14, '', true);  
                $pdf->SetXY(86,62);
                $pdf->Cell(10, 5, $year_of_birth, $border, 1, 'L', 0, '', 1);
                $pdf->SetXY(114,62);
                $pdf->Cell(10, 5, $month_of_birth, $border, 1, 'L', 0, '', 1);
                $pdf->SetXY(135,62);
                $pdf->Cell(10, 5, $day_of_birth, $border, 1, 'L', 0, '', 1);
                
                $pdf->SetFont('angsanaupc', '', 14, '', true);  
                $pdf->SetXY(86,67);
                $pdf->Cell(70, 5, $passport_no, $border, 1, 'L', 0, '', 1);

        }  


        

       

		
        # แสดงไฟล์ pdf  
        ob_start();
        $pdf->Output('declare_japan_'.$result[0]['ser_code'].'_'.date('d-m-Y_His',time()).'.pdf', 'I');  
        ob_end_flush();

?> 