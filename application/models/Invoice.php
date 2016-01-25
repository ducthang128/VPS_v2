<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Invoice extends MY_Model {
    private $sgb_percent = 0.3;
    private $sgs_percent = 0.05;
    private $base_price = 100;
    private $duration_rate = array(5=>1,
                                   10=>1.02,
                                   15=>1.04,
                                   20=>1.06,
                                   25=>1.08,
                                   30=>1.1);//%
    private $busline = array('1' => 'Bến Thành - Bến xe Chợ Lớn',
                            '2' => 'Bến Thành - Bến xe Miền Tây',
                            '3' => 'Bến Thành - Thạnh Lộc',
                            '4' => 'Bến Thành - Cộng Hòa - Bến xe An Sương',
                            '5' => 'Bến xe Chợ Lớn - Biên Hòa',
                            '6' => 'Bến xe Chợ Lớn - Đại học Nông Lâm',
                            '7' => 'Bến xe Chợ Lớn - Gò Vấp',
                            '8' => 'Bến xe Quận 8 - Đại học Quốc gia',
                            '9' => 'Bến xe Chợ Lớn - Bình Chánh - Hưng Long',
                            '10' => 'Đại học Quốc gia - Bến xe Miền Tây',
                            '100' => 'Bến xe Củ Chi - Cầu Tân Thái',
                            '101' => 'Bến xe Chợ Lớn - Bến Phú Định',
                            '102' => 'Bến Thành - Nguyễn Văn Linh - Bến xe Miền Tây',
                            '103' => 'Bến xe Chợ Lớn - Bến xe Ngã 4 Ga',
                            '104' => 'Bến xe An Sương - Đại học Nông Lâm',
                            '107' => 'Bến xe Củ Chi - Bố Heo',
                            '11' => 'Bến Thành - Đầm Sen',
                            '110' => 'Phú Xuân - Hiệp Phước',
                            '12' => 'Bến Thành - Thác Giang Điền',
                            '122' => 'Bến xe An Sương - Tân Quy',
                            '123' => 'Phú Mỹ Hưng (khi H) - Quận 1',
                            '124' => 'Phú Mỹ Hưng (khu S) - Quận 1',
                            '126' => 'Bến xe Củ Chi - Bình Mỹ',
                            '13' => 'Công viên 23/9 - Bến xe Củ Chi',
                            '139' => 'Bến xe Miền Tây - Khu tái định cư Phú Mỹ',
                            '14' => 'Bến xe Miền Đông - 3/2 - Bến xe Miền Tây',
                            '140' => 'Công viên 23/9 - Phạm Thế Hiển - Ba Tơ');
    private $frame_play = array('05h00-05h59',
                                '06h00-06h59',
                                '07h00-07h59',
                                '08h00-08h59',
                                '09h00-09h59',
                                '10h00-10h59',
                                '11h00-11h59',
                                '12h00-12h59',
                                '13h00-13h59',
                                '14h00-14h59',
                                '15h00-15h59',
                                '16h00-16h59',
                                '17h00-17h59',
                                '18h00-18h59',
                                '19h00-19h59',
                                '20h00-20h59');
    private $frame_rate = array(1,      //05h00
                                1.2,    //06h00
                                1.5,    //07h00
                                1.2,    //08h00
                                1,      //09h00
                                1.2,    //10h00
                                1.2,    //11h00
                                1.2,    //12h00
                                1,      //13h00
                                1,      //14h00
                                1,      //15h00
                                1.2,    //16h00
                                1.5,    //17h00
                                1.2,    //18h00
                                1,      //19h00
                                1);     //20h00

    public function __construct() {
        parent::__construct();
    }


    public function get_list_invoice($userid,$limit=100,$offset=0)
    {
        if ($userid <> 0)
        {
            $query = $this->db->select( 'invoice_id' )
                            			->distinct()
                                        ->from( 'ads_clips' )
                                        ->where('userid', $userid)
                            			->get()
                                        ->result_array();
            if (count($query) == 0){
                $invoice_id = $this->db->select( 'oder_id' )
                            			->distinct()
                                        ->from( 'users' )
                                        ->where('user_id', $userid)
                            			->get()
                                        ->result_array();
                $this->add_invoice_row_by_timeframe($invoice_id[0]['oder_id'],$userid);
                $query = $this->db->select( 'invoice_id' )
                            			->distinct()
                                        ->from( 'ads_clips' )
                            			->get()
                                        ->result_array();
            }
        }
        else
        {
            $query = $this->db->select( 'invoice_id' )
                                        ->distinct()
                            			->from( 'ads_clips' )
                            			->where('invoice_id <>', '')
                            			->get()
                                        ->result_array();

        }

        //$debug_msg =  $this->show_debug_info($query);
        //echo $debug_msg;
        $html = '<form action="/manage/invoice" method="post" target="_self" enctype="multipart/form-data">';
        $html .= '<table class="table table-hover">
                    <tbody>
                        <tr>
                            <th style="text-align: center">STT</th>
                            <th>Khách hàng</th>
                            <th>Thời lượng phát</th>
                            <th>Thành tiền</th>
                            <th style="text-align: center">Tình trạng</th>
                            <th style="text-align: center">Thao tác</th>
                        </tr>';
        $stt = 1;
        foreach ($query as $key => $value)
        {
            $user = $this->db->select( 'first_name, last_name' )
                        			->from( 'users' )
                                    ->where('oder_id', $value['invoice_id'])
                        			->limit(1,0)
                        			->get()
                                    ->result_array();
            if (!isset($user[0]))
            {
                continue;
            }
            $username = $user[0]['first_name'] .' '. $user[0]['last_name'];

            $detail = $this->db->select( 'clipid' )
                        			->from( 'ads_clips' )
                                    ->where('invoice_id', $value['invoice_id'])
                        			->get()
                                    ->result_array();
            //$result['debug'] = $this->show_debug_info($detail);
            $total_durations = 0;
            $total_prices = 0;
            foreach ($detail as $item)
            {
                $clip_price = $this->db->select_sum('price')->get_where('duration_play_day', array('clipid' => $item['clipid']))->result_array();
                $clip_duration = $this->db->select_sum('total_duration')->get_where('duration_play_day', array('clipid' => $item['clipid']))->result_array();
                $total_durations = $total_durations + $clip_duration[0]['total_duration'];
                $total_prices = $total_prices + $clip_price[0]['price'];
            }
            $approved = $this->db->select('approved')->where('oder_id',$value['invoice_id'])->get('users')->result_array();
            if (count($approved) == 1)
            {
                $is_approved = intval($approved[0]['approved']);
            }
            else
            {
                $is_approved = 0;
            }
            if ($is_approved <> 1)
            {
                $html .= '<tr>
                            <td style="text-align: center"><a href="/manage/invoice_view/'.$value['invoice_id'].'" target="_blank" title="Xem đơn hàng">'. (string)$stt .'</a></td>
                            <td>'. $username .'</td>
                            <td>'.$this->product_price($total_durations,'s').'</td>
                            <td>'.$this->product_price($total_prices).'</td>
                            <td style="text-align: center">
                                <p class="text-yellow"><span class="fa fa-edit" data-toggle="tooltip" data-placement="left" title="Đơn hàng chờ duyệt"></span></p>
                            </td>
                            <td style="text-align: center">
                                <button type="submit" name="'.$value['invoice_id'].'" value="approved" class="fa fa-check btn btn-info btn-flat" data-toggle="tooltip" data-placement="left" title="Chập thuận đơn hàng"></button>
                                <button type="submit" name="'.$value['invoice_id'].'" value="canceled" class="fa fa-times btn btn-danger btn-flat" data-toggle="tooltip" data-placement="left" title="Hủy đơn hàng"></button>
                                <a href="/manage/invoice_view/'.$value['invoice_id'].'" target="_blank" title="Xem đơn hàng" class="fa fa-eye btn btn-info btn-flat" role="button"></a>
                            </td>
                          </tr>';
                $stt = $stt + 1;
            }
            else
            {
                $html .= '<tr>
                            <td style="text-align: center"><a href="/manage/invoice_view/'.$value['invoice_id'].'" target="_blank" title="Xem đơn hàng">'. (string)$stt .'</a></td>
                            <td>'. $username .'</td>
                            <td>'.$this->product_price($total_durations,'s').'</td>
                            <td>'.$this->product_price($total_prices).'</td>
                            <td style="text-align: center">
                                <p class="text-aqua"><span class="fa fa-check-square" data-toggle="tooltip" data-placement="left" title="Đơn hàng đã được chấp thuận"></span></p>
                            </td>
                            <td style="text-align: center">
                                <button type="submit" name="'.$value['invoice_id'].'" value="approved" class="fa fa-check btn btn-info btn-flat disabled"></button>
                                <button type="submit" name="'.$value['invoice_id'].'" value="canceled" class="fa fa-times btn btn-danger btn-flat disabled"></button>
                                <a href="/manage/invoice_view/'.$value['invoice_id'].'" target="_blank" title="Xem đơn hàng" class="fa fa-eye btn btn-info btn-flat" role="button"></a>
                            </td>
                          </tr>';
                $stt = $stt + 1;
            }
        }
        $html .= '</tbody></table></form>';
        $result['html'] = $html;
        return $result;
    }
    public function get_customer_id_by_oder($oderID)
    {
        $this->db->select('user_id');
        $this->db->where('oder_id',$oderID);
        $result = $this->db->get('users');
        if ($this->db->affected_rows() == 1)
        {
            $data = $result->row_array();
            return $data['user_id'];
        }
        else
        {
            return false;
        }
    }
    public function customer_info($user_id)
    {
        $this->db->where('user_id',$user_id);
        $result = $this->db->get('users');
        if ($this->db->affected_rows() == 1)
        {
            $data = $result->row_array();
            return $data;
        }
        else
        {
            return false;
        }
    }

    public function update_invoice($data)
    {
        $debug_msg = '';
        $total_duration_play_in_frame = 1 * 60 * 60; //1h * 60m * 60s -> 3600s
        $sgb_duration_play_in_frame = round($total_duration_play_in_frame * $this->sgb_percent); //30%
        $sgs_duration_play_in_frame = round($total_duration_play_in_frame * $this->sgs_percent); //5%
        $customer_duration_play_in_frame = round($total_duration_play_in_frame - $sgb_duration_play_in_frame - $sgs_duration_play_in_frame);
        if ($data['action'] == 'update')
        {
            $this->db->select('reg_effective_begin, reg_effective_end, reg_frame, reg_max_duration, reg_timeplay, clipid, busline');
            $query = $this->db->get_where('ads_clips', array('clipid' => $data['clipid']))->result_array();
            if (count($query)>0)
            {
                $old_data['action'] = $data['action'];
                $old_data['frame_value'] = $query[0]['reg_frame'];
                $old_begin = explode('-',$query[0]['reg_effective_begin']);
                $old_date_begin = $old_begin[2].'/'.$old_begin[1].'/'.$old_begin[0];
                $old_end = explode('-',$query[0]['reg_effective_end']);
                $old_date_end = $old_end[2].'/'.$old_end[1].'/'.$old_end[0];
                $old_data['reservation_value'] = $old_date_begin.' - '.$old_date_end;
                $old_data['duration_value'] = $query[0]['reg_max_duration'];
                $old_data['timeplay_value'] = $query[0]['reg_timeplay'];
                $old_data['clipid'] = $query[0]['clipid'];
                $old_data['busline_value'] = $query[0]['busline'];
            }

            $date = explode(" - ", $data['reservation_value']);
            $begin = explode('/',$date[0]);
            $date_begin = $begin[2].'-'.$begin[1].'-'.$begin[0];
            $date_current = date("Y-m-d", time());// ngay hien tai
            $date_begin_current = strtotime($date_begin)-strtotime($date_current);
            if ($date_begin_current <= 0)
            {
                $date_begin = $date_current; // neu ngay bat dau trong qua khu -> chuyen thanh ngay hien tai
            }
            $end = explode('/',$date[1]);
            $date_end = $end[2].'-'.$end[1].'-'.$end[0];
            $total_date = 1 + (strtotime($date_end) - strtotime($date_begin))/86400;
            //dieu chinh gioi han so lan phat cua clip trong tung khung
            $tmp_times_play = round($customer_duration_play_in_frame / intval($data['duration_value']) / 10);// 1 clip chi phat toi da 10% thoi luong khung
            $times_play_allow = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50);
            if (intval($data['timeplay_value']) > $tmp_times_play)
            {
                for ($x = 0; $x < count($times_play_allow) ; $x++) {
                    if ($times_play_allow[$x] <= $tmp_times_play && $tmp_times_play < $times_play_allow[$x+1])
                    {
                        $data['timeplay_value'] = $times_play_allow[$x];
                        break;
                    }
                }
            }

            //cap nhat thong tin clip
            $update_data = array('reg_effective_begin' => $date_begin,
                                  'reg_effective_end' => $date_end,
                                  'busline' => $data['busline_value'],
                                  'reg_frame' => $data['frame_value'],
                                  'reg_max_duration' => $data['duration_value'],
                                  'reg_timeplay' => $data['timeplay_value']);
            $this->db->where('clipid', $data['clipid']);
            $this->db->update('ads_clips', $update_data);
            //cap nhat thong tin thoi luong phat
            //delete old info
            if ($total_date >= 1)
            {
                $this->db->delete('duration_play_day', array('clipid' => $data['clipid']));
            }
            //insert for replace new info
            $this->load->helper('string');
            $rate_per_frame = $this->frame_rate;
            $rate_by_duration = $this->duration_rate;
            for ($x = 0; $x < $total_date ; $x++) {
                do { //tạo id unique
                    $row_id = random_string('alnum', 16);
                    $row_id_exist = $this->db->get_where('duration_play_day', array('id' => $row_id), 1)->num_rows();
                } while ($row_id_exist > 0);
                if (intval($data['busline_value']) > 0)
                {
                    $price = $this->base_price * $data['duration_value'] * $data['timeplay_value'] * $rate_per_frame[$data['frame_value']] * $rate_by_duration[$data['duration_value']];
                    $insert_data = array(
                       'id' => $row_id,
                       'clipid' => $data['clipid'],
                       'busline' => $data['busline_value'],
                       'date' => date("Y-m-d", strtotime($date_begin)+($x*86400)),
                       'frame' => $data['frame_value'],
                       'duration' => $data['duration_value'],
                       'times' => $data['timeplay_value'],
                       'price' => $price,
                       'total_duration' => $data['duration_value'] * $data['timeplay_value']
                    );
                    $this->db->insert('duration_play_day', $insert_data);
                }
                else
                {
                    $busline_data = $this->busline;
                    foreach ($busline_data as $busline_id=>$busline_item){
                        do { //tạo id unique
                            $row_id = random_string('alnum', 16);
                            $row_id_exist = $this->db->get_where('duration_play_day', array('id' => $row_id), 1)->num_rows();
                        } while ($row_id_exist > 0);
                        $price = $this->base_price * $data['duration_value'] * $data['timeplay_value'] * $rate_per_frame[$data['frame_value']] * $rate_by_duration[$data['duration_value']];
                        $insert_data = array(
                           'id' => $row_id,
                           'clipid' => $data['clipid'],
                           'busline' => $busline_id,
                           'date' => date("Y-m-d", strtotime($date_begin)+($x*86400)),
                           'frame' => $data['frame_value'],
                           'duration' => $data['duration_value'],
                           'times' => $data['timeplay_value'],
                           'price' => $price,
                           'total_duration' => $data['duration_value'] * $data['timeplay_value']
                        );
                        $this->db->insert('duration_play_day', $insert_data);
                    }
                }

            }
            //tinh thoi luong con lai
            for ($x = 0; $x < $total_date ; $x++) {
                $total_in_day = 0;
                //tinh tong thoi gian clip dc phat trong khung gio:
                if (intval($data['busline_value']) > 0)
                {
                    $where_cond = array('date' => date("Y-m-d", strtotime($date_begin)+($x*86400)),
                                        'busline' => $data['busline_value'],
                                        'frame' => $data['frame_value']);
                    $query = $this->db->select('total_duration')->get_where('duration_play_day', $where_cond)->result_array();
                    //tong thoi luong phat trong ngay
                    foreach ($query as $row)
                    {
                        $total_in_day = $total_in_day + $row['total_duration'];
                    }
                    $remain_play_duration = $customer_duration_play_in_frame - $total_in_day;
                    if ($remain_play_duration < 0)
                    {
                        //khoi phu lai gia tri truoc do
                        return $this->update_invoice($old_data);
                    }
                    else
                    {
                        return null;
                    }
                }
                else
                {

                    foreach ($busline_data as $busline_id=>$busline_item){
                        $total_in_day = 0;
                        $where_cond = array('date' => date("Y-m-d", strtotime($date_begin)+($x*86400)),
                                            'busline' => $busline_id,
                                            'frame' => $data['frame_value']);
                        $query = $this->db->select('total_duration')->get_where('duration_play_day', $where_cond)->result_array();
                        //tong thoi luong phat trong ngay
                        foreach ($query as $row)
                        {
                            $total_in_day = $total_in_day + $row['total_duration'];
                        }
                        $remain_play_duration = $customer_duration_play_in_frame - $total_in_day;
                        $debug_msg .= $this->db->last_query().'\n';
                        //echo 'Busline '.$busline_id. ' remain '.$remain_play_duration.'Db: '.$debug_msg;
                        if ($remain_play_duration < 0)
                        {
                            //khoi phu lai gia tri truoc do
                            return $this->update_invoice($old_data);
                        }
                    }
                    return null;
                }
            }
        }
        elseif ($data['action'] == 'remove')
        {
            $clipid = substr($data['clipid'], -16);
            $this->db->delete('ads_clips', array('clipid' => $clipid));
            $this->db->delete('duration_play_day', array('clipid' => $clipid));
            return $data['clipid'];
        }

    }
    public function confirm_invoice($info)
    {
        foreach ($info as $invoice_id => $action){
            if ($this->check_invoice($invoice_id)){
                if ($action === 'approved'){
                    $this->db->where('oder_id', $invoice_id)->update('users', array('approved' => 1));
                }elseif ($action === 'canceled'){
                    $this->db->delete('ads_clips', array('invoice_id' => $invoice_id));
                    $this->db->delete('users', array('oder_id' => $invoice_id));
                    //$this->db->where('oder_id', $invoice_id)->update('users', array('approved' => 0));
                }
            }
        }
    }

    public function check_invoice($oderID)
    {
        $items_exist = ($this->db->select('clipid')->get_where('ads_clips', array('invoice_id' => $oderID))->num_rows() > 0) ? true : false;
        return $items_exist;
    }
    public function create_invoice($oderID,$userid,$for_print=0)
    {
        if ($for_print == 0)
        {
            $action_header = '<th rowspan="2" style="text-align: center; vertical-align: middle;">Thao tác</th>';
            $action_footer = '<tr id="btn_add_clip">
                    <td colspan="11"></td>
                    <td style="text-align: center; vertical-align: middle;">
                      <div class="btn-group no-print">
                        <button id="add_clip" type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Thêm Clip mới"><span class="fa fa-plus-square"></span> Thêm Clip </button>
                      </div>
                    </td>
                    <script type="text/javascript">
                        $("#add_clip").click(function(){
                            $.post("/manage/create_invoice/'.$oderID.'",
                            {
                                action: "add",
                                oderid: \''.$oderID.'\'
                            },
                            function(data, status){
                                $("#btn_add_clip").before(data);
                            });
                        });
                    </script>
                  </tr>';
        }
        else
        {
            $action_header = '';
            $action_footer = '';
        }
        $html = '<div class="col-xs-12 table-responsive"><table class="table table-striped table-bordered"><colgroup><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /></colgroup><tr><th rowspan="2" style="text-align: center; vertical-align: middle;">STT</th><th colspan="10" style="text-align: center;">Chi tiết dịch vụ</th>'.$action_header.'</tr><tr>
                    <th style="text-align: center; vertical-align: middle;" colspan="2">Khung giờ phát quảng cáo</th>
                    <th style="text-align: center; vertical-align: middle; width:20%" colspan="2">Thời lượng tối đa của mỗi Clip</th>
                    <th style="text-align: center; vertical-align: middle;" colspan="2" >Ngày phát quảng cáo</th>
                    <th style="text-align: center; vertical-align: middle;" >Tuyến xe</th>
                    <th style="text-align: center; vertical-align: middle;" >Số lần phát</th>
                    <th style="text-align: center; vertical-align: middle;">Đơn giá</br>(đồng/giây)</th>
                    <th style="text-align: center; vertical-align: middle;">Thành tiền</br>(vnđ)</th>
                  </tr>';
        $tmp_num = 1;
        $total_price = 0;
        if ($oderID != '')
        {
            $items_exist = $this->db->select('clipid, busline, reg_max_duration, reg_frame, reg_effective_begin, reg_effective_end, reg_timeplay')->get_where('ads_clips', array('invoice_id' => $oderID));
            if ($items_exist->num_rows() > 0)
            {
                foreach ($items_exist->result_array() as $row)
                {
                    $clip_price = $this->db->select_sum('price')->get_where('duration_play_day', array('clipid' => $row['clipid']))->result_array();
                    $row['price'] = $clip_price[0]['price'];
                    $total_price = $total_price + $row['price'];
                    $rate_per_frame = $this->frame_rate;
                    $rate_by_duration = $this->duration_rate;
                    $row['baseprice'] = $this->base_price  * $rate_per_frame[$row['reg_frame']] * $rate_by_duration[$row['reg_max_duration']];
                    $html .= $this->add_invoice_row_by_timeframe($oderID,$userid,$row,$tmp_num,$for_print);
                    $tmp_num = $tmp_num + 1;
                }
            }
            else
            {
                if ($for_print != 0)
                {
                    return null;
                }
                $html .= $this->add_invoice_row_by_timeframe($oderID,$userid,array(),$tmp_num,$for_print);
            }
        }
        else
        {
            $html .= $this->add_invoice_row_by_timeframe($oderID,$userid,array(),$tmp_num,$for_print);
        }

        $html .= $action_footer.'</table></div>';
        $html .= '<script type="text/javascript">
                    function update_invoice(clip_item_id){
                        $("#reservation_"+clip_item_id).after(\'<div id="waiting" class="input-group-addon"><img src="/bes/img/ajax-loader.gif" style="width: 20px;" /></div>\');
                        $.post("/manage/update_invoice/'.$oderID.'",
                            {
                                action: "update",
                                oderid: \''.$oderID.'\',
                                clipid: clip_item_id,
                                frame_value: $("#frame_"+clip_item_id).val(),
                                duration_value: $("#range_"+clip_item_id).val(),
                                reservation_value: $("#reservation_"+clip_item_id).val(),
                                busline_value: $("#busline_"+clip_item_id).val(),
                                timeplay_value: $("#timeplay_"+clip_item_id).val()
                            },
                            function(data, status){
                                $("#waiting").hide(\'slow\', function(){ $("#waiting").remove(); });
                                location.reload();
                            });
                    }
                  </script>';
        $result['list'] = $html;
        $result['totalprice'] = $this->product_price($total_price);
        $result['vat'] = $this->product_price($total_price / 10);
        $result['total_invoice'] = $this->product_price($total_price + ($total_price / 10));
        return $result;
    }

    private function busline_dropdown($busline_item_id,$default='00')
    {
        $busline_data = $this->busline;
        $busline_data['00'] = 'Tất cả tuyến xe';
        ksort($busline_data);
        $html = '<select style="width: 72px;" id="busline_'.$busline_item_id.'" class="form-control" >';
        foreach ($busline_data as $busline_id=>$busline_item){
            $selected = ( $default == $busline_id ) ? 'selected' : '';
            $html .= '<option value="'.$busline_id.'" '.$selected.'>'.$busline_id. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .$busline_item.'</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public function add_invoice_row_by_timeframe($invoice_id='',$userid,$item_data=array(),$tmp_num='',$for_print=0)
    {
        $debug_msg = '';
        $frame = $this->frame_play;
        $busline_data = $this->busline;
        //var_dump($item_data);
        if (empty($item_data))
        {

            //echo 'Oder empty, create new.';
            $this->load->helper('string');
            do {
                $clip_item_id = random_string('alnum', 16);
                $clip_id_exist = $this->db->get_where('ads_clips', array('clipid' => $clip_item_id), 1)->num_rows();
            } while ($clip_id_exist > 0);
            $reg_frame = 0;
            $busline_id = '01';
            $time_play = 10;
            $range_value = 15;
            $reservation_value = date("d/m/Y").' - '.date("d/m/Y");
            $total_duration_play_in_frame = 1 * 60 * 60; //1h * 60m * 60s -> 3600s
            $sgb_duration_play_in_frame = round($total_duration_play_in_frame * $this->sgb_percent); //30%
            $sgs_duration_play_in_frame = round($total_duration_play_in_frame * $this->sgs_percent); //5%
            $customer_duration_play_in_frame = round($total_duration_play_in_frame - $sgb_duration_play_in_frame - $sgs_duration_play_in_frame);
            foreach ($busline_data as $bus_line_id=>$busline_item){
                //tinh thoi luong con lai
                foreach ($frame as $frame_id=>$frame_item){
                    $total_in_day = 0;
                    $where_cond = array('date' => date("Y-m-d"),
                                        'busline' => $bus_line_id,
                                        'frame' => $reg_frame);
                    $query = $this->db->select('total_duration')->get_where('duration_play_day', $where_cond)->result_array();
                    //tong thoi luong phat trong ngay
                    foreach ($query as $row)
                    {
                        $total_in_day = $total_in_day + $row['total_duration'];
                    }
                    //$debug_msg .= $this->db->last_query().'</br>';
                    $remain_play_duration = $customer_duration_play_in_frame - $total_in_day;
                    if ($remain_play_duration > 300)
                    {
                        $reg_frame = $frame_id;
                        $busline_id = $bus_line_id;
                        break 2;
                    }
                }
            }
            if (strlen($invoice_id) <> 16)
            {
                do {
                    $invoice_id = random_string('alnum', 16);
                    $invoice_id_exist = $this->db->get_where('ads_clips', array('invoice_id' => $invoice_id), 1)->num_rows();
                } while ($invoice_id_exist > 0);
            }

            $data = array(
               'invoice_id' => $invoice_id,
               'clipid' => $clip_item_id ,
               'busline' => $busline_id,
               'reg_max_duration' => 15 ,
               'reg_frame' => $reg_frame,
               'reg_timeplay' => $time_play,
               'reg_effective_begin' => date("Y-m-d"),
               'reg_effective_end' => date("Y-m-d"),
               'userid' => $userid
            );

            $this->db->insert('ads_clips', $data);
            $this->db->where('user_id', $userid)->update('users', array('oder_id' => $invoice_id));

            //update invoice data
            $new_item['action'] = 'update';
            $new_item['oderid'] = $invoice_id;
            $new_item['clipid'] = $clip_item_id;
            $new_item['frame_value'] = $reg_frame;
            $new_item['duration_value'] = 15;
            $new_item['reservation_value'] = $reservation_value;
            $new_item['busline_value'] = $busline_id;
            $new_item['timeplay_value'] = $time_play;
            $this->update_invoice($new_item);
        }
        else
        {
            $time_play = $item_data['reg_timeplay'];;
            $clip_item_id = $item_data['clipid'];
            $reg_frame = $item_data['reg_frame'];
            $busline_id = $item_data['busline'];
            $range_value = intval($item_data['reg_max_duration']);
            $reservation_value = date("d/m/Y", strtotime($item_data['reg_effective_begin'])).' - '.date("d/m/Y", strtotime($item_data['reg_effective_end']));
        }
        if (!isset($item_data['price']))
        {
            $item_data['price'] = 'n/a';
        }
        if (!isset($item_data['baseprice']))
        {
            $item_data['baseprice'] = 'n/a';
        }
        if ($for_print == 0)
        {
            $dropdown_frame_play = $this->timeframe_dropdown($clip_item_id,$reg_frame);
            $dropdown_bus_line = $this->busline_dropdown($clip_item_id,$busline_id);
            $dropdown_times_play = $this->timeplay_dropdown($clip_item_id,$time_play);
            $date_play = '<div class="input-group col-md-10">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right active" value="'.$reservation_value.'" id="reservation_'.$clip_item_id.'" >
                        </div>';
            $duration_play = '<div class="col-md-12">
                            <input type="text" id="range_'.$clip_item_id.'" value="" />
                        </div>';
            $action_button = '<td colspan="2" style="text-align: center; vertical-align: middle;">
                            <button onclick="update_invoice(\''.$clip_item_id.'\')" type="button" class="btn btn-info" data-toggle="tooltip" data-placement="left" title="Cập nhật thông tin đơn giá phát quảng cáo của Clip"><span class="fa fa-refresh"></span> </button>
                            <button onclick="remove_row(\''.$invoice_id.$clip_item_id.'\')" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Hủy bỏ Clip hiện tại"><span class="fa fa-minus-square"></span></button>
                        </td>';
            $text_center = '';
        }
        else
        {
            $dropdown_frame_play = $frame[$reg_frame];
            if ($busline_id != '00')
            {
                $dropdown_bus_line = $busline_data[$busline_id];
            }
            else
            {
                $dropdown_bus_line = 'Toàn bộ các tuyến';
            }

            $dropdown_times_play = $time_play;
            $date_play = $reservation_value;
            $duration_play = $range_value;
            $action_button = '';
            $text_center = 'style="text-align:center"';
        }
        $js = '<script type="text/javascript">
                    function remove_row(row){
                        var r = confirm("Bạn muốn hủy clip này? \nThao tác này sẽ không thể khôi phục lại thông tin đã hủy.");
                        if (r == true) {
                            $.post("/manage/update_invoice/'.$invoice_id.'",
                            {
                                action: "remove",
                                oderid: \''.$invoice_id.'\',
                                clipid: row
                            },
                            function(data, status){
                                var paras = document.getElementsByClassName(row);
                                do {
                                    paras[0].parentNode.removeChild(paras[0]);
                                }
                                while(paras[0]);
                                alert("Clip đã được xóa.");
                            });
                        }
                    }
                    $("#reservation_'.$clip_item_id.'").daterangepicker();
                    $(function () {
                            $("#range_'.$clip_item_id.'").ionRangeSlider({
                                hide_min_max: true,
                                keyboard: true,
                                min: 5,
                                max: 30,
                                from: '.$range_value.',
                                step: 5,
                                postfix: " s",
                                grid: true
                            });
                        });
                </script>';

        $html = '<tr class="'.$invoice_id.$clip_item_id.'">
                    <td style="text-align: center; vertical-align: middle;">'.$tmp_num.'
                        <input type="hidden" value="'.$clip_item_id.'" id="clip_item_id" />
                        <input type="hidden" value="'.$invoice_id.'" id="invoice_id" />
                    </td>
                    <td colspan="2" '.$text_center.'>'.$dropdown_frame_play.'</td>
                    <td colspan="2" '.$text_center.'>'.$duration_play.'</td>
                    <td colspan="2" '.$text_center.'>'.$date_play.'</td>
                    <td '.$text_center.'>'.$dropdown_bus_line.'</td>
                    <td '.$text_center.'>'.$dropdown_times_play.'</td>
                    <td style="text-align: right; vertical-align: middle;">'.$this->product_price($item_data['baseprice']).'</td>
                    <td style="text-align: right; vertical-align: middle;">'.$this->product_price($item_data['price']).'</td>
                    '.$action_button;
        return $html.$js.'</tr>';
    }

    private function create_invoice_by_total_clip($data)
    {
        $html  = '<input type="hidden" name="totalclip" value="'.$data['totalclip'].'" />';
        $html .= '<div class="col-xs-12 table-responsive"><table class="table table-striped table-bordered">
                 <colgroup><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /><col /></colgroup>
                 <tr><td colspan="12"><div class="btn-group no-print"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span> Tổng số Clip quảng cáo ('.$data['totalclip'].')</button><ul class="dropdown-menu">';
        if (isset($data['oderid']) && $data['oderid'] != '')
        {
            $total_clip_required = array(1,2,3,4,5,10,15,20,30,40,50,60,70,80);
            for ($x = 1; $x <= sizeof($total_clip_required); $x++) {
                $html .= '<li><a href="/manage/create_invoice/'.$data['oderid'].'/'.$total_clip_required[$x-1].'">'.$total_clip_required[$x-1].'</a></li>';
            }
        }
        $html .= '</ul></div></td></tr><tr><th>STT</th><th colspan="8" style="text-align: center;">Chi tiết dịch vụ</th><th>Đơn giá</th><th>Thành tiền</th></tr>';
        $js = '<script type="text/javascript">';
        if (isset($data['totalclip']) && $data['totalclip'] >= 1) {
            for ($y = 1; $y <= $data['totalclip']; $y++) {
                $js .= "$('#reservation_".$y."').daterangepicker();";
                $html .= '<tr>
                            <td rowspan="7" style="text-align: center; vertical-align: middle;">'.$y.'</td>
                            <td colspan="8"><strong>Khung giờ phát quảng cáo</strong></td>
                            <td rowspan="7"></td>
                            <td rowspan="7"></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" name="'.$y.'_frame_5" />05h00-05h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_6" />06h00-06h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_7" />07h00-07h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_8" />08h00-08h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_9" />09h00-09h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_10" />10h00-10h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_11" />11h00-11h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_12" />12h00-12h59</label></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" name="'.$y.'_frame_13" />13h00-13h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_14" />14h00-14h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_15" />15h00-15h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_16" />16h00-16h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_17" />17h00-17h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_18" />18h00-18h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_19" />19h00-19h59</label></td>
                            <td><label><input type="checkbox" name="'.$y.'_frame_20" />20h00-20h59</label></td>
                        </tr>
                        <tr>
                            <td colspan="8"><strong>Thời lượng tối đa</strong> </td>
                        </tr>
                        <tr>';
                $html .= '  <td colspan="2"><div class="radio"><label><input type="radio" name="'.$y.'_otp_thoiluong" id="otp_5s" value="5" checked>5 giây</label></div></td>
                            <td colspan="2"><div class="radio"><label><input type="radio" name="'.$y.'_otp_thoiluong" id="otp_10s" value="10">10 giây</label></div></td>
                            <td colspan="2"><div class="radio"><label><input type="radio" name="'.$y.'_otp_thoiluong" id="otp_15s" value="15">15 giây</label></div></td>
                            <td colspan="2"><div class="radio"><label><input type="radio" name="'.$y.'_otp_thoiluong" id="otp_30s" value="30">30 giây</label></div></td>
                        </tr>
                        <tr>
                            <td colspan="8"><strong>Thời gian phát quảng cáo</strong> </td>
                        </tr>
                        <tr>
                            <td colspan="8">
                                <div class="form-group col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right active" name="'.$y.'_otp_thoigian" id="reservation_'.$y.'">
                                    </div>
                                <!-- /.input group -->
                                </div>
                            </td>
                        </tr>';
                }
            }
        $js .= '</script>';
        $html .= '</table></div>';
        $data_return = array('html'=> $html,'js'=> $js);
        return $data_return;
    }

    private function product_price($priceFloat=0,$symbol = 'đ') {
        $symbol_thousand = '.';
        $decimal_place = 0;
        if ($priceFloat > 0)
        {
            $price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
        }
        else
        {
            $price = $priceFloat;
        }
        return $price.' '.$symbol;
    }

    private function timeframe_dropdown($clip_item_id,$default)
    {
        $html = '<select id="frame_'.$clip_item_id.'" class="form-control" >';
        $frame = $this->frame_play;
        for ($x = 0; $x < sizeof($frame); $x++) {
            $selected = ( $default == $x ) ? 'selected' : '';
            $html .= '<option value="'.$x.'" '.$selected.'>'.$frame[$x].'</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private function timeplay_dropdown($clip_item_id,$default)
    {
        $max = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,20,25,30,35,40,45,50);
        $html = '<select style="width: 65px;" id="timeplay_'.$clip_item_id.'" class="form-control" >';
        for ($x = 0; $x < count($max) ; $x++) {
            $selected = ( $default == $max[$x] ) ? 'selected' : '';
            $html .= '<option value="'.$max[$x].'" '.$selected.'>'.$max[$x].'</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private function show_debug_info($data)
    {
        ob_start();
        var_dump($data);
        $raw_result = ob_get_clean();
        $html = '<div class="row">
                <div class="col-md-12">
                    <div class="box collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong>Thông tin Debug</strong></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                          </div>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <span>Last Query: '.$this->db->last_query().'</span></br></br>
                            <span>Raw data: '.$raw_result.'</span></br></br>
                            <span>Result: '.$this->printNestedArray($data).'</span>
                        </div>
                    </div>
                </div>
            </div>';
    return $html;
    }

    private function printNestedArray($a)
    {
        if (is_array($a))
        {
            $html = '';
            foreach ($a as $key => $value) {
                $html .= htmlspecialchars("$key => ");
                if (is_array($value))
                {
                    $html .=  $this->printNestedArray($value);
                }
                else
                {
                    $html .=  htmlspecialchars($value);
                }
                $html .= '<br />';
            }
        }
        return $html;
    }

    // HTML Minifier
    private function minify_html($input) {
        if(trim($input) === "") return $input;
        // Remove extra white-space(s) between HTML attribute(s)
        $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, str_replace("\r", "", $input));
        // Minify inline CSS declaration(s)
        if(strpos($input, ' style=') !== false) {
            $input = $this->minify_css($input);
        }
        if(strpos($input, ' <script') !== false) {
            $input = $this->minify_js($input);
        }
        return preg_replace(
            array(
                // t = text
                // o = tag open
                // c = tag close
                // Keep important white-space(s) after self-closing HTML tag(s)
                '#<(img|input)(>| .*?>)#s',
                // Remove a line break and two or more white-space(s) between tag(s)
                '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
                '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
                '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
                '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
                '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
                '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
                // Remove HTML comment(s) except IE comment(s)
                '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
            ),
            array(
                '<$1$2</$1>',
                '$1$2$3',
                '$1$2$3',
                '$1$2$3$4$5',
                '$1$2$3$4$5$6$7',
                '$1$2$3',
                '<$1$2',
                '$1 ',
                '$1',
                ""
            ),
        $input);
    }
    // CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
    private function minify_css($input) {
        if(trim($input) === "") return $input;
        return preg_replace(
            array(
                // Remove comment(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                // Remove unused white-space(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                // Replace `:0 0 0 0` with `:0`
                '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                // Replace `background-position:0` with `background-position:0 0`
                '#(background-position):0(?=[;\}])#si',
                // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
                '#(?<=[\s:,\-])0+\.(\d+)#s',
                // Minify string value
                '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
                '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                // Minify HEX color code
                '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                // Replace `(border|outline):none` with `(border|outline):0`
                '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                // Remove empty selector(s)
                '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
            ),
            array(
                '$1',
                '$1$2$3$4$5$6$7',
                '$1',
                ':0',
                '$1:0 0',
                '.$1',
                '$1$3',
                '$1$2$4$5',
                '$1$2$3',
                '$1:0',
                '$1$2'
            ),
        $input);
    }
    // JavaScript Minifier
    private function minify_js($input) {
        if(trim($input) === "") return $input;
        return preg_replace(
            array(
                // Remove comment(s)
                '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
                // Remove white-space(s) outside the string and regex
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
                // Remove the last semicolon
                '#;+\}#',
                // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
                '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
                // --ibid. From `foo['bar']` to `foo.bar`
                '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
            ),
            array(
                '$1',
                '$1$2',
                '}',
                '$1$3',
                '$1.$3'
            ),
        $input);
    }
}