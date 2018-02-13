<?php
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php include_once('../unity/tag_head.php'); ?>

    <!-- lib -->

   <!-- lib -->

   <style>
        
   </style>
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
      <div class="container">

      <div class="content-heading">
               <!-- START Language list-->
               <div class="pull-right">
                 
               </div>
               <!-- END Language list    -->
               <font id="title_page"></font>
               <small data-localize="dashboard.WELCOME"></small>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">
                <form class="form-horizontal">
                    

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


 
</div>

<?php include_once('../unity/tag_script.php');?>
</body>

</html>