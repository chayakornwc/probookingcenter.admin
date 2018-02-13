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
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/manage_usergroup';      # กำหนด Folder
    $wsfile		= '/select_list_usergroup.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'method'          => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    #RETURN
    $set_data = $data_return;


    $thead = ' <thead>
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th class="text-center" width="40%">ชื่อกลุ่ม</th>
                        <th class="text-center" width="15%"></th>
                        
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

        $num_row = 1;
        $result = $data_return['results'];
        
        $tr = '';
        foreach($result as $key => $value){

            $td = '<td class="text-center">'.$num_row.'</td>';
            $td .= '<td class="text-center">'.$value['group_name'].'</td>';
            
            $td .= '<td class="text-center">
                         <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_user_group(\'update\','.$value['group_id'].')">
                            <em class="icon-note"></em>
                            แก้ไข
                        </button>
                    </td>';

            $tr .= '<tr>'.$td.'</tr>';

            $num_row++;

        }

        $tbody = '<tbody>'.$tr.'</tbody>';

    }



    $table = '<table id="" class="table table-bordered table-hover table-devgun">   
                    '.$thead.'
                    '.$tbody.'
                </table>';

    $set_data['table'] = $table;


    echo json_encode($set_data);
    


}
else if($_REQUEST['method'] == 2){ // modal manage 

    #REQUEST

    $action_method = $_REQUEST['action_method'];

    if($action_method == 'add'){

        $title_modal        = 'เพิ่ม';
        $user_group_id      = '';
        $user_group_name    = '';
        $method             = 3;



    }
    else{
        
        $title_modal        = 'แก้ไข';
        $user_group_id      = '';
        $user_group_name    = '';
        $method             = 4;

        #WS
        $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder	= '/manage_usergroup';      # กำหนด Folder
        $wsfile		= '/select_by_id_usergroup.php';     # กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(
                                'group_id'        => $_REQUEST['group_id'],
                                'method'          => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );

        
        if($data_return['status'] == 200){

            $result = $data_return['results'][0];

            $user_group_id      = $result['group_id'];
            $user_group_name    = $result['group_name'];

        }

        
       

    }
    


    $set_data['modal'] = '<div id="modal-manage-user-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 id="myModalLabel" class="modal-title">'.$title_modal.' กลุ่มผู้ใช้งาน</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" name="user_group_id" id="user_group_id" value="'.$user_group_id.'">
                                        <input type="hidden" name="user_group_name_old" id="user_group_name_old" value="'.$user_group_name.'">
                                        <div class="form-horizontal">

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">ชื่อกลุ่มผู้ใช้งาน :</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="user_group_name" name="user_group_name" value="'.$user_group_name.'">
                                                </div>
                                            </div>


                                            
                                        </div>


                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                        <button type="button" class="btn btn-primary" onclick="check_submit_user_group('.$method.')">
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

    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/manage_usergroup';      # กำหนด Folder
    $wsfile		=	'/insert_usergroup.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'group_name'      => $_REQUEST['user_group_name'],
                            'create_user_id'  => $_SESSION['login']['user_id'],
                            
                            'remark'          => '',
                            'method'          => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 4){ // update
    
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/manage_usergroup';      # กำหนด Folder
    $wsfile		=	'/update_usergroup.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  

                            'group_id'        => $_REQUEST['user_group_id'],
                            'group_name'      => $_REQUEST['user_group_name'],
                            'update_user_id'  => $_SESSION['login']['user_id'],
                            
                            'remark'          => '',
                            'method'          => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 5){ //check group name

    if($_REQUEST['user_group_name'] != $_REQUEST['user_group_name_old']){


        #WS
        $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder	=   '/manage_usergroup';      # กำหนด Folder
        $wsfile		=	'/check_groupname.php';     # กำหนด File
        $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		=	array(  
                                'group_name'      => $_REQUEST['user_group_name'],
                                'method'          => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );

        $set_data['status'] = $data_return['results'];

    }
    else{

        $set_data['status'] = 'TRUE';

    }

 
    echo json_encode($set_data);




}
else{

}
















?>