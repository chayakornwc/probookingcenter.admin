<?php
#include
include_once('../admin/unity/post_to_ws/post_to_ws.php');
include_once('../admin/unity/php_script.php');

include_once('../admin/unity/PHPMailer-master/PHPMailerAutoload.php');

date_default_timezone_set('Asia/Bangkok');

function send_email_reset_password_agen($email,$user_id,$user_name,$user_fullname){
        
    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host         = 'smtp.gmail.com';               // Specify main and backup SMTP servers
    $mail->SMTPAuth     = true;                           // Enable SMTP authentication
    $mail->Username     = EMAIL;                          // SMTP username
    $mail->Password     = EMAIL_PASSWORD;                 // SMTP password
    $mail->SMTPSecure   = 'tls';                          // Enable TLS encryption, `ssl` also accepted
    $mail->Port         = 587 ;                           // TCP port to connect to

    $mail->CharSet = "utf-8";


    $mail->setFrom(EMAIL, 'บริษัทจิตรวิไลย อินเตอร์ทัวร์'); 
    $mail->addAddress($email, $user_fullname);     // Add a recipient
    


    $mail->isHTML(true);                                  // Set email format to HTML
    /*$img = '../img/1488461326107.jpg';
    $mail->AddAttachment($img);*/

    $url_reset_password = 'http://www.jitwilaitour.com/login/reset_password.php?u='.base64_encode_dev_gun($user_id);

    $htmlContent = '<div style="font-family:HelveticaNeue-Light,Arial,sans-serif;background-color:#eeeeee">
                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">

                            <tbody>
                                <tr>
                                    <td>

                                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                            <tbody>
                                                <tr>
                                                    <td>

                                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="3" height="80" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td width="30"></td>
                                                                                    <td align="left" valign="middle" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                                        <a href="#" target="_blank">
                                                                                            รูป
                                                                                            <img src="">
                                                                                        </a>
                                                                                    </td>
                                                                                    <td width="30"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr bgcolor="#ffffff">
                                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                                    <td>
                                                                        <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td colspan="4" align="center">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="4" align="center">
                                                                                        <h2 style="font-size:24px">RESET PASSWORD</h2>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="4">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="120" align="right" valign="top">
                                                                                        <h3>ชื่อ - สกุล : </h3>
                                                                                    </td>
                                                                                    <td width="30"></td>
                                                                                    <td align="left" valign="middle">
                                                                                        <h3>'.$user_fullname.'</h3>
                                                                                    </td>
                                                                                    <td width="30"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="120" align="right" valign="top">
                                                                                        <h3> Username : </h3>
                                                                                    </td>
                                                                                    <td width="30"></td>
                                                                                    <td align="left" valign="middle">
                                                                                        <h3>'.$user_name.'</h3>
                                                                                    </td>
                                                                                    <td width="30"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td colspan="4">&nbsp;</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center">
                                                                                        <div style="text-align:center;width:100%;padding:40px 0">
                                                                                            <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;padding:0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center" style="margin:0;text-align:center">
                                                                                                            <a href="'.$url_reset_password.'" style="font-size:18px;font-family:HelveticaNeue-Light,Arial,sans-serif;line-height:22px;text-decoration:none;color:#ffffff;font-weight:bold;border-radius:2px;background-color:#00a3df;padding:14px 10px;display:block" target="_blank">
                                                                                                                RESET PASSOWORD
                                                                                                            </a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>&nbsp;</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table width="630" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td colspan="2" height="30"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="360" valign="top">
                                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"> </div>
                                                                                        <div style="line-height:5px;padding:0;margin:0">&nbsp;</div>
                                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                                    </td>
                                                                                    <td align="right" valign="top">

                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2" height="5"></td>
                                                                                </tr>

                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>';

    $mail->Subject = 'RESET PASSWORD';
    $mail->Body    = $htmlContent;
    $mail->AltBody = '';

    if(!$mail->send()) {
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;

    } else {
        //echo 'Message has been sent';
    }

}

function send_email_reset_password($email,$user_id,$user_name,$user_fullname){
    
$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host         = 'smtp.gmail.com';               // Specify main and backup SMTP servers
$mail->SMTPAuth     = true;                           // Enable SMTP authentication
$mail->Username     = EMAIL;                          // SMTP username
$mail->Password     = EMAIL_PASSWORD;                 // SMTP password
$mail->SMTPSecure   = 'tls';                          // Enable TLS encryption, `ssl` also accepted
$mail->Port         = 587 ;                           // TCP port to connect to

$mail->CharSet = "utf-8";


$mail->setFrom(EMAIL, 'บริษัทจิตรวิไลย อินเตอร์ทัวร์'); 
$mail->addAddress($email, $user_fullname);     // Add a recipient



$mail->isHTML(true);                                  // Set email format to HTML
/*$img = '../img/1488461326107.jpg';
$mail->AddAttachment($img);*/

$url_reset_password = URL_SERVER.'/login/resetpassword.php?u='.base64_encode_dev_gun($user_id);

$htmlContent = '<div style="font-family:HelveticaNeue-Light,Arial,sans-serif;background-color:#eeeeee">
                    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">

                        <tbody>
                            <tr>
                                <td>

                                    <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                        <tbody>
                                            <tr>
                                                <td>

                                                    <table width="690" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="3" height="80" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                    <table width="690" align="center" border="0" cellspacing="0" cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="30"></td>
                                                                                <td align="left" valign="middle" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                                    <a href="#" target="_blank">
                                                                                        รูป
                                                                                        <img src="">
                                                                                    </a>
                                                                                </td>
                                                                                <td width="30"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr bgcolor="#ffffff">
                                                                <td width="30" bgcolor="#eeeeee"></td>
                                                                <td>
                                                                    <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="4" align="center">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4" align="center">
                                                                                    <h2 style="font-size:24px">RESET PASSWORD</h2>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="120" align="right" valign="top">
                                                                                    <h3>ชื่อ - สกุล : </h3>
                                                                                </td>
                                                                                <td width="30"></td>
                                                                                <td align="left" valign="middle">
                                                                                    <h3>'.$user_fullname.'</h3>
                                                                                </td>
                                                                                <td width="30"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="120" align="right" valign="top">
                                                                                    <h3> Username : </h3>
                                                                                </td>
                                                                                <td width="30"></td>
                                                                                <td align="left" valign="middle">
                                                                                    <h3>'.$user_name.'</h3>
                                                                                </td>
                                                                                <td width="30"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td colspan="4">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center">
                                                                                    <div style="text-align:center;width:100%;padding:40px 0">
                                                                                        <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;padding:0">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td align="center" style="margin:0;text-align:center">
                                                                                                        <a href="'.$url_reset_password.'" style="font-size:18px;font-family:HelveticaNeue-Light,Arial,sans-serif;line-height:22px;text-decoration:none;color:#ffffff;font-weight:bold;border-radius:2px;background-color:#00a3df;padding:14px 10px;display:block" target="_blank">
                                                                                                            RESET PASSOWORD
                                                                                                        </a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td width="30" bgcolor="#eeeeee"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table width="630" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="2" height="30"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="360" valign="top">
                                                                                    <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"> </div>
                                                                                    <div style="line-height:5px;padding:0;margin:0">&nbsp;</div>
                                                                                    <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                                </td>
                                                                                <td align="right" valign="top">

                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" height="5"></td>
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>';

$mail->Subject = 'RESET PASSWORD';
$mail->Body    = $htmlContent;
$mail->AltBody = '';

if(!$mail->send()) {
    //echo 'Message could not be sent.';
    //echo 'Mailer Error: ' . $mail->ErrorInfo;

} else {
    //echo 'Message has been sent';
}

}

function send_email_invoice($book_code){


// ดึงข้อมูล booking 
    #ws
    $wsserver   = URL_WS;
    $wsfolder	= '/send_email'; //กำหนด Folder
    $wsfile		= '/send_invoice.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                            'book_code'    => $book_code,
                            'method'       => 'GET'
                        );


    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){
        $result = $data_return['results'][0];

        $invoice_no = $result['invoice_code'];
        $book_code  = $result['book_code'];
        $book_id    = $result['book_id'];
        $ser_name   = $result['ser_name'];
        $date       = thai_date_short(strtotime($result['per_date_start'])).' - '.thai_date_short(strtotime($result['per_date_end']));
        $qty        = number_format($result['QTY']);
        $air_name   = $result['air_name'];


        $email      = $result['agen_email'];

        $status     = text_status_book($result['status']);
       

        if($result['status'] == 0){//จอง

        
        //เช็คว่าเคยส่ง invoice หรือยัง ถ้ายังให้ อัพเดทสถานะเป็นส่ง invoice
         #ws
            $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหนด Folder
            $wsfile		= '/update_send_invoice.php'; //กำหนด File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
            $data 		= array(    
                                'book_code'     => $result['book_code'],
                                'method'        => 'PUT'
                                );


            $data_return =	json_decode( post_to_ws($url,$data),true );
        //เช็คว่าเคยส่ง invoice หรือยัง ถ้ายังให้ อัพเดทสถานะเป็นส่ง invoice

        }


    }


    $url_invoice    = URL_SERVER.'print/pdf_invoice.php?book_id='.$book_id.'&book_code='.$book_code;
    $url_logo       = URL_SERVER.'/unity/img/jitwilai_logo.png';
// ดึงข้อมูล booking 




    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host         = 'smtp.gmail.com';               // Specify main and backup SMTP servers
    $mail->SMTPAuth     = true;                           // Enable SMTP authentication
    $mail->Username     = EMAIL;                          // SMTP username
    $mail->Password     = EMAIL_PASSWORD;                 // SMTP password
    $mail->SMTPSecure   = 'tls';                          // Enable TLS encryption, `ssl` also accepted
    $mail->Port         = 587 ;                           // TCP port to connect to

    $mail->CharSet = "utf-8";


    $mail->setFrom(EMAIL, 'บริษัทจิตรวิไลย อินเตอร์ทัวร์'); 
    $mail->addAddress($email, '');     // Add a recipient
    


    $mail->isHTML(true);                                  // Set email format to HTML
    /*$img = '../img/1488461326107.jpg';
    $mail->AddAttachment($img);*/



    $htmlContent = '<div style="font-family:HelveticaNeue-Light,Arial,sans-serif;background-color:#eeeeee">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">

            <tbody>
                <tr>
                    <td>

                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                            <tbody>
                                <tr>
                                    <td>

                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" height="80" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="padding:0;margin:0;font-size:0;line-height:0">
                                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                        <a href="#" target="_blank">
                                                                            รูป
                                                                            <img src="">
                                                                        </a>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#ffffff">
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                    <td>
                                                        <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="4" align="center">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4" align="center">
                                                                        <img style="width:500px;" src="'.$url_logo.'" alt="">
                                                                       
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4" align="center">
                                                                        <h2 style="font-size:24px">ใบแจ้งหนี้/Invoice &nbsp; <br> Invoice No : '.$invoice_no.'</h2>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="200" align="right" valign="top">
                                                                        <h3>หมายเลขใบจอง : </h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                        <h3>'.$book_code.'</h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="200" align="right" valign="top">
                                                                        <h3>สถานะ : </h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                        <h3 class="green">'.$status.'</h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="200" align="right" valign="top">
                                                                        <h3>โปรแกรมทัวร์ : </h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                        <h3>'.$ser_name.'</h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="200" align="right" valign="top">
                                                                        <h3>ช่วงเวลาเดินทาง : </h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                        <h3>'.$date.'</h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="200" align="right" valign="top">
                                                                        <h3>จำนวนผู้เดินทาง : </h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                        <h3>'.$qty.'</h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="200" align="right" valign="top">
                                                                        <h3>สายการบิน : </h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                        <h3>'.$air_name.'</h3>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="4">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center">
                                                                        <div style="text-align:center;width:100%;padding:40px 0">
                                                                            <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;padding:0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" style="margin:0;text-align:center">
                                                                                            <a href="'.$url_invoice.'" style="font-size:18px;font-family:HelveticaNeue-Light,Arial,sans-serif;line-height:22px;text-decoration:none;color:#ffffff;font-weight:bold;border-radius:2px;background-color:#00a3df;padding:14px 10px;display:block" target="_blank">
                                                                                                ดาวน์โหลด Invoice
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="630" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="2" height="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="360" valign="top">
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                        <div style="line-height:5px;padding:0;margin:0">&nbsp;</div>
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                    </td>
                                                                    <td align="right" valign="top">

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" height="5"></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>

        </table>
    </div>';


    $mail->Subject = 'แจ้ง Invoice'; // ชื่อเรื่องอีเมล
    $mail->Body    = $htmlContent;
    $mail->AltBody = '';

    $set_date = array();


    if(!$mail->send()) {
        $set_date['status'] = 'FALSE';
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;

    } else {
        $set_date['status'] = 'TRUE';
        //echo 'Message has been sent';
    }

    echo json_encode($set_date);
}



function send_email_booking($book_code){


// ดึงข้อมูล booking 
    #ws
    $wsserver   = URL_WS;
    $wsfolder	= '/send_email'; //กำหนด Folder
    $wsfile		= '/send_booking.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                            'book_code'    => $book_code,
                            'method'       => 'GET'
                        );


    $data_return =	json_decode( post_to_ws($url,$data),true );

  

    if($data_return['status'] == 200){
        $result = $data_return['results'][0];


        $user_fullname  = $result['user_name'];
        $book_date  = thai_date_short(strtotime($result['book_date']));
        $book_code  = $result['book_code'];
        
        $agen_com_name = $result['agen_com_name'];
        $agen_name     = $result['agen_name'];
        $agen_tel      = $result['agen_tel'];
        $agen_email    = $result['agen_email'];

        $ser_code      = $result['ser_code'];
        $ser_name      = $result['ser_name'];

        $date       = thai_date_short(strtotime($result['per_date_start'])).' - '.thai_date_short(strtotime($result['per_date_end']));
        $amountgrandtotal = number_format($result['book_amountgrandtotal']);
        $commition      = number_format($result['per_com_company_agency']).'+'.number_format($result['per_com_agency']);
        $status     = text_status_book($result['status']);

        $book_comment = $result['book_comment'];


       

     

    }


    $url_invoice    = URL_SERVER.'print/pdf_invoice.php?book_id='.$book_id.'&book_code='.$book_code;
    $url_logo       = URL_SERVER.'/unity/img/jitwilai_logo.png';
// ดึงข้อมูล booking 




    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host         = 'smtp.gmail.com';               // Specify main and backup SMTP servers
    $mail->SMTPAuth     = true;                           // Enable SMTP authentication
    $mail->Username     = EMAIL;                          // SMTP username
    $mail->Password     = EMAIL_PASSWORD;                 // SMTP password
    $mail->SMTPSecure   = 'tls';                          // Enable TLS encryption, `ssl` also accepted
    $mail->Port         = 587 ;                           // TCP port to connect to

    $mail->CharSet = "utf-8";


    $mail->setFrom(EMAIL, 'บริษัทจิตรวิไลย อินเตอร์ทัวร์'); 
    $mail->addAddress($agen_email, '');     // Add a recipient
    


    $mail->isHTML(true);                                  // Set email format to HTML
    /*$img = '../img/1488461326107.jpg';
    $mail->AddAttachment($img);*/



    $htmlContent = '<div style="font-family:HelveticaNeue-Light,Arial,sans-serif;background-color:#eeeeee">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">

            <tbody>
                <tr>
                    <td>

                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                            <tbody>
                                <tr>
                                    <td>

                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" height="80" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="padding:0;margin:0;font-size:0;line-height:0">
                                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                        <a href="#" target="_blank">
                                                                            รูป
                                                                            <img src="">
                                                                        </a>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#ffffff">
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                    <td>
                                                        <table width="570" align="left" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="7" align="center">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7" align="center">
                                                                         <img style="width:500px;" src="'.$url_logo.'" alt="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7" align="center">
                                                                        <h2 style="font-size:24px">การจอง/BOOKING</h2>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>ผู้รับจอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="4">
                                                                        <h5> '.$user_fullname.' </h5>
                                                                    </td> 
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่จอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$book_date.'</h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>เลขที่การจอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$book_code.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>AGENT : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_com_name.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>ผู้จอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_name.' </h5>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>เบอร์โทรศัพท์ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_tel.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>Email : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_email.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>โปรแกรมทัวร์ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$ser_name.' </h5>
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>รหัสโปรแกรม : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$ser_code.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่เดินทาง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$date.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>ราคา : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$amountgrandtotal.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>คอมมิชชั่น : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$commition.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>สถานะ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$status.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>หมายเหตุ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$book_comment.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="4">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center">
                                                                        <div style="text-align:center;width:100%;padding:40px 0">
                                                                            <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;padding:0">
                                                                                <tbody>
                                                                                    
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="630" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="2" height="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="360" valign="top">
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                        <div style="line-height:5px;padding:0;margin:0">&nbsp;</div>
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                    </td>
                                                                    <td align="right" valign="top">

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" height="5"></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>

        </table>
    </div>';

   
   

    $mail->Subject = 'แจ้ง Booking'; // ชื่อเรื่องอีเมล
    $mail->Body    = $htmlContent;
    $mail->AltBody = '';

    $set_date = array();


    if(!$mail->send()) {
        $set_date['status'] = 'FALSE';
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;

    } else {
        $set_date['status'] = 'TRUE';
        //echo 'Message has been sent';
    }

   // echo json_encode($set_date);
}

function send_email_payment($book_id,$pay_id){

  $book_id = str_replace("'","",$book_id);
// ดึงข้อมูล booking 
    #ws
    $wsserver   = URL_WS;
    $wsfolder	= '/send_email'; //กำหนด Folder
    $wsfile		= '/send_payment.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                            'book_id'    => $book_id,
                            'pay_id'     => $pay_id,
                            'method'     => 'GET'
                        );


    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){
        $result = $data_return['results'][0];


        $user_fullname  = $result['user_name'];
        $book_date  = thai_date_short(strtotime($result['book_date']));
        $book_code  = $result['book_code'];
        
        $agen_com_name = $result['agen_com_name'];
        $agen_name     = $result['agen_name'];
        $agen_tel      = $result['agen_tel'];
        $agen_email    = $result['agen_email'];

        $ser_code      = $result['ser_code'];
        $ser_name      = $result['ser_name'];

        $date       = thai_date_short(strtotime($result['per_date_start'])).' - '.thai_date_short(strtotime($result['per_date_end']));

        $pay_date   = thai_date_short(strtotime($result['pay_date']));
        $pay_time   = $result['pay_time'];

        $pay_received = number_format($result['pay_received']);

        $amountgrandtotal = number_format($result['book_amountgrandtotal']);
        $commition      = number_format($result['per_com_company_agency']).'+'.number_format($result['per_com_agency']);
    
        if($result['status'] == 0){ 
            return;
            $status = '<h4 style="color:red">ไม่รับการอนุมัติ</h4>'; //ไม่ได้รับอนุมัติ ไม่ส่ง email
        }
        else{
            $status = '<h4 style="color:green">อนุมัติ</h4>';
        }
        if($result['book_status'] == 20){ 
            $book_status = '<h4 style="color:green">ชำระ มัดจำ(Deposite)บางส่วน</h4>'; //ไม่ได้รับอนุมัติ ไม่ส่ง email
        }
        else if($result['book_status'] == 25){
            $book_status = '<h4 style="color:green">ชำระ มัดจำ(Deposite)เต็มจำนวน</h4>';
        }
        else if($result['book_status'] == 30){
            $book_status = '<h4 style="color:green">ชำระ เต็มจำนวน(Full payment)บางส่วน</h4>';
        }
        else if($result['book_status'] == 35){
            $book_status = '<h4 style="color:green">ชำระ เต็มจำนวน(Full payment)เต็มจำนวน</h4>';
        }

        $status_book   = text_status_book($result['status_book']);
    
     


       

     

    }


    //$url_invoice    = URL_SERVER.'print/pdf_invoice.php?book_id='.$book_id.'&book_code='.$book_code;
    $url_logo       = URL_SERVER.'/unity/img/jitwilai_logo.png';
// ดึงข้อมูล booking 




    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host         = 'smtp.gmail.com';               // Specify main and backup SMTP servers
    $mail->SMTPAuth     = true;                           // Enable SMTP authentication
    $mail->Username     = EMAIL;                          // SMTP username
    $mail->Password     = EMAIL_PASSWORD;                 // SMTP password
    $mail->SMTPSecure   = 'tls';                          // Enable TLS encryption, `ssl` also accepted
    $mail->Port         = 587 ;                           // TCP port to connect to

    $mail->CharSet = "utf-8";


    $mail->setFrom(EMAIL, 'บริษัทจิตรวิไลย อินเตอร์ทัวร์'); 
    $mail->addAddress($agen_email, '');     // Add a recipient
    


    $mail->isHTML(true);                                  // Set email format to HTML
    /*$img = '../img/1488461326107.jpg';
    $mail->AddAttachment($img);*/



    $htmlContent = '<div style="font-family:HelveticaNeue-Light,Arial,sans-serif;background-color:#eeeeee">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">

            <tbody>
                <tr>
                    <td>

                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                            <tbody>
                                <tr>
                                    <td>

                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" height="80" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="padding:0;margin:0;font-size:0;line-height:0">
                                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                        <a href="#" target="_blank">
                                                                            รูป
                                                                            <img src="">
                                                                        </a>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#ffffff">
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                    <td>
                                                        <table width="570" align="left" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="7" align="center">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7" align="center">
                                                                         <img style="width:500px;" src="'.$url_logo.'" alt="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7" align="center">
                                                                        <h2 style="font-size:24px">อนุมัติการชำระเงิน</h2>
                                                                    </td>
                                                                </tr>
                                                                  <tr>
                                                                    <td colspan="7" align="center">
                                                                        <h2 style="font-size:24px">เลขที่การจอง '.$book_code.' </h2>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>ผู้รับจอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="4">
                                                                        <h5> '.$user_fullname.' </h5>
                                                                    </td> 
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่จอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$book_date.'</h5>
                                                                    </td>
                                                                 
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>AGENT : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_com_name.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>ผู้จอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_name.' </h5>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>เบอร์โทรศัพท์ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_tel.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>Email : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_email.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>โปรแกรมทัวร์ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$ser_name.' </h5>
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>รหัสโปรแกรม : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$ser_code.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่เดินทาง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$date.' </h5>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่โอน : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$pay_date.' '.$pay_time.'</h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>จำนวนเงิน : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$pay_received.' </h5>
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>สถานะการจอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$book_status.' </h5>
                                                                    </td>
                                                                </tr>

                                                               
                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="4">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center">
                                                                        <div style="text-align:center;width:100%;padding:40px 0">
                                                                            <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;padding:0">
                                                                                <tbody>
                                                                                    
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="630" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="2" height="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="360" valign="top">
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                        <div style="line-height:5px;padding:0;margin:0">&nbsp;</div>
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                    </td>
                                                                    <td align="right" valign="top">

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" height="5"></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>

        </table>
    </div>';


    $mail->Subject = 'แจ้ง สถานะการชำระเงิน'; // ชื่อเรื่องอีเมล
    $mail->Body    = $htmlContent;
    $mail->AltBody = '';

    $set_date = array();


    if(!$mail->send()) {
        $set_date['status'] = 'FALSE';
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;

    } else {
        $set_date['status'] = 'TRUE';
        //echo 'Message has been sent';
    }

    //echo json_encode($set_date);
}

function send_email_payment_cancel($book_id,$pay_id){

  $book_id = str_replace("'","",$book_id);
// ดึงข้อมูล booking 
    #ws
    $wsserver   = URL_WS;
    $wsfolder	= '/send_email'; //กำหนด Folder
    $wsfile		= '/send_payment.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                            'book_id'    => $book_id,
                            'pay_id'     => $pay_id,
                            'method'     => 'GET'
                        );


    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){
        $result = $data_return['results'][0];


        $user_fullname  = $result['user_name'];
        $book_date  = thai_date_short(strtotime($result['book_date']));
        $book_code  = $result['book_code'];
        
        $agen_com_name = $result['agen_com_name'];
        $agen_name     = $result['agen_name'];
        $agen_tel      = $result['agen_tel'];
        $agen_email    = $result['agen_email'];

        $ser_code      = $result['ser_code'];
        $ser_name      = $result['ser_name'];

        $date       = thai_date_short(strtotime($result['per_date_start'])).' - '.thai_date_short(strtotime($result['per_date_end']));

        $pay_date   = thai_date_short(strtotime($result['pay_date']));
        $pay_time   = $result['pay_time'];

        $pay_received = number_format($result['pay_received']);

        $amountgrandtotal = number_format($result['book_amountgrandtotal']);
        $commition      = number_format($result['per_com_company_agency']).'+'.number_format($result['per_com_agency']);
    
        if($result['status'] == 9){ 
          
            $status = '<h4 style="color:red">ไม่รับการอนุมัติ</h4>'; //ไม่ได้รับอนุมัติ ไม่ส่ง email
        }
        else{
            $status = '<h4 style="color:green">อนุมัติ</h4>';
        }
        if($result['book_status'] == 20){ 
            $book_status = '<h4 style="color:green">ชำระ มัดจำ(Deposite)บางส่วน</h4>'; //ไม่ได้รับอนุมัติ ไม่ส่ง email
        }
        else if($result['book_status'] == 25){
            $book_status = '<h4 style="color:green">ชำระ มัดจำ(Deposite)เต็มจำนวน</h4>';
        }
        else if($result['book_status'] == 30){
            $book_status = '<h4 style="color:green">ชำระ เต็มจำนวน(Full payment)บางส่วน</h4>';
        }
        else if($result['book_status'] == 35){
            $book_status = '<h4 style="color:green">ชำระ เต็มจำนวน(Full payment)เต็มจำนวน</h4>';
        }

        $remark = '<h4 style="color:red">'.$result['remark'].'</h4>';
        $remark_cancel = '<h4 style="color:red">'.$result['remark_cancel'].'</h4>';

        $status_book   = text_status_book($result['status_book']);
    
     


       

     

    }


    //$url_invoice    = URL_SERVER.'print/pdf_invoice.php?book_id='.$book_id.'&book_code='.$book_code;
    $url_logo       = URL_SERVER.'/unity/img/jitwilai_logo.png';
// ดึงข้อมูล booking 




    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host         = 'smtp.gmail.com';               // Specify main and backup SMTP servers
    $mail->SMTPAuth     = true;                           // Enable SMTP authentication
    $mail->Username     = EMAIL;                          // SMTP username
    $mail->Password     = EMAIL_PASSWORD;                 // SMTP password
    $mail->SMTPSecure   = 'tls';                          // Enable TLS encryption, `ssl` also accepted
    $mail->Port         = 587 ;                           // TCP port to connect to

    $mail->CharSet = "utf-8";


    $mail->setFrom(EMAIL, 'บริษัทจิตรวิไลย อินเตอร์ทัวร์'); 
    $mail->addAddress($agen_email, '');     // Add a recipient
    


    $mail->isHTML(true);                                  // Set email format to HTML
    /*$img = '../img/1488461326107.jpg';
    $mail->AddAttachment($img);*/



    $htmlContent = '<div style="font-family:HelveticaNeue-Light,Arial,sans-serif;background-color:#eeeeee">
        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">

            <tbody>
                <tr>
                    <td>

                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                            <tbody>
                                <tr>
                                    <td>

                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" height="80" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="padding:0;margin:0;font-size:0;line-height:0">
                                                        <table width="690" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" style="padding:0;margin:0;font-size:0;line-height:0">
                                                                        <a href="#" target="_blank">
                                                                            รูป
                                                                            <img src="">
                                                                        </a>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#ffffff">
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                    <td>
                                                        <table width="570" align="left" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="7" align="center">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7" align="center">
                                                                         <img style="width:500px;" src="'.$url_logo.'" alt="">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7" align="center">
                                                                        <h2 style="font-size:24px; color:red" >ไม่ผ่านการอนุมัติชำระเงิน</h2>
                                                                    </td>
                                                                </tr>
                                                                  <tr>
                                                                    <td colspan="7" align="center">
                                                                        <h2 style="font-size:24px">เลขที่การจอง '.$book_code.' </h2>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="7">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>ผู้รับจอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="4">
                                                                        <h5> '.$user_fullname.' </h5>
                                                                    </td> 
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่จอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$book_date.'</h5>
                                                                    </td>
                                                                 
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>AGENT : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_com_name.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>ผู้จอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_name.' </h5>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>เบอร์โทรศัพท์ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_tel.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>Email : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$agen_email.' </h5>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>โปรแกรมทัวร์ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$ser_name.' </h5>
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>รหัสโปรแกรม : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$ser_code.' </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่เดินทาง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$date.' </h5>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>วันที่โอน : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$pay_date.' '.$pay_time.'</h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>จำนวนเงิน : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle">
                                                                         <h5>'.$pay_received.' </h5>
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>สถานะการจอง : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$status.' </h5>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="100" align="right" valign="top">
                                                                        <h5>หมายเหตุ : </h5>
                                                                    </td>
                                                                    <td width="30"></td>
                                                                    <td align="left" valign="middle" colspan="5">
                                                                         <h5>'.$remark_cancel.' </h5>
                                                                    </td>
                                                                </tr>

                                                               
                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5" height="40" style="padding:0;margin:0;font-size:0;line-height:0"></td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="4">&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table width="570" align="center" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center">
                                                                        <div style="text-align:center;width:100%;padding:40px 0">
                                                                            <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;padding:0">
                                                                                <tbody>
                                                                                    
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td width="30" bgcolor="#eeeeee"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table align="center" width="750px" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee" style="width:750px!important">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="630" align="center" border="0" cellspacing="0" cellpadding="0" bgcolor="#eeeeee">
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="2" height="30"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="360" valign="top">
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                        <div style="line-height:5px;padding:0;margin:0">&nbsp;</div>
                                                                        <div style="color:#a3a3a3;font-size:12px;line-height:12px;padding:0;margin:0"></div>
                                                                    </td>
                                                                    <td align="right" valign="top">

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" height="5"></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>

        </table>
    </div>';


    $mail->Subject = 'แจ้ง สถานะการชำระเงิน'; // ชื่อเรื่องอีเมล
    $mail->Body    = $htmlContent;
    $mail->AltBody = '';

    $set_date = array();


    if(!$mail->send()) {
        $set_date['status'] = 'FALSE';
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;

    } else {
        $set_date['status'] = 'TRUE';
        //echo 'Message has been sent';
    }

    //echo json_encode($set_date);
}

?>