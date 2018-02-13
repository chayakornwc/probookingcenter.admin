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
    $wsfolder	= '/review'; //กำหนด Folder
    $wsfile		= '/select_list_review.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'word_search'   => $_REQUEST['word_search'],
                                'country_id'    => $_REQUEST['country_id'],
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
                    <th width="15%">Status</th>
                    <th width="10%">ประเทศ</th>
                    <th width="60%">รายละเอียด</th>
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
                $status = '<div class="label label-success">แสดง</div>';
            }
            else if($value['status'] == 2){
                $status = '<div class="label label-warning">ไม่แสดง</div>';
            }
            else if($value['status'] == 9){
                $status = '<div class="label label-error">ลบ</div>';
            }
            else{
                $status = '';
            }

            $td = '<td class="text-center">'.number_format($count++).'</td>';
            $td .= '<td class="text-center">'.$status.'</td>';
            $td .= '<td class="text-center">'.$value['country_name'].'</td>';
            $td .= '<td class="text-center">'.$value['review_detail'].'</td>';
            $td .= '<td class="text-center">
                        <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_review(\'update\','.$value['review_id'].')">
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
        $method             = 3;

        $btn_dowload_1        = 'url="" disabled';
        $btn_dowload_2        = 'url="" disabled';
        $btn_dowload_3        = 'url="" disabled';
        $btn_dowload_4        = 'url="" disabled';
        $btn_dowload_5        = 'url="" disabled';

        $status_1 = 'checked';
        $status_2 = '';
        $country_id = '';
    }   
    else{
        
        $title_modal        = 'แก้ไข';
        $method             = 4;
   

        #WS
        $wsserver   = URL_WS;
        $wsfolder	= '/review'; //กำหนด Folder
        $wsfile		= '/select_id.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    

                                    'review_id'     => $_REQUEST['review_id'],
                                    'method'        => 'GET'
                                );

    
        $data_return =	json_decode( post_to_ws($url,$data),true );

        if($data_return['status'] == 200){

            $result = $data_return['results'][0];
            
            $review_detail  = $result['review_detail'];
            $country_id     = $result['country_id'];

            if($result['status'] == 1){
                $status_1 = 'checked';
                $status_2 = '';
            }
            else{
                $status_1 = '';
                $status_2 = 'checked';
            }

            if(is_file($result['review_url_img_1'])){
                $btn_dowload_1 = 'href="'.$result['review_url_img_1'].'" url="'.$result['review_url_img_1'].'"';
            }
            else{
                $btn_dowload_1 = 'href="javascript:;" url="" disabled';
            }

            if(is_file($result['review_url_img_2'])){
                $btn_dowload_2 = 'href="'.$result['review_url_img_2'].'" url="'.$result['review_url_img_2'].'"';
            }
            else{
                $btn_dowload_2 = 'href="javascript:;" url="" disabled';
            }
            if(is_file($result['review_url_img_3'])){
                $btn_dowload_3 = 'href="'.$result['review_url_img_3'].'" url="'.$result['review_url_img_3'].'"';
            }
            else{
                $btn_dowload_3 = 'href="javascript:;" url="" disabled';
            }
            if(is_file($result['review_url_img_4'])){
                $btn_dowload_4 = 'href="'.$result['review_url_img_4'].'" url="'.$result['review_url_img_4'].'"';
            }
            else{
                $btn_dowload_4 = 'href="javascript:;" url="" disabled';
            }
            if(is_file($result['review_url_img_5'])){
                $btn_dowload_5 = 'href="'.$result['review_url_img_5'].'" url="'.$result['review_url_img_5'].'"';
            }
            else{
                $btn_dowload_5 = 'href="javascript:;" url="" disabled';
            }
        }
    }
    


    $set_data['modal'] = '<div id="modal-manage-review" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 id="myModalLabel" class="modal-title">'.$title_modal.' รีวิว</h4>
                                    </div>
                                    <div class="modal-body">

                                        <form class="form-horizontal">
                                            <input type="hidden" value="'.$_REQUEST['review_id'].'" id="review_id" name="review_id">
                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">เลือกประเทศ :</label>
                                                <div class="col-lg-7">

                                                    <select name="review_country" id="review_country" class="form-control">
                                                        '.select_option_country(1,$country_id).'
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-3 control-label">รายละเอียด :</label>
                                                <div class="col-lg-7">
                                                    <textarea rows="4" cols="" class="form-control note-editor" style="resize:none;" id="review_detail" name="review_detail">'.$review_detail.'</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">รูปที่ 1  :</label>
                                            <div class="col-sm-7">
                                                    <div class="input-group">

                                                        <input type="file" data-classbutton="btn btn-default" id="review_img_1" name="review_img_1" data-classinput="form-control inline" class="form-control filestyle">
                                                        <span class="input-group-btn">
                                                            <a id="review_img_1_old" class="btn btn-default btn-primary" target="_blank" '.$btn_dowload_1.'>ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">รูปที่ 2  :</label>
                                            <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input type="file" data-classbutton="btn btn-default" id="review_img_2" name="review_img_2" data-classinput="form-control inline" class="form-control filestyle">
                                                        <span class="input-group-btn">
                                                            <a  id="review_img_2_old" class="btn btn-default btn-primary" target="_blank" '.$btn_dowload_2.'>ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">รูปที่ 3  :</label>
                                            <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input type="file" data-classbutton="btn btn-default" id="review_img_3" name="review_img_3" data-classinput="form-control inline" class="form-control filestyle">
                                                        <span class="input-group-btn">
                                                            <a id="review_img_3_old" class="btn btn-default btn-primary"  target="_blank" '.$btn_dowload_3.'>ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                                            </div>

                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">รูปที่ 4  :</label>
                                            <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input type="file" data-classbutton="btn btn-default" id="review_img_4" name="review_img_4" data-classinput="form-control inline" class="form-control filestyle">
                                                        <span class="input-group-btn">
                                                            <a id="review_img_4_old" class="btn btn-default btn-primary" target="_blank" '.$btn_dowload_4.'>ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">รูปที่ 5  :</label>
                                            <div class="col-sm-7">
                                                    <div class="input-group"> 
                                                        <input type="file" data-classbutton="btn btn-default" id="review_img_5" name="review_img_5" data-classinput="form-control inline" class="form-control filestyle">
                                                        <span class="input-group-btn">
                                                            <a id="review_img_5_old" class="btn btn-default btn-primary"  target="_blank" '.$btn_dowload_5.'>ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                                            </div>
                                            </div>

                                        
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Status : </label>
                                            <div class="col-sm-7">
                                                <div class="radio c-radio">
                                                    <label>
                                                        <input type="radio" name="review_status" class="status_1" value="1" '.$status_1.'>
                                                        <span class="fa fa-circle"></span>
                                                            แสดง
                                                        </label>
                                                </div>
                                                <div class="radio c-radio">
                                                    <label>
                                                        <input type="radio" name="review_status" class="status_2" value="2" '.$status_2.'>
                                                        <span class="fa fa-circle"></span>
                                                            ไม่แสดง
                                                        </label>
                                                </div>
                                            </div>
                                            </div>

                                            
                                        </form>


                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                        <button type="button" class="btn btn-primary" onclick="check_submit_review('.$method.')">
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
    $wsfolder	=   '/review';      # กำหนด Folder
    $wsfile		=	'/insert_review.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'country_id'            => $_REQUEST['review_country'],
                            'review_detail'         => $_REQUEST['review_detail'],

                            'review_url_img_1'      => $_REQUEST['review_img_1'],
                            'review_url_img_2'      => $_REQUEST['review_img_2'],
                            'review_url_img_3'      => $_REQUEST['review_img_3'],
                            'review_url_img_4'      => $_REQUEST['review_img_4'],
                            'review_url_img_5'      => $_REQUEST['review_img_5'],

                            'create_user_id'        => $_SESSION['login']['user_id'],
                            'status'                => $_REQUEST['status'],
                            'remark'                => '',
                            'method'                => 'PUT'
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
    $wsfolder	=   '/review';      # กำหนด Folder
    $wsfile		=	'/update_review.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'review_id'             => $_REQUEST['review_id'],
                            'country_id'            => $_REQUEST['review_country'],
                            'review_detail'         => $_REQUEST['review_detail'],

                            'review_url_img_1'      => $_REQUEST['review_img_1'],
                            'review_url_img_2'      => $_REQUEST['review_img_2'],
                            'review_url_img_3'      => $_REQUEST['review_img_3'],
                            'review_url_img_4'      => $_REQUEST['review_img_4'],
                            'review_url_img_5'      => $_REQUEST['review_img_5'],

                            'update_user_id'        => $_SESSION['login']['user_id'],
                            'status'                => $_REQUEST['status'],
                            'remark'                => '',
                            'method'                => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 5){ //upload img 



    for($i = 1; $i <= 5; $i++){
        
        if(isset($_FILES['review_img_'.$i])){


            if($_FILES['review_img_'.$i]['size'] != 0){

                $path = $_FILES['review_img_'.$i]['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);

                $file_name = '../upload/review/img_'.$i.'_'.date('Y_m_d_H_I_s').'.'.$ext;

                if(move_uploaded_file($_FILES['review_img_'.$i]['tmp_name'],$file_name)){
                    $set_data['review_img_'.$i]    = $file_name;
                    $set_data['status_img'] = 'TRUE';
                }
                else{
                    $set_data['status_img'] = 'FALSE';
                }

            }


        }
        else{
            $set_data['review_img_'.$i]     = $_REQUEST['review_img_'.$i.'_old'];
        }

    }


    

 
    echo json_encode($set_data);




}
else{

}
















?>