<?php
if(session_id()==''){
    session_start();
}

echo '<pre>';
print_r($_SESSION);





?>