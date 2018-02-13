<?php include_once('../unity/main_core.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include_once('../unity/tag_head.php'); ?>

    <!-- lib -->
   <?php include_once('../unity/lib_heightchart.php');?>
   <!-- lib -->

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
			
		
    
            <div class="row">
                <div class="col-md-2">
                    <div class="panel b">
                    <div class="panel-heading bg-gray-lighter text-bold">รายการตั้งค่า</div>
                    <div class="list-group">
                        <a href="#tab_air" onclick="get_list_air()" data-toggle="tab" class="list-group-item">สายการบิน</a>
                        <a href="#tab_country" onclick="get_list_country()" data-toggle="tab" class="list-group-item">ประเทศ</a>
                        <a href="#tab_bankbook"  onclick="get_list_bankbook()" data-toggle="tab" class="list-group-item">สมุดธนาคาร</a>
                        <a href="#tab_agency"  onclick="get_list_agency()" data-toggle="tab" class="list-group-item">บริษัท Agency</a>
                        <a href="#tab_user_group" onclick="get_list_user_group()" data-toggle="tab" class="list-group-item">กลุ่มผู้ใช้งาน</a>
                        <a href="#tab_permissions_group" onclick="get_list_role()" data-toggle="tab" class="list-group-item">สิทธิ์เข้าใช้งาน</a>
                    </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="tab-content p0 b0">

                        <div id="tab_air" class="tab-pane active">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-lighter text-bold">สายการบิน</div>
                                <div class="panel-body">
                                    <div class="table-responsive" id="interface_table_air">

                                    </div>
                                </div>
                                <div class="panel-body">

                                    <div class="col-lg-12" align="center">
                                        <button type="button" class="mb-sm btn btn-green" onclick="manage_air('add')">
                                            <em class="icon-plus"></em>
                                            เพิ่มสายการบิน
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div id="tab_country" class="tab-pane ">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-lighter text-bold">ประเทศ</div>
                                <div class="panel-body">
                                    <div class="table-responsive" id="interface_table_country">
                                    
                                    

                                    </div>


                                </div>

                                <div class="panel-body">

                                    <div class="col-lg-12" align="center">
                                        <button type="button" class="mb-sm btn btn-green" onclick="manage_country('add')">
                                            <em class="icon-plus"></em>
                                            เพิ่มประเทศ
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div id="tab_bankbook" class="tab-pane">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-lighter text-bold">สมุดธนาคาร</div>
                                <div class="panel-body">
                                    <div class="table-responsive" id="interface_table_bankbook">



                                    </div>
                                </div>
                                <div class="panel-body">

                                    <div class="col-lg-12" align="center">
                                        <button type="button" class="mb-sm btn btn-green" onclick="manage_bankbook('add')">
                                            <em class="icon-plus"></em>
                                            เพิ่มสมุดธนาคาร
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        
                        <div id="tab_agency" class="tab-pane">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-lighter text-bold">บริษัท Agency</div>
                                <div class="panel-body">
                                    <div class="table-responsive" id="interface_table_agency">



                                    </div>
                                </div>
                                <div class="panel-body">

                                    <div class="col-lg-12" align="center">
                                        <button type="button" class="mb-sm btn btn-green" onclick="manage_agency('add')">
                                            <em class="icon-plus"></em>
                                            เพิ่มบริษัท Agency
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div id="tab_user_group" class="tab-pane ">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-lighter text-bold">กลุ่มผู้ใช้งาน</div>
                                <div class="panel-body">
                                    <div class="table-responsive" id="interface_table_usergroup">
                                        
                                    </div>

                                </div>

                                 <div class="panel-body">

                                    <div class="col-lg-12" align="center">
                                        <button type="button" class="mb-sm btn btn-green" onclick="manage_user_group('add')">
                                            <em class="icon-plus"></em>
                                            เพิ่มกลุ่มผู้ใช้งาน
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div id="tab_permissions_group" class="tab-pane ">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-lighter text-bold">สิทธิ์เข้าใช้งาน</div>
                                <div class="panel-body">

                                    <div class="table-responsive" id="interface_table_role">

                                      
                                    </div>


                                </div>
                            </div>
                        </div>
                   
                   
                    </div>
                </div>
            </div>




           


            
         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


   <?php include_once('../unity/tag_script.php');?>


     <script>

        $(document).ready(function(event){
            get_list_air();
        })
       
        // FUNCTION AIR

        function get_list_air(){

            $('#tab_air').addClass('whirl double-up');
            $.post('action_manage_air.php',{

                method : 1

            },function(data,textStatus,xhr){

                setTimeout(function() {
                    $('#tab_air').removeClass('whirl double-up');
                    
                }, 500);


                data_j = JSON.parse(data);

                $('#interface_table_air').html(data_j.table);
            });

        }

        function manage_air(action_method,air_id){

            $('#tab_air').addClass('whirl double-up');
            $('#modal-manage-air').remove();

            $.post('action_manage_air.php',{

                method          : 2,
                air_id          : air_id,
                action_method   : action_method

            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                
                $('#air_img').filestyle();

                setTimeout(function() {
                    $('#tab_air').removeClass('whirl double-up');
                    
                }, 500);

                $('#modal-manage-air').modal('show');
            });

        }

        function submit_air(method){

             $.post('action_manage_air.php',{

                
                air_id         : $('#air_id').val(),
                air_name       : $('#air_name').val(),
                air_url_img    : $('#air_url_img').val(),
                status         : $('input[name="air_status"]:checked').val(),
                
                method          : method

            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
            
                setTimeout(function() {
                    $('#tab_air').removeClass('whirl double-up');
                    
                }, 500);
                $('#modal-manage-air').modal('hide');

                get_list_air();
            });

           
        }

        function check_submit_air(method){

            if($('#air_name').val().trim() == ''){
                $('#air_name').focus();
                swal('กรุณากรอกชื่อสายการบิน','','warning');
                return;
            }


            var formData = new FormData();
            formData.append('air_img', $('#air_img')[0].files[0]);
            formData.append('air_img_old',$('#air_img_old').val());


            formData.append('air_name',$('#air_name').val());
            formData.append('air_name_old',$('#air_name_old').val());

            formData.append('method',5);

            $.ajax({
                url             : 'action_manage_air.php',
                type            : 'POST',
                data            : formData,
                processData     : false,  // tell jQuery not to process the data
                contentType     : false,  // tell jQuery not to set contentType
                success         : function(data) {
                    
                    data_j  = JSON.parse(data);
                    console.log(data_j);
                    if(data_j.status_air_name == 'TRUE'){

                        if(data_j.status_img == 'TRUE'){

                            $('#air_url_img').val(data_j.url_img);

                            SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_air('+method+')');

                        }
                        else{
                            swal('อัพโหลดรูปภาพไม่สำเร็จ กรุณาลองใหม่ภายหลัง','','warning');    
                        }

                    }
                    else{
                        $('#air_name').focus();
                        swal('ชื่อสายการบิน นี้ถูกใช้แล้ว','','warning');
                    }

                }
            });

        }




        // FUNCTION AIR
        

        // FUNCTION COUNTRY
        function get_list_country(){

            $('#tab_country').addClass('whirl double-up');
            $.post('action_manage_country.php',{

                method : 1

            },function(data,textStatus,xhr){

                setTimeout(function() {
                    $('#tab_country').removeClass('whirl double-up');
                    
                }, 500);


                data_j = JSON.parse(data);

                $('#interface_table_country').html(data_j.table);
            });

        }
        function manage_country(action_method,country_id){

            $('#tab_country').addClass('whirl double-up');
            $('#modal-manage-country').remove();


            $.post('action_manage_country.php',{

                method          : 2,
                country_id      : country_id,
                action_method   : action_method

            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);
                $('body').append(data_j.modal);

                setTimeout(function() {
                    $('#tab_country').removeClass('whirl double-up');
                    
                }, 500);

                $('#modal-manage-country').modal('show');


            });


        }

        function submit_country(method){

           $('#tab_country').addClass('whirl double-up');
           $('#modal-manage-country').modal('hide');

           $.post('action_manage_country.php',{

                method          : method,

                country_id      : $('#country_id').val(),
                country_name    : $('#country_name').val(),
                country_deposit : $('#country_deposit').val(),
                status          : $('input[name="country_status"]:checked').val()


            },function(data,textStatus,xhr){

        
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                get_list_country();
              
            });


        }

        function check_submit_country(method){
            if($('#country_name').val().trim() == ''){
                $('#country_name').focus();
                swal('กรุณากรอกชื่อประเทศ','','warning');
                return;
            }

            $.post('action_manage_country.php',{

                method                  : 5,
                country_name         : $('#country_name').val(),
                country_name_old     : $('#country_name_old').val(),

            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);

                if(data_j.status == 'TRUE'){
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_country('+method+')');
                }
                else{
                    swal('ชื่อประเทศ นี้ถูกใช้แล้ว','','warning');
                }

            });

        }
        // FUNCTION COUNTRY
        
        
        


        // FUNCTION USERGROUP
        function get_list_user_group(){
            
           $('#tab_user_group').addClass('whirl double-up');
            $.post('action_manage_usergroup.php',{

                method : 1

            },function(data,textStatus,xhr){

                setTimeout(function() {
                    $('#tab_user_group').removeClass('whirl double-up');
                    
                }, 500);


                data_j = JSON.parse(data);

                $('#interface_table_usergroup').html(data_j.table);
            });

        }
        function manage_user_group(action_method,group_id){

            $('#tab_user_group').addClass('whirl double-up');
            $('#modal-manage-user-group').remove();


            $.post('action_manage_usergroup.php',{

                method          : 2,
                group_id        : group_id,
                action_method   : action_method

            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);
                $('body').append(data_j.modal);

                setTimeout(function() {
                    $('#tab_user_group').removeClass('whirl double-up');
                    
                }, 500);

                $('#modal-manage-user-group').modal('show');


            });
            
            
        }
        function submit_user_group(method){

           $('#tab_user_group').addClass('whirl double-up');
           $('#modal-manage-user-group').modal('hide');

           $.post('action_manage_usergroup.php',{

                method          : method,
                user_group_id   : $('#user_group_id').val(),
                user_group_name : $('#user_group_name').val()

            },function(data,textStatus,xhr){

        
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                get_list_user_group();
              
            });


        }
        function check_submit_user_group(method){

            if($('#user_group_name').val().trim() == ''){
                $('#user_group_name').focus();
                swal('กรุณากรอกชื่อกลุ่มผู้ใช้งาน','','warning');
                return;
            }

            $.post('action_manage_usergroup.php',{

                method                  : 5,
                user_group_name         : $('#user_group_name').val(),
                user_group_name_old     : $('#user_group_name_old').val(),

            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);

                if(data_j.status == 'TRUE'){
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_user_group('+method+')');
                }
                else{
                    swal('ชื่อกลุ่มผู้ใช้งาน นี้ถูกใช้แล้ว','','warning');
                }

            });

        }
        // FUNCTION USERGROUP

        

        // FUNCTION Agency
        function get_list_agency(){
            
            $('#tab_agency').addClass('whirl double-up');
            
            $.post('action_manage_com_agency.php',{

                method : 1

            },function(data,textStatus,xhr){

                setTimeout(function() {
                    $('#tab_agency').removeClass('whirl double-up');
                    
                }, 500);


                data_j = JSON.parse(data);

                $('#interface_table_agency').html(data_j.table);
            });


        }
        function manage_agency(action_method,agen_com_id){

            $('#tab_agency').addClass('whirl double-up');
            $('#modal-manage-agency').remove();
            $.post('action_manage_com_agency.php',{
                    method          : 2,
                    agen_com_id     : agen_com_id,
                    action_method   : action_method
            },function(data,textStatus,xhr){

                 setTimeout(function() {
                    $('#tab_agency').removeClass('whirl double-up');
                    
                }, 500);

                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                $(":file").filestyle('clear');
                $('select').chosen();
                $('#modal-manage-agency').modal('show');

            });


        }

        function submit_agency(method){


            $('#tab_agency').addClass('whirl double-up');
            $('#modal-manage-agency').modal('hide');

           $.post('action_manage_com_agency.php',{

                method                  : method,
                agen_com_id             : $('#agen_com_id').val(),
                agen_com_name           : $('#agen_com_name').val(),
                agen_com_tel            : $('#agen_com_tel').val(),
                agen_com_fax            : $('#agen_com_fax').val(),
                agen_com_ttt_on         : $('#agen_com_ttt_on').val(),

                agen_com_ttt_url_img : $('#agen_com_ttt_img_old').attr('url'),
                agen_com_logo_img   : $('#agen_com_logo_img_old').attr('url'),

                
                agen_com_address1        : $('#agen_com_address1').val(),
                agen_com_address2        : $('#agen_com_address2').val(),
                agen_remark             : $('#agen_remark').val(),
                agency_status           : $('input[name="agency_status"]:checked').val(),
                agen_status             : $('input[name="agen_status"]:checked').val(),

            },function(data,textStatus,xhr){

        
                data_j = JSON.parse(data);
                console.log(data_j);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                get_list_agency();
              
            });

        }

        function check_submit_agency(method){

            counter_input_null = true;
            $('.input_required').each(function(event){

                if($(this).val()==''){
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบถ้วน');

                    counter_input_null = false;
                    return false;
                }

            });
            
            if(counter_input_null == false){
                return;
            }
            else{
                
                 $.post('action_manage_com_agency.php',{

                method                  : 5,
                agen_com_name           : $('#agen_com_name').val(),
                agen_com_name_old       : $('#agen_com_name_old').val(),

                },function(data,textStatus,xhr){
  
                data_j = JSON.parse(data);
                
                if(data_j.status == 'FALSE'){
                    swal('ชื่อบริษัท นี้ถูกใช้งานแล้ว','','warning');
                }
               
                else{


                    var formData = new FormData();

                    formData.append('method',6);


                    formData.append('agen_com_ttt_img_old', $('#agen_com_ttt_img_old').attr('url'));
                    formData.append('agen_com_logo_img_old', $('#agen_com_logo_img_old').attr('url'));

                    if($('#agen_com_ttt_url_img').val()!=''){
                        formData.append('agen_com_ttt_url_img', $('#agen_com_ttt_url_img')[0].files[0]);
                    }
                    if($('#agen_com_logo_url_img').val()!=''){
                        formData.append('agen_com_logo_url_img', $('#agen_com_logo_url_img')[0].files[0]);
                    }

                    $.ajax({
                        url             : 'action_manage_com_agency.php',
                        type            : 'POST',
                        data            : formData,
                        processData     : false,  // tell jQuery not to process the data
                        contentType     : false,  // tell jQuery not to set contentType
                        success         : function(data) {

                            $('#content-wrapper').removeClass('whirl double-up');
                            $('input[type="file"]').prop('disabled',false);
                            data_j  = JSON.parse(data);
                            
                            $('#agen_com_ttt_img_old').attr('href',CHECK_HREF_IMG_NULL(data_j.img_agency_company_url_ttt));
                            $('#agen_com_logo_img_old').attr('href',CHECK_HREF_IMG_NULL(data_j.img_agency_company_url_logo));

                            $('#agen_com_ttt_img_old').attr('url',(data_j.img_agency_company_url_ttt));
                            $('#agen_com_logo_img_old').attr('url',(data_j.img_agency_company_url_logo));


                            SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_agency('+method+')');
                        }
                    });

                }


            });


            }   

        }

        // FUNCTION BANKBOOK
        function get_list_bankbook(){
            
            $('#tab_bankbook').addClass('whirl double-up');
            
            $.post('action_manage_bankbook.php',{

                method : 1

            },function(data,textStatus,xhr){

                setTimeout(function() {
                    $('#tab_bankbook').removeClass('whirl double-up');
                    
                }, 500);


                data_j = JSON.parse(data);

                $('#interface_table_bankbook').html(data_j.table);
            });


        }
        function manage_bankbook(action_method,bankbook_id){

            $('#tab_bankbook').addClass('whirl double-up');
            $('#modal-manage-bankbook').remove();
            $.post('action_manage_bankbook.php',{
                    method          : 2,
                    bankbook_id     : bankbook_id,
                    action_method   : action_method
            },function(data,textStatus,xhr){

                 setTimeout(function() {
                    $('#tab_bankbook').removeClass('whirl double-up');
                    
                }, 500);

                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                $('#modal-manage-bankbook').modal('show');

            });


        }

        function submit_bankbook(method){


            $('#tab_bankbook').addClass('whirl double-up');
            $('#modal-manage-bankbook').modal('hide');

           $.post('action_manage_bankbook.php',{

                method              : method,
                bankbook_id         : $('#bankbook_id').val(),
                bank_name           : $('#bank_name').val(),
                bankbook_branch     : $('#bankbook_branch').val(),
                bankbook_code       : $('#bankbook_code').val(),
                bankbook_name       : $('#bankbook_name').val(),
                bankbook_status     : $('input[name="bankbook_status"]:checked').val(),

            },function(data,textStatus,xhr){

        
                data_j = JSON.parse(data);
                console.log(data_j);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                get_list_bankbook();
              
            });

        }

        function check_submit_bankbook(method){

            counter_input_null = true;
            $('.input_required').each(function(event){

                if($(this).val()==''){
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบถ้วน');

                    counter_input_null = false;
                    return false;
                }

            });
            
            if(counter_input_null == false){
                return;
            }
            else{
                
                 $.post('action_manage_bankbook.php',{

                method                  : 5,
                bankbook_code           : $('#bankbook_code').val(),
                bankbook_code_old       : $('#bankbook_code_old').val(),

                },function(data,textStatus,xhr){

                    data_j = JSON.parse(data);

                    if(data_j.status == 'TRUE'){
                        SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_bankbook('+method+')');
                    }
                    else{
                        swal('เลขที่บัญชี  นี้ถูกใช้แล้ว','','warning');
                    }

                });

            }

        }
        // FUNCTION BANKBOOK


        // FUNCTION ROLE
        function get_list_role(){
            
             $('#tab_permissions_group').addClass('whirl double-up');
            
            $.post('action_manage_role.php',{

                method : 1

            },function(data,textStatus,xhr){

                setTimeout(function() {
                    $('#tab_permissions_group').removeClass('whirl double-up');
                    
                }, 500);


                data_j = JSON.parse(data); 

                $('#interface_table_role').html(data_j.table);
            });

        }


        function upload_role(group_id){
            
             $('#tab_permissions_group').addClass('whirl double-up');
            
            $.post('action_manage_role.php',{

                method      : 2,
                group_id    : group_id,
                menu_1      : $('input[name="menu_1_'+group_id+'"]:checked').val(),
                menu_2      : is_checked_role($('#menu_2_'+group_id)),
                menu_3      : is_checked_role($('#menu_3_'+group_id)),
                menu_4      : is_checked_role($('#menu_4_'+group_id)),
                menu_5      : is_checked_role($('#menu_5_'+group_id)),
                menu_6      : is_checked_role($('#menu_6_'+group_id)),
                menu_7      : is_checked_role($('#menu_7_'+group_id)),
                menu_8      : is_checked_role($('#menu_8_'+group_id)),
                menu_9      : is_checked_role($('#menu_9_'+group_id)),
                menu_10      : is_checked_role($('#menu_10_'+group_id)),
                menu_11      : is_checked_role($('#menu_11_'+group_id)),
                menu_12      : is_checked_role($('#menu_12_'+group_id)),
                menu_13      : is_checked_role($('#menu_13_'+group_id)),
                menu_14      : is_checked_role($('#menu_14_'+group_id)),
                menu_15      : is_checked_role($('#menu_15_'+group_id)),
                menu_17      : is_checked_role($('#menu_17_'+group_id)),
                menu_18      : is_checked_role($('#menu_18_'+group_id)),
           //     menu_16      : is_checked_role($('#menu_16_'+group_id)),


            },function(data,textStatus,xhr){

                setTimeout(function() {
                    $('#tab_permissions_group').removeClass('whirl double-up');
                    
                }, 500);


                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
                
            });

        }

        function is_checked_role(element){
            if(element.is(':checked')){
                return element.val();
            }
            else{
                return 0;
            }
        }
        // FUNCTION ROLE
       
     </script>

</body>

</html>