<?php include_once('../unity/main_core.php');?>

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
         <div class="content-wrapper">
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
                        <label class="col-lg-1 control-label">รายละเอียด :</label>
                        <div class="col-lg-4">
                           <div class="panel">
                                <div class="panel-body">
                                <input type="hidden" value="" id="input_contact_id" name="input_contact_id">
                                  <textarea rows="10" class="form-control note-editor" id="textarea_contactdetail"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-1" align="center">
                            <button type="button" class="btn btn-primary" onclick = "SWEET_ALERT_CONFIRM('ยืนยันการแก้ไขข้อมูล','',' submitcontact()');">
                                <em class="icon-cursor"></em>
                                บันทึก
                            </button>
                        </div>
                    </div>


                </form>
                </div>
            </div>
            
         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


   <?php include_once('../unity/tag_script.php');?>
<script>   
    $(document).ready(function(event){
        getdatacontact();

        });

    function getdatacontact() {
        $.post('action_manage_contact.php',{
            method          : 1,

         },function(data,textStatus,xhr){
            data_j = JSON.parse(data);
             $('#input_contact_id').val(data_j.contact_id);
             $('#textarea_contactdetail').val(data_j.contact_detail);
       });

    }

    function submitcontact() {
         $.post('action_manage_contact.php',{

            method              : 2,
            contact_id          : $('#input_contact_id').val(),
            contact_detail      : $('#textarea_contactdetail').val(),

         },function(data,textStatus,xhr){
            data_j = JSON.parse(data);
            SWEET_ALERT_INSER_UPDATE(data_j.status,2);
       });
    }




</script>   

</body>

</html>