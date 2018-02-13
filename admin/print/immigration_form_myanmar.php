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
		$pdf->SetFont('angsanaupc', '', 14, '', true);  
        
        if($data_return['status'] == 200){
            $result = $data_return['results'];

        }
        else{
            # เพิ่มหน้า  
            $pageCount = $pdf->setSourceFile('template/myanmar/immigration_form_myanmar.pdf');
            $tplIdx    = $pdf->importPage(1);
            $pdf->addPage();
            $pdf->useTemplate($tplIdx);  
            
        }


        foreach ($result as $key => $value) {
            
            # เพิ่มหน้า  
            $pageCount = $pdf->setSourceFile('template/myanmar/immigration_form_myanmar.pdf');
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

                $date_stay                      = count(dateRange($value['arrival_date'], $value['per_date_end']));
               
                $city_state                     = '';
                $country                        = $value['room_country'];
                $address_in_myanmar             = $value['per_hotel'];

                $period_date_start              = m_d_y($value['per_date_start']);
                $period_date_end                = m_d_y($value['per_date_end']);


            # เขียนข้อมูล
            #---------------------- แผ่นด้านซ้าย ---------------------------
                $pdf->SetXY(10,19);
                $pdf->Cell(45, 5, $no, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(6,29);
                $pdf->Cell(25, 5, $falmily_name, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(32,29);
                $pdf->Cell(25, 5, $first_name, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(58,29);
                $pdf->Cell(25, 5, $middle_name, $border, 1, 'C', 0, '', 1);

                if($sex == 1 ){ //ช
                    $pdf->SetXY(84,26);
                    $pdf->Image('template/check.png', '', '', 5, 5, '', '', '', false, 300, '', false, false, 1, false, false, false);
                }
                else if($sex == 2){ // ญ
                    $pdf->SetXY(84,32);
                    $pdf->Image('template/check.png', '', '', 5, 5, '', '', '', false, 300, '', false, false, 1, false, false, false);
                }

               // $pdf->Image('template/check.png', '', '', 40, 40, '', '', '', false, 300, '', false, false, 1, false, false, false);

                $pdf->SetXY(10,40);
                $pdf->Cell(25, 5, $date_of_birth, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(58,40);
                $pdf->Cell(25, 5, $place_of_birth, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(10,48);
                $pdf->Cell(25, 5, $nationality, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(58,48);
                $pdf->Cell(25, 5, $occupation, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(10,57);
                $pdf->Cell(25, 5, $passport_no, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(40,57);
                $pdf->Cell(25, 5, $passport_no_place_of_issue, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(73,57);
                $pdf->Cell(25, 5, $passport_no_date_of_issue, $border, 1, 'C', 0, '', 1);
                
                $pdf->SetXY(10,65);
                $pdf->Cell(25, 5, $visa_no, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(40,65);
                $pdf->Cell(25, 5, $visa_no_place_of_issue, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(73,65);
                $pdf->Cell(25, 5, $visa_no_date_of_issue, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(70,72);
                $pdf->Image('template/check.png', '', '', 5, 5, '', '', '', false, 300, '', false, false, 1, false, false, false);
                $pdf->SetXY(73,73);
                $pdf->Cell(25, 5, $by_air, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(78,79);
                $pdf->Cell(25, 5, $filght_no, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(76,87);
                $pdf->Cell(25, 5, $date_stay, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(7,97);
                $pdf->Image('template/check.png', '', '', 5, 5, '', '', '', false, 300, '', false, false, 1, false, false, false);

                $pdf->SetXY(6,115);
                $pdf->Cell(18, 5, $city_state, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(28,115);
                $pdf->Cell(18, 5, $country, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(52,115);
                $pdf->Cell(45, 5, $address_in_myanmar, $border, 1, 'C', 0, '', 1);

            #---------------------- แผ่นด้านซ้าย ---------------------------

            #---------------------- แผ่นด้านขวา ---------------------------
                $pdf->SetXY(130,15);
                $pdf->Cell(25, 5, $filght_no, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(180,15);
                $pdf->Cell(25, 5, $period_date_start, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(110,30);
                $pdf->Cell(25, 5, $falmily_name, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(136,30);
                $pdf->Cell(25, 5, $first_name, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(162,30);
                $pdf->Cell(25, 5, $middle_name, $border, 1, 'C', 0, '', 1);

                if($sex == 1 ){ //ช
                    $pdf->SetXY(187,26);
                    $pdf->Image('template/check.png', '', '', 5, 5, '', '', '', false, 300, '', false, false, 1, false, false, false);
                }
                else if($sex == 2){ // ญ
                    $pdf->SetXY(187,32);
                    $pdf->Image('template/check.png', '', '', 5, 5, '', '', '', false, 300, '', false, false, 1, false, false, false);
                }


                $pdf->SetXY(115,40);
                $pdf->Cell(30, 5, $passport_no, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(150,40);
                $pdf->Cell(25, 5, $passport_no_place_of_issue, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(175,40);
                $pdf->Cell(25, 5, $passport_no_date_of_issue, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(115,49);
                $pdf->Cell(40, 5, $nationality, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(160,49);
                $pdf->Cell(40, 5, $occupation, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(115,57);
                $pdf->Cell(40, 5, $visa_no, $border, 1, 'C', 0, '', 1);
                $pdf->SetXY(160,57);
                $pdf->Cell(40, 5, $visa_no_place_of_issue, $border, 1, 'C', 0, '', 1);

                $pdf->SetXY(115,83);
                $pdf->Cell(80, 5, $address_in_myanmar, $border, 1, 'C', 0, '', 1);
            #---------------------- แผ่นด้านขวา ---------------------------



        }  


        
		
        # แสดงไฟล์ pdf  
        ob_start();
        $pdf->Output('tm_maynmar_'.$result[0]['ser_code'].'_'.date('d-m-Y_His',time()).'.pdf', 'I');  
        ob_end_flush();

?> 