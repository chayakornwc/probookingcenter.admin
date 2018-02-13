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
         <div class="content-wrapper" id = "content-wrapper">
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
                <div class="table-responsive" id="interface_table">
                    <table id="" class="table table-bordered table-hover table-devgun">
                        <thead>
                            <tr>
                                <th width="5%">
                                    #
                                </th>
                                <th width="20%">
                                    ชื่อซี่รีย์
                                </th>
                                <th width="15%">
                                    Code
                                </th>
                                <th width="10%">
                                    ประเทศ
                                </th>
                                <th width="10%">
                                    สายการบิน
                                </th>
                                <th width="10%">
                                    File Word
                                </th>
                                <th width="10%">
                                    File PDF
                                </th>
                                <th width="10%">
                                </th>

                              
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            
                <div class="panel-body" id="interface_pagination">
                    
                
                </div>

            </div>
            


            <!-- BTN AREA -->

            <div class="row">
                <div class="col-lg-12" align="center">
                    <button type="button" class="mb-sm btn btn-green" onclick="location.href='manage_travel.php'">
                        <em class="icon-plus"></em>
                        เพิ่ม ซี่รีย์ทัวร์
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

        var url_search  = 'action_manage_travel.php';
        var data_search;
        var page       = 0;
        var page_limit = 99;
        var data       = [];
        var tmp_data   = [];

        function search_page(){

            data_search = {
                word_search     : $('#input_search').val(),
                country_id      : $('#country').val(),
                offset          : page,
                method          : 1

            };

            GET_LIST_TABLE(data_search,url_search);

        }


   </script>

</body>

</html>