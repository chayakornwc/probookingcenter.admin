<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>


<?php 

	#WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_home'; //กำหนด Folder
    $wsfile		= '/select_home_footer.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

	$div_travel_new 	= '';
	if($data_return['status'] == 200){
		if(isset($data_return['results'])){
			foreach($data_return['results'] as $key => $value){
				$url = str_replace('../','../admin/',$value['ser_url_img_1']);// รูปใหญ่
					if(is_file($url)){
								$div_travel_new .= '<div class="f_news clearfix">
						<a class="f_news-img black-hover" href="../search/tour.php?sid='.$value['ser_id'].'">
							<img class="img-responsive" src="'.$url.'" alt="">
							<div class="tour-layer delay-1"></div>
						</a>
						<div class="f_news-content">
							<a href="../search/tour.php?sid='.$value['ser_id'].'"><p class="color-white">'.$value['ser_name'].'</p></a>
						</div>
					</div>';

					}


			}

		}




	}
?>
<footer class="bg-bluejeans type-2">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 ">
				<div class="footer-block">
					<img src="../includes/img/jitwilai_logo.png" alt="" class="logo-footer" style="width:250px">
					<div class="f_text color-white">บริษัทจิตรวิไลย อินเตอร์ทัวร์ ดำเนินธุรกิจด้านการบริการท่องเที่ยว ดังนี้ สาธารณรัฐประชาชนจีน ปักกิ่ง เซี่ยงไฮ้ คุนหมิง กวางเจา เฉินตู กุ้ยหลิน มองโกเลีย ซีอาน เจิ้งโจว ฯลฯ ประเทศไต้หวัน </div>
					
				</div>
			</div>
			<div class="col-lg-4 no-padding">
				<div class="footer-block">
					<h6>Travel New</h6>
					
					<?php echo $div_travel_new;?>


				</div>
			</div>
			<div class="col-lg-4 ">
				<div class="footer-block">
					<h6>WITH US.</h6>
					<div class="f_text color-white">
						<i class="fa fa-map-marker"></i><span> ที่อยู่</span><br><br>
						<i class="fa fa-phone"></i><span> +93 123 456 789</span><br>
						<i class="fa fa-envelope-o"></i><span> letstravel@mail.com</span>
						<br>
						<br>
						<br>
						<br>
					</div>
						
					<div class="footer-share" align="center">
						<a href="#"><span class="fa fa-facebook"></span></a>
						<a href="#"><span class="fa fa-twitter"></span></a>
						<a href="#"><span class="fa fa-google-plus"></span></a>
						<a href="#"><span class="fa fa-pinterest"></span></a>
					</div>
				</div>
				</div> 
			</div>
		</div>
	</div>
	
</footer>