<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../admin/unity/php_script.php');?>
<?php
#REQUEST
$agen_id = base64_decode_dev_gun($_REQUEST['u']);

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
                                <div class="f-login-title color-dr-blue-2">Reset Password</div>
                                
                            </div>
                            <form class="form-horizontal">
                              
                                <div class="form-group">
                                  <input type="hidden" id="agen_id" name="agen_id" value="<?php echo $agen_id;?>">
                                    <label class="control-label col-sm-3" for="password">Password:</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" placeholder="Enter password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="confirm_password">Confirm Password:</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="confirm_password" placeholder="Enter password">
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <div class="col-sm-offset-3 col-sm-9">
                                      <button type="button" class="btn btn-success btn-block" onclick="check_submit_password()">
                            ยืนยัน
                        </button>
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
    function check_submit_password(){
        if($('#password').val()==''){
            $('#password').focus();
            swal('กรุณากรอกรหัสผ่าน','','warning');
            return;
        }
        else if($('#password').val()!= $('#confirm_password').val()){
            $('#password').focus();
            swal('กรุณากรอกรหัสผ่านให้ตรงกัน','','warning');
            return;
        }
        else{
            submit_update_password();
        }
    }
    function submit_update_password(){
        block_ui();
        $.post('action_reset_password.php',
        {
            agen_id     : $('#agen_id').val(),
            password    : $('#password').val(),
            method      : 1
        },function(data,textStatus,xhr){
            unblock_ui();
            data_j = JSON.parse(data);
            
            if(data_j.status == 'TRUE'){
                SWEET_ALERT_CONFIRM('เปลี่ยนรหัสผ่านสำเร็จ','','location.href="../login"');
            }
            else{
                SWEET_ALERT_CONFIRM('เปลี่ยนรหัสผ่านไม่สำเร็จ','','');
            }

            
        })
    }
</script>



</body>
</html>				   