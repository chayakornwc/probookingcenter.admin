<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>

<?php


    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_preview'; //กำหนด Folder
    $wsfile		= '/select_country.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    $list_country = '';
    if($data_return['status'] == 200){

        $result = $data_return['results'];
        
        foreach($result as $key => $value){

            $list_country .= '<li><a href="#'.$value['country_id'].'" data-filter=".filter_'.$value['country_id'].'">'.$value['country_name'].'</a></li>';

        }
        
    }


    $wsserver   = URL_WS;
    $wsfolder	= '/f_preview'; //กำหนด Folder
    $wsfile		= '/select_review.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $div_data = '';
    if($data_return['status'] == 200){

        $result = $data_return['results'];


        foreach($result as $key => $value){
            
            $url_review = str_replace('../','../admin/',$value['review_url_img_1']);

            if(is_file($url_review)){


            $div_data .= ' <div class="item filter_'.$value['country_id'].' gal-item style-3 gal-big col-mob-12 col-xs-6 col-sm-6">
                                <a class="black-hover" href="review_detail.php?r='.$value['review_id'].'">
                                    <div class="gal-item-icon">
                                        <img class="img-full img-responsive" src="'.$url_review.'" alt="">
                                        <div class="tour-layer delay-1"></div>
                                        <div class="vertical-align">
                                            <span class="c-button small bg-white delay-2"><span>อ่านต่อ</span></span>
                                        </div>
                                    </div>
                                </a>
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

    <div class="main-wraper padd-70-70">
        <img class="center-image" src="../includes/img/tmp/pexels-photo-242236.jpeg" alt="" style="display: none;">
        <div class="container">
            <div class="filter style-2">
                <ul class="filter-nav">
                    <li class="selected"><a href="#all" data-filter="*">ทั้งหมด</a></li>
                    <?php echo $list_country;?>

                    
                </ul>
            </div>
            <div class="filter-content row">
                <div class="grid-sizer col-mob-12 col-xs-6 col-sm-2"></div>

                <?php echo $div_data;?>
                                                                                                                                                                                    
            </div>
        </div>        		
    </div>


    <!-- contant -->
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    





</body>
</html>				   