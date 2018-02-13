<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

include_once('../unity/php_send_email.php');
#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD

if($_REQUEST['method'] == 1){//select list

    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking_detail'; //กำหนด Folder
    $wsfile		= '/select_list_booking.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'word_search'   => $_REQUEST['word_search'],
                                'status_0'      => $_REQUEST['status_0'],
                                'status_10'      => $_REQUEST['status_10'],
                                'status_20'      => $_REQUEST['status_20'],
                                'status_25'      => $_REQUEST['status_25'],
                                'status_30'      => $_REQUEST['status_30'],
                                'status_35'      => $_REQUEST['status_35'],
                                'status_40'      => $_REQUEST['status_40'],
                                'status_05'      => $_REQUEST['status_05'],
                                'country_id'      => $_REQUEST['country_id'],
                                'date_start'    => Y_m_d($_REQUEST['date_start']),
                                'date_end'      => Y_m_d($_REQUEST['date_end']),

                                'menu_12'       => $_SESSION['login']['menu_12'],
                                'user_id'       => $_SESSION['login']['user_id'],

                                'offset'        => $_REQUEST['offset'],
                                'limit'         => LIMIT,
                                'method'        => 'GET'
                            );

    
 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    $set_data['all_row'] = intval($data_return['all_row']);
    $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page


    $thead = '<thead>
				<tr>
                                <th  width="5%">
                                    #
                                </th>
                                <th>สถานะ</th>
                                <th>Booking No.</th>
                                <th>รหัส ซีรีย์</th>
                                <th>Period</th>
                                <th>จำนวนคน</th>
                                <th>ยอดรวม</th>
                                <th class="bg-success-light">ยอดสุทธิ</th>
                                <th>วันที่จอง</th>
                                <th class="bg-danger-light">วันหมดอายุ</th>
                                <th>ชื่อบริษัท</th>
                                <th>Booking By</th>
                                <th>Sales contact</th>
                                <th></th>
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
            $td_duedate = '';
            if (intval($value['status']) >= 25 && intval($value['status']) != 40 ){
                $td_duedate = ' <td class="text-center bg-danger-light">'.thai_date_short(strtotime($value['book_due_date_full_payment'])).'</td>';
            }else {
                if (intval($value['book_master_deposit']) == 0){
                $td_duedate = ' <td class="text-center bg-danger-light">'.thai_date_short(strtotime($value['book_due_date_full_payment'])).'</td>';
                }else{
                $td_duedate = ' <td class="text-center bg-danger-light">'.thai_date_short(strtotime($value['book_due_date_deposit'])).'</td>';
                }
                
            }

            $td = '<td class="text-center">'.number_format($count++).'</td>';
            $td .= '<td class="text-center">'.html_status_book($value['status']).'</td>';
            $td .= '<td class="text-center">'.$value['book_code'].'</td>';
            $td .= '<td class="text-center">'.$value['ser_code'].'</td>';
            $td .= '<td class="text-center">'.thai_date_short(strtotime($value['per_date_start'])).' - '.thai_date_short(strtotime($value['per_date_end'])).'</td>';
            $td .= '<td class="text-right">'.number_format(intval($value['QTY'])).'</td>';
            $td .= '<td class="text-right">'.number_format(intval($value['book_total'])).'</td>';
            $td .= '<td class="text-right bg-success-light">'.number_format(intval($value['book_amountgrandtotal'])).'</td>';
            $td .= '<td class="text-center">'.thai_date_short(strtotime($value['book_date'])).'</td>';
            $td .=  $td_duedate;
            $td .= '<td class="text-center">'.$value['agen_com_name'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_name'].'</td>';
            $td .= '<td class="text-center">'.$value['user_name'].'</td>';
            $td .= ' <td class="text-center">
                                    <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'&source=detail\'">
                                        <em class="icon-note"></em>
                                        รายละเอียด
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


?>