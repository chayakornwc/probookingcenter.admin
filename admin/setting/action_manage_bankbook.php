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
    $wsfolder	= '/manage_bankbook';      # กำหนด Folder
    $wsfile		= '/select_list_bankbook.php';     # กำหนด File
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
                        <th class="text-center" width="15%">Status</th>
                        <th class="text-center" width="15%">ธนาคาร</th>
                        <th class="text-center" width="15%">สาขา</th>
                        <th class="text-center" width="15%">เลขที่บัญชี</th>
                        <th class="text-center" width="15%">ชื่อสมุดบัญชี</th>
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


            if($value['status'] == 1){
                $status = '<div class="label label-success">ใช้งาน</div>';
            }
            else if($value['status'] == 2){
                $status = '<div class="label label-warning">ระงับการใช้งาน</div>';
            }


            $td = '<td class="text-center">'.$num_row.'</td>';
            $td .= '<td class="text-center">'.$status.'</td>';
            $td .= '<td class="text-center">'.$value['bank_name'].'</td>';
            $td .= '<td class="text-center">'.$value['bankbook_branch'].'</td>';
            $td .= '<td class="text-center">'.$value['bankbook_code'].'</td>';
            $td .= '<td class="text-center">'.$value['bankbook_name'].'</td>';
            
            $td .= '<td class="text-center">
                          <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_bankbook(\'update\','.$value['bankbook_id'].')">
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
        $method             = 3;

        $bankbook_id            = '';
        $bank_name              = '';
        $bankbook_branch        = '';
        $bankbook_code          = '';
        $bankbook_name          = '';

        $bankbook_status_1      = 'checked';
        $bankbook_status_2      = '';


    }
    else{
        
        $title_modal        = 'แก้ไข';
        $user_group_id      = '';
        $user_group_name    = '';
        $method             = 4;

        #WS
        $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder	= '/manage_bankbook';      # กำหนด Folder
        $wsfile		= '/select_by_id_bankbook.php';     # กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(
                                'bankbook_id'        => $_REQUEST['bankbook_id'],
                                'method'          => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );

        
        if($data_return['status'] == 200){

            $result = $data_return['results'][0];

            $bankbook_id            = $result['bankbook_id'];
            $bank_name              = $result['bank_name'];
            $bankbook_branch        = $result['bankbook_branch'];
            $bankbook_code          = $result['bankbook_code'];
            $bankbook_name          = $result['bankbook_name'];



            if($result['status'] == 1){
                $bankbook_status_1      = 'checked';
                $bankbook_status_2      = '';
            }
            else if($result['status'] == 2){
                $bankbook_status_1      = '';
                $bankbook_status_2      = 'checked';
            }


        }

    }
    


    $set_data['modal'] = ' <div id="modal-manage-bankbook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="myModalLabel" class="modal-title">'.$title_modal.'สมุดธนาคาร</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal">

                        <input type="hidden" value="'.$bankbook_id.'" id="bankbook_id" name="bankbook_id">
                        <input type="hidden" value="'.$bankbook_code.'" id="bankbook_code_old" name="bankbook_code_old">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">ธนาคาร '.$result['status'].' :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control input_required" id="bank_name" name="bank_name" value="'.$bank_name.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">สาขา :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control input_required" id="bankbook_branch" name="bankbook_branch" value="'.$bankbook_branch.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">เลขที่บัญชี :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control input_required" id="bankbook_code" name="bankbook_code" maxlength="10" value="'.$bankbook_code.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">ชื่อสมุดบัญชี :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control input_required" id="bankbook_name" name="bankbook_name" value="'.$bankbook_name.'">
                            </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-3 control-label">Status : </label>
                           <div class="col-sm-7">
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="bankbook_status" class="status_1" value="1" '.$bankbook_status_1.'>
                                    <span class="fa fa-circle"></span>
                                        ใช้งาน
                                    </label>
                              </div>
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="bankbook_status" class="status_2" value="2" '.$bankbook_status_2.'>
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
                    <button type="button" class="btn btn-primary" onclick="check_submit_bankbook('.$method.')">
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
    $wsfolder	=   '/manage_bankbook';      # กำหนด Folder
    $wsfile		=	'/insert_bankbook.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'status'            => $_REQUEST['bankbook_status'],
                            'bank_name'         => $_REQUEST['bank_name'],
                            'bankbook_code'     => $_REQUEST['bankbook_code'],
                            'bankbook_name'     => $_REQUEST['bankbook_name'],
                            'bankbook_branch'   => $_REQUEST['bankbook_branch'],

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
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/manage_bankbook';      # กำหนด Folder
    $wsfile		=	'/update_bankbook.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'bankbook_id'       => $_REQUEST['bankbook_id'],
                            'status'            => $_REQUEST['bankbook_status'],
                            'bank_name'         => $_REQUEST['bank_name'],
                            'bankbook_code'     => $_REQUEST['bankbook_code'],
                            'bankbook_name'     => $_REQUEST['bankbook_name'],
                            'bankbook_branch'   => $_REQUEST['bankbook_branch'],

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
else if($_REQUEST['method'] == 5){ //check group name

    if($_REQUEST['bankbook_code'] != $_REQUEST['bankbook_code_old']){


        #WS
        $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder	=   '/manage_bankbook';      # กำหนด Folder
        $wsfile		=	'/check_bankbookcode.php';     # กำหนด File
        $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		=	array(  
                                'bankbook_code'      => $_REQUEST['bankbook_code'],
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