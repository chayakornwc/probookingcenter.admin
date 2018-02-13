<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../admin/unity/php_script.php');?>

<!DOCTYPE html>
<html>
<head>

    <?php include_once('../includes/tag_head.php');?>

</head>
<body data-color="theme-7">

    <?php include_once('../includes/loading.php');?>
    <?php include_once('../includes/menu.php');?>
  
    
    <!-- contant -->
    <img class="center-image" src="../includes/img/tmp/pexels-photo-155244.jpeg" alt="">
        <div class="container">
            <div class="login-fullpage">                                                                            
                <div class="row">
                    <div class="login-logo" style="padding-top: 13%;">
                        <img class="center-" src="../includes/img/jitwilai_logo.png" alt="" width="100%">
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <div class="f-login-content">
                            <div class="f-login-header">
                                <div class="f-login-title color-dr-blue-2">ลืมรหัสผ่าน</div>
                                   <p class="text-center">กรุณาใส่ Email เพื่อทำการ Reset รหัสผ่าน</p>
                            </div>
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="email">Email:</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="resetInputEmail1" placeholder="Enter email">
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-danger btn-block" onclick = "submit_reset()">Reset</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <a href="../login" class="f-14"><u>เข้าสู่ระบบ</u></a>
                                    </div>
                                </div>

                            </form>
                        </div>				
                    </div>
                </div>
            </div>
           
        </div>  

    <!-- contant -->
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    


 <script>
    
    
    function submit_reset(){
            if($('#resetInputEmail1').val()==''){
                $('#resetInputEmail1').focus();
                swal('กรุณากรอก Email','','warning');
                return;
            }
            block_ui();
            $.post('action_recover.php',{

                user_email   : $('#resetInputEmail1').val(),
                method      : 1

            },function(data,textStatus,xhr){
                unblock_ui();
                data_j = JSON.parse(data);
                if(data_j.status == 'TRUE'){
                   
                     swal('กรุณาตรวจสอบ URL Reset Password ที่ Email ของท่าน','','warning');
                }
                else{
                    swal('ไม่พบ Email นี้ในระบบ','','warning');
                }
            });


        }
</script>




</body>
</html>				   