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
            $pageCount = $pdf->setSourceFile('template/myanmar/declare_myanmar_front_2p.pdf');
            $tplIdx    = $pdf->importPage(1);
            $pdf->addPage('L');
            $pdf->useTemplate($tplIdx);  
        }


        $position = array(
            0 => array(
                'name'              => 35,
                'passport_no'       => 40,
                'nationality'       => 38,
                'date_of_birth'     => 90,
                'occupation'        => 40,
                'filght_no'         => 45,
                'form'              => 85,
                'date_of_arrival'   => 45,
            ),
            1 => array(
                'name'              => 185,
                'passport_no'       => 190,
                'nationality'       => 188,
                'date_of_birth'     => 240,
                'occupation'        => 190,
                'filght_no'         => 195,
                'form'              => 235,
                'date_of_arrival'   => 195,
            )
        );


        
        $left_or_right  = 0;
        $counter_paper  = 0;
        $border         = 0; // เส้นกรอบข้อมูล
        foreach ($result as $key => $value) {
            
            if($counter_paper % 2 == 0){
                # เพิ่มหน้า  
                $pageCount = $pdf->setSourceFile('template/myanmar/declare_myanmar_front_2p.pdf');
                $tplIdx    = $pdf->importPage(1);
                $pdf->addPage('L');
                $pdf->useTemplate($tplIdx);  
               
            }

            $name               = $value['room_prename'].''.$value['room_fname'].' '.$value['room_lname'];
            $passport_no        = $value['room_passportno'];
            $nationality        = $value['room_nationality'];
            $date_of_birth      = d_m_y($value['room_birthday']);
            $occupation         = $value['room_career'];
            $filght_no          = $value['ser_go_flight_code'];
            $form               = $value['room_country'];
            $date_of_arrival    = d_m_y($value['arrival_date']); // วันที่มาถึง arrival_date
            
            $pdf->SetXY($position[$left_or_right]['name'],61);
            $pdf->Cell(70, 5, $name, $border, 1, 'L', 0, '', 1);

            $pdf->SetXY($position[$left_or_right]['passport_no'],66);
            $pdf->Cell(70, 5, $passport_no, $border, 1, 'L', 0, '', 1);

            $pdf->SetXY($position[$left_or_right]['nationality'],72);
            $pdf->Cell(35, 5, $nationality, $border, 1, 'C', 0, '', 1);

            $pdf->SetXY($position[$left_or_right]['date_of_birth'],72);
            $pdf->Cell(35, 5, $date_of_birth, $border, 1, 'C', 0, '', 1);

            $pdf->SetXY($position[$left_or_right]['occupation'],79);
            $pdf->Cell(70, 5, $occupation, $border, 1, 'L', 0, '', 1);


            $pdf->SetXY($position[$left_or_right]['filght_no'],85);
            $pdf->Cell(30, 5, $filght_no, $border, 1, 'C', 0, '', 1);

            $pdf->SetXY($position[$left_or_right]['form'],85);
            $pdf->Cell(30, 5, $form, $border, 1, 'C', 0, '', 1);

            $pdf->SetXY($position[$left_or_right]['date_of_arrival'],92);
            $pdf->Cell(30, 5, $date_of_arrival, $border, 1, 'C', 0, '', 1);

            if($left_or_right == 0){
                $left_or_right = 1;
            }
            else {
                $left_or_right = 0;
            }
                
            $counter_paper++;

        }

       
        
		
        # แสดงไฟล์ pdf  
        ob_start();
        $pdf->Output('declare_maynmar_'.$result[0]['ser_code'].'_'.date('d-m-Y_His',time()).'.pdf', 'I');  
        ob_end_flush();

?> 