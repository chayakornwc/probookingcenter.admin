<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD

if($_REQUEST['method'] == 1){//select list

    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/manage_user'; //กำหนด Folder
    $wsfile		= '/select_list_user.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'word_search'   => $_REQUEST['word_search'],
                                'status_1'      => $_REQUEST['status_1'],
                                'status_2'      => $_REQUEST['status_2'],

                                'offset'        => $_REQUEST['offset'],
                                'limit'         => LIMIT,
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    $set_data['all_row'] = intval($data_return['all_row']);
    $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page


    $thead = '<thead>
				<tr>
                    <th width="5%"> #</th>
                    <th width="10%">Status</th>
                    <th width="15%">กลุ่มผู้ใช้งาน</th>
                    <th width="20%">Username</th>
                    <th width="20%">ชื่อ - สกุล</th>
                    <th width="15%">E-mail</th>
                    <th width="10%">เบอร์โทรศัพท์</th>
                    <th width="10%"></th>
                </tr>
			</thead>';

    if($data_return['status'] != 200){
        if($data_return['status']==503){
            $non_txt = 'เกิดข้อผิดพลาดระหว่างการเข้าถึง เซิร์ฟเวอร์';
        }
        else if($data_return['status'] == 204){
            $non_txt = 'ไม่พบข้อมูลที่ค้นหา';
        }
        else{
            $non_txt = 'การเชื่อมต่อเซิฟเวอร์ผิดพลาด';
        }
        $tbody = '<tbody><tr>
                        <td colspan="20" style="text-align:center">
                            <div class="alert alert-warning" role="alert">
                            <font style="font-size:25px">'.$non_txt.'</font>
                            </div>
                        </td>
                    </tr>
                </tbody>';
    }
    else{

        $count		= ($_REQUEST['offset']*LIMIT)+1;
        $result     = $data_return['results'];
        $tr         = '';
        
        foreach($result as $key => $value){

            if($value['status'] == 1){
                $status = '<div class="label label-success">ใช้งาน</div>';
            }
            else if($value['status'] == 2){
                $status = '<div class="label label-warning">ระงับการใช้งาน</div>';
            }
            else if($value['status'] == 9){
                $status = '<div class="label label-error">ลบ</div>';
            }
            else{
                $status = '';
            }

            $td = '<td class="text-center">'.number_format($count++).'</td>';
            $td .= '<td class="text-center">'.$status.'</td>';
            $td .= '<td class="text-center">'.$value['group_name'].'</td>';
            $td .= '<td class="text-center">'.$value['user_name'].'</td>';
            $td .= '<td class="text-left">'.$value['user_fname'].' '.$value['user_lname'].'</td>';
            $td .= '<td class="text-center">'.$value['user_email'].'</td>';
            $td .= '<td class="text-center">'.$value['user_tel'].'</td>';
            $td .= '<td class="text-center">
                        <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_user(\'update\','.$value['user_id'].')">
                            <em class="icon-note"></em>
                            แก้ไข
                        </button>
                    </td>';
            

            $tr .= '<tr>'.$td.'</tr>';
        }
        $tbody = '<tbody>'.$tr.'</tbody>';

    }


    $set_data['interface_table'] = '<table id="" class="table table-bordered table-hover table-devgun">
                '.$thead.'
                '.$tbody.'
                </table>
            ';


    echo json_encode($set_data);




}
else if($_REQUEST['method'] == 2){ // modal manage 

    #REQUEST

    $action_method = $_REQUEST['action_method'];

    if($action_method == 'add'){

        
        $title_modal        = 'เพิ่ม';
        $user_name          = '';
        $user_fname         = '';
        $user_lname         = '';
        $user_nickname      = '';
        $user_email         = '';
        $user_tel           = '';
        $user_group         = '';
        $user_line_id       = '';
        $user_address       = '';
        $method             = 3;

        $password = '<div class="form-group">
                            <label class="col-lg-3 control-label">Password :</label>
                            <div class="col-lg-7">
                               
                                <input type="password" class="form-control" id="user_password" name="user_password">
                                    
                            </div>
                        </div>';
        
        $readonly           = '';


        $status_1 = 'checked';
        $status_2 = '';

    }
    else {

         
        $title_modal        = 'แก้ไข';
        $user_name          = '';
        $user_fname         = '';
        $user_lname         = '';
        $user_nickname      = '';
        $user_email         = '';
        $user_tel           = '';
        $user_group         = '';
        $user_line_id       = '';
        $user_address       = '';
        $method             = 4;

        $password = ' <div class="form-group">
                                <div class="col-lg-7 col-lg-offset-7">
                                    <button type="button" class="mb-sm btn btn-warning  btn-xs" onclick="manage_password('.$_REQUEST['user_id'].')">
                                        <em class="icon-reload"></em>
                                        แก้ไขรหัสผ่าน
                                    </button>
                                </div>
                            </div>';
        $password = '';
        
        $readonly           = 'readonly';


        #WS
        $wsserver   = URL_WS;
        $wsfolder	= '/manage_user'; //กำหนด Folder
        $wsfile		= '/select_id_user.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    

                                    'user_id'       => $_REQUEST['user_id'],
                                    'method'        => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );


        if($data_return['status'] == 200){

            $result = $data_return['results'][0];

            $user_name  = $result['user_name'];
            $user_fname = $result['user_fname'];
            $user_lname = $result['user_lname'];
            $user_nickname = $result['user_nickname'];
            $user_email = $result['user_email'];
            $user_tel   = $result['user_tel'];
            $user_group = $result['group_id'];
            $user_line_id = $result['user_line_id'];
            $user_address = $result['user_address'];

            if($result['status'] == 1){

                $status_1 = 'checked';
                $status_2 = '';

            }
            else{

                $status_1 = '';
                $status_2 = 'checked';

            }


        }

    }
   

    #USER GROUP
    $wsserver   = URL_WS;
    $wsfolder	= '/manage_usergroup'; //กำหนด Folder
    $wsfile		= '/select_list_usergroup.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){

        $result = $data_return['results'];
        $option_user_group = '';

        foreach($result as $key => $value){

            $selected_user_group = '';
            if($user_group == $value['group_id']){
                $selected_user_group = 'selected';
            }

            $option_user_group .= '<option value="'.$value['group_id'].'" '.$selected_user_group.'>'.$value['group_name'].'</option>';
        }

    }
    else{
        $option_user_group = '<option value="">ไม่พบข้อมูล</option>';
    }



    $set_data['modal'] = ' <div id="modal-manage-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 id="myModalLabel" class="modal-title">'.$title_modal.' ผู้ใช้งาน</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" value="'.$_REQUEST['user_id'].'" name="user_id" id="user_id">
                                        <input type="hidden" value="'.$user_name.'" name="user_name_old" id="user_name_old">
                                        <input type="hidden" value="'.$user_email.'" name="user_email_old" id="user_email_old">

                                        <form class="form-horizontal">

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">ชื่อ :</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="user_fname" name="user_fname" value="'.$user_fname.'" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">สกุล :</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="user_lname" name="user_lname" value="'.$user_lname.'" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">ชื่อเล่น :</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="user_nickname" name="user_nickname" value="'.$user_nickname.'">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">Email :</label>
                                                <div class="col-lg-7">
                                                  
                                                    <input type="email" class="form-control" id="user_email" name="user_email" value="'.$user_email.'" required>
                                                  
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">เบอร์โทรศัพท์ :</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="user_tel" name="user_tel" value="'.$user_tel.'" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">Line ID :</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="user_line_id" name="user_line_id" value="'.$user_line_id.'" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">ที่อยู่ :</label>
                                                <div class="col-lg-7">
                                                    <textarea rows="4" cols="" class="form-control note-editor" style="resize:none;" id="user_address" name="user_address">'.$user_address.'</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">Username :</label>
                                                <div class="col-lg-7">
                                                  
                                                    <input type="text" class="form-control input_eng_num" id="user_name" name="user_name" value="'.$user_name.'" '.$readonly.'>
                                                        
                                                </div>
                                            </div>

                                            '.$password.'

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">กลุ่มผู้ใช้งาน :</label>
                                                <div class="col-lg-7">
                                                    
                                                    <select name="user_group_id" id="user_group_id" class="chosen-select form-control">
                                                        '.$option_user_group.'
                                                    </select>
                                                    
                                                </div>
                                            </div>

                                        
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Status : </label>
                                            <div class="col-sm-7">
                                                <div class="radio c-radio">
                                                    <label>
                                                        <input type="radio" name="user_status" class="status_1" value="1" '.$status_1.'>
                                                        <span class="fa fa-circle"></span>
                                                            ใช้งาน
                                                        </label>
                                                </div>
                                                <div class="radio c-radio">
                                                    <label>
                                                        <input type="radio" name="user_status" class="status_2" value="2" '.$status_2.'>
                                                        <span class="fa fa-circle"></span>
                                                            ระงับการใช้งาน
                                                        </label>
                                                </div>
                                            </div>
                                            </div>

                                            
                                        </form>


                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                        <button type="button" class="btn btn-primary" onclick="check_submit('.$method.')">
                                            <em class="icon-cursor"></em>
                                            บันทีก
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>';

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 3){ // insert 


    $wsserver   = URL_WS;
    $wsfolder	= '/manage_user'; //กำหนด Folder
    $wsfile		= '/insert_user.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'status'            => $_REQUEST['user_status'],
                                'group_id'          => $_REQUEST['user_group_id'],
                                'user_name'         => trim($_REQUEST['user_name']),
                                'user_password'     => hash_password(trim($_REQUEST['user_password'])),
                                'user_fname'        => $_REQUEST['user_fname'],
                                'user_lname'        => $_REQUEST['user_lname'],
                                'user_nickname'        => $_REQUEST['user_nickname'],
                                'user_email'        => trim($_REQUEST['user_email']),
                                'user_address'      => $_REQUEST['user_address'],
                                'user_tel'          => $_REQUEST['user_tel'],
                                'user_line_id'      => $_REQUEST['user_line_id'],
                                'create_user_id'    => $_SESSION['login']['user_id'],
                                'remark'            => '',


                                'method'            => 'PUT'
                            );
   
   $data_return =	json_decode( post_to_ws($url,$data),true );
  
   $set_data['data_return']    = $data_return;
   $set_data['status']         = $data_return['status'];
   $set_data['type_alert']     = 1;

   echo json_encode($set_data);


}
else if($_REQUEST['method'] == 4){ // update
    
   
    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/manage_user'; //กำหนด Folder
    $wsfile		= '/update_user.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                
                                'user_id'           => $_REQUEST['user_id'],
                                'status'            => $_REQUEST['user_status'],
                                'group_id'          => $_REQUEST['user_group_id'],
                                'user_fname'        => $_REQUEST['user_fname'],
                                'user_lname'        => $_REQUEST['user_lname'],
                                'user_nickname'        => $_REQUEST['user_nickname'],
                                'user_email'        => $_REQUEST['user_email'],
                                'user_address'      => $_REQUEST['user_address'],
                                'user_tel'          => $_REQUEST['user_tel'],
                                'user_line_id'      => $_REQUEST['user_line_id'],
                                'update_user_id'    => $_SESSION['login']['user_id'],
                                'remark'            => '',

                                'method'            => 'PUT'
                            );
   
   $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 5){ //check user_name & email


    if($_REQUEST['user_email'] != $_REQUEST['user_email_old']){

        #Email
        $wsserver   = URL_WS;
        $wsfolder	= '/manage_user'; //กำหนด Folder
        $wsfile		= '/check_email.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    
                                    'user_email'     => $_REQUEST['user_email'],
                                    'method'        => 'GET'
                                );

    
        $data_return =	json_decode( post_to_ws($url,$data),true );

        $set_data['email_status'] = $data_return['results'];

    }
    else{
        $set_data['email_status'] = 'TRUE';
    }


    if($_REQUEST['user_name'] != $_REQUEST['user_name_old']){

        #USER NAME
        $wsserver   = URL_WS;
        $wsfolder	= '/manage_user'; //กำหนด Folder
        $wsfile		= '/check_user.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    
                                    'user_name'     => $_REQUEST['user_name'],
                                    'method'        => 'GET'
                                );

    
        $data_return =	json_decode( post_to_ws($url,$data),true );
        
        $set_data['username_status'] = $data_return['results'];
      
    }
    else{
        $set_data['username_status'] = 'TRUE';
    }


    echo json_encode($set_data);

    

}
else if($_REQUEST['method'] == 6){ // modal update password


    $set_data['modal'] = '<div id="modal-manage-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 id="myModalLabel" class="modal-title">แก้ไขรหัสผ่าน</h4>
                                        </div>
                                        <div class="modal-body">

                                            <form class="form-horizontal">
                                                <input type="hidden" value="'.$_REQUEST['user_id'].'" name="user_id_password" id="user_id_password">
                                                <div class="form-group">
                                                    <label class="col-lg-2 control-label">Password :</label>
                                                    <div class="col-lg-10">
                                                        <input type="password" class="form-control" id="user_password" name="user_password">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-lg-2 control-label">Confirm Password :</label>
                                                    <div class="col-lg-10">
                                                        <input type="password" class="form-control" id="confirm_user_password" name="confirm_user_password">
                                                    </div>
                                                </div>
                                                
                                            </form>


                                        </div>


                                        <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                            <button type="button" class="btn btn-primary" onclick="check_submit_password()">
                                                <em class="icon-cursor"></em>
                                                บันทีก
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>';
    
    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 7){


    $wsserver   = URL_WS;
    $wsfolder	= '/manage_user'; //กำหนด Folder
    $wsfile		= '/update_user_password.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                
                                'user_id'           => $_REQUEST['user_id_password'],
                                'user_password'     => hash_password(trim($_REQUEST['user_password'])),

                                'update_user_id'    => $_SESSION['login']['user_id'],
                                'method'            => 'PUT'
                            );
   
   $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);


}
else{

}
















?>