<?php



    session_start();

    $_SESSION['login']['user_id'] = 1;


    echo json_encode($_SESSION);
?>