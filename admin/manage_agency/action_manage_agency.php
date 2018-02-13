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
    $wsfolder	= '/agency'; //กำหนด Folder
    $wsfile		= '/select_list_agency.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'word_search'   => $_REQUEST['word_search'],
                                'status_1'      => $_REQUEST['status_1'],
                                'status_2'      => $_REQUEST['status_2'],
                                'status_0'      => $_REQUEST['status_0'],

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
                    <th width="10%">Username</th>
                    <th width="15%">ชื่อ - สกุล</th>
                    <th width="15%">ชื่อบริษัท</th>
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
            else if($value['status'] == 0){
                $status = '<div class="label label-primary">รอการตรวจสอบ</div>';
            }
            else{
                $status = '';
            }

            $td = '<td class="text-center">'.number_format($count++).'</td>';
            $td .= '<td class="text-center">'.$status.'</td>';
            $td .= '<td class="text-center">'.$value['agen_user_name'].'</td>';
            $td .= '<td class="text-left">'.$value['agen_fname'].' '.$value['agen_lname'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_com_name'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_email'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_tel'].'</td>';
            $td .= '<td class="text-center">
                        <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_agency(\'update\','.$value['agen_id'].')">
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

        $title_modal         = 'เพิ่ม';
        $agency_name         = '';
        $agency_fname        = '';
        $agency_lname        = '';
        $agency_nickname     = '';
        $agency_position       = '';
        $agency_email        = '';
        $agency_tel          = '';
        $agency_lineid      = '';
        $agency_skype          = '';
        $agency_company_id        = '';
        
        $method             = 3;

        $password = '<div class="form-group">
                            <label class="col-lg-3 control-label">Password :</label>
                            <div class="col-lg-7">
                               
                                <input type="password" class="form-control" id="agency_password" name="agency_password">
                                    
                            </div>
                        </div>';
        
        $readonly           = '';


        $status_1 = 'checked';
        $status_2 = '';
        $status_0 = '';

        $show_status_1 = 'checked';
        $show_status_2 = '';
        
    }
    else{
        
          
        $title_modal        = 'แก้ไข';
        $agency_name         = '';
        $agency_fname        = '';
        $agency_lname        = '';
        $agency_nickname     = '';
        $agency_position       = '';
        $agency_email        = '';
        $agency_tel          = '';
        $agency_lineid      = '';
        $agency_skype          = '';
        $agency_company_id        = '';
        $method             = 4;

     
        $password = '';
        
        $readonly           = 'readonly';


        #WS
        $wsserver   = URL_WS;
        $wsfolder	= '/agency'; //กำหนด Folder
        $wsfile		= '/select_list_agency_id.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    

                                    'agen_id'       => $_REQUEST['agency_id'],
                                    'method'        => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );


        if($data_return['status'] == 200){

            $result = $data_return['results'][0];
            $agency_name  = $result['agen_user_name'];
            $agency_fname = $result['agen_fname'];
            $agency_lname = $result['agen_lname'];
            $agency_nickname = $result['agen_nickname'];
            $agency_position = $result['agen_position'];
            $agency_email   = $result['agen_email'];
            $agency_tel = $result['agen_tel'];
            $agency_lineid = $result['agen_line_id'];
            $agency_skype = $result['agen_skype'];
            $agency_company_id = $result['agency_company_id'];

            if($result['status'] == 0){
                $status_0 = 'checked';
                $status_1 = '';
                $status_2 = '';
            }
            else if($result['status'] == 1){
                $status_0 = '';
                $status_1 = 'checked';
                $status_2 = '';

            }
            else{
                $status_0 = '';
                $status_1 = '';
                $status_2 = 'checked';

            }
            if($result['agen_show'] == 1){

                $show_status_1 = 'checked';
                $show_status_2 = '';

            }
            else{

                $show_status_1 = '';
                $show_status_2 = 'checked';

            }

            
  

        }

    }
   
       #Agency Company name
    $wsserver   = URL_WS;
    $wsfolder	= '/agency'; //กำหนด Folder
    $wsfile		= '/get_com_agen.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){

        $result = $data_return['results'];
        $option_company_agency = '';

        foreach($result as $key => $value){

            $selected_company_agency = '';
            if($agency_company_id == $value['agen_com_id']){
                $selected_company_agency = 'selected';
            }

            $option_company_agency .= '<option value="'.$value['agen_com_id'].'" '.$selected_company_agency.'>'.$value['agen_com_name'].'</option>';
        }

    }
    else{
        $option_company_agency = '<option value="">ไม่พบข้อมูล</option>';
    }



    $set_data['modal'] = '
    
    <div id="modal-manage-agency" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="myModalLabel" class="modal-title">'.$title_modal.'</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="'.$_REQUEST['agency_id'].'" name="agency_id" id="agency_id">
                    <input type="hidden" value="'.$agency_name.'" name="agency_name_old" id="agency_name_old">
                    <input type="hidden" value="'.$agency_email.'" name="agency_email_old" id="agency_email_old">


                
                    
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label class="col-lg-3 control-label">ชื่อ :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agency_fname" name="agency_fname" value="'.$agency_fname.'" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">สกุล :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agency_lname" name="agency_lname" value="'.$agency_lname.'" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">ชื่อเล่น :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agency_nickname" name="agency_nickname" value="'.$agency_nickname.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">ตำแหน่ง :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agency_position" name="agency_position" value="'.$agency_position.'" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Email :</label>
                            <div class="col-lg-7">
                                
                                <input type="email" class="form-control" id="agency_email" name="agency_email" value="'.$agency_email.'" required>
                                 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">เบอร์โทรศัพท์ :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agency_tel" name="agency_tel" value="'.$agency_tel.'" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Line ID :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agency_lineid" name="agency_lineid" value="'.$agency_lineid.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Skype :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agency_skype" name="agency_skype" value="'.$agency_skype.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Username :</label>
                            <div class="col-lg-7">
                               
                                <input type="text" class="form-control" id="agency_name" name="agency_name" value="'.$agency_name.'"  '.$readonly.'>
                                  
                            </div>
                        </div>
                       '.$password.'
                        <div class="form-group">
                           <label class="col-sm-3 control-label">Status : </label>
                           <div class="col-sm-7">
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="agency_status" class="status_0" value="0" '.$status_0.'>
                                    <span class="fa fa-circle"></span>
                                        รอการตรวจสอบ
                                    </label>
                              </div>
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="agency_status" class="status_1" value="1" '.$status_1.'>
                                    <span class="fa fa-circle"></span>
                                        ใช้งาน
                                    </label>
                              </div>
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="agency_status" class="status_2" value="2" '.$status_2.'>
                                    <span class="fa fa-circle"></span>
                                        ระงับการใช้งาน
                                    </label>
                              </div>
                           </div>
                        </div>
                        
                        <hr>
                        <div class="form-group">
                           <label class="col-sm-3 control-label">การแสดงผล : </label>
                           <div class="col-sm-7">
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="show_status" class="status_1" value="1" '.$show_status_1.'>
                                    <span class="fa fa-circle"></span>
                                       แสดง
                                    </label>
                              </div>
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="show_status" class="status_2" value="2" '.$show_status_2.'>
                                    <span class="fa fa-circle"></span>
                                       ไม่แสดง
                                    </label>
                              </div>
                           </div>
                        </div>


                        <hr>
                          <div class="form-group">
                                                <label class="col-lg-3 control-label">ชื่อบริษัท :</label>
                                                <div class="col-lg-7">
                                                    
                                                    <select name="agency_company_id" id="agency_company_id" class="chosen-select form-control">
                                                        '.$option_company_agency.'
                                                    </select>
                                                    
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
    $wsfolder	= '/agency'; //กำหนด Folder
    $wsfile		= '/insert_agency.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'agen_user_name'            => trim($_REQUEST['agency_name']),
                                'agen_password'             => hash_password(trim($_REQUEST['agency_password'])),
                                'agen_fname'                => $_REQUEST['agency_fname'],
                                'agen_lname'                => $_REQUEST['agency_lname'],
                                'agen_nickname'             => $_REQUEST['agency_nickname'],
                                'agen_position'             => $_REQUEST['agency_position'],
                                'agen_email'                => trim($_REQUEST['agency_email']),
                                'agen_tel'                  => $_REQUEST['agency_tel'],
                                'agen_line_id'              => $_REQUEST['agency_lineid'],
                                'agen_skype'                => $_REQUEST['agency_skype'],
                                'agency_company_id'              => $_REQUEST['agency_company_id'],
                                'create_user_id'            => $_SESSION['login']['user_id'],
                                'remark'                    => '',
                                'status'                    => $_REQUEST['agency_status'],
                                'agen_show'                    => $_REQUEST['agen_show'],
                                'method'                    => 'PUT'
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
    $wsfolder	=   '/agency';      # กำหนด Folder
    $wsfile		=	'/update_agency.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  

                                'agen_id'                   => $_REQUEST['agency_id'],
                                'agen_user_name'            => trim($_REQUEST['agency_name']),
                                'agen_password'             => hash_password(trim($_REQUEST['agency_password'])),
                                'agen_fname'                => $_REQUEST['agency_fname'],
                                'agen_lname'                => $_REQUEST['agency_lname'],
                                'agen_nickname'             => $_REQUEST['agency_nickname'],
                                'agen_position'             => $_REQUEST['agency_position'],
                                'agen_email'                => trim($_REQUEST['agency_email']),
                                'agen_tel'                  => $_REQUEST['agency_tel'],
                                'agen_line_id'              => $_REQUEST['agency_lineid'],
                                'agen_skype'                => $_REQUEST['agency_skype'],
                                'agency_company_id'              => $_REQUEST['agency_company_id'],
                                'update_user_id'            => $_SESSION['login']['user_id'],
                                'remark'                    => '',
                                'status'                    => $_REQUEST['agency_status'],
                                'agen_show'                    => $_REQUEST['agen_show'],
                                
                                'method'                    => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 5){ //check group 

 if($_REQUEST['agency_email'] != $_REQUEST['agency_email_old']){

        #Email
        $wsserver   = URL_WS;
        $wsfolder	= '/agency'; //กำหนด Folder
        $wsfile		= '/check_agen_email.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    
                                    'agency_email'     => $_REQUEST['agency_email'],
                                    'method'        => 'GET'
                                );

    
        $data_return =	json_decode( post_to_ws($url,$data),true );

        $set_data['email_status'] = $data_return['results'];

    }
    else{
        $set_data['email_status'] = 'TRUE';
    }


    if($_REQUEST['agency_name'] != $_REQUEST['agency_name_old']){

        #USER NAME
        $wsserver   = URL_WS;
        $wsfolder	= '/agency'; //กำหนด Folder
        $wsfile		= '/check_agen_username.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    
                                    'agency_name'     => $_REQUEST['agency_name'],
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

else{

}
















?>