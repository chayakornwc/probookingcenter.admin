<?php include_once('../unity/main_core.php');?>
<?php include_once('../unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../unity/php_script.php');?>



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
               <font id="">ดูรายละเอียดพีเรียด</font>
               <small data-localize="dashboard.WELCOME"></small>
            </div>

            <div class="row">
                
                <div class="col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading bg-gray-lighter"><u>ข้อมูลซี่รีย์</u></div>
                        <div class="panel-body">
                            <table class="table bb">
                                <tbody>
                                    <tr>
                                        <td width="50%">
                                            <strong>ชื่อซีรีย์</strong>
                                        </td>
                                        <td><font id="ser_name"></font></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Code</strong>
                                        </td>
                                        <td ><font id="ser_code"></font></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>ประเทศ</strong>
                                        </td>
                                        <td ><font id="ser_country"></font></td>
                                    </tr>
                                      <tr>
                                        <td>
                                            <strong>สายการบิน</strong>
                                        </td>
                                        <td ><font id="air_name"></font></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="panel-heading bg-gray-lighter"><u>ข้อมูลพีเรียด</u></div>
                            <table class="table bb">
                                <tbody>
                                    
                                    <tr>
                                        <td width="50%">
                                            <strong>วันเดินทางไป</strong>
                                        </td>
                                        <td ><font id="date_start"></font></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>วันเดินทางกลับ</strong>
                                        </td>
                                        <td ><font id="date_end"></font></td>
                                    </tr>
                                     <tr>
                                        <td>
                                            <strong>Bus</strong>
                                        </td>
                                        <td ><font id="bus_no"></font></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>จำนวนที่นั่ง</strong>
                                        </td>
                                        <td><font id="qty"></font>
                                    </tr>
                                     <tr>
                                        <td>
                                            <strong>ราคา</strong>
                                        </td>
                                        <td><font id="price"></font>
                                    </tr>
                                     <tr>
                                        <td>
                                            <strong>คอมมิชชั่น</strong>
                                        </td>
                                        <td><font id="com"></font>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>สถานะ</strong>
                                        </td>
                                        <td>
                                            <div id="status"></div> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="panel-heading bg-gray-lighter"><u>การชำระเงิน</u></div>
                            <table class="table bb">
                                <tbody>
                                    
                                    <tr>
                                        <td width="50%">
                                            <strong>ยอดเงินรวม</strong>
                                        </td>
                                        <td ><font id="sum_total"></font></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>ยอดเงินที่ได้รับ</strong>
                                        </td>
                                        <td ><font  id="sum_receipt" color = "green"></font></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>คงเหลือ</strong>
                                        </td>
                                        <td><font id="balance" color = "red"></font>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading bg-gray-lighter"><u>รายละเอียด</u></div>
                        <div class="panel-body">
                            <table class="table bb">
                                <tbody>
                                    <tr>
                                        <td width="50%">
                                            <strong>ชื่อโรงแรม</strong>
                                        </td>
                                        <td><input type="text" class="form-control" name ="txthotel_name" id="txthotel_name" value = ""> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>เบอร์โทรศัพท์ โรงแรม</strong>
                                        </td>
                                        <td><input type="text" class="form-control" name ="txthotel_tel" id="txthotel_tel" value = ""> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong><font id="balance" color = "red">วันที่เดินทางถึง*</font></strong>
                                        </td>
                                        <td><input type="text" class="form-control date period_requried " name ="txtarrival_date" id="txtarrival_date" value = ""> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                             <button type="button" class="mb-sm btn btn-primary" onclick="submit_hotel()">
                                            <em class="icon-cursor"></em>
                                            บันทึก
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                    <div class="col-lg-12" align="center">
                        <button style="width:180px" type="button" class="mb-sm btn btn-success" onclick="export_roomlist()">
                            <em class="icon-printer"></em>
                            พิมพ์ข้อมูลผู้เดินทาง
                        </button>
                        <button style="width:180px" type="button" class="mb-sm btn btn-info" onclick="export_tag_bag()">
                            <em class="icon-printer"></em>
                            พิมพ์ Tag กระเป๋า
                        </button>
                        
                        <div align="center" class="" id="div_btn_tm_declare">
                            
                        </div>

                        

                    </div>


                </div>

                <div class="col-lg-9">
                     <div role="tabpanel" class="panel">
                <!-- Nav tabs-->
                <ul role="tablist" class="nav nav-tabs nav-justified">
                    <li role="presentation" class="active">
                        <a href="#booking" aria-controls="booking" role="tab" data-toggle="tab">
                            <em class="fa fa-clock-o fa-fw"></em>รายละเอียด</a>
                    </li>
                     <li role="presentation">
                        <a href="#roomlist" onclick="get_room_type();" aria-controls="roomlist" role="tab" data-toggle="tab">
                            <em class="fa fa-users fa-fw"></em>ข้อมูลผู้เดินทาง </a>
                    </li>
                </ul>
                 <!-- Tab panes-->
                <div class="tab-content p0">
                    <div id="booking" role="tabpanel" class="tab-pane active">
                       
                            <div class="panel-heading"></div>
                     <!-- START table-responsive-->
                                <div class="panel-body table-responsive" id="interface_table">
                                </div>
            
                                    <div class="panel-body" id="interface_pagination">
                    
                
                                    </div>
             

                </div>


                                    <div id="roomlist" role="tabpanel" class="tab-pane">
                                        <br>
                                        <div id="div_room">
                                        </div>
                                    </div>

                </div>
                    
                </div>


                     
                 
                    

                </div>
             

            
            
            </div>
           
                 
                    

                </div>

            </div>
            
            <!-- BTN AREA -->
            <div class="row">
                <div class="col-lg-12" align="center">
                    <button type="button" class="mb-sm btn btn-warning" onclick="location.href='../manage_period'">
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


        var per_id  = '<?php echo $_REQUEST["per_id"];?>';
        var bus_no  = '<?php echo $_REQUEST["bus_no"];?>';
        var url_search  = 'action_manage_period.php';
        var data_search;
        var page       = 0;
        var page_limit = 99;
        var data       = [];
        var tmp_data   = [];
        var sdate_start = '';
        var sdate_end = '';
        $(document).ready(function(e){
            CREATE_INPUT_DATE();
            get_data_period(per_id);
            search_page();
        });


        function get_data_period(per_id){

            $.post('action_manage_period.php',
            {   
                method      : 2,
                per_id      : per_id,
                bus_no      : bus_no

            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);

                $('#div_btn_tm_declare').html(data_j.btn_tm_declare);

                $('#ser_name').text(data_j.ser_name);
                $('#ser_code').text(data_j.ser_code);
                $('#ser_country').text(data_j.country_name);
                $('#air_name').text(data_j.air_name);
            
                $('#date_start').html(data_j.per_date_start);
                $('#date_end').html(data_j.per_date_end);
                $('#bus_no').html(bus_no);
                $('#per_qty_seats').text(data_j.per_qty_seats);
                $('#qty').html(data_j.QTY);
                $('#price').html(data_j.price);
                $('#com').html(data_j.com);

                $('#sum_total').html(data_j.sum_total);
                $('#sum_receipt').html(data_j.sum_receipt);
                $('#balance').html(data_j.balance);
                
                $('#txthotel_name').val(data_j.per_hotel);
                $('#txthotel_tel').val(data_j.per_hotel_tel);
                $('#txtarrival_date').val(data_j.arrival_date);
                
                $('#status').html(data_j.status);

            });


        }    
          function search_page(){
     
            data_search = {
               
                per_id      : per_id,
                bus_no      : bus_no,



                offset      : page,
                method      : 3

            };

            GET_LIST_TABLE(data_search,url_search);

        }

        function submit_hotel(){
           

            $('#content-wrapper').addClass('whirl double-up');
            $.post('action_manage_period.php',
            {

                method              : 24,
                per_id              : per_id,
                per_hotel           : $('#txthotel_name').val(),
                per_hotel_tel       : $('#txthotel_tel').val(),
                arrival_date       : $('#txtarrival_date').val()
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
        
                 SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
            })
            setTimeout(function() {
                $('#content-wrapper').removeClass('whirl double-up');
                
            }, 500);
        }
        function get_room_type(){
         $('#content-wrapper').addClass('whirl double-up');
            $.post('action_manage_period.php',
            {

                method          : 99,
                per_id      : per_id,
                bus_no      : bus_no
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#div_room').html(data_j.div_room);
                $('.btn-room-file[href="javascript:;"]').addClass('disabled');
                $('select').chosen();
                CREATE_INPUT_DATE();
                
                
            })
            setTimeout(function() {
                $('#content-wrapper').removeClass('whirl double-up');
                
            }, 500);
        }
            function check_submit_room(book_code){
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_book(\''+book_code+'\')');

        }
        function submit_book(book_code){
          
            prename_array         = [];
            firstname_array         = [];
            lastname_array          = [];
            fullname_array          = [];
            sex_array               = [];
            country_array           = [];
            national_array          = [];
            address_array           = [];
            birthday_array          = [];
            passportno_array        = [];
            expire_array            = [];
            room_file_array         = [];
            remark_array            = [];
            career_array            = [];
            placeofbirth_array            = [];
            place_pp_array            = [];
            date_pp_array            = [];

            $('input[name="prename\''+book_code+'\'[]"]').each(function(){
                prename_array.push($(this).val());
            });
            $('input[name="firtname\''+book_code+'\'[]"]').each(function(){
                firstname_array.push($(this).val());
            });
            $('input[name="lastname\''+book_code+'\'[]"]').each(function(){
                lastname_array.push($(this).val());
            });
             $('input[name="room_name_thai\''+book_code+'\'[]"]').each(function(){
                fullname_array.push($(this).val());
            });
             $('select[name="sex\''+book_code+'\'[]"]').each(function(){
                sex_array.push($(this).val());
            });
            $('input[name="country\''+book_code+'\'[]"]').each(function(){
                country_array.push($(this).val());
            });
            $('input[name="national\''+book_code+'\'[]"]').each(function(){
                national_array.push($(this).val());
            });
            $('input[name="address\''+book_code+'\'[]"]').each(function(){
                address_array.push($(this).val());
            });
             $('input[name="birthday\''+book_code+'\'[]"]').each(function(){
                birthday_array.push($(this).val());
            });
             $('input[name="passportno\''+book_code+'\'[]"]').each(function(){
                passportno_array.push($(this).val());
            });
             $('input[name="expire\''+book_code+'\'[]"]').each(function(){
                expire_array.push($(this).val());
            });
             $('input[name="remark\''+book_code+'\'[]"]').each(function(){
                remark_array.push($(this).val());
            });
              $('input[name="room_file\''+book_code+'\'[]"]').each(function(){
                room_file_array.push($(this).val());
            });
            $('input[name="career\''+book_code+'\'[]"]').each(function(){
                career_array.push($(this).val());
            });
            $('input[name="placeofbirth\''+book_code+'\'[]"]').each(function(){
                placeofbirth_array.push($(this).val());
            }); 
            $('input[name="place_pp\''+book_code+'\'[]"]').each(function(){
                place_pp_array.push($(this).val());
            }); 
            $('input[name="date_pp\''+book_code+'\'[]"]').each(function(){
                date_pp_array.push($(this).val());
            });      
           


            $('#content-wrapper').addClass('whirl double-up');
            
              $.post('action_manage_period.php',
            {

                method                      : 98,
                book_code                   : book_code,
                prename_arr               : prename_array,
                firstname_arr               : firstname_array,
                lastname_arr                : lastname_array,
                fullname_arr                : fullname_array,
                sex_arr                     : sex_array,
                country_arr                 : country_array,
                national_arr                : national_array,
                address_arr                 : address_array,
                birthday_arr                : birthday_array,
                passportno_arr              : passportno_array,
                expire_arr                  : expire_array,
                room_file_arr             : room_file_array,
                remark_arr                  : remark_array,
                career_arr                  : career_array,
                placeofbirth_arr                 : placeofbirth_array,
                place_pp_arr                 : place_pp_array,
                date_pp_arr                : date_pp_array

            },function(data,textStatus,xhr){
             $('#content-wrapper').removeClass('whirl double-up');
                
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
            });


          
        }



                function manage_upload(room_id){
            $('#modal-manage-room').remove();

            $.post('action_manage_period.php',
            {
                method      : 20,
                room_id     : room_id
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                $('#modal-manage-room').modal('show');
                $(":file").filestyle('clear');
            });
            
            
        }
            function upload_file_room(room_id){

            if($('#file_room').val() == ''){
                swal('กรุณาเลือกไฟล์');
            }
            else{  

                var formData = new FormData();
                formData.append('room_id',room_id);
                formData.append('file_room', $('#file_room')[0].files[0]);
                formData.append('method',21);

                $('#modal-manage-room').modal('hide');
                $('#content-wrapper').addClass('whirl double-up');
                $.ajax({
                    url : 'action_manage_period.php',
                    type : 'POST',
                    data : formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(data) {
                        console.log(data);
                        get_room_type();
                        /*setTimeout(function() {
                            $('#content-wrapper').removeClass('whirl double-up');
                            
                        }, 500);*/
                    }
                });

            }

            


        }

         function check_submit_room_tl(){
                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_book_tl()');

        }
        function submit_book_tl(){
           
            prename_array         = [];
            firstname_array         = [];
            lastname_array          = [];
            fullname_array          = [];
            sex_array               = [];
            country_array           = [];
            national_array          = [];
            address_array           = [];
            birthday_array          = [];
            passportno_array        = [];
            expire_array            = [];
            room_file_array         = [];
            remark_array            = [];
            career_array            = [];
            placeofbirth_array            = [];
            place_pp_array            = [];
            date_pp_array            = [];

            $('input[name="prename_tl[]"]').each(function(){
                prename_array.push($(this).val());
            });
            $('input[name="firtname_tl[]"]').each(function(){
                firstname_array.push($(this).val());
            });
            $('input[name="lastname_tl[]"]').each(function(){
                lastname_array.push($(this).val());
            });
             $('input[name="room_name_thai_tl[]"]').each(function(){
                fullname_array.push($(this).val());
            });
             $('select[name="sex_tl[]"]').each(function(){
                sex_array.push($(this).val());
            });
            $('input[name="country_tl[]"]').each(function(){
                country_array.push($(this).val());
            });
            $('input[name="national_tl[]"]').each(function(){
                national_array.push($(this).val());
            });
            $('input[name="address_tl[]"]').each(function(){
                address_array.push($(this).val());
            });
             $('input[name="birthday_tl[]"]').each(function(){
                birthday_array.push($(this).val());
            });
             $('input[name="passportno_tl[]"]').each(function(){
                passportno_array.push($(this).val());
            });
             $('input[name="expire_tl[]"]').each(function(){
                expire_array.push($(this).val());
            });
             $('input[name="remark_tl[]"]').each(function(){
                remark_array.push($(this).val());
            });
              $('input[name="room_file_tl[]"]').each(function(){
                room_file_array.push($(this).val());
            });
            $('input[name="career_tl[]"]').each(function(){
                career_array.push($(this).val());
            });
            $('input[name="placeofbirth_tl[]"]').each(function(){
                placeofbirth_array.push($(this).val());
            });
            $('input[name="place_pp_tl[]"]').each(function(){
                place_pp_array.push($(this).val());
            });
            $('input[name="date_pp_tl[]"]').each(function(){
                date_pp_array.push($(this).val());
            });

            $('#content-wrapper').addClass('whirl double-up');
            
              $.post('action_manage_period.php',
            {

                method                      : 97,
                per_id      : per_id,
                bus_no      : bus_no,
                prename_arr               : prename_array,
                firstname_arr               : firstname_array,
                lastname_arr                : lastname_array,
                fullname_arr                : fullname_array,
                sex_arr                     : sex_array,
                country_arr                 : country_array,
                national_arr                : national_array,
                address_arr                 : address_array,
                birthday_arr                : birthday_array,
                passportno_arr              : passportno_array,
                expire_arr                  : expire_array,
                room_file_arr             : room_file_array,
                remark_arr                  : remark_array,
                career_arr                  : career_array,
                placeofbirth_arr                 : placeofbirth_array,
                place_pp_arr                 : place_pp_array,
                date_pp_arr                : date_pp_array

            },function(data,textStatus,xhr){
             $('#content-wrapper').removeClass('whirl double-up');
                
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
            });


          
        }


        
                function manage_upload_tl(per_leader_id){
            $('#modal-manage-room-tl').remove();

            $.post('action_manage_period.php',
            {
                method      : 22,
                per_leader_id     : per_leader_id
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                $('#modal-manage-room-tl').modal('show');
                $(":file").filestyle('clear');
            });
            
            
        }
            function upload_file_room_tl(per_leader_id){

            if($('#file_room_tl').val() == ''){
                swal('กรุณาเลือกไฟล์');
            }
            else{  

                var formData = new FormData();
                formData.append('per_leader_id',per_leader_id);
                formData.append('file_room_tl', $('#file_room_tl')[0].files[0]);
                formData.append('method',23);

                $('#modal-manage-room-tl').modal('hide');
                $('#content-wrapper').addClass('whirl double-up');
                $.ajax({
                    url : 'action_manage_period.php',
                    type : 'POST',
                    data : formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(data) {
                        console.log(data);
                        get_room_type();
                        /*setTimeout(function() {
                            $('#content-wrapper').removeClass('whirl double-up');
                            
                        }, 500);*/
                    }
                });

            }

            


        }

        function export_roomlist(){
            window.open("../report/export_roomlist.php?per_id="+per_id+"&bus_no=" + bus_no, "_blank");
        }

        function export_tag_bag(){
            window.open("../print/export_tag_bag.php?per_id="+per_id+"&bus_no=" + bus_no, "_blank");
        }

    </script>


</body>
</html>