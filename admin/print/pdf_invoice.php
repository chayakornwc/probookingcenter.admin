<?php
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/tcpdf/tcpdf.php');
include_once('../unity/fpdi/fpdi.php');
include_once('../unity/php_script.php');


	#set var
	$result = array();

 	#WS
	$wsserver   = URL_WS;
	$wsfolder	= '/booking'; //กำหนด Folder
	$wsfile		= '/select_period_booking_update.php'; //กำหนด File
	$url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
	$data 		= array(    
								'book_id'       => $_REQUEST['book_id'],
								'book_code'     => $_REQUEST['book_code'],
								'method'        => 'GET'
							);


	$data_return =	json_decode( post_to_ws($url,$data),true );

	if($data_return['status'] == 200){
		$result = $data_return['results'][0];

	}
	

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
		$pdf->SetFont('angsanaupc', '', 16, '', true);  
	
		# เพิ่มหน้า  
		$pageCount = $pdf->setSourceFile('template/invoice.pdf');
		$tplIdx    = $pdf->importPage(1);
		$pdf->addPage();
		$pdf->useTemplate($tplIdx);  


        # เขียนข้อมูล
			#no
			$pdf->SetXY(115, 20.5);
			$pdf->Write(0, $result['invoice_code']);  
			
			#date // วันที่พิมพ์เอกสาร
			$pdf->SetXY(160, 20.5);
			$pdf->Write(0, d_m_y($result['invoice_date']));  

			
			

			#invoice to 
			$pdf->SetXY(39,42);
			$pdf->Write(0, $result['agen_com_name']);

			#sale  agent
			$pdf->SetXY(136, 42.7);
			$pdf->Write(0, ($result['agenname']));


			#tel.
			
			$pdf->SetXY(130, 50.3); #47.3
			$pdf->Write(0, $result['agen_tel']);  
			
			#address1.
            $pdf->SetXY(29, 50.3);
			$pdf->Write(0, $result['agen_com_address1']);
			#address2.
            $pdf->SetXY(13, 55.8);
			$pdf->Write(0, $result['agen_com_address2']);
			#email.
            $pdf->SetXY(130, 55.8);
			$pdf->Write(0, $result['agen_email']);

            #air.
            $pdf->SetXY(27, 65);
			$pdf->Write(0, $result['ser_code']);

			 #period.
            $pdf->SetXY(66, 65);
			$pdf->Write(0, d_m_y($result['per_date_start']).' - '.d_m_y($result['per_date_end']));


            #rount.
            $pdf->SetXY(145, 65);
			$pdf->Write(0, $result['ser_route']);

			
			
			$list = array();

			$i = 1;
			$row = 1;

			$list_price = array();
			foreach ($result['booking_list'] as $key => $value) {
				if($value['book_list_qty']> 0 ){
					$list_price[] = $value;

				}
			}


			$sumqty = 0;

			foreach ($list_price as $key => $value) {

					if($value['book_list_qty']>0){

						$list[] = array($i,'',$value['book_list_name'],number_format($value['book_list_price']),number_format($value['book_list_qty']),number_format($value['book_list_total']));
						if (intval($value['book_list_code']) < 6 ){
						$sumqty = $sumqty + $value['book_list_qty'];
						}
						$i++;

					}
					$row++;
			}
			$total_single_charge = 0;
			
			for($i = count($list_price); $i < 8; $i++){

				if($i == 5 && ($result['single_charge'] > 0 && $result['book_room_single'] > 0)){
					$total_single_charge = intval($result['single_charge']) * intval($result['book_room_single']);
					$list[] = array('','','Single Charge ' ,'',number_format($result['book_room_single']),number_format($total_single_charge));

				}
				else if($i == 6){
					$list[] = array('','','ค่าคอมมิชชั่น ('.number_format($result['per_com_company_agency']).' + '.number_format($result['per_com_agency']).')','',number_format($sumqty),'- '.number_format(($result['book_com_agency_company']+$result['book_com_agency'])));
				}
				else if($i == 7){
					$list[] = array('','','Discount','','','- '.number_format($result['book_discount']));
				}
				else{
					$list[] = array();
				}

			}
			
			
			#list

		
			
			$y = 82.7;
			for($i = 0 ; $i < count($list); $i++){
				$pdf->SetX(5);
				$pdf->SetY($y);
				$pdf->Cell( 20  , 10 , $list[$i][0] , 0 );

				$pdf->SetX(25);
				$pdf->Write(0, $list[$i][1]);
				
				$pdf->SetX(45);
				$pdf->Write(0, $list[$i][2]);

				
				$pdf->SetX(110);
				$pdf->Cell( 23  , 5 ,  $list[$i][3],0 , 1 , 'R' );
			
				$pdf->SetY($y);
				$pdf->SetX(135);
				$pdf->Cell(15  ,5 ,  $list[$i][4],0 , 1 , 'R' );

				$pdf->SetY($y);
				$pdf->SetX(155);
				$pdf->Cell( 25  , 5 ,  $list[$i][5],0 , 1 , 'R' );
				$y+= 7.45;
			}


			$total_price = ($result['book_amountgrandtotal']);

			
			#total price text.
            $pdf->SetXY(35, 143.5);
			$pdf->Write(0, Convert($total_price));

			$pdf->SetFont('angsanaupc', '', 18, '', true);  
			#total price .
            $pdf->SetXY(155, 143);
			$pdf->Cell( 25  , 5 ,  number_format($total_price),0 , 1 , 'R' );
			//$pdf->Write(0, number_format($total_price));

			#deposit 
			$pdf->SetXY(155, 152);
			$pdf->Cell( 25  , 5 ,  number_format($result['book_master_deposit']),0 , 1 , 'R' );
			//$pdf->Write(0, number_format($result['book_master_deposit']));

			#fullpayment 
			$pdf->SetXY(155, 158);
			$pdf->Cell( 25  , 5 ,  number_format($result['book_master_full_payment']),0 , 1 , 'R' );
			//$pdf->Write(0, number_format($result['book_master_full_payment']));
			
			#deposit date
			$pdf->SetXY(75, 152);
			$pdf->Write(0, d_m_y($result['book_due_date_deposit']));

			#full payment date
			$pdf->SetXY(75, 158);
			$pdf->Write(0, d_m_y($result['book_due_date_full_payment']));
			
		

			$pdf->SetFont('angsanaupc', '', 14, '', true);  
			#sale  
			$pdf->SetXY(40, 178.7);
			$pdf->Write(0, ($result['username']));

			$pdf->SetFont('angsanaupc', '', 14, '', true);  
			#remark extra คำสั่งพิเศษ
			$pdf->SetXY(102, 172);
			$pdf->Write(0, ('หมายเหตุ : '));
			
			$pdf->MultiCell(0,0,$result['book_comment'] ,0, '', 0, 1, '', '', true);

        # แสดงไฟล์ pdf  
        ob_start();
        $pdf->Output('invoice_no_'.$result['invoice_code'].'_'.date('d-m-Y_His',time()).'.pdf', 'I');  
        ob_end_flush();

?> 