

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include_once('../unity/tag_head.php'); ?>
</head>

<body >
   
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
                <p class="text-center pv">เข้าสู่ระบบ</p>
                <div role="form" data-parsley-validate="" novalidate="" class="mb-lg">
                    <div class="form-group has-feedback">
                        <input id="user_name" type="text" placeholder="Username" autocomplete="off" required class="form-control">
                    <span class="fa fa-envelope form-control-feedback text-muted"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input id="user_pwd" type="password" placeholder="Password" required class="form-control">
                        <span class="fa fa-lock form-control-feedback text-muted"></span>
                    </div>

                    <!--<div class="form-group">
                        <div role="alert" class="alert alert-info alert-dismissible fade in" align="center">
                           <button type="button" data-dismiss="alert" aria-label="Close" class="close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                           <strong>กรุณากรอกข้อมูลให้ครบ</strong>
                        </div>
                    </div>-->

                    <button type="button" class="btn btn-block btn-primary mt-lg" onclick="check_submit_login()">
                        เข้าสู่ระบบ
                    </button>

                    <div class="clearfix">
                        <br>
                        <!--<div class="checkbox c-checkbox pull-left mt0">
                            <label>
                                <input type="checkbox" value="" name="remember">
                                <span class="fa fa-check"></span>
                                Remember Me
                            </label>
                        </div>-->
                        <div class="pull-right">
                            <a href="recover.php" class="text-muted">
                               ลืมรหัสผ่าน
                            </a>
                        </div>
                    </div>
                    
                </div>
                <!--<p class="pt-lg text-center">Need to Signup?</p>
                    <a href="register.html" class="btn btn-block btn-default">
                    Register Now
                </a>-->
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



   <script>

        $('#user_pwd').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                check_submit_login();
            }
        });

        function submit_login(){

            $.post('action_login.php',{

                user_name   : $('#user_name').val(),
                password    : $('#user_pwd').val(),
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