<?php
include_once('../unity/php_script.php');


#REQUEST
$user_id = base64_decode_dev_gun($_REQUEST['u']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../unity/tag_head.php'); ?>
</head>


<body>
    <div class="wrapper">
        <div class="block-center mt-xl wd-xl">
            <!-- START panel-->
            <div class="panel panel-dark panel-flat">
                <div class="panel-heading text-center">
                    
                        <!--<img src="img/logo.png" alt="Image" class="block-center img-rounded">-->
                        บริษัทจิตรวิไลย อินเตอร์ทัวร์
                    
                </div>
                <div class="panel-body" align="center">
                    <img src="../unity/img/jitwilai_logo.png" alt="App Logo" class="img-responsive" width="250px" >
                </div>
                <div class="panel-body">
                    <p class="text-center pv">PASSWORD RESET</p>
                    <div role="form">
                        <p class="text-center">กรุณาใส่ Password ใหม่</p>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id;?>">
                        <div class="form-group has-feedback">
                            <label for="password" class="text-muted">Password </label>
                            <input id="password" type="password" placeholder="Password" autocomplete="off" class="form-control">
                            <span class="fa fa-cog form-control-feedback text-muted"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="confirm_password" class="text-muted">Confirm Password </label>
                            <input id="confirm_password"  type="password" type="confirm_password" placeholder="Confirm Password" autocomplete="off" class="form-control">
                            <span class="fa fa-cog form-control-feedback text-muted"></span>
                        </div>
                        <button type="button" class="btn btn-success btn-block" onclick="check_submit_password()">
                            ยืนยัน
                        </button>
                    </div>
                </div>
            </div>
            <!-- END panel-->
           <div class="p-lg text-center">
                <span>&copy;</span>
                <span>2016</span>
                <span>-</span>
                <span>DevGun</span>
                <br>
                <span></span>
            </div>
        </div>
    </div>

    <?php include_once('../unity/tag_script.php');?>
</body> 

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
        $('body').addClass('whirl double-up');
        $.post('action_reset_password.php',
        {
            user_id     : $('#user_id').val(),
            password    : $('#password').val(),
            method      : 1
        },function(data,textStatus,xhr){
            $('body').removeClass('whirl double-up');
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

</html>