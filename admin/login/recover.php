

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
                        <p class="text-center">กรุณาใส่ Email เพื่อทำการ Reset รหัสผ่าน</p>
                        <div class="form-group has-feedback">
                            <label for="resetInputEmail1" class="text-muted"></label>
                            <input id="resetInputEmail1" type="email" placeholder="Enter email" autocomplete="off" class="form-control">
                            <span class="fa fa-envelope form-control-feedback text-muted"></span>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block" onclick="submit_reset()">Reset</button>


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
                            <a href="index.php" class="text-muted">
                                เข้าสู่ระบบ
                            </a>
                        </div>
                    </div>


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
 <script>
    
    
    function submit_reset(){
            if($('#resetInputEmail1').val()==''){
                $('#resetInputEmail1').focus();
                swal('กรุณากรอก Email','','warning');
                return;
            }
            $('body').addClass('whirl double-up');
            $.post('action_recover.php',{

                user_email   : $('#resetInputEmail1').val(),
                method      : 1

            },function(data,textStatus,xhr){
                $('body').removeClass('whirl double-up');
                console.log(data);
                data_j = JSON.parse(data);
                if(data_j.status == 'TRUE'){
                     //location.href=data_j.url_menu;
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