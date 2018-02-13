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
    $wsfolder   = '/manage_company_agency';      # กำหนด Folder
    $wsfile     = '/select_list_com_agency.php';     # กำหนด File
    $url        = $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data       = array(
                            'method'          => 'GET'
                            );

    $data_return =  json_decode( post_to_ws($url,$data),true );
    
    #RETURN
    $set_data = $data_return;


    $thead = ' <thead>
                     <tr>
                        <th class="text-center" width="5%">#</th>
                        <th class="text-center" width="15%">Status</th>
                        <th class="text-center" width="15%">ชื่อบริษัท</th>
                        <th class="text-center" width="15%">เบอร์โทรศัพท์</th>
                        <th class="text-center" width="15%">หมายเหตุ</th>
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
            else if($value['status'] == 0){
                $status = '<div class="label label-info">รอการตรวจสอบ</div>';
            }


            $td = '<td class="text-center">'.$num_row.'</td>';
            $td .= '<td class="text-center">'.$status.'</td>';
            $td .= '<td class="text-center">'.$value['agen_com_name'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_com_tel'].'</td>';
            $td .= '<td class="text-center">'.$value['remark'].'</td>';
            $td .= '<td class="text-center">
                          <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_agency(\'update\','.$value['agen_com_id'].')">
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

        $agen_com_name              = '';
        $agen_com_tel        = '';
        $agen_com_fax          = '';
        $agen_com_ttt_on          = '';
        $agen_com_address1          = '';
        $agen_com_address2          = '';
        $agen_remark          = '';

        $agency_status_0      = '';
        $agency_status_1      = 'checked';
        $agency_status_2      = '';


    }
    else{
        
        $title_modal        = 'แก้ไข';
        $agen_com_name              = '';
        $agen_com_tel        = '';
        $agen_com_fax          = '';
        $agen_com_ttt_on          = '';
        $agen_com_address1          = '';
        $agen_com_address2          = '';
        $agen_remark          = '';
        $method             = 4;

        #WS
        $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder   = '/manage_company_agency';      # กำหนด Folder
        $wsfile     = '/select_list_com_agency_by_id.php';     # กำหนด File
        $url        = $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data       = array(
                                'agen_com_id'        => $_REQUEST['agen_com_id'],
                                'method'          => 'GET'
                                );

        $data_return =  json_decode( post_to_ws($url,$data),true );

        
        if($data_return['status'] == 200){

            $result = $data_return['results'][0];

            $agen_com_id            = $result['agen_com_id'];
            $agen_com_name              = $result['agen_com_name'];
            $agen_com_tel        = $result['agen_com_tel'];
            $agen_com_fax          = $result['agen_com_fax'];
            $agen_com_ttt_on          = $result['agen_com_ttt_on'];
        
            $agen_com_address1          = $result['agen_com_address1'];
            $agen_com_address2          = $result['agen_com_address2'];
            $agen_remark          = $result['remark'];



            if($result['status'] == 1){
                $agency_status_0      = '';
                $agency_status_1      = 'checked';
                $agency_status_2      = '';
            }
            else if($result['status'] == 2){
                $agency_status_0      = '';
                $agency_status_1      = '';
                $agency_status_2      = 'checked';
            }
            else if($result['status'] == 0){
                $agency_status_0      = 'checked';
                $agency_status_1      = '';
                $agency_status_2      = '';
            }
           
            if(is_file($result['agen_com_ttt_url_img'])){
                $btn_dowload_1 = 'href="'.$result['agen_com_ttt_url_img'].'" url="'.$result['agen_com_ttt_url_img'].'"';
            }
            else{
                $btn_dowload_1 = 'href="javascript:;" url="" disabled';
            }

            if(is_file($result['agen_com_logo_img'])){
                $btn_dowload_2 = 'href="'.$result['agen_com_logo_img'].'" url="'.$result['agen_com_logo_img'].'"';
            }
            else{
                $btn_dowload_2 = 'href="javascript:;" url="" disabled';
            }

        }

    }
    


    $set_data['modal'] = ' <div id="modal-manage-agency" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="myModalLabel" class="modal-title">'.$title_modal.'บริษัท Agency</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal">

                        <input type="hidden" value="'.$agen_com_id.'" id="agen_com_id" name="agen_com_id">
                        <input type="hidden" value="'.$agen_com_name.'" id="agen_com_name_old" name="agen_com_name_old">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">บริษัท :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control input_required" id="agen_com_name" name="agen_com_name" value="'.$agen_com_name.'">
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-lg-3 control-label">ที่อยู่ บรรทัด1 :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agen_com_address1" name="agen_com_address1" value="'.$agen_com_address1.'">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">ที่อยู่ บรรทัด2 :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agen_com_address2" name="agen_com_address2" value="'.$agen_com_address2.'">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">เบอร์โทรศัพท์ :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agen_com_tel" name="agen_com_tel" value="'.$agen_com_tel.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">แฟกซ์ :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control " id="agen_com_fax" name="agen_com_fax"  value="'.$agen_com_fax.'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">เลข ททท. :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agen_com_ttt_on" name="agen_com_ttt_on" value="'.$agen_com_ttt_on.'">
                            </div>
                        </div>
                              <div class="form-group">
                           <label class="col-sm-3 control-label">รูป ททท :</label>
                           <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="file" data-classbutton="btn btn-default" id="agen_com_ttt_url_img" name="agen_com_ttt_url_img" data-classinput="form-control inline" class="form-control filestyle">
                                    <span class="input-group-btn">
                                        <a id="agen_com_ttt_img_old" class="btn btn-default btn-primary" target="_blank" '.$btn_dowload_1.'>ดาวน์โหลด</a>
                                    </span>
                                </div>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-3 control-label">รูป Logo บริษัท :</label>
                           <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="file" data-classbutton="btn btn-default" id="agen_com_logo_url_img" name="agen_com_logo_url_img" data-classinput="form-control inline" class="form-control filestyle">
                                    <span class="input-group-btn">
                                        <a id="agen_com_logo_img_old" class="btn btn-default btn-primary" target="_blank" '.$btn_dowload_2.'>ดาวน์โหลด</a>
                                    </span>
                                </div>
                           </div>
                        </div>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">หมายเหตุ :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="agen_remark" name="agen_remark" value="'.$agen_remark.'">
                            </div>
                        </div>


                        <div class="form-group">
                           <label class="col-sm-3 control-label">Status : </label>
                           <div class="col-sm-7">
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="agen_status" class="status_1" value="1" '.$agency_status_1.'>
                                    <span class="fa fa-circle"></span>
                                        ใช้งาน
                                    </label>
                              </div>
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="agen_status" class="status_2" value="2" '.$agency_status_2.'>
                                    <span class="fa fa-circle"></span>
                                        ระงับการใช้งาน
                                    </label>
                              </div>
                              <div class="radio c-radio">
                                 <label>
                                    <input type="radio" name="agen_status" class="status_0" value="0" '.$agency_status_0.'>
                                    <span class="fa fa-circle"></span>
                                        รอการตรวจสอบ
                                    </label>
                              </div>
                           </div>
                        </div>
                    </form>
                </div>


                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                    <button type="button" class="btn btn-primary" onclick="check_submit_agency('.$method.')">
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
    $wsfolder   =   '/manage_company_agency';      # กำหนด Folder
    $wsfile     =   '/insert_com_agency.php';     # กำหนด File
    $url        =   $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data       =   array(  
                                'agen_com_name'              => $_REQUEST['agen_com_name'],
                                'agen_com_tel'               => $_REQUEST['agen_com_tel'],
                                'agen_com_fax'               => $_REQUEST['agen_com_fax'],
                                'agen_com_ttt_on'            =>  $_REQUEST['agen_com_ttt_on'],
                                'agen_com_ttt_url_img'       => $_REQUEST['agen_com_ttt_url_img'],
                                'agen_com_logo_img'          => $_REQUEST['agen_com_logo_img'],
                                'agen_com_address1'          => $_REQUEST['agen_com_address1'],
                                'agen_com_address2'          => $_REQUEST['agen_com_address2'],
                                'create_user_id'            => $_SESSION['login']['user_id'],
                                'remark'                    => $_REQUEST['agen_remark'],
                                'status'                    => $_REQUEST['agen_status'],
                                'method'                    => 'PUT'
                            );

    $data_return =  json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 4){ // update

    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder   =   '/manage_company_agency';      # กำหนด Folder
    $wsfile     =   '/update_com_agency.php';     # กำหนด File
    $url        =   $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data       =   array(  
                                'agen_com_id'              => $_REQUEST['agen_com_id'],
                                'agen_com_name'              => $_REQUEST['agen_com_name'],
                                'agen_com_tel'               => $_REQUEST['agen_com_tel'],
                                'agen_com_fax'               => $_REQUEST['agen_com_fax'],
                                'agen_com_ttt_on'            =>  $_REQUEST['agen_com_ttt_on'],
                                'agen_com_ttt_url_img'       => $_REQUEST['agen_com_ttt_url_img'],
                                'agen_com_logo_img'          => $_REQUEST['agen_com_logo_img'],
                                'agen_com_address1'          => $_REQUEST['agen_com_address1'],
                                'agen_com_address2'          => $_REQUEST['agen_com_address2'],
                                'update_user_id'            => $_SESSION['login']['user_id'],
                                'remark'                    => $_REQUEST['agen_remark'],
                                'status'                    => $_REQUEST['agen_status'],
                                'method'                    => 'PUT'
                            );

    $data_return =  json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);



}
else if($_REQUEST['method'] == 5){ //check agen_com_name

    if($_REQUEST['agen_com_name'] != $_REQUEST['agen_com_name_old']){


        #WS
        $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder   =   '/manage_company_agency';      # กำหนด Folder
        $wsfile     =   '/check_com_agen_name.php';     # กำหนด File
        $url        =   $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data       =   array(  
                                'agen_com_name'      => $_REQUEST['agen_com_name'],
                                'method'          => 'GET'
                                );

        $data_return =  json_decode( post_to_ws($url,$data),true );

        $set_data['status'] = $data_return['results'];

        
    }
    else{

        $set_data['status'] = 'TRUE';

    }

 
    echo json_encode($set_data);




}
else if($_REQUEST['method'] == 6){

    $set_data['img_agency_company_url_ttt']     = $_REQUEST['agen_com_ttt_img_old'];
    $set_data['img_agency_company_url_logo']    = $_REQUEST['agen_com_logo_img_old'];

    if(isset($_FILES['agen_com_ttt_url_img'])){
        if($_FILES['agen_com_ttt_url_img']['size'] != 0){

            $path = $_FILES['agen_com_ttt_url_img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/company_agency/img_ttt_'.$i.'_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['agen_com_ttt_url_img']['tmp_name'],$file_name)){
                $set_data['img_agency_company_url_ttt']    = $file_name;
                $set_data['status_img'] = 'TRUE';
            }
            else{
                $set_data['status_img'] = 'FALSE';
            }
        }
    }
     

    if(isset($_FILES['agen_com_logo_url_img'])){
        if($_FILES['agen_com_logo_url_img']['size'] != 0){

            $path = $_FILES['agen_com_logo_url_img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/company_agency/img_logo_'.$i.'_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['agen_com_logo_url_img']['tmp_name'],$file_name)){
                $set_data['img_agency_company_url_logo']    = $file_name;
                $set_data['status_img'] = 'TRUE';
            }
            else{
                $set_data['status_img'] = 'FALSE';
            }
        }
    }  

    echo json_encode($set_data);


}
else{

}
















?>