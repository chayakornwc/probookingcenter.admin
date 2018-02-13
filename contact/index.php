<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php 

	#WS
    $wsserver   = URL_WS;
    $wsfolder	= '/contact'; //กำหนด Folder
    $wsfile		= '/select_contact_id.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){
        $result             = $data_return['results'][0];
        $contact_detail     = $result['contact_detail'];
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
                <div class="padd-90"></div>
                <div class="row">
                    
                    <div class="col-lg-3">
                        <img class="img-responsive img-full radius-5" src="../includes/img/jitwilai_logo.png" alt="">
                    </div>
                    <div class="col-lg-9">
                        <div class="simple-text">
                            <h3>บริษัทจิตรวิไลย อินเตอร์ทัวร์</h3>
                            <font class="color-dark-2">
                                <?php echo $contact_detail;?>
                            </font>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- contant -->
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    





</body>
</html>				   