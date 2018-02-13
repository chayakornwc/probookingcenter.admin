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
                                <div class="f-login-title color-dr-blue-2">ยินดีต้อนรับเข้าสู่ระบบ</div>
                                
                            </div>
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="text">Username:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="username" placeholder="Enter Username" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="password">Password:</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="agen_password" placeholder="Enter password" required>
                                    </div>
                                </div>
                                
                                <div class="form-group"> 
                                    <div class="col-sm-offset-3 col-sm-12">
                                        <button type="button" class="btn btn-primary" onclick="check_submit_login()">เข้าสู่ระบบ</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <a href="send_email.php" class="f-14"><u>ลืมรหัสผ่าน</u></a>
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

        function submit_login(){

            $.post('action_login.php',{

                user_name   : $('#username').val(),
                password    : $('#agen_password').val(),
                method      : 1

            },function(data,textStatus,xhr){
                console.log(data);
                data_j = JSON.parse(data);
                if(data_j.status == 'TRUE'){
                    location.href=data_j.url_menu;
                }
                else{
                    swal('Username หรือ Password ไม่ถูกต้องกรุณาตรวจสอบใหม่อีกครั้ง','','warning');
                }
            });


        }


        function check_submit_login(){
          
            conter_input_null = true;
            $('input[required]').each(function(e){

                if(($(this).val().trim())==''){
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบถ้วน','','warning');
                    conter_input_null = false;

                    return false;
                }

            });


            if(conter_input_null == true){
                submit_login();
            }


        }

   </script>



</body>
</html>				   