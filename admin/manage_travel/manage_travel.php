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
               <font id="">จัดการทัวร์</font>
               <small data-localize="dashboard.WELCOME"></small>
            </div>
            

            <div class="row">
                

                <!-- series -->
                <div class="col-lg-3">

                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            ข้อมูล ซี่รีย์
                        </div>

                        <div class="panel-body">

                            <form role="form">

                                <input type="hidden" value="3" id="method" name="method">
                                <input type="hidden" value="" id="ser_id" name="ser_id">

                                <div class="form-group">
                                    <label>ชื่อซีรีย์ : </label>
                                    <input type="text" class="form-control" id="series_name" name="series_name">
                                </div>

                                <div class="form-group">
                                    <label>Code : </label>
                                    <input type="text" class="form-control" id="series_code" name="series_code">
                                </div>
                                <div class="form-group">
                                    <label>รายละเอียด: </label>
                                    <textarea  class="form-control" id="series_description" name="series_description" rows="5" cols=""></textarea>
                                </div>
                                <div class="form-group">
                                    <label>เส้นทางไป-กลับ : </label>
                                    <input type="text" class="form-control" id="series_route" name="series_route">
                                </div>
                                <div class="form-group">
                                    <label>การแสดงผล : </label>
                                    <select name="series_show" id="series_show" class="form-control">
                                        
                                        <option value="0">ทั่วไป</option>
                                        <option value="1">โปรโมท</option>
                                        <option value="2">โปรโมชั่น</option>
                                        <option value="3">Hot sale</option>
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>แสดงผลเพิ่มเติม : </label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="ser_is_promote" id="ser_is_promote"> โปรโมท
                                        </label>
                                        <label style="margin-left:10mm;">
                                            <input type="checkbox" name="ser_is_recommend" id="ser_is_recommend"> Recommend
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>ประเทศ : </label>
                                    <select name="country" id="country" class="form-control">
                                        
                                        <?php echo select_option_country();?>
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>เงินมัดจำ/ต่อนั่ง : </label>
                                    <input type="text" class="form-control input_num" id="series_deposit" name="series_deposit">
                                </div>
                                <div class="form-group">
                                    <label>สายการบิน : </label>
                                    <select name="airline" id="airline" class="form-control">
                                            
                                             <?php echo select_option_airline();?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label"  style="padding-left: 0px;padding-right: 0px;">Flight การบิน ขาไป : </label>
                                     <div class="col-sm-4" style="padding-left: 0px;padding-right: 0px;">
                                    <input type="text" class="form-control" id="ser_go_flight_code" name="ser_go_flight_code" placeholder="Flight NO">
                                </div>
                                     <div class="col-sm-4"  style="padding-left: 0px;padding-right: 0px;">
                                    <input type="text" class="form-control" id="ser_go_route" name="ser_go_route" placeholder="เส้นทาง">
                                </div>
                                 <div class="col-sm-4" style="padding-left: 0px;padding-right: 0px;">
                                    <input type="text" class="form-control" id="ser_go_time" name="ser_go_time" placeholder="เวลา">
                                </div>
                                </div>
                                    <div class="form-group">
                                    <label class="col-sm-12 control-label"  style="padding-left: 0px;padding-right: 0px;">Flight การบิน ขากลับ : </label>
                                      <div class="col-sm-4" style="padding-left: 0px;padding-right: 0px;">
                                    <input type="text" class="form-control" id="ser_return_flight_code" name="ser_return_flight_code" placeholder="Flight NO">
                                </div>
                                     <div class="col-sm-4"  style="padding-left: 0px;padding-right: 0px;">
                                    <input type="text" class="form-control" id="ser_return_route" name="ser_return_route" placeholder="เส้นทาง">
                                </div>
                                 <div class="col-sm-4" style="padding-left: 0px;padding-right: 0px;">
                                    <input type="text" class="form-control" id="ser_return_time" name="ser_return_time" placeholder="เวลา">
                                </div>
                                </div>
                               
                               
                               
                                <hr>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Status : </label>
                                    <div class="col-sm-8">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="series_status" class="status_1" value="1" id = "series_status_1">
                                                <span class="fa fa-circle"></span>
                                                    ใช้งาน
                                                </label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="series_status" class="status_2" value="2" id = "series_status_2">
                                                <span class="fa fa-circle"></span>
                                                    ระงับการใช้งาน
                                                </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>


                                <div class="form-group">
                                    <label>ไฟล์โปรแกรมทัวน์ Word  : </label>
                                    <div class="input-group">
                                 
                                        <input type="file" data-classbutton="btn btn-default" id="series_file_word" name="series_file_word" data-classinput="form-control inline" class="form-control filestyle" accept=".doc, .docx">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" id="series_file_word_old" class="btn btn-default btn-primary" url="" target="_blank">ดาวน์โหลด</a>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>ไฟล์โปรแกรมทัวน์ PDF  :</label>
                                    <div class="input-group">
                                        <input type="file" data-classbutton="btn btn-default" id="series_file_pdf" name="series_file_pdf" data-classinput="form-control inline" class="form-control filestyle" accept="application/pdf">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" id="series_file_pdf_old" class="btn btn-default btn-primary" url="" target="_blank">ดาวน์โหลด</a>
                                        </span>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group">
                                    <label>รูปที่ 1  :</label>
                                    <div class="input-group">
                                        <input type="file" data-classbutton="btn btn-default" id="series_img_1" name="series_img_1" data-classinput="form-control inline" class="form-control filestyle" accept="image/*">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" id="series_img_1_old" class="btn btn-default btn-primary" url="" target="_blank">ดาวน์โหลด</a>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>รูปที่ 2  :</label>
                                    <div class="input-group">
                                        <input type="file" data-classbutton="btn btn-default" id="series_img_2" name="series_img_2" data-classinput="form-control inline" class="form-control filestyle" accept="image/*">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" id="series_img_2_old" class="btn btn-default btn-primary" url="" target="_blank" >ดาวน์โหลด</a>
                                        </span>
                                    </div>
                                </div>
                                <!--
                                <div class="form-group">
                                    <label>รูปที่ 3  :</label>
                                    <div class="input-group">
                                        <input type="file" data-classbutton="btn btn-default" id="series_img_3" name="series_img_3" data-classinput="form-control inline" class="form-control filestyle" accept="image/*">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" id="series_img_3_old" class="btn btn-default btn-primary" url="" target="_blank" >ดาวน์โหลด</a>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>รูปที่ 4  :</label>
                                    <div class="input-group">
                                        <input type="file" data-classbutton="btn btn-default" id="series_img_4" name="series_img_4" data-classinput="form-control inline" class="form-control filestyle" accept="image/*">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" id="series_img_4_old" class="btn btn-default btn-primary" url="" target="_blank">ดาวน์โหลด</a>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>รูปที่ 5  :</label>
                                    <div class="input-group">
                                        <input type="file" data-classbutton="btn btn-default" id="series_img_5" name="series_img_5" data-classinput="form-control inline" class="form-control filestyle" accept="image/*">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" id="series_img_5_old" class="btn btn-default btn-primary" url="" target="_blank">ดาวน์โหลด</a>
                                        </span>
                                    </div>
                                </div>
                                -->
                            </form>

                        </div>

                        <div class="panel-body" align="center">
                            <button type="button" class="btn btn-primary" onclick="check_submit_series()">
                                <em class="icon-cursor"></em>
                                บันทีก
                            </button>
                        </div>
                        

                    </div>
                    


                </div>

                <div class="col-lg-9">

                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            ข้อมูลพีเรียด
                        </div>
                        
                        <div class="panel-body">
                            <div class="table-responsive" id="interface_table"> 
                              
                            </div>
                        </div>

                        <div class="panel-body" align="center" id="div_btn_period">

                            
                        </div>

                    </div>

                </div>

            </div>

            <!-- BTN AREA -->
            <div class="row">
                <div class="col-lg-12" align="center">
                    <button type="button" class="mb-sm btn btn-warning" onclick="location.href='../manage_travel'">
                        <em class="icon-action-undo"></em>
                        ย้อนกลับ
                    </button>
                </div>
            </div>



         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


   <?php include_once('../unity/tag_script.php');?> 



   <script>

        var series_id = '<?php echo $_REQUEST["series_id"];?>';

        $(document).ready(function(event){

            $('#country').change(function(e){

                $('#series_deposit').val($(this).find('option:selected').attr('deposit'));

            });

            
            get_data_series(series_id);
            

        });

        // SERIES

        function check_submit_series(){

            if($('#series_name').val()==''){
                $('#series_name').focus();
                swal('กรุณากรอกข้อมูลให้ครบ','','warning');
                return;
            }
            else if($('#series_code').val()==''){
                $('#series_code').focus();
                swal('กรุณากรอกข้อมูลให้ครบ','','warning');
                return;
            }
            else if($('#series_route').val()==''){
                $('#series_route').focus();
                swal('กรุณากรอกข้อมูลให้ครบ','','warning');
                return;
            }
            else if($('#series_deposit').val()==''){
                $('#series_deposit').focus();
                swal('กรุณากรอกข้อมูลให้ครบ','','warning');
                return;
            }

            $('#content-wrapper').addClass('whirl double-up');
            
            var formData = new FormData();

            formData.append('series_file_word_old',$('#series_file_word_old').attr('url'));
            formData.append('series_file_pdf_old',$('#series_file_pdf_old').attr('url'));
            formData.append('series_img_1_old',$('#series_img_1_old').attr('url'));
            formData.append('series_img_2_old',$('#series_img_2_old').attr('url'));

            if($('#series_file_word').val()!=''){
                formData.append('series_file_word', $('#series_file_word')[0].files[0]);
            }

            if($('#series_file_pdf').val()!=''){
                formData.append('series_file_pdf', $('#series_file_pdf')[0].files[0]);
            }

            if($('#series_img_1').val()!=''){
                formData.append('series_img_1', $('#series_img_1')[0].files[0]);
            }

            if($('#series_img_2').val()!=''){
                formData.append('series_img_2', $('#series_img_2')[0].files[0]);
            }

            formData.append('method',5);

            $('input[type="file"]').prop('disabled',true);
            
            $.ajax({
                url             : 'action_manage_travel.php',
                type            : 'POST',
                data            : formData,
                processData     : false,  // tell jQuery not to process the data
                contentType     : false,  // tell jQuery not to set contentType
                success         : function(data) {
                    $('#content-wrapper').removeClass('whirl double-up');
                    $('input[type="file"]').prop('disabled',false);
                    data_j  = JSON.parse(data);
                    
                    $('#series_file_word_old').attr('url',data_j.series_file_word);
                    $('#series_file_pdf_old').attr('url',data_j.series_file_pdf);
                    $('#series_img_1_old').attr('url',data_j.series_img_1);
                    $('#series_img_2_old').attr('url',data_j.series_img_2);
                 
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_series()');
                }
            });


        }

        function submit_series(){

            var recommend = 0;
            var promote = 0;

            if( $('#ser_is_recommend').is(':checked') ){
                recommend = 1;
            }
            if( $('#ser_is_promote').is(':checked') ){
                promote = 1;
            }
          
            $.post('action_manage_travel.php',
            {
                method              : $('#method').val(),
                series_id           : $('#ser_id').val(),

                series_route        : $('#series_route').val(),
                ser_show            : $('#series_show').val(),
                ser_is_recommend    : recommend,
                ser_is_promote      : promote,
                series_file_word    : $('#series_file_word_old').attr('url'),
                series_file_pdf     : $('#series_file_pdf_old').attr('url'),
                series_img_1        : $('#series_img_1_old').attr('url'),
                series_img_2        : $('#series_img_2_old').attr('url'),

                series_name         : $('#series_name').val(),
                series_code         : $('#series_code').val(),
                series_description  : $('#series_description').val(), 
                country             : $('#country').val(),
                series_deposit      : $('#series_deposit').val(),
                airline             : $('#airline').val(),

                ser_go_flight_code             : $('#ser_go_flight_code').val(),
                ser_go_route             : $('#ser_go_route').val(),
                ser_go_time             : $('#ser_go_time').val(),
                ser_return_flight_code             : $('#ser_return_flight_code').val(),
                ser_return_route             : $('#ser_return_route').val(),
                ser_return_time             : $('#ser_return_time').val(),
                
                status              : $('input[name="series_status"]:checked').val(),


            },function(data,textStatus,xhr){

                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
                $(":file").filestyle('clear');
                console.log(data_j);
                get_data_series(data_j.results);
            });


        }


        function get_data_series(series_id){
            $('#content-wrapper').addClass('whirl double-up');
            $.post('action_manage_travel.php',
            {

                method  : 2,
                ser_id  : series_id

            },function(data,textStatus,xhr){
                get_list_period(series_id);
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);

                console.log( data_j );

                $('#method').val(data_j.method);
                $('select').chosen('destroy');
                if(data_j.method==4){

                    $('#ser_id').val(data_j.ser_id);
                   

                    $('#series_name').val(data_j.ser_name);
                    $('#series_code').val(data_j.ser_code);
                    $('#series_description').val(data_j.series_description);
                    
                    $('#ser_go_flight_code').val(data_j.ser_go_flight_code);
                    $('#ser_go_route').val(data_j.ser_go_route);
                    $('#ser_go_time').val(data_j.ser_go_time);
                    $('#ser_return_flight_code').val(data_j.ser_return_flight_code);
                    $('#ser_return_route').val(data_j.ser_return_route);
                    $('#ser_return_time').val(data_j.ser_return_time);


                    $('#country').val(data_j.country_id).trigger('change');
                    $('#series_deposit').val(data_j.ser_deposit);

                    $('#series_route').val(data_j.ser_route);
                    $('#airline').val(data_j.air_id).trigger('change');

                    $('#series_show').val(data_j.ser_show).trigger('change');

                    if( data_j.ser_is_promote == 1 ){
                        $('#ser_is_promote').prop('checked', true);
                    }
                    if( data_j.ser_is_recommend == 1 ){
                        $('#ser_is_recommend').prop('checked', true);
                    }

                    $('#series_file_word_old').attr('url',data_j.ser_url_word);
                    $('#series_file_pdf_old').attr('url',data_j.ser_url_pdf);
                    $('#series_img_1_old').attr('url',data_j.ser_url_img_1);
                    $('#series_img_2_old').attr('url',data_j.ser_url_img_2);

                    $('#series_file_word_old').attr('href',data_j.ser_href_word);
                    $('#series_file_pdf_old').attr('href',data_j.ser_href_pdf);
                    $('#series_img_1_old').attr('href',data_j.ser_href_img_1);
                    $('#series_img_2_old').attr('href',data_j.ser_href_img_2);
                   
                   if (data_j.status == '1'){
                      $("#series_status_1").prop("checked", true);
                      $("#series_status_2").prop("checked", false);
                   }else {
                      $("#series_status_1").prop("checked", false);
                      $("#series_status_2").prop("checked", true);
                   }

                }
                else{
                     $('#country').trigger('change');
                      $("#series_status_1").prop("checked", true);
                      $("#series_status_2").prop("checked", false);
                }
                $('select').chosen();

            });

        }

        // SERIES


        // PERIOD

        function get_list_period(series_id){
            $('#content-wrapper').addClass('whirl double-up');
            $.post('action_manage_travel.php',
            {
                method      : 6,
                series_id   : series_id
            },function(data,textStatus,xhr){
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);

                $('#interface_table').html(data_j.interface_table);
                $('#div_btn_period').html(data_j.btn_add_period);
            }); 
            
        }
       function get_bus(series_id){
            $('#content-wrapper').addClass('whirl double-up');
            $.post('action_manage_travel.php',
            {
                method      : 6,
                series_id   : series_id
            },function(data,textStatus,xhr){
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);

                $('#interface_table').html(data_j.interface_table);
                $('#div_btn_period').html(data_j.btn_add_period);
            }); 
            
        }
        function manage_period(series_id,period_id,action_method){
            
            $('#content-wrapper').addClass('whirl double-up');
            $('#modal-manage-period').remove(); 

            $.post('action_manage_travel.php',
            {   
                series_id           : series_id,
                period_id           : period_id,
                action_method       : action_method,
                method              : 7

            },function(data,textStatus,xhr){
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                 $(":file").filestyle('clear');
                $('#modal-manage-period').modal('show');
                CREATE_INPUT_DATE();
                
            });
        
        }


 



        function check_submit_period(series_id,method){
            counter_input_null = true;
            $('.period_requried').each(function(e){

                if($(this).val()==''){
                    $(this).focus();
                    swal('กรุณากรอกข้อมูลให้ครบ','','warning');
                    counter_input_null = false;
                    return false;
                }
            });

            bus_qty = 0;
            $('input[name="bus_no[]"]').each(function(){

                bus_qty += parseInt($(this).val());

                

            });



            
            if(counter_input_null == false){
                return;
            }
            else if(bus_qty != parseInt($('#per_qty_seats').val())){
                swal('กรุณากรอกข้อมูลให้เท่ากับ จำนวนที่นั่งเท่านั้น','','warning');
                $('input[name="bus_no[]"]:first').focus();
                return;
            }
            else{
            $('#content-wrapper').addClass('whirl double-up');
                
            var formData = new FormData();

            formData.append('per_url_word_old',$('#per_url_word_old').attr('url'));
            formData.append('per_url_pdf_old',$('#per_url_pdf_old').attr('url'));

            if($('#per_url_word').val()!=''){
                formData.append('per_url_word', $('#per_url_word')[0].files[0]);
            }

            if($('#per_url_pdf').val()!=''){
                formData.append('per_url_pdf', $('#per_url_pdf')[0].files[0]);
            }


         
            formData.append('method',10);

            $('input[type="file"]').prop('disabled',true);
            
            $.ajax({
                url             : 'action_manage_travel.php',
                type            : 'POST',
                data            : formData,
                processData     : false,  // tell jQuery not to process the data
                contentType     : false,  // tell jQuery not to set contentType
                success         : function(data) {
                   
                    $('input[type="file"]').prop('disabled',false);
                    data_j  = JSON.parse(data);
                    
                    $('#per_url_word_old').attr('url',data_j.per_url_word);
                    $('#per_url_pdf_old').attr('url',data_j.per_url_pdf);

                    

                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_period('+series_id+','+method+')');
                }
            });

            }

        }

        function submit_period(series_id,method){

            $('#modal-manage-period').modal('hide');

            bus_order_array = [];
            bus_qty_array   = [];

            order = 1;
            
            $('input[name="bus_no[]"]').each(function(){

                bus_order_array.push(order);
                bus_qty_array.push($(this).val());

                order++;

            });

            var on_fire = 0;
            if( $('#per_on_fire').is(':checked') ){
                on_fire = 1;
            }

            $.post('action_manage_travel.php',
            {

                method              : method,
                series_id           : series_id,
                period_id           : $('#per_id').val(),
                period_date_start   : $('#period_date_start').val(),
                period_date_end     : $('#period_date_end').val(),
                per_qty_seats       : $('#per_qty_seats').val(),

                period_price_1      : $('#period_price_1').val(),
                period_price_2      : $('#period_price_2').val(),
                period_price_3      : $('#period_price_3').val(),
                period_price_4      : $('#period_price_4').val(),
                period_price_5      : $('#period_price_5').val(),
                single_charge       : $('#single_charge').val(),
                status              : $('input[name="travel_status"]:checked').val(), 
                per_on_fire         : on_fire,

                per_url_word        : $('#per_url_word_old').attr('url'),
                per_url_pdf         : $('#per_url_pdf_old').attr('url'),

                bus_order_arr       : bus_order_array,
                bus_qty_arr       : bus_qty_array,

                period_commission_agency            : $('#period_commission_agency').val(),
                period_commission_company_agency    : $('#period_commission_company_agency').val(),
                period_cost                         : $('#period_cost').val(),
                period_expenses                     : $('#period_expenses').val()



            },function(data,textStatus,xhr){
             $('#content-wrapper').removeClass('whirl double-up');
                
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
                get_data_series(series_id)
            });


        }

        function add_bus(){
            $('#btn_add_bus').prop('disabled',true);
            $.post('action_manage_travel.php',
            {
                order   : ($('input[name="bus_no[]"]').length)+1,
                method  : 11
            },function(data,textStatus,xhr){
                $('#btn_add_bus').prop('disabled',false);
                data_j = JSON.parse(data);

                $('#contant_bus').append(data_j.bus_input);

            });
        }

        $('body').on('click','.del_bus',function(){

            $(this).parent().parent().parent().parent().remove();

            order = 1;
            $('.bus_order').each(function(e){

                $(this).text('Bus '+order +' : ');

                order++;
            });



        })
        function manage_upload(per_id){
            $('#modal-manage-uploadfile').remove();

            $.post('action_manage_travel.php',
            {
                method      : 20,
                per_id     : per_id
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                $('#modal-manage-uploadfile').modal('show');
                $(":file").filestyle('clear');
            });
            
            
        }
        function check_upload_file_cost(){
            if($('#file_cost').val() == ''){
                swal('กรุณาเลือกไฟล์');
            }else{
            SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','upload_file_cost()');
            }
        }
        function upload_file_cost(){
            
                var formData = new FormData();
                formData.append('per_id',$('#per_id').val());
                formData.append('file_cost', $('#file_cost')[0].files[0]);
                formData.append('method',21);

                $('#modal-manage-uploadfile').modal('hide');
                $('#content-wrapper').addClass('whirl double-up');
                $.ajax({
                    url : 'action_manage_travel.php',
                    type : 'POST',
                    data : formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(data) {
                    $('#content-wrapper').removeClass('whirl double-up');
                        data_j = JSON.parse(data);
                        SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
                        get_cost_file();
                        /*setTimeout(function() {
                            $('#content-wrapper').removeClass('whirl double-up');
                            
                        }, 500);*/
                    }
                });

         
            


        }
        function get_cost_file(){

            $.post('action_manage_travel.php',
            {   
                per_id              : $('#per_id').val(),
                method              : 22

            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);

                
                $('#per_cost_file').attr('url',data_j.per_cost_file);
                $('#per_cost_file').attr('href',data_j.per_cost_file);

            });

        }
        // PERIOD
   </script>

</body>

</html>