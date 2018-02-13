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


     $thead = '<thead>
                <tr>
                    <th class="text-center"  rowspan="2">#</th>
                    <th class="text-center"  rowspan="2">ชื่อกลุ่ม<br>ผู้ใช้งาน</th>
                    <th class="text-center"  colspan="2">Dashboard</th>
                    <th class="text-center"  rowspan="2">แจ้งเตือน</th>

                    <th class="text-center" rowspan="2">จัดการการจองทัวร์</th>
                    <th class="text-center"  rowspan="2">รายละเอียดการ Booking</th>
                    <th class="text-center"  rowspan="2">จัดการทัวร์</th>

              
                    
                    <th class="text-center" rowspan="2">ซี่รีย์ทัวร์</th>
                    <th class="text-center"  rowspan="2">รีวิวทัวร์</th>
                    <th class="text-center"  rowspan="2">จัดการ Contact US</th>
                    <th class="text-center"  rowspan="2">จัดการผู้ใช้งาน</th>
                    <th class="text-center"  rowspan="2">จัดการเอเจนซี่</th>
                    <th class="text-center" rowspan="2">รายงาน</th>
                    <th class="text-center"  rowspan="2">ตั้งค่า</th>
                    <th class="text-center" width="5%" rowspan="2">แจ้งการชำระเงิน/อนุมัติการชำระเงิน</th>
                    <th class="text-center" width="5%" rowspan="2">แสดงข้อมูลจัดการการจองทัวร์ทั้งหมด</th>
                    <th class="text-center" width="5%" rowspan="2">แสดงต้นทุน/ค่าใช้จ่าย</th>
                    <th class="text-center"  rowspan="2">Print Reciept	</th>
                    <th class="text-center"  rowspan="2">invoice ค้างชำระ</th>
                    <th rowspan="2"></th>
                </tr>
                <tr>
                    <th>ผู้บริหาร</th>
                    <th>Sale</th>
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
            #dashboard
            if($value['menu_1'] == 1){
                $radio_0 = '';
                $radio_1 = 'checked';
            }
            else{
                $radio_0 = 'checked';
                $radio_1 = '';
            }
            $td .= '<td class="text-center">
                        <label class="radio-inline c-radio">
                            <input id="inlineradio2" type="radio" name="menu_1_'.$value['group_id'].'" value="1" '.$radio_1.'>
                            <span class="fa fa-circle"></span>
                        </label>
                    </td>
                    <td class="text-center">
                        <label class="radio-inline c-radio">
                            <input id="inlineradio2" type="radio" name="menu_1_'.$value['group_id'].'" value="0" '.$radio_0.'>
                            <span class="fa fa-circle"></span>
                        </label>
                    </td>';
            #แจ้งเตือน
            if($value['menu_2'] == 1){
                $menu_2 = 'checked';
            }
            else{
                 $menu_2 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_2_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_2.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #จัดการการจองทัวร์
            if($value['menu_3'] == 1){
                $menu_3 = 'checked';
            }
            else{
                 $menu_3 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_3_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_3.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
              #รายละเอียดการ Booking
            if($value['menu_15'] == 1){
                $menu_15 = 'checked';
            }
            else{
                 $menu_15 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_15_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_15.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
         
            #จัดการการทัวร์
            if($value['menu_14'] == 1){
                $menu_14 = 'checked';
            }
            else{
                 $menu_14 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_14_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_14.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
          
            #ซี่รีย์ทัวร์
            if($value['menu_4'] == 1){
                $menu_4 = 'checked';
            }
            else{
                 $menu_4 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_4_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_4.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #รีวิวทัวร์
            if($value['menu_5'] == 1){
                $menu_5 = 'checked';
            }
            else{
                 $menu_5 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_5_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_5.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #จัดการ Contact US
            if($value['menu_6'] == 1){
                $menu_6 = 'checked';
            }
            else{
                 $menu_6 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_6_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_6.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #จัดการผู้ใช้งาน
            if($value['menu_7'] == 1){
                $menu_7 = 'checked';
            }
            else{
                 $menu_7 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_7_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_7.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #จัดการเอเจนซี่
            if($value['menu_8'] == 1){
                $menu_8 = 'checked';
            }
            else{
                 $menu_8 = '';
            }	
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_8_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_8.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #รายงาน
            if($value['menu_9'] == 1){
                $menu_9 = 'checked';
            }
            else{
                 $menu_9 = '';
            }	
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_9_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_9.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #ตั้งค่า
            if($value['menu_10'] == 1){
                $menu_10 = 'checked';
            }
            else{
                 $menu_10 = '';
            }	
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_10_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_10.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #อนุมัติการชำระเงิน
            if($value['menu_11'] == 1){
                $menu_11 = 'checked';
            }
            else{
                 $menu_11 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_11_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_11.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            #แสดงข้อมูลจัดการการจองทัวร์ทั้งหมด
            if($value['menu_12'] == 1){
                $menu_12 = 'checked';
            }
            else{
                 $menu_12 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_12_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_12.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
                      #แสดงต้นทุน / ค่าใช้จ่าย
            if($value['menu_17'] == 1){
                $menu_17 = 'checked';
           }
           else{
                $menu_17 = '';
           }
           $td .= '<td class="text-center">
                       <label class="checkbox-inline c-checkbox">
                           <input id="menu_17_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_17.'>
                           <span class="fa fa-check"></span>
                       </label>
                   </td>';
           
            #Print Reciept
            if($value['menu_13'] == 1){
                 $menu_13 = 'checked';
            }
            else{
                 $menu_13 = '';
            }
            $td .= '<td class="text-center">
                        <label class="checkbox-inline c-checkbox">
                            <input id="menu_13_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_13.'>
                            <span class="fa fa-check"></span>
                        </label>
                    </td>';
            # invoice ค้างชำระ
            if($value['menu_18'] == 1){
                $menu_18 = 'checked';
           }
           else{
                $menu_18 = '';
           }
           $td .= '<td class="text-center">
           <label class="checkbox-inline c-checkbox">
               <input id="menu_18_'.$value['group_id'].'" type="checkbox" value="1" '.$menu_18.'>
               <span class="fa fa-check"></span>
           </label>
       </td>';
            $td .= '<td>
                        <button type="button" class="btn btn-xs btn-primary" onclick="upload_role('.$value['group_id'].')">
                            <em class="icon-cursor"></em>
                            บันทีก
                        </button>
                    </td>';

            $tr .= '<tr>'.$td.'</tr>';

            $num_row++;

        }

        $tbody = '<tbody>'.$tr.'</tbody>';

    }



    $table = '<table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;">   
                    '.$thead.'
                    '.$tbody.'
                </table>';

    $set_data['table'] = $table;


    echo json_encode($set_data);
    


}
else if($_REQUEST['method'] == 2){ // upload role
    
   
    #WS
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/manage_usergroup';      # กำหนด Folder
    $wsfile		= '/update_usergroup_role.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                            'group_id'        => $_REQUEST['group_id'],
                            'menu_1'          => $_REQUEST['menu_1'],
                            'menu_2'          => $_REQUEST['menu_2'],
                            'menu_3'          => $_REQUEST['menu_3'],
                            'menu_4'          => $_REQUEST['menu_4'],
                            'menu_5'          => $_REQUEST['menu_5'],
                            'menu_6'          => $_REQUEST['menu_6'],
                            'menu_7'          => $_REQUEST['menu_7'],
                            'menu_8'          => $_REQUEST['menu_8'],
                            'menu_9'          => $_REQUEST['menu_9'],
                            'menu_10'          => $_REQUEST['menu_10'],
                            'menu_11'          => $_REQUEST['menu_11'],
                            'menu_12'          => $_REQUEST['menu_12'],
                            'menu_13'          => $_REQUEST['menu_13'],
                            'menu_14'          => $_REQUEST['menu_14'],
                            'menu_15'          => $_REQUEST['menu_15'],
                            'menu_17'          => $_REQUEST['menu_17'],
                            'menu_18'          => $_REQUEST['menu_18'],
                          //  'menu_16'          => $_REQUEST['menu_16'],

                            'method'          => 'PUT'
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