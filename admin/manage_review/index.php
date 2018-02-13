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
         <div class="content-wrapper" id ="content-wrapper">
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
                                <th width="15%">
                                    Status
                                </th>
                                <th width="10%">
                                    ประเทศ
                                </th>
                                <th width="60%">
                                    รายละเอียด
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
                    <button type="button" class="mb-sm btn btn-green" onclick="manage_review('add')">
                        <em class="icon-plus"></em>
                        เพิ่มรีวิว
                    </button>
                </div>
            </div>


         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


   <?php include_once('../unity/tag_script.php');?>


    <!-- TMP HTML-->
    
    

   <script>
    
        $(document).ready(function(event){
            
         search_page();

        });
        var url_search  = 'action_manage_review.php';
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



        function manage_review(action_method,review_id){

            $('#content-wrapper').addClass('whirl double-up');
            $('#modal-manage-review').remove();
            $.post('action_manage_review.php',
            {
                method          : 2,
                action_method   : action_method,
                review_id       : review_id
            },function(data,textStatus,xhr){
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);
                $('body').append(data_j.modal);
                $(":file").filestyle('clear');
                $('select').chosen();
                $('#modal-manage-review').modal('show');
            });


        }

        function check_submit_review(method){

            $('#content-wrapper').addClass('whirl double-up');
            $('#modal-manage-review').modal('hide');
            var formData = new FormData();

            formData.append('method',5);

            formData.append('review_img_1_old', $('#review_img_1_old').attr('url'));
            formData.append('review_img_2_old', $('#review_img_2_old').attr('url'));
            formData.append('review_img_3_old', $('#review_img_3_old').attr('url'));
            formData.append('review_img_4_old', $('#review_img_4_old').attr('url'));
            formData.append('review_img_5_old', $('#review_img_5_old').attr('url'));


            if($('#review_img_1').val()!=''){
                formData.append('review_img_1', $('#review_img_1')[0].files[0]);
            }
            if($('#review_img_2').val()!=''){
                formData.append('review_img_2', $('#review_img_2')[0].files[0]);
            }
            if($('#review_img_3').val()!=''){
                formData.append('review_img_3', $('#review_img_3')[0].files[0]);
            }
            if($('#review_img_4').val()!=''){
                formData.append('review_img_4', $('#review_img_4')[0].files[0]);
            }
            if($('#review_img_5').val()!=''){
                formData.append('review_img_5', $('#review_img_5')[0].files[0]);
            }


            $('input[type="file"]').prop('disabled',true);
            
            $.ajax({
                url             : 'action_manage_review.php',
                type            : 'POST',
                data            : formData,
                processData     : false,  // tell jQuery not to process the data
                contentType     : false,  // tell jQuery not to set contentType
                success         : function(data) {
                    $('#content-wrapper').removeClass('whirl double-up');
                    $('input[type="file"]').prop('disabled',false);
                    data_j  = JSON.parse(data);
                    
                    $('#review_img_1_old').attr('href',CHECK_HREF_IMG_NULL(data_j.review_img_1));
                    $('#review_img_2_old').attr('href',CHECK_HREF_IMG_NULL(data_j.review_img_2));
                    $('#review_img_3_old').attr('href',CHECK_HREF_IMG_NULL(data_j.review_img_3));
                    $('#review_img_4_old').attr('href',CHECK_HREF_IMG_NULL(data_j.review_img_4));
                    $('#review_img_5_old').attr('href',CHECK_HREF_IMG_NULL(data_j.review_img_5));

                    $('#review_img_1_old').attr('url',(data_j.review_img_1));
                    $('#review_img_2_old').attr('url',(data_j.review_img_2));
                    $('#review_img_3_old').attr('url',(data_j.review_img_3));
                    $('#review_img_4_old').attr('url',(data_j.review_img_4));
                    $('#review_img_5_old').attr('url',(data_j.review_img_5));


                    SWEET_ALERT_CONFIRM('ยืนยันการบันทึกข้อมูล','','submit_review('+method+')');
                }
            });

        }

        function submit_review(method){
            
            $('#content-wrapper').addClass('whirl double-up');

            $.post('action_manage_review.php',
            {   
                method          : method,
                review_id       : $('#review_id').val(),
                review_country  : $('#review_country').val(),
                review_detail   : $('#review_detail').val(),
                
                review_img_1    : $('#review_img_1_old').attr('url'),
                review_img_2    : $('#review_img_2_old').attr('url'),
                review_img_3    : $('#review_img_3_old').attr('url'),
                review_img_4    : $('#review_img_4_old').attr('url'),
                review_img_5    : $('#review_img_5_old').attr('url'),

                status          : $('input[name="review_status"]:checked').val()

            },function(data,textStatus,xhr){
                $('#content-wrapper').removeClass('whirl double-up');
                data_j = JSON.parse(data);
                SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
                search_page();
            });


        }


        

   </script>

</body>

</html>