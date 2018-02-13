<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>

<?php


    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_contact_agency'; //กำหนด Folder
    $wsfile		= '/select_contact_agency.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(        
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $div_contact = '';

    if($data_return['status'] == 200){
        $result = $data_return['results'];

        $i = 1;
        foreach($result as $key => $value){

            $url_agency = str_replace('../','../admin/',$value['agen_com_logo_img']);
            if(is_file($url_agency)){
                $img = '<img class="icon-img border-grey-2" src="'.$url_agency.'" alt="" width="100px">';
            }
            else{
                $img = '';
            }
            if ($i == 1){
                $div_contact .= '<div class="col-md-12">';
            }

            $div_contact .= '<div class="col-xs-12 col-sm-6 col-md-3" style="padding-bottom:20px">
                                <div class="swiper-slide swiper-slide-visible swiper-slide-active" style="width: 300px; height: 328px;">
                                    <div class="icon-block style-2 bg-white">
                                        '.$img.'
                                        <h5 class="icon-title color-dark-2">'.$value['agen_com_name'].'</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item text-left f-12">
                                                ชื่อ : '.$value['agen_fname'].' '.$value['agen_lname'].'('.$value['agen_nickname'].')
                                                <br>
                                                เบอร์โทรศัพท์ : '.$value['agen_tel'].'
                                                <br>
                                                Line ID : <a class="color-blue-2" href="http://line.me/ti/p/~'.$value['agen_line_id'].'" target="_blank">'.$value['agen_line_id'].'</a>
                                                <br>
                                                ที่อยู่ : '.$value['agen_com_address'].'
                                            </li>
                                        </ul>
                                    
                                    </div>
                                </div>
                            </div>';
             if ($i == 4){
                $div_contact .= '</div>';
               
            }
                            $i = $i + 1;
                            if ($i == 5){
                                 $i = 1;
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
            <div class = "text-center">
            <font size = "8"><b>ติดต่อ Agency</b></font>
            </div>
            <div class="row">

                <?php echo $div_contact;?>
                
                



            </div>

        </div>
    </div>

    

    <!-- contant -->
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    





</body>
</html>				   