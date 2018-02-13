<?php
    if(session_id()==''){
        session_start();
    }

   // check_login();


    function check_login(){
        if(!isset($_SESSION['login'])){
            header( "location: ../login/action_logout.php" );
    
        }
    }





?>