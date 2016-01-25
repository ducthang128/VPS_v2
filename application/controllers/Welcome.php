<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
	public function __construct()
    {
		parent::__construct();
	}

	public function index()
	{
        if($this->verify_min_level(1))
        {
            redirect('/manage');
            return true;
        }
        else
        {
            $this->load->view('welcome_message');
        }
	}

    public function remote_control(){
        if (isset($_POST['request']) && isset($_POST['key'])){
        $this->load->library('encrypt');
        $requested = $this->encrypt->decode($_POST['request'],$_POST['key']);
        $request_data = json_decode($requested);
        $request_array_data = (array)$request_data;
        switch ($request_array_data['action']){
            case 'get_list_video':
                $request_form = $request_array_data['macaddr'];
                $clip_play_status = (array)$request_array_data['clip_duration'];

                $all_clip_played = array();
                if (count($clip_play_status)>0){
                    foreach ($clip_play_status as $clip_played){
                        array_push($all_clip_played,(array)$clip_played);
                    }
                    foreach ($all_clip_played as $clip_item){
                        $update_info = array('times' => intval($clip_item['times']),
                                             'total_duration' => intval($clip_item['total_duration']));
                        $query = $this->db->select('clipid')->where('checksum',intval($clip_item['crc']))->get('ads_clips')->result_array();
                        if (count($query)>0){
                            $clipid = $query[0]['clipid'];
                            $this->db->where('clipid', $clipid)->update('duration_play_day', $update_info);
                        }
                    }
                }
                $this->load->model('ads_content_model');
                $list = $this->ads_content_model->list_clip_to_bus();
                if ($list !== false)
                {
                    $return_data = $this->encrypt->encode($list,$_POST['key']);
                    die(base64_encode($return_data));
                }
                else
                {
                    die('Access Deny');
                }
            break;


            default :
                die('Unknow command');
            }
        }
        else
        {
            die();
        }
	}

 	public function reg()
	{
        if (isset($_POST)){
          $post_data = $this->security->xss_clean($_POST,TRUE);
          $this->load->model('User_model');
          $result = $this->User_model->add_temporary_customer($post_data);
          if ($result['result'] == false)
          {
              $data['url'] = '/';
              $data['timeout'] = 30;
              $data['msg'] = $result['msg'];
              $this->load->view('script_redirect',$data);
          }
          else
          {
              $data['url'] = $_SERVER["HTTP_HOST"].'/login';
              $data['timeout'] = 5;
              $data['msg'] = "Đăng ký dịch vụ hoàn tất, kiễm tra email để thông tin chi tiết.";
              $mail_html  = 'Chào '.$post_data['first_name'].' '.$post_data['last_name'].'<br>';
              $mail_html .= 'Cảm ơn quý khách đã lựa chọn sử dụng dịch vụ quảng cáo của chúng tôi.<br>';
              $mail_html .= 'Để sử dụng dịch vụ, quý khách cần hoàn tất đơn hàng và các thủ tục cần thiết.<br>';
              $mail_html .= 'Đơn hàng của quý khác có giá trị 07 kể từ ngày nhận được thư thông báo này.<br>';
              $mail_html .= 'Nhấn vào đây để đến <a href="http://bes.saigonsolutions.com.vn/'.$data['url'].'" target="_blank">Ðơn Hàng</a>.<br>';
              $mail_html .= 'Thông tin truy cập đơn hàng tạm thời<br>';
              $mail_html .= ' - Trang đăng nhập: '.$data['url'].'<br>';
              $mail_html .= ' - Username: '. $result['username'].'<br>';
              $mail_html .= ' - Password: '. $result['password'].'<br><br>';
              $mail_html .= 'Trân trọng thông báo.<br>';
              $mail_html .= '<br><br><br>';
              $mail_html .= 'Bạn nhận được thư này do bạn (hoặc một ai đó) đã đăng ký sử dụng dịch vụ quảng cáo trên hệ thống xe Bus thành phố HCM, nếu bạn thực sự không có đăng ký sử dụng dịch vụ thì bạn có thể bảo qua thông tin trong email này.';
              $test_sendmail = $this->authentication->send_email($post_data['email'],$post_data['first_name'].' '.$post_data['last_name'],'[BES] Dang ky su dung dich vu quang cao',$mail_html);
              $this->load->view('script_redirect',$data);

          }
		}
	}
}
?>