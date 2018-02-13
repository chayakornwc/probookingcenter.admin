<?php include_once('../unity/main_core.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php include_once('../unity/tag_head.php'); ?>

    <!-- lib -->
   <?php include_once('../unity/lib_heightchart.php');?>
   <!-- lib -->

   <style>
        .chart {
            min-width: 100%;
            max-width: 100%;
            height: 400px;
            margin: 0 auto
        }
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
      <section>
         <!-- Page content-->
        <div class="content-wrapper" id="content-wrapper">
            <div class="content-heading">
               <!-- START Language list-->
               <div class="pull-right">
                 
               </div>
               <!-- END Language list    -->
               <font id="">แจ้งเตือน</font>
               <small data-localize="dashboard.WELCOME"></small>
            </div>
			
			
            <div class="row">

                <div class="col-lg-12">
                
                    <div class="panel">
                        <div class="panel-heading bg-gray-lighter text-bold">รายการแจ้งเตือน</div>
                        <div class="panel-body">
                            <p>
                                <span id = "txt_order">รายการแจ้งเตือนล่าสุด</span>
                                <strong id = "count_all"> 20 </strong>
                                <span>รายการ</span>
                            </p>
                        <ul class="list-group">
                              <div class="" id="alert_noti">

                             </div>
                           
                        </ul>

                          <div align="center">
                                                <button type="button" class="btn btn-primary btn-default" onclick="all_data()">
                                                    <strong>แสดงทั้งหมด</strong>
                                                </button>
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
      $(document).ready(function(){
            alert_noti();
        });
		function alert_noti() {
          
          $.post('../alert_msg/action_manage_alert.php',
        {   
            group :  '<?php echo $_SESSION['login']['group_name'] ?>'  ,
            limit  : 20,
            method  : 2

        },function(data,textStatus,xhr){

            data_j = JSON.parse(data);
            

            $('#alert_noti').html(data_j.alert_noti);
            $('#count_all').html(20);
            $('#txt_order').html('รายการแจ้งเตือนล่าสุด');
            
        });

       
        }

        function all_data() {
          
          $.post('../alert_msg/action_manage_alert.php',
        {   
            group :  '<?php echo $_SESSION['login']['group_name'] ?>'  ,

            limit  : 10000,
            method  : 2

        },function(data,textStatus,xhr){

            data_j = JSON.parse(data);
            

            $('#alert_noti').html(data_j.alert_noti);
            $('#count_all').html(data_j.count_all);
            $('#txt_order').html('รายการแจ้งเตือนทั้งหมด');
        });

       
        }
    </script>

</body>

</html>