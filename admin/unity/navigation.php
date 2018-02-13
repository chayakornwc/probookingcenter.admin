<nav role="navigation" class="navbar topnavbar">
            <!-- START navbar header-->
<div class="navbar-header">
    <a href="#/" class="navbar-brand">
        <div class="brand-logo">
            <img src="../unity/img/jitwilai_logo.png" alt="App Logo" class="img-responsive" width="110px" >
        </div>
        <div class="brand-logo-collapsed">
        
            <img src="../unity/img/jitwilai_logo.png" alt="App Logo" class="img-responsive">
        </div>
    </a>
</div>
<!-- END navbar header-->
<!-- START Nav wrapper-->
<div class="nav-wrapper">
    <!-- START Left navbar-->
    <ul class="nav navbar-nav">
        <li>
            <!-- Button used to collapse the left sidebar. Only visible on tablet and desktops-->
            <a href="#" data-trigger-resize="" data-toggle-state="aside-collapsed" class="hidden-xs">
            <em class="fa fa-navicon"></em>
            </a>
            <!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
            <a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
            <em class="fa fa-navicon"></em>
            </a>
        </li>
    
    </ul>
    <!-- END Left navbar-->
    <!-- START Right Navbar-->
    <ul class="nav navbar-nav navbar-right">
        
        <!-- Fullscreen (only desktops)-->
        <li class="visible-lg">
            <a href="#" data-toggle-fullscreen="">
            <em class="fa fa-expand"></em>
            </a>
        </li>
         <!-- START Alert menu-->
        <li class="dropdown dropdown-list">
            <a href="#" data-toggle="dropdown" onclick = "">
            <em class="icon-bell"></em>
             <div class="label label-warning" id="alert_count_today">
            
            </div>
            </a>
            <!-- START Dropdown menu-->
            <ul class="dropdown-menu animated flipInX">
            <li>
                <!-- START list group-->
                <div class="list-group">
                    <!-- list item-->
                    <div class="" id="alert_list_today">

                    </div>
                   
                    <!-- last list item-->
                    <a href="../noti/noti_today.php" class="list-group-item">
                        <small>
                            ดูรายการอื่นทั้งหมด
                        </small>
                        <!--   <span class="label label-danger pull-right">20</span> -->
                    </a>
                </div>
                <!-- END list group-->
            </li>
            </ul>
            <!-- END Dropdown menu-->
        </li>
        <!-- END Alert menu-->
        
        <!-- START Alert menu-->
        <li class="dropdown dropdown-list">
            <a href="#" data-toggle="dropdown" onclick = "update_read_date()">
            <em class="icon-bell"></em>
             <div class="label label-danger" id="alert_count">

            </div>
            </a>
            <!-- START Dropdown menu-->
            <ul class="dropdown-menu animated flipInX">
            <li>
                <!-- START list group-->
                <div class="list-group">
                    <!-- list item-->
                    <div class="" id="alert_list">

                    </div>
                   
                    <!-- last list item-->
                    <a href="../noti" class="list-group-item">
                        <small>
                            ดูรายการอื่นทั้งหมด
                        </small>
                        <!--   <span class="label label-danger pull-right">20</span> -->
                    </a>
                </div>
                <!-- END list group-->
            </li>
            </ul>
            <!-- END Dropdown menu-->
        </li>
        <!-- END Alert menu-->
        
        <li>
            <a href="javascript:manage_password(<?php echo $_SESSION['login']['user_id'];?>);" >
                <em class="icon-settings"></em>
            </a>
        </li>
        <li >
         <a href="#" >
         <em class="icon-user"></em>
       <font > : <?php echo  $_SESSION['login']['user_name']; ?></font>
         </a>
       </li>
        <li>
            <a href="../login/action_logout.php" >
                <em class="icon-logout"></em>
            </a>
        </li>
    </ul>
    <!-- END Right Navbar-->
</div>
<!-- END Nav wrapper-->

</nav>


<script>
        
        var tid = setTimeout(alert_msg, 1000);
        function alert_msg() {
            alert_today();
          $.post('../alert_msg/action_manage_alert.php',
        {   
            group :  '<?php echo $_SESSION['login']['group_name'] ?>'  ,

            method  : 1

        },function(data,textStatus,xhr){

            data_j = JSON.parse(data);
            

            $('#alert_list').html(data_j.alert_list);
            $('#alert_count').html(data_j.alert_count);
        });

        tid = setTimeout(alert_msg, 5000); // repeat myself
        }

        function abortTimer() { // to be called when you want to stop the timer
        clearTimeout(tid);
        } 
    function alert_today(){
      
          $.post('../alert_msg/action_manage_alert.php',
        {   
            user_id :  '<?php echo $_SESSION['login']['user_id'] ?>'  ,

            method  : 5

        },function(data,textStatus,xhr){

            data_j = JSON.parse(data);
            

            $('#alert_list_today').html(data_j.alert_list_today);
            $('#alert_count_today').html(data_j.alert_count_today);
        });
    }    
    function update_read_date(){
        $.post('../alert_msg/action_manage_alert.php',
        {   

            method  : 4

        },function(data,textStatus,xhr){

            data_j = JSON.parse(data);

          //  $('#alert_count').html('');
        });
    }        
    function manage_password(user_id){
        $('#modal-manage-password').remove();
        $.post('../manage_user/action_manage_user.php',
        {   
            user_id : user_id,

            method  : 6

        },function(data,textStatus,xhr){

            data_j = JSON.parse(data);
            
            $('body').append(data_j.modal);

            $('#modal-manage-password').modal('show');
        });
        
    }
    function check_submit_password(){
        if($('#user_password').val()==''){
            $('#user_password').focus();
            swal('กรุณากรอกข้อมูลให้ครบ');
            return;
        }
        else if($('#confirm_user_password').val()==''){
            $('#confirm_user_password').focus();
            swal('กรุณากรอกข้อมูลให้ครบ');
            return;
        }
        else if($('#user_password').val() != $('#confirm_user_password').val()){
            swal('กรุณากรอก Password ให้ตรงกัน');
            return;
        }
        else{
            submit_password();
        }
    }
    function submit_password(){
        $.post('../manage_user/action_manage_user.php',
        {   
            method          : 7,
            user_password   : $('#user_password').val(),
            user_id_password : $('#user_id_password').val(),

        },function(data,textStatus,xhr){
            data_j = JSON.parse(data);
            $('#modal-manage-password').modal('hide');
            
            SWEET_ALERT_INSER_UPDATE(data_j.status,data_j.type_alert);
        });
    }
</script>