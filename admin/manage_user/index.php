<?php include_once('../unity/main_core.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include_once('../unity/tag_head.php'); ?>
</head>

<body >
   <div class="wrapper">
      <!-- top navbar-->
      <header class="topnavbar-wrapper">
         <!-- START Top Navbar-->
         <?php include_once('../unity/navigation.php');?>
         <!-- END Top Navbar-->
      </header>
      <!-- sidebar-->
     
      <?php include_once('../unity/menu.php');?>
      
      <!-- Main section-->
      <section>
         <!-- Page content-->
         <div class="content-wrapper" id="content-wrapper">

            <div class="content-heading">
               <!-- START Language list-->
               <div class="pull-right">
                 
               </div>
               <!-- END Language list    -->
               <font id="title_page"></font>
               <small data-localize="dashboard.WELCOME"></small>
            </div>


            <!-- SEARTCH AREA -->
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-1 control-label">Status :</label>
                        
                        <div class="col-lg-4">
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_1" type="checkbox" class="checkbox_search" value="1" checked>
                                <span class="fa fa-check"></span>ใช้งาน
                            </label>
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_2" type="checkbox" class="checkbox_search" value="2">
                                <span class="fa fa-check"></span>ระงับการใช้งาน
                            </label>
                        </div>

                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="input_search" class="col-lg-1 control-label">คำค้นหา :</label>
                        <div class="col-lg-4">
                            <input id="input_search" type="text" class="form-control input_search">
                        </div>
                    </div>
                     <hr>
                    <div class="form-group">
                        <div class="col-lg-3 col-lg-offset-1">
                            <button type="button" class="mb-sm btn btn-inverse" onclick="search_page()">
                                <em class="icon-magnifier"></em>
                                ค้นหา
                            </button>
                        </div>
                    </div>

                </div>
                </div>
            </div>


            
            <!-- TABLE AREA -->
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <!-- START table-responsive-->
                <div class="table-responsive" id="interface_table">
                    
                </div>
            
                <div class="panel-body" id="interface_pagination">
                    
                
                </div>

            </div>


            <!-- BTN AREA -->
            <div class="row">
                <div class="col-lg-12" align="center">
                    <button type="button" class="mb-sm btn btn-green" onclick="manage_user('add')">
                        <em class="icon-plus"></em>
                        เพิ่มผู้ใช้งาน
                    </button>
                </div>
            </div>





         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


   <?php include_once('../unity/tag_script.php');?>

   

    <script>

        
        $(document).ready(function(){
            search_page();
        })

        var url_search  = 'action_manage_user.php';
        var data_search;
        var page       = 0;
        var page_limit = 99;
        var data       = [];
        var tmp_data   = [];

        function search_page(){

            data_search = {
                word_search : $('#input_search').val(),
                status_1    : IS_CHECKED($('#status_1')),
                status_2    : IS_CHECKED($('#status_2')),

                offset      : page,
                method      : 1

            };

            GET_LIST_TABLE(data_search,url_search);

        }


        function manage_user(action_method,user_id){
            $('#content-wrapper').addClass('whirl double-up');
            $('#modal-manage-user').remove();
            $.post('action_manage_user.php',
            {
                method          : 2,
                action_method   : action_method,
                user_id         : user_id
            },function(data,textStatus,xhr){
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);

                $('body').append(data_j.modal);
                $('select').chosen();
                $('#modal-manage-user').modal('show');

            });

        }  



        function check_submit(method){

            check_null_input = true;
           $('input[required]').each(function(e){

                if($(this).val()==''){

                    check_null_input = false;
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบถ้วน','','warning');
                    return false;

                }

            });

            if(check_null_input == false){
                return; 
            }
            else if(validateEmail($('#user_email').val()) == false){
                swal('กรุณากรอกรูปแบบ Email ให้ถูกต้อง','','warning');
                return;
            }
            else{

                check_email_username(method);

            }




        }

        function check_email_username(method){


            $.post('action_manage_user.php',
            {   
                method              : 5,
                user_email          : $('#user_email').val(),
                user_email_old      : $('#user_email_old').val(),

                user_name           : $('#user_name').val(),
                user_name_old       : $('#user_name_old').val(),

            },function(data,textStatus,xhr){
                
                data_j = JSON.parse(data);
                
                if(data_j.email_status == 'FALSE'){
                    swal('Email นี้ถูกใช้งานแล้ว','','warning');
                }
                else if(data_j.username_status == 'FALSE'){
                    swal('Username นี้ถูกใช้งานแล้ว','','warning');
                }
                else{
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_user('+method+')');
                }


            });


        }   

       


        function submit_user(method){

            $('#tab_country').addClass('whirl double-up');
            $('#modal-manage-user').modal('hide');
            $.post('action_manage_user.php',
            {
                user_id         : $('#user_id').val(),
                user_fname      : $('#user_fname').val(),
                user_lname      : $('#user_lname').val(),
                user_nickname   : $('#user_nickname').val(),
                user_email      : $('#user_email').val(),
                user_tel        : $('#user_tel').val(),
                user_line_id    : $('#user_line_id').val(),
                user_address    : $('#user_address').val(),
                user_name       : $('#user_name').val(),
                user_password   : $('#user_password').val(),
                user_group_id   : $('#user_group_id').val(),
                user_status     : $('input[name="user_status"]:checked').val(),

                method          : method,

            },function(data,textStatus,xhr){

                console.log(data);

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                search_page();

            });


        }




        <?php 
            // เอาไปใส่ function กลาง file ../unity/navigation.php
            $comment = "
        function manage_password(user_id){
            $('#modal-manage-password').remove();
            $.post('action_manage_user.php',
            {   
                user_id : user_id,

                method  : 6

            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);
                
                $('body').append(data_j.modal);

                $('#modal-manage-password').modal('show');
            });
            
        }

            function check_submit_password(){
            if($('#user_password').val()==''){
                $('#user_password').focus();
                swal('กรุณากรอกข้อมูลให้ครบ');
                return;
            }
            else if($('#confirm_user_password').val()==''){
                $('#confirm_user_password').focus();
                swal('กรุณากรอกข้อมูลให้ครบ');
                return;
            }
            else if($('#user_password').val() != $('#confirm_user_password').val()){
                swal('กรุณากรอก Password ให้ตรงกัน');
                return;
            }
            else{
                submit_password();
            }
        }
        
        function submit_password(){
            $.post('action_manage_user.php',
            {   
                method          : 7,
                user_password   : $('#user_password').val(),
                user_id_password : $('#user_id_password').val(),

            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#modal-manage-password').modal('hide');
                
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
            });
        }
        ";
        ?>
        

        

    </script>

</body>

</html>