<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ads_content_model extends MY_Model {
    var $max_video_width = 1280;
    var $max_video_height = 720;
    var $max_profile_video_code = 14000; //14k -> 150k https://en.wikipedia.org/wiki/H.264/MPEG-4_AVC
    var $frame_play = array('05h00-05h59',
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

    public function __construct() {
        parent::__construct();
        $this->load->model('video_info_model');
    }

    public function get_clip_playing(){
        $list = $this->db->get('quang_cao_dang_phat')->result_array();
        foreach ($list as $row => $clip){
            $clipname = $this->db->select('filename')->from('ads_clips')->where('clipid', $clip['ma_clip'])->get()->row_array();
            $list[$row]['filename'] = $clipname['filename'];
        }
        return $list;
    }

    public function list_clip_to_bus()
    {
        $list_clip = array();
        $active_clips = $this->db->select( 'clipid' )
                                    ->from( 'ads_active' )
                                    ->where(array('admin'=>1,'manager'=>1,'customer'=>1,'system'=>1))
                        			->get()
                                    ->result_array();
        if (count($active_clips)>0){
            foreach ($active_clips as $clip)
            {
                $query1 = $this->db->select( 'busline,clipid,frame,duration,times,total_duration' )
                                            ->from( 'duration_play_day' )
                                            ->where('clipid',$clip['clipid'])
                                			->get()
                                            ->result_array();
                $query2 = $this->db->select( 'filename,unique_name,filepath,checksum,rawSeconds' )
                                            ->from( 'ads_clips' )
                                            ->where('clipid',$clip['clipid'])
                                			->get()
                                            ->result_array();
                $query2[0]['filepath'] = str_replace($_SERVER["DOCUMENT_ROOT"],'http://'.$_SERVER["HTTP_HOST"].'/',$query2[0]['filepath']);
                $clip_info = array_merge($query1[0],$query2[0]);
                array_push($list_clip,$clip_info);
            }

        }

        if (count($list_clip)>0)
        {
            return json_encode($list_clip);
        }
        else
        {
            return false;
        }
    }

    public function create_list_content($auth_role,$list_ads)
    {
        $row_id = 1;
        $str_row = '';
        if (!is_array($list_ads))
        {
            return null;
        }
        foreach ($list_ads as $row)
        {
           if ($auth_role == 'manager' && $row['filename'] == ''){
                continue;
           }
           $str_row .= '<tr>';
           $str_row .= '<td>'.$row_id.'</td>';
           if ($row['filename'] != '')
           {
               $slipted = explode(".", $row['filename']);
               $extension = end($slipted);
               $file_name = str_replace('.'.$extension,"",$row['filename']);

               $video_Width = ( $row['srcWidth'] > 720 ) ? 720 : $row['srcWidth'];
               $video_Height = ( $row['srcHeight'] > 405 ) ? 405 : $row['srcHeight'];

               $str_row .= '<td><a href="JavaScript:html5Lightbox.showLightbox(2, \'/adsclips/'. $row['unique_name']. '\', \''. $file_name.' - ' .$row['duration']. '\', '.$video_Width.', '.$video_Height.', \'/adsclips/'. $row['unique_name']. '\');">'. $file_name. '</a></td>';
           }
           else
           {
               if ($auth_role <> 'customer')
               {
                    $str_row .= '<td>Chờ đăng tải nội dung mới</td>';
               }
               else
               {
                    $upload_form = '<form id="upload_form" enctype="multipart/form-data" method="post" action="/manage/clipupload" style="padding:0px;width:100%;">
                                        <input type="file" name="image_video_file" id="image_video_file" onchange="fileSelected();" style="width:70%;display:inline;font-size:10px;"/>
                                        <input type="input" style="display:none" name="clipid" value="'. $row['clipid'].'"/>
                                        <input type="submit" value="Tải lên" style="font-size:10px;"/>
                                    </form>';
                    $str_row .= '<td>'.$upload_form.'</td>';
               }

           }

           if (strlen($row['duration']) == 0)
           {
                $query = $this->db->select( 'duration' )
                                    ->from( 'duration_play_day' )
                                    ->where('clipid', $row['clipid'])
                        			->get()
                                    ->result_array();
                if (count($query)>0){
                    $duration = $query[0]['duration'];
                    $max_duration = 'max('.$duration.'s)';
                }
                else
                {
                    $duration = $row['clipid'];
                    $max_duration = 'max('.$duration.'s)';
                }
           }
           else
           {
                $duration = $row['duration'];
                $max_duration = $row['duration'];
           }


            if ($row['capacity'] == 'n/a Byte')
            {
                $max_capacity = $this->video_info_model->bytesToSize1024(intval($duration) * ($this->max_profile_video_code / 8) * 1024,1);
                $row['capacity'] = 'max('.$max_capacity.')';
            }

           $str_row .= '<td>'.$this->frame_play[$row['reg_frame']].'</td>';


           $str_row .= '<td>'.$max_duration.'</td>';
           unset($duration);
           unset($max_duration);

           $srcWidth = ($row['srcWidth']==0) ? $this->max_video_width : $row['srcWidth'];
           $srcHeight = ($row['srcHeight']==0) ? $this->max_video_height : $row['srcHeight'];
           $src_recommend_resolution = ($row['srcWidth']==0 || $row['srcHeight']==0) ? 'max('.$srcWidth. '*' .$srcHeight.'px@30fps)' : $row['srcWidth']. 'x' .$row['srcHeight'];
           $str_row .= '<td>'.$src_recommend_resolution.'</td>';

           $date_upload = ($row['dateupload'] == '0000-00-00 00:00:00') ?  '('.date("d-m-Y H:i:s",time()).')' : $row['dateupload'];
           $str_row .= '<td>'.$date_upload.'</td>';

           $str_row .= '<td>'.$row['user_name'].'</td>';

           if ($row['status']['admin'] == 1 && $row['status']['manager'] == 1 && $row['status']['customer'] == 1 && $row['status']['system'] == 1)
           {
                $str_row .= '<td><span class="label label-success">Cho phép</span></td>';
           }
           else
           {
                if ($row['filename'] == '')
                {
                    $msg_status = 'Chưa có clip';
                }
                else
                {
                    if ($row['status']['admin'] == 0 && $row['status']['manager'] == 0)
                    {
                        $msg_status = 'Đang chờ duyệt bởi Admin và Manager';
                    }
                    elseif ($row['status']['admin'] == 0)
                    {
                        $msg_status = 'Đang chờ duyệt bởi Admin';
                    }
                    elseif ($row['status']['manager'] == 0)
                    {
                        $msg_status = 'Đang chờ duyệt bởi manager';
                    }
                    elseif ($row['status']['system'] == 0)
                    {
                        $msg_status = 'Tài khoản tạm khóa do hết kinh phí, vui lòng nạp thêm.';
                    }
                    elseif ($row['status']['customer'] == 0)
                    {
                        $msg_status = 'Bạn đã tạm khóa';
                    }
                }
                $str_row .= '<td><span class="label label-danger" data-toggle="tooltip" data-placement="bottom" title="'.$msg_status.'">Đã tạm ngưng</span></td>';
           }
            if ($row['filename'] != ''){
               if ($row['status'][$auth_role] == '1')
               {
                    $bt_status = '<button onclick="clip_action(\''.$row['clipid'].'\',\'disable\');" type="button"  id="'.$row['clipid'].'_bt" class="btn btn-warning btn-xs" >Tạm khóa</button>';
               }
               else
               {
                    $bt_status = '<button onclick="clip_action(\''.$row['clipid'].'\',\'enable\');" type="button"  id="'.$row['clipid'].'_bt" class="btn btn-info btn-xs" >Cho phép</button>';
               }
               $bt_remove = '<button onclick="clip_action(\''.$row['clipid'].'\',\'del\');" type="button"  id="'.$row['clipid'].'_bt" class="btn btn-primary btn-xs">Xóa</button>';
            }
            else
            {
                $bt_remove = '';
                $bt_status = '';
            }
           $str_row .= '<td>'.$bt_status.' '.$bt_remove.'</td>';
           $str_row .= '</tr>';
           $row_id = $row_id + 1;
        }
        $result['result'] = $str_row;
        $result['debug'] = $this->show_debug_info($list_ads);
    return $result;
    }


    public function update_clip_status($role,$userid,$clipid,$action)
    {
        $query = $this->db->select('userid')
                            ->from('ads_clips')
                            ->where('clipid', $clipid)
                            ->get()
                            ->result_array();
        $owner_id = (count($query)>0) ? $query[0]['userid'] : 0;

        if ($action === 'del')
        {
            if ($owner_id == $userid)
            {
                if ($this->delete_clip($clipid,$role)){
                    return 'delete by user';
                }
            }
            else
            {
                if ($role <> 'customer')
                {
                    if ($this->delete_clip($clipid,$role)){
                        return 'delete by admin / manager';
                    }
                }
            }
        }
        elseif ($action === 'disable')
        {
            $data = array($role => 0);
            $this->db->where('clipid', $clipid)
                     ->update('ads_active', $data);
            return true;
        }
        elseif ($action === 'enable')
        {
            $data = array($role => 1);
            $this->db->where('clipid', $clipid)
                     ->update('ads_active', $data);
            return true;
        }
        return false;
    }

    private function delete_clip($clipid,$role)
    {
        $query = $this->db->select('filepath')
                            ->from('ads_clips')
                            ->where('clipid', $clipid)
                            ->get()
                            ->result_array();
        $file_to_removed = (count($query)>0) ? $query[0]['filepath'] : '';
        if ($file_to_removed != '')
        {
            if (file_exists($file_to_removed))
            {
                //uncomment when use SLIPT function
//                $files = glob(str_replace(".mp4","/*",$query[0]['filepath'])); // get all file names
//                foreach($files as $file){ // iterate files
//                  if(is_file($file))
//                    unlink($file); // delete file
//                }
//                rmdir(str_replace(".mp4","",$query[0]['filepath']));
                unlink($file_to_removed);
            }
            if ($role <> 'customer')
            {
                $this->db->delete('duration_play_day', array('clipid' => $clipid));
                $this->db->delete('ads_clips', array('clipid' => $clipid));
            }
            else
            {
                $data = array(
                               'duration' => null,
                               'capacity' => null,
                               'srcWidth' => null,
                               'srcHeight' => null,
                               'dateupload' => null,
                               'filename' => null,
                               'unique_name' => null,
                               'filepath' => null,
                               'checksum' => null
                            );
                $this->db->where('clipid', $clipid);
                $this->db->update('ads_clips', $data);
            }
            $this->db->delete('ads_active', array('clipid' => $clipid));
            return true;
        }
        return false;
    }

	public function get_list_clips($role,$userid,$offset=0,$match=1)
	{
        // Selected user table data
		$selected_columns = array(
			'clipid',
			'filename',
            'unique_name',
            'filepath',
            'userid',
            'extension',
            'duration',
            'srcWidth',
			'srcHeight',
			'rawSeconds',
			'checksum',
			'capacity',
            'dateupload',
            'reg_frame',
			'note'
		);

		// User table query
        if( $role == 'manager' || $role == 'admin' )
        {
            if ($match == 1)
            {
                $query = $this->db->select( $selected_columns )
                        			->from( 'ads_clips' )
                                    ->where('userid >', 0)
                                    ->order_by('reg_frame ASC, userid DESC')
                        			->limit(100,$offset)
                        			->get();
            }
            else
            {
                $query = $this->db->select( $selected_columns )
                                    ->from( 'ads_clips' )
                                    ->where('userid >', 0)
                        			->like('filename', $match)
                                    ->order_by('reg_frame ASC, userid DESC')
                        			->limit(100,$offset)
                        			->get();
            }

        }
        else
        {
      		if ($match == 1)
            {
                $query = $this->db->select( $selected_columns )
                        			->from( 'ads_clips' )
                        			->where( 'userid', $userid )
                                    ->order_by('reg_frame ASC, userid DESC')
                        			->limit(100,$offset)
                        			->get();
            }
            else
            {
                $query = $this->db->select( $selected_columns )
                        			->from( 'ads_clips' )
                                    ->like('filename', $match)
                        			->where( 'userid', $userid )
                                    ->order_by('reg_frame ASC, userid DESC')
                        			->limit(100,$offset)
                        			->get();
            }
        }

		if ( $query->num_rows() > 0 )
		{
			$result = $query->result_array();
            foreach ($result as $key=>$row)
            {
                $str_capacity = $this->video_info_model->bytesToSize1024($row['capacity']);
                $userinfo = (array)$this->auth_model->get_auth_data($row['userid']);
                $result[$key]['user_name'] = $userinfo['first_name'].' '.$userinfo['last_name'];
                $result[$key]['capacity'] = $str_capacity;
                $result[$key]['status'] = $this->get_clip_status($row['clipid']);
            }
            return $result;
		}

		return false;
	}

    private function get_clip_status($clipid)
    {
        // Selected status data
		$selected_columns = array(
			'admin',
			'manager',
            'customer',
            'system'
		);
		// User table query
		$query = $this->db->select( $selected_columns )
			->from( 'ads_active' )
            ->where( 'clipid', $clipid )
			->limit(1)
			->get();
        if ( $query->num_rows() > 0 )
        {
            return $query->row_array();
        }
        return false;
    }

    public function update_ads_content($video_info,$alias_name,$clipid=''){
        if ($video_info['srcWidth'] > $this->max_video_width)
        {
            return 'Chiều rộng khung hình '.$video_info['srcWidth'].' vượt mức giới hạn. Yều cầu chỉ từ '.$this->max_video_width.'px';
        }
        if ($video_info['srcHeight'] > $this->max_video_height)
        {
            return 'Chiều cao khung hình '.$video_info['srcHeight'].' vượt mức giới hạn. Yều cầu chỉ từ '.$this->max_video_height.'px';
        }

        $file_name_temp = explode(".", $video_info['filename']);
        $extension = end($file_name_temp);
        if (strlen($clipid) != 16)
        {
            $this->load->helper('string');
            do { //tạo id unique
                $adsclipsid = random_string('alnum', 16);
                $row_id_exist = $this->db->get_where('ads_clips', array('clipid' => $adsclipsid), 1)->num_rows();
            } while ($row_id_exist > 0);
            $action = 'new';
        }
        else
        {
            $adsclipsid = $clipid;

            $get_max_duration = $this->db->select('duration')->get_where('duration_play_day', array('clipid' => $adsclipsid), 1)->row_array();
            $max_duration = intval($get_max_duration['duration']);
            $max_capacity = intval($max_duration) * ($this->max_profile_video_code / 8) * 1024;

            if ($video_info['size'] > $max_capacity)
            {
                return 'Dung lượng '.$video_info['size'].' vượt quá định mức, yêu cầu không vượt quá '.$this->video_info_model->bytesToSize1024($max_capacity);
            }

            if ($video_info['duration']['rawSeconds'] > $max_duration)
            {
                return 'Thời lượng '.$video_info['duration']['rawSeconds'].' vượt quá định mức đã đăng ký, yêu cầu không vượt quá '.$max_duration.'s';
            }

            $action = 'update';
        }

        $data = array(
            'clipid'    => $adsclipsid,
            'filename'  => $alias_name,
            'unique_name' => $video_info['filename'],
            'filepath' => $video_info['filepath'],
            'userid' => $this->auth_user_id,
            'extension' => $extension,
            'format' => $video_info['format'],
            'duration' => $video_info['duration']['raw'],
            'capacity' => $video_info['size'],
            'dateupload' => date("Y-m-d h:i:s"),
            'note' => 'Đăng tải bởi '.$this->auth_user_name,
            'srcWidth' => $video_info['srcWidth'],
            'srcHeight' => $video_info['srcHeight'],
            'hours' => $video_info['duration']['hours'],
            'minutes' => $video_info['duration']['minutes'],
            'seconds' => $video_info['duration']['seconds'],
            'fractions' => $video_info['duration']['fractions'],
            'bitrate' => $video_info['bitrate'],
            'audioBitrate' => $video_info['audioBitrate'],
            'audioSampleFrequency' => $video_info['audioSampleFrequency'],
            'rawSeconds' => $video_info['duration']['rawSeconds'],
            'checksum' => $video_info['checksum']
        );
        //them moi
        if ($action == 'update')
        {
            $this->db->where('clipid', $adsclipsid);
            $this->db->update('ads_clips', $data);
        }
        else
        {
            $this->db->insert('ads_clips', $data);
        }
        $this->db->insert('ads_active', array('clipid' => $adsclipsid,'note' => 'Đang chờ duyệt...'));
        $this->add_log($this->auth_user_name.' đã thêm clip quảng cáo mới. ClipID:'.$data['clipid']);
        return true;
    }

    private function add_log($action){
        $Class = $this->router->fetch_class();
        $Action = $this->router->fetch_method();
        $data = array(
            'user' => $this->auth_user_id,
            'action' => $Class.'->'.$Action.'->'.$action
        );
        $this->db->insert('systemlogs', $data);
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
}
?>