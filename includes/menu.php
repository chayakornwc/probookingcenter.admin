<?php
// select contry
	
    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_preview'; //กำหนด Folder
    $wsfile		= '/select_country.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );


    $menu_country = '';
    if($data_return['status'] == 200){

        $result = $data_return['results'];
        
        foreach($result as $key => $value){
			$menu_country .= ' <li><a href="../search/index.php?s=menu&cid='.$value['country_id'].'">'.$value['country_name'].'</a></li>';
			//$menu_country .= '<a href="#" name = '.$value['country_id'].'>'.$value['country_name'].'</a>';

        }
        
    }
			$li_menu = '';
	        if(isset($_SESSION['login']["agen_user_name"])){
				$agen_user_name = $_SESSION["login"]["agen_user_name"];
				$li_menu .='<li class="type-1" id="booking">
						<a href="../booking">'.$agen_user_name.'</a>
					</li>
					 <li class="type-1" id="logout">
						<a href="../login/action_logout.php">LOG OUT</a>
					</li> 
				';
        		}else{

				$li_menu .='<li class="type-1" id="login">
						<a href="../login">LOG IN</a>
					</li>
					 
				';
				}

?>


 <header class="color-1 hovered menu-3">
   <div class="container">
   	  <div class="row">
   	  	 <div class="col-md-12">
  	  	    <div class="nav"> 
   	  	    <a href="javascript:;" class="logo">
   	  	    	<img src="../includes/img/jitwilai_logo.png" width="20%">
   	  	    </a>
   	  	    <div class="nav-menu-icon">
		      <a href="#"><i></i></a>
		    </div>
   	  	 	<nav class="menu">
			  	<ul>
					<li class="type-1" id="home">
						<a href="../home">HOME</a>
					</li>
					<li class="type-1" id="search">
						<a href="#">TOUR<span class="fa fa-angle-down"></span></a>
						<ul class="dropmenu">
						<?php echo $menu_country; ?>
						</ul>
					</li>
					<li class="type-1" id="review">
						<a href="../review">REVIEW</a>
					</li>
					<li class="type-1"  id="contact">
						<a href="../contact">ABOUT US</a>
					</li>
					<li class="type-1" id="agency">
						<a href="../agency">Agency</a>
					</li>
					<?php echo $li_menu; ?>

					<!-- <li class="type-1" id="login">
						<a href="../login">เข้าสู่ระบบ</a>
					</li>
					 <li class="type-1" id="booking">
						<a href="../booking"><?php echo $agen_user_name; ?></a>
					</li> -->
			  	</ul>
		   </nav>
		   </div>
   	  	 </div>
   	  </div>
   </div>
</header>

<div class="padd-90"></div>