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
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_0" type="checkbox" class="checkbox_search" value="0">
                                <span class="fa fa-check"></span>รอการตรวจสอบ
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
                    <table id="" class="table table-bordered table-hover table-devgun">
                        <thead>
                            <tr>
                                <th  width="5%">
                                    #
                                </th>
                                <th width="10%">
                                    Status
                                </th>
                                <th c width="10%">
                                    Username
                                </th>
                                <th  width="15%">
                                    ชื่อ - สกุล
                                </th>
                                 <th width="15%">
                                    ชื่อบริษัท
                                </th>
                                <th  width="15%">
                                    E-mail
                                </th>
                                <th width="10%">
                                    เบอร์โทรศัพท์
                                </th>
                                <th  width="10%">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            
                <div class="panel-body" id="interface_pagination">
                    
                    <nav>
                        <ul class="pagination m0">
                            <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">«</span>
                            </a>
                            </li>
                            <li><a href="#">1</a>
                            </li>
                            <li><a href="#">2</a>
                            </li>
                            <li class="active"><a href="#">3</a>
                            </li>
                            <li><a href="#">4</a>
                            </li>
                            <li><a href="#">5</a>
                            </li>
                            <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">»</span>
                            </a>
                            </li>
                        </ul>
                    </nav>

                </div>

            </div>


            <!-- BTN AREA -->
            <div class="row">
                <div class="col-lg-12" align="center">
                    <button type="button" class="mb-sm btn btn-green" onclick="manage_agency('add')">
                        <em class="icon-plus"></em>
                        เพิ่มเอเจนซี่
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

        var url_search  = 'action_manage_agency.php';
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
                status_0    : IS_CHECKED($('#status_0')),

                offset      : page,
                method      : 1

            };

            GET_LIST_TABLE(data_search,url_search);

        }

        function manage_agency(method,agency_id){
            $('#content-wrapper').addClass('whirl double-up');
            $('#modal-manage-agency').remove();
            $.post('action_manage_agency.php',
            {
                method          : 2,
                action_method   : method,
                agency_id         : agency_id
            },function(data,textStatus,xhr){
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);

                $('body').append(data_j.modal);
                $(":file").filestyle('clear');
                $('select').chosen();
               $('#modal-manage-agency').modal('show');
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
            else if(validateEmail($('#agency_email').val()) == false){
                swal('กรุณากรอกรูปแบบ Email ให้ถูกต้อง','','warning');
                return;
            }
            else{



                check_email_agency(method);

            }




        }

        function check_email_agency(method){


            $.post('action_manage_agency.php',
            {   
                method              : 5,
                agency_email          : $('#agency_email').val(),
                agency_email_old      : $('#agency_email_old').val(),

                agency_name           : $('#agency_name').val(),
                agency_name_old       : $('#agency_name_old').val(),

            },function(data,textStatus,xhr){
                
                data_j = JSON.parse(data);
                
                if(data_j.email_status == 'FALSE'){
                    swal('Email นี้ถูกใช้งานแล้ว','','warning');
                }
                else if(data_j.username_status == 'FALSE'){
                    swal('Username นี้ถูกใช้งานแล้ว','','warning');
                  }
                else{
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_agency('+method+')');
                }


            });


        }   


       


        function submit_agency(method){

            $('#tab_country').addClass('whirl double-up');
            $('#modal-manage-agency').modal('hide');
            $.post('action_manage_agency.php',
            {
                agency_id               : $('#agency_id').val(),
                agency_fname            : $('#agency_fname').val(),
                agency_lname            : $('#agency_lname').val(),
                agency_nickname         : $('#agency_nickname').val(),
                agency_position         : $('#agency_position').val(),
                agency_email            : $('#agency_email').val(),
                agency_tel              : $('#agency_tel').val(),
                agency_lineid           : $('#agency_lineid').val(),
                agency_skype            : $('#agency_skype').val(),
                agency_name             : $('#agency_name').val(),
                agency_password         : $('#agency_password').val(),
                agency_status           : $('input[name="agency_status"]:checked').val(),
                agen_show           : $('input[name="show_status"]:checked').val(),
                agency_company_id     : $('#agency_company_id').val(),

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