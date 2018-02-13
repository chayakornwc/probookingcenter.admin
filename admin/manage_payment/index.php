<?php
    #include
    include_once('../unity/main_core.php');
    include_once('../unity/post_to_ws/post_to_ws.php');
    include_once('../unity/php_script.php');
?>

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
                <form class="form-horizontal">
                    
                    
                    <div class="form-group">
                        <label class="col-lg-1 control-label">Status :</label>

                        <div class="col-lg-9">
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_20" type="checkbox" value="20" checked>
                                <span class="fa fa-check"></span>มัดจำบางส่วน
                            </label>
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_25" type="checkbox" value="25" checked>
                                <span class="fa fa-check"></span>มัดจำเต็มจำนวน
                            </label>
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_30" type="checkbox" value="30" checked>
                                <span class="fa fa-check"></span>Full payment บางส่วน
                            </label>
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_35" type="checkbox" value="35" checked>
                                <span class="fa fa-check"></span>Full payment เต็มจำนวน
                            </label>
                        </div>
                       

                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-lg-1 control-label">สถานะ :</label>

                        <div class="col-lg-9">
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_payment_0" type="checkbox" value="0" checked>
                                <span class="fa fa-check"></span>รอตรวจสอบ
                            </label>
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_payment_1" type="checkbox" value="1" >
                                <span class="fa fa-check"></span>ผ่านการตรวจสอบ
                            </label>
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_payment_9" type="checkbox" value="9" >
                                <span class="fa fa-check"></span>ไม่ผ่านการตรวจสอบ
                            </label>
                        </div>
                       

                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="country_search" class="col-lg-1 control-label">ค้นหาประเทศ :</label>
                        <div class="col-lg-4">
                         <select name="country" id="country" class="form-control select_search">
                                <option value="">ทุกประเทศ</option>
                                <?php echo select_option_country(1);?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="input_search" class="col-lg-1 control-label">วันที่โอนเงิน :</label>
                        <div class="col-lg-4">

                            <div class="input-group">
                                 <input id="input_date_start" type="text" class="form-control date_search" >
                                 <span class="input-group-addon"><em class="fa fa-minus"></em></span>
                                 <input id="input_date_end" type="text" class="form-control date_search" >
                            </div>

                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label for="input_search" class="col-lg-1 control-label">คำค้นหา :</label>
                        <div class="col-lg-4">
                            <input id="input_search" type="text" class="form-control">
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

                </form>
                </div>
            </div>


            <!-- TABLE AREA -->
             <div class="panel panel-default">
                <div class="panel-heading"></div>
                <!-- START table-responsive-->
                <div class="panel-body" >
                    <div class="table-responsive" id="interface_table">
                        
                    </div>
                </div>
            
                <div class="panel-body" id="interface_pagination">
                    
                
                </div>

            </div>



             <!-- BTN AREA -->
            <!--<div class="row">
                <div class="col-lg-12" align="center">
                    <button type="button" class="mb-sm btn btn-green" onclick="location.href='manage_booking.php'">
                        <em class="icon-plus"></em>
                        เพิ่มการจอง
                    </button>
                </div>
            </div>-->
            
            
         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


    <?php include_once('../unity/tag_script.php');?>

    <script>

        $(document).ready(function(){
            search_page();
        });

        var url_search  = 'action_manage_payment.php';
        var data_search;
        var page       = 0;
        var page_limit = 99;
        var data       = [];
        var tmp_data   = [];
        var sdate_start = '';
        var sdate_end = '';

        function search_page(){
            
            if ($('#input_date_start').val() == '') {
                sdate_start = '1/1/1900'
            }else{
                sdate_start = $('#input_date_start').val();
            }
            if ($('#input_date_end').val() == '') {
                sdate_end = '1/1/9999'
            }else{
                sdate_end = $('#input_date_end').val();
            }
        
            data_search = {
                word_search : $('#input_search').val(),
                status_20    : IS_CHECKED($('#status_20')),
                status_25    : IS_CHECKED($('#status_25')),
                status_30    : IS_CHECKED($('#status_30')),
                status_35    : IS_CHECKED($('#status_35')),
                status_40    : IS_CHECKED($('#status_40')),

                status_payment_0    : IS_CHECKED($('#status_payment_0')),
                status_payment_1    : IS_CHECKED($('#status_payment_1')),
                status_payment_9    : IS_CHECKED($('#status_payment_9')),
                
                country_id   : $('#country').val(),
                date_start  : sdate_start,
                date_end    : sdate_end,

                offset      : page,
                method      : 1

            };

            GET_LIST_TABLE(data_search,url_search);

        }

        function IS_CHECKED_CUSTOME(element){
            if(element.is(':checked')){
                return element.val();
            }
            else{
                return 99999999;
            }
        }

        function approve_payment(pay_id,book_status,book_id,user_id){
             SWEET_ALERT_CONFIRM('ยืนยันการอนุมัติ','','submit_approve('+pay_id+','+book_status+','+book_id+','+user_id+')');
        }
        function submit_approve(pay_id,book_status,book_id,user_id){
            $('#content-wrapper').addClass('whirl double-up');
               $.post('action_manage_payment.php',
            {

                method                          : 13,
                pay_id                          : pay_id,
                book_status                     : book_status,
                user_id                         : user_id,
                book_id                         : book_id,

            },function(data,textStatus,xhr){
                 $('#content-wrapper').removeClass('whirl double-up');
                console.log(data);

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                search_page();
               
               
            });
        }
        function cancel_approve_payment(pay_id,book_id,user_id){
            $('#modal-manage-cancel-pay').remove();
            $.post('action_manage_payment.php',
            {
                method          : 22,
                pay_id          : pay_id,
                book_id          : book_id,
                user_id          : user_id
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);

                $('body').append(data_j.modal);
                $(":file").filestyle('clear');
                $('select').chosen();
                $('#modal-manage-cancel-pay').modal('show');
                CREATE_INPUT_DATE();
            });
        }
        function check_cancel_approve(pay_id,book_id,user_id){
            counter_input_null = true;
           
             $('textarea[required]').each(function(e){
                if($(this).val()==''){
                    counter_input_null = false;
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบถ้วน','','warning');
                    return false;

                }

            });
            if(counter_input_null == false){
                return;
            }
            else{
            SWEET_ALERT_CONFIRM('ยืนยันไม่อนุมัติ การชำระเงิน','','submit_cancel_approve('+pay_id+','+book_id+','+user_id+')');
            }
        }
           function submit_cancel_approve(pay_id,book_id,user_id){
  

            $('#modal-manage-cancel-pay').modal('hide');
                
             
            $('#content-wrapper').addClass('whirl double-up');
               $.post('action_manage_payment.php',
            {

                method                          : 14,
                pay_id                          : pay_id,
                remark_cancel                   : $('#txtremark_payment_cancel').val(),
                user_id                         : user_id,
                book_id                         : book_id,

            },function(data,textStatus,xhr){
                 $('#content-wrapper').removeClass('whirl double-up');
                console.log(data);

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);

                search_page();
               
               
            });
            
        }

    </script>


</body>

</html>