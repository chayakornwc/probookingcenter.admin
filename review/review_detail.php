<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>

<?php


    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_preview'; //กำหนด Folder
    $wsfile		= '/select_review_by_id.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(        
                                'review_id'     => $_REQUEST['r'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){

        $result = $data_return['results'][0];

        $review_country     = $result['country_name'];
        $review_detail      = $result['review_detail'];

       

        $img = '';
        for($i = 1; $i <= 5; $i++){

            $url_review = str_replace('../','../admin/',$result['review_url_img_'.$i]);

            if(is_file($url_review)){
                 $img .= '<div class="swiper-slide">
                                <img class="img-responsive" src="'.$url_review.'" alt="" style="width:100%">
                            </div>';
            }

        }

    }

?>
<!DOCTYPE html>
<html>
<head>

    <?php include_once('../includes/tag_head.php');?>

</head>
<body data-color="theme-7">

    <?php include_once('../includes/loading.php');?>
    <?php include_once('../includes/menu.php');?>
  
    
    <!-- contant -->

    <div class="main-wraper  padd-70-70">
        <img class="center-image" src="../includes/img/tmp/pexels-photo-242236.jpeg" alt="" style="display: none;">
        <div class="container">
            
            <div class="gallery-detail">

                <div class="top-baner arrows">
                    <div class="swiper-container" data-autoplay="5000" data-loop="1" data-speed="1000" data-center="0" data-slides-per-view="1" id="tour-slide-2">
                        <div class="swiper-wrapper">
                            
                            <?php echo $img;?>
                            		  				  				  	
                        </div>
                        <div class="pagination pagination-hidden poin-style-1"></div>
                    </div>
                    <div class="arrow-wrapp arr-s-1">
                        <div class="cont-1170">
                            <div class="swiper-arrow-left sw-arrow"><span class="fa fa-angle-left"></span></div>
                            <div class="swiper-arrow-right sw-arrow"><span class="fa fa-angle-right"></span></div>
                        </div>
                    </div>
                </div>
                <div class="gd-content">
                    
                    <h2 class="gd-title"><?php echo $review_country;?></h2>
                    <font class="color-dark-2"><?php echo $review_detail;?></font>
                
                </div>

            </div>
        </div>        		
    </div>

    <!-- contant -->
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    





</body>
</html>				   