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

   <style type="text/css">
       .ui-status{margin:2px;font-size:14px;display: inline-block;color:#000}

       .ui-status.s-10{color:#f878f2}

       .ui-status.s-20{color:#51c6ea}
       .ui-status.s-25{color:#2f80e7}

       .ui-status.s-30{color:#43d967}
       .ui-status.s-35{color:#27c24c}
   </style>
</head>

<body>

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
                        <label for="country_search" class="col-lg-1 control-label">ค้นหาประเทศ :</label>
                        <div class="col-lg-4">
                         <select name="country" id="country" class="form-control select_search">
                                <option value="">ทุกประเทศ</option>
                                <?php echo select_option_country(1);?>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="input_search" class="col-lg-1 control-label">ช่วงวันที่ :</label>
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
                        <div class="col-lg-4">
                           <label class="checkbox-inline c-checkbox">
                                    <input id="status_3" type="checkbox" value="3" >
                                <span class="fa fa-check"></span>แสดงเฉพาะปิดทัวร์
                            </label>
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
                <div class="panel-body" id="interface_table">
                </div>
            
                <div class="panel-body" id="interface_pagination">
                    
                
                </div>

            </div>


             <!-- BTN AREA -->
            
            
         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


   <?php include_once('../unity/tag_script.php');?>
 <script>
        
        $(document).ready(function(){
          search_page();
        })

        var url_search  = 'action_manage_period.php';
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
                status_3    : IS_CHECKED($('#status_3')),
                country_id   : $('#country').val(),
                date_start : sdate_start,
                date_end : sdate_end,



                offset      : page,
                method      : 1

            };

            GET_LIST_TABLE_NO_LIMIT(data_search,url_search);

        }

   </script>

</body>

</html>