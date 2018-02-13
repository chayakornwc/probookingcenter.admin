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
			
		
    
            <div class="row">
                <div class="col-md-3">
                    <div class="panel b">
                    <div class="panel-heading bg-gray-dark text-bold">รายงาน</div>
                    <div class="list-group">
                        <a href="#tab_report_payment" onclick="" data-toggle="tab" class="list-group-item">รายงาน รับโอนเงินจากเอเจ้นท์</a>
                        <a href="#tab_report_com_sale" onclick="" data-toggle="tab" class="list-group-item">รายงาน ค่าคอมมิชชั่นพนักงาน</a>
                        <a href="#tab_report_grosssale_country" onclick="" data-toggle="tab" class="list-group-item">รายงาน สรุปยอดขาย เรียงตามประเทศ</a>
                        <a href="#tab_report_grosssale_agency" onclick="" data-toggle="tab" class="list-group-item" >รายงาน สรุปยอดขาย เรียงตามเอเจนซี่</a>
                        <a href="#tab_report_grosssale_sale" onclick="" data-toggle="tab" class="list-group-item">รายงาน สรุปยอดขาย เรียงตามพนักงาน</a> 
                        <a href="#tab_report_invoice_sale" onclick="" data-toggle="tab" class="list-group-item">รายงาน สรุป Invoice</a> 
                        <a href="#tab_report_payment_all" onclick="" data-toggle="tab" class="list-group-item">รายงาน แจ้งการโอนเงิน</a> 
                    
                       <!--  <a href="#tab_report_booking" onclick="get_report_booking()" data-toggle="tab" class="list-group-item">รายงานการ Booking</a> -->
                    </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content p0 b0">

                        <div id="tab_report_payment" class="tab-pane active">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-darker text-bold">รายงาน รับโอนเงินจากเอเจ้นท์</div>
                                    <div class="panel-body">
                                        <form class="form-horizontal">
                          
                                            <div class="form-group">
                                                <label for="input_search" class="col-lg-2 control-label">ช่วงวันที่ :</label>
                                                    <div class="col-lg-6">

                                                        <div class="input-group">
                                                            <input id="input_date_from_payment" type="text" class="form-control date_search" >
                                                            <span class="input-group-addon"><em class="fa fa-minus"></em></span>
                                                            <input id="input_date_to_payment" type="text" class="form-control date_search" >
                                                        </div>

                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="country_search" class="col-lg-2 control-label">สมุดธนาคาร :</label>
                                                    <div class="col-lg-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                            <select name="bankbook" id="bankbook" class="form-control select_search">
                                                            <option value="">ทั้งหมด</option>
                                                            <?php echo select_option_bankbook(1);?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-lg-3 col-lg-offset-2">
                                                    <button type="button" class="mb-sm btn btn-success" onclick="export_excel_payment()">
                                                    <em class="icon-printer"></em>
                                                    Export to excel
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                            </div>
                        </div>
                            
                        <div id="tab_report_com_sale" class="tab-pane">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-darker text-bold">รายงาน ค่าคอมมิชชั่นพนักงาน</div>
                                    <div class="panel-body">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label for="input_search" class="col-lg-2 control-label">ช่วงวันที่ :</label>
                                                    <div class="col-lg-6">
                                                        <div class="input-group">
                                                            <input id="input_date_from_com_sale" type="text" class="form-control date_search" >
                                                            <span class="input-group-addon"><em class="fa fa-minus"></em></span>
                                                            <input id="input_date_to_com_sale" type="text" class="form-control date_search" >
                                                        </div>

                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="country_search" class="col-lg-2 control-label" >ชื่อพนักงาน* :</label>
                                                    <div class="col-lg-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                            <select name="user_sale" id="user_sale" class="form-control select_search">
                                                            <option value="">-- ระบุชื่อพนักงาน --</option>
                                                            <?php echo select_option_user(1);?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-lg-3 col-lg-offset-2">
                                                  <button type="button" class="mb-sm btn btn-success" onclick="export_excel_com_sale()">
                                                    <em class="icon-printer"></em>
                                                    Export to excel
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                </div>   
                            </div>   
                        </div>   

                        <div id="tab_report_grosssale_country" class="tab-pane">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-darker text-bold">รายงาน สรุปยอดขาย เรียงตามประเทศ</div>
                                    <div class="panel-body">
                                        <form class="form-horizontal">
                          
                                            <div class="form-group">
                                                <label for="input_search" class="col-lg-2 control-label">ช่วงวันที่ :</label>
                                                    <div class="col-lg-6">

                                                        <div class="input-group">
                                                            <input id="input_date_from_grosssale" type="text" class="form-control date_search" >
                                                            <span class="input-group-addon"><em class="fa fa-minus"></em></span>
                                                            <input id="input_date_to_grosssale" type="text" class="form-control date_search" >
                                                        </div>

                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="country_search" class="col-lg-2 control-label">ประเทศ :</label>
                                                    <div class="col-lg-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                            <select name="country_grosssale" id="country_grosssale" class="form-control select_search">
                                                            <option value="">ทั้งหมด</option>
                                                            <?php echo select_option_country(1);?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="country_search" class="col-lg-2 control-label">รหัส ซี่รี่ทัวร์ :</label>
                                                    <div class="col-lg-6"  id = "ser_code_2">
                                                        <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                            <select name="ser_id" id="ser_id" class="form-control select_search">
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                               <div class="col-lg-3 col-lg-offset-2">
                                                    <button type="button" class="mb-sm btn btn-success" onclick="export_excel_grosssale_country()">
                                                    <em class="icon-printer"></em>
                                                    Export to excel
                                                    </button>
                                                </div> 
                                            </div>
                                        </form>
                                    </div>
                            </div>
                        </div>

                        <div id="tab_report_grosssale_agency" class="tab-pane">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-darker text-bold">รายงาน สรุปยอดขาย เรียงตามเอเจนซี่</div>
                                    <div class="panel-body">
                                        <form class="form-horizontal">
                          
                                            <div class="form-group">
                                                <label for="input_search" class="col-lg-2 control-label">ช่วงวันที่ :</label>
                                                    <div class="col-lg-6">

                                                        <div class="input-group">
                                                            <input id="input_date_from_grosssale_agency" type="text" class="form-control date_search" >
                                                            <span class="input-group-addon"><em class="fa fa-minus"></em></span>
                                                            <input id="input_date_to_grosssale_agency" type="text" class="form-control date_search" >
                                                        </div>

                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="country_search" class="col-lg-2 control-label">บริษัทเอเจนซี่ :</label>
                                                    <div class="col-lg-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                            <select name="com_agency_grosssale" id="com_agency_grosssale" class="form-control select_search">
                                                            <option value="">ทั้งหมด</option>
                                                            <?php echo select_option_com_agency(1);?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="country_search" class="col-lg-2 control-label">ชื่อเอเจนซี่ :</label>
                                                    <div class="col-lg-6"  id = "agency_grosssale_2">
                                                        <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                            <select name="agency_grosssale" id="agency_grosssale" class="form-control select_search">
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <hr>
                                           <div class="form-group">
                                               <div class="col-lg-3 col-lg-offset-2">
                                                    <button type="button" class="mb-sm btn btn-success" onclick="export_excel_grosssale_agent()">
                                                    <em class="icon-printer"></em>
                                                    Export to excel
                                                    </button>
                                                </div> 
                                            </div> 
                                        </form>
                                    </div>
                            </div>
                        </div>

                        <div id="tab_report_grosssale_sale" class="tab-pane">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-darker text-bold">รายงาน สรุปยอดขาย เรียงตามพนักงาน</div>
                                    <div class="panel-body">
                                        <form class="form-horizontal">
                          
                                            <div class="form-group">
                                                <label for="input_search" class="col-lg-2 control-label">ช่วงวันที่ :</label>
                                                    <div class="col-lg-6">

                                                        <div class="input-group">
                                                            <input id="input_date_from_grosssale_sale" type="text" class="form-control date_search" >
                                                            <span class="input-group-addon"><em class="fa fa-minus"></em></span>
                                                            <input id="input_date_to_grosssale_sale" type="text" class="form-control date_search" >
                                                        </div>

                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="country_search" class="col-lg-2 control-label">ชื่อพนักงาน :</label>
                                                    <div class="col-lg-6">
                                                        <div class="input-group col-lg-12 col-md-12 col-xs-12" >
                                                            <select name="sale_grosssale" id="sale_grosssale" class="form-control select_search">
                                                            <option value="">ทั้งหมด</option>
                                                            <?php echo select_option_user(1);?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            
                                            <hr>
                                             <div class="form-group">
                                               <div class="col-lg-3 col-lg-offset-2">
                                                    <button type="button" class="mb-sm btn btn-success" onclick="export_excel_grosssale_sale()">
                                                    <em class="icon-printer"></em>
                                                    Export to excel
                                                    </button>
                                                </div> 
                                            </div>  
                                        </form>
                                    </div>
                            </div>
                        </div>

                        <div id="tab_report_invoice_sale" class="tab-pane">
                            <div class="panel b">
                                <div class="panel-heading bg-gray-darker text-bold">รายงาน สรุปยอด Invoice</div>
                                    <div class="panel-body">
                                        <form class="form-horizontal">
                          
                                            <div class="form-group">
                                                <label for="input_search" class="col-lg-2 control-label">ช่วงวันที่ :</label>
                                                    <div class="col-lg-6">

                                                        <div class="input-group">
                                                            <input id="input_date_from_invoice_sale" type="text" class="form-control date_search" >
                                                            <span class="input-group-addon"><em class="fa fa-minus"></em></span>
                                                            <input id="input_date_to_invoice_sale" type="text" class="form-control date_search" >
                                                        </div>

                                                    </div>
                                            </div>
                                            <hr>
                                             <div class="form-group">
                                               <div class="col-lg-3 col-lg-offset-2">
                                                    <button type="button" class="mb-sm btn btn-success" onclick="export_excel_invoice_sale()">
                                                    <em class="icon-printer"></em>
                                                    Export to excel
                                                    </button>
                                                </div> 
                                            </div>  
                                        </form>
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
        var today = new Date();
        
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 var lastday = (function(){
return function(year, month){
var date = new Date(year, month, 0);
return date.getDate();
};

})();

        $(document).ready(function(event){
            $('#input_date_from_payment').val('1/' + mm + '/' + yyyy);
            $('#input_date_to_payment').val(lastday(yyyy,mm) +'/' + mm + '/' + yyyy);

            $('#input_date_from_com_sale').val('1/' + mm + '/' + yyyy);
            $('#input_date_to_com_sale').val(lastday(yyyy,mm) +'/' + mm + '/' + yyyy);

            $('#input_date_from_grosssale').val('1/' + mm + '/' + yyyy);
            $('#input_date_to_grosssale').val(lastday(yyyy,mm) +'/' + mm + '/' + yyyy);

            $('#input_date_from_grosssale_agency').val('1/' + mm + '/' + yyyy);
            $('#input_date_to_grosssale_agency').val(lastday(yyyy,mm) +'/' + mm + '/' + yyyy);

            $('#input_date_from_grosssale_sale').val('1/' + mm + '/' + yyyy);
            $('#input_date_to_grosssale_sale').val(lastday(yyyy,mm) +'/' + mm + '/' + yyyy);

            $('#input_date_from_invoice_sale').val('1/' + mm + '/' + yyyy);
            $('#input_date_to_invoice_sale').val(lastday(yyyy,mm) +'/' + mm + '/' + yyyy);

            $('#country_grosssale').change(function(){
                select_country_grosssale();
            });

            $('#com_agency_grosssale').change(function(){
                select_com_agency_grosssale();
            });


            select_country_grosssale();
            select_com_agency_grosssale();

        })
        function select_country_grosssale(){
            
            $.post('action_manage_report.php',
            {

                method              :  1,
                country_id       :  $('#country_grosssale').val(), // global

            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#ser_code_2').html(data_j.select_country_grosssale);
                $('select').chosen();
                
            })
           
         }
         function select_com_agency_grosssale(){
            
            $.post('action_manage_report.php',
            {

                method              :  2,
                com_agency_id       :  $('#com_agency_grosssale').val(), // global

            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#agency_grosssale_2').html(data_j.select_com_agency_grosssale);
                $('select').chosen();
                
            })
           
         }
       
       function export_excel_payment(){
            if ($('#input_date_from_payment').val() == '') {
                date_from = '1/1/1900'
            }else{
                date_from = $('#input_date_from_payment').val();
            }
            if ($('#input_date_to_payment').val() == '') {
                date_to = '1/1/9999'
            }else{
                date_to = $('#input_date_to_payment').val();
            }
           
           bankbook_id =  $('#bankbook').val();
           url = '../report/export_payment.php?date_from='+ date_from  + '&date_to='+ date_to +'&bankbook_id='+ bankbook_id;
        
        window.open(url, "_blank");

       }
       function export_excel_com_sale(){
           if ($('#user_sale').val() == '') {
            swal('กรุณาระบุชื่อพนักงาน','','warning');
            return;
           }
            if ($('#input_date_from_com_sale').val() == '') {
                date_from = '1/1/1900'
            }else{
                date_from = $('#input_date_from_com_sale').val();
            }
            if ($('#input_date_to_com_sale').val() == '') {
                date_to = '1/1/9999'
            }else{
                date_to = $('#input_date_to_com_sale').val();
            }
           
           user_id =  $('#user_sale').val();
           url = '../report/export_com_sale.php?date_from='+ date_from  + '&date_to='+ date_to +'&user_id='+ user_id;
        
        window.open(url, "_blank");

       }
       
       function export_excel_grosssale_country(){
     
            if ($('#input_date_from_grosssale').val() == '') {
                date_from = '1/1/1900'
            }else{
                date_from = $('#input_date_from_grosssale').val();
            }
            if ($('#input_date_to_grosssale').val() == '') {
                date_to = '1/1/9999'
            }else{
                date_to = $('#input_date_to_grosssale').val();
            }
           
            country_id =  $('#country_grosssale').val();
            ser_id =  $('#ser_id').val();
           url = '../report/export_com_grosssale.php?date_from='+ date_from  + '&date_to='+ date_to +'&country_id='+ country_id + '&ser_id='+ ser_id + '&sort=0';
        
        window.open(url, "_blank");

       }
       function export_excel_grosssale_agent(){
     
            if ($('#input_date_from_grosssale_agency').val() == '') {
                date_from = '1/1/1900'
            }else{
                date_from = $('#input_date_from_grosssale_agency').val();
            }
            if ($('#input_date_to_grosssale_agency').val() == '') {
                date_to = '1/1/9999'
            }else{
                date_to = $('#input_date_to_grosssale_agency').val();
            }
           
            com_agency_id =  $('#com_agency_grosssale').val();
            agency_id =  $('#agency_grosssale').val();
           url = '../report/export_com_grosssale_agent.php?date_from='+ date_from  + '&date_to='+ date_to +'&com_agency_id='+ com_agency_id + '&agency_id='+ agency_id + '&sort=1';
        
        window.open(url, "_blank");

       }
       function export_excel_grosssale_sale(){
     
            if ($('#input_date_from_grosssale').val() == '') {
                date_from = '1/1/1900'
            }else{
                date_from = $('#input_date_from_grosssale').val();
            }
            if ($('#input_date_to_grosssale').val() == '') {
                date_to = '1/1/9999'
            }else{
                date_to = $('#input_date_to_grosssale').val();
            }
           
            user_id =  $('#sale_grosssale').val();
           url = '../report/export_com_grosssale_sale.php?date_from='+ date_from  + '&date_to='+ date_to +'&user_id='+ user_id  + '&sort=2';
        
        window.open(url, "_blank");

       }
       function export_excel_invoice_sale(){
            if ($('#input_date_from_invoice_sale').val() == '') {
                date_from = '1/1/1900'
            }else{
                date_from = $('#input_date_from_invoice_sale').val();
            }
            if ($('#input_date_to_invoice_sale').val() == '') {
                date_to = '1/1/9999'
            }else{
                date_to = $('#input_date_to_invoice_sale').val();
            }
            url = '../report/export_excel_invoice_sale.php?date_from='+ date_from  + '&date_to='+ date_to;
            window.open(url, "_blank");
       }
     </script>

</body>

</html>