<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends MY_Controller {
    var $menu_panel = array();
    public function __construct()
    {
		parent::__construct();
        if($this->verify_min_level(1))
        {
            $this->load->model('User_model');
            $this->load->model('Invoice');
            //menu default
            $basename = 'http://'.$_SERVER["HTTP_HOST"].'/'.$this->router->fetch_class().'/';
            $this->menu_panel[0] = array('user_allow'=>'169', 'name'=>'Lịch Phát', 'href'=>$basename, 'icon'=>'fa-dashboard');
            $this->menu_panel[1] = array('user_allow'=>'69', 'name'=>'Nội dung quảng cáo','href'=>$basename.'content','icon'=>'fa-file-video-o');
            $this->menu_panel[2] = array('user_allow'=>'9', 'name'=>'Danh sách đơn hàng','href'=>$basename.'invoice','icon'=>'fa-list-alt');
            $this->menu_panel[3] = array('user_allow'=>'1', 'name'=>'Đơn hàng','href'=>$basename.'create_invoice','icon'=>'fa-list-alt');
            $this->menu_panel[4] = array('user_allow'=>'69', 'name'=>'Quảng cáo đang phát','href'=>$basename.'playing','icon'=>'fa-play-circle');
            $this->menu_panel[5] = array('user_allow'=>'69', 'name'=>'Camera','href'=>$basename.'camera','icon'=>'fa-camera ');

        }
        elseif ($this->tokens->match && $this->optional_login())
        {

        }
        else
        {
            die('<html><head><script type="text/javascript">function rd(){window.location="/login";}</script></head><body onload="rd()"><span>Phiên làm việc đã bị thay đổi hoặc hết hạn, vui lòng <a href="/login" target="_self" title="đăng nhập hệ thống quản lý">đăng nhập</a> lại.</span></body></html>');
        }
    }

    public function camera(){
        $this->load->model('ads_content_model');
        $data['user_info'] = $this->User_model->get_user_info($this->auth_user_name);
        $data['breadcrumb'] = '<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                               <li><i class="fa fa-dashboard"></i>Xem Camera hành trình</li>';
        $data['contents'] = $this->load->view('view_camera',null, true);
        $data['menu_panel'] = $this->User_model->create_menu($this->menu_panel,$this->router->fetch_method(),$this->auth_level);
        $this->load->view('manage',$data);
    }

    public function playing(){
        $this->load->model('ads_content_model');
        $data['user_info'] = $this->User_model->get_user_info($this->auth_user_name);
        $data['breadcrumb'] = '<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                               <li><i class="fa fa-dashboard"></i>Quảng cáo đang phát</li>';
        $list['clip_playing'] = $this->ads_content_model->get_clip_playing();
        $data['contents'] = $this->load->view('clip_playing',$list, true);
        $data['menu_panel'] = $this->User_model->create_menu($this->menu_panel,$this->router->fetch_method(),$this->auth_level);
        $this->load->view('manage',$data);
    }

	public function create_sample_user()
    {
        $user_admin = $this->User_model->create_user('sysadmin','P@ssw0rd','sysadmin@gmail.com','admin');
        $user_manager = $this->User_model->create_user('manage','P@ssw0rd','saigonbus@gmail.com','manager');
        $user_customer = $this->User_model->create_user('customer','P@ssw0rd','customer@gmail.com','customer');
        print_r($user_admin);
        print_r($user_manager);
        print_r($user_customer);
    }

    public function testmail()
    {
        $test_sendmail = $this->authentication->send_email('thangvo@vtrading.com.vn','Mr Thang','Test email','Test sending mail');
        print_r($test_sendmail);
    }
    public function update_invoice($oderID)
    {
        $post_data = $this->input->post(NULL, TRUE);
        if (isset($post_data['action']) && isset($post_data['oderid']) && $post_data['oderid'] == $oderID)
        {
            $result = $this->Invoice->update_invoice($post_data);
            print_r($result);
        }
    }

    public function create_invoice()
    {
        $user_info = $this->Invoice->customer_info($this->auth_user_id);
        $userdata['user_info'] = $user_info;
        $userdata['postdata'] = $this->input->post(NULL, TRUE);
        if (isset($userdata['postdata']['action']) && $userdata['postdata']['action'] == 'add')
        {
            $new_row = $this->Invoice->add_invoice_row_by_timeframe($userdata['postdata']['oderid'],$this->auth_user_id);
            die($new_row);
        }
        if ($user_info['approved'] == 0)
        {
            $services = $this->Invoice->create_invoice($user_info['oder_id'],$this->auth_user_id);
            $services['user_info'] = $user_info;
            $data['contents'] = $this->load->view('invoice',$services, true);
        }
        else
        {
            $data['contents'] = '<span>Đơn hàng đã được chấp nhận.</span></br><span>Bạn có thể đăng tải và quản lý các Clip quảng cáo <a href="/manage/content" target="_self" title="Ðăng tải nội dung quảng cáo">tại đây</a></span>';
            //unset($this->menu_panel[3]);
        }

        $data['breadcrumb'] = '<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
                               <li><i class="fa fa-pencil-square-o active"></i> <span>Đăng ký dịch vụ</span></li>';
        $data['user_info'] = $user_info;
        if ($user_info['approved'] == 1)
        {
            $this->menu_panel[1]['user_allow'] = '169';
        }
        $data['menu_panel'] = $this->User_model->create_menu($this->menu_panel,$this->router->fetch_method(),$this->auth_level);
        $this->load->view('manage',$data);
    }

    public function invoice_print($oderID)
    {
        if ($this->Invoice->check_invoice($oderID))
        {
            $services['is_printed'] = true;
            $html = $this->load->view('general_header','',true);
            $html .= $this->load->view('invoice_print',$services,true);
            $customer_id = $this->Invoice->get_customer_id_by_oder($oderID);
            $services = $this->Invoice->create_invoice($oderID,$customer_id,1);
            $services['user_info'] = $this->Invoice->customer_info($customer_id);
            $html .= $this->load->view('invoice',$services, true);
            echo $html;
        }
        else
        {
            $data['timeout'] = 0;
            $this->load->view('script_redirect',$data);
        }
    }

    public function invoice_view($oderID)
    {
        if ($this->Invoice->check_invoice($oderID))
        {
            $html = $this->load->view('general_header','',true);
            $html .= $this->load->view('invoice_print','',true);
            $customer_id = $this->Invoice->get_customer_id_by_oder($oderID);
            $services = $this->Invoice->create_invoice($oderID,$customer_id,1);
            $services['user_info'] = $this->Invoice->customer_info($customer_id);
            $html .= $this->load->view('invoice',$services, true);
            echo $html;
        }
        else
        {
            $data['timeout'] = 0;
            $this->load->view('script_redirect',$data);
        }
    }

    public function invoice()
    {
        $data['breadcrumb'] = '<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
                               <li><i class="fa fa-list-alt active"></i> <span>Danh sách đơn hàng</span></li>';
        $data['user_info'] = $this->User_model->get_user_info($this->auth_user_name);
        if($this->auth_role == 'admin')// 1-> customer; 6 -> manager; 9 -> admin
        {
            $invoice['list_invoice'] = $this->Invoice->get_list_invoice(0);
            $post_data = $this->input->post(NULL, TRUE);
            if (isset($post_data) && is_array($post_data) && !empty($post_data))
            {
                $this->Invoice->confirm_invoice($post_data);
            }
        }
        else
        {
            $invoice['list_invoice'] = $this->Invoice->get_list_invoice($this->auth_user_id);
            //echo $this->auth_user_id;
        }

        $data['contents'] = $this->load->view('content/invoice',$invoice, true);
        $data['menu_panel'] = $this->User_model->create_menu($this->menu_panel,$this->router->fetch_method(),$this->auth_level);
        $this->load->view('manage',$data);
    }

    public function index()
	{
        $data['user_info'] = $this->User_model->get_user_info($this->auth_user_name);
        $data['breadcrumb'] = '<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                               <li><i class="fa fa-dashboard"></i>Lịch Phát</li>';
        $user_info = $this->Invoice->customer_info($this->auth_user_id);
        if ($user_info['approved'] == 1)
        {
            $this->menu_panel[1]['user_allow'] = '169';
        }
        $data['contents'] = $this->load->view('view_schedule',null, true);
        $data['menu_panel'] = $this->User_model->create_menu($this->menu_panel,$this->router->fetch_method(),$this->auth_level);
        $this->load->view('manage',$data);
	}

    public function content($clipid='',$action='')
    {
        $this->load->model('ads_content_model');
        if ($clipid !== '')
        {
            if ($action === 'search')
            {
                $list_ads = $this->ads_content_model->get_list_clips($this->auth_role,$this->auth_user_id,0,$clipid);
            }
            else
            {
                $result = $this->ads_content_model->update_clip_status($this->auth_role,$this->auth_user_id,$clipid,$action);
                print_r($result) ;
            }
        }

        if (!isset($list_ads))
        {
            $list_ads = $this->ads_content_model->get_list_clips($this->auth_role,$this->auth_user_id);
        }

        $user_info = $this->Invoice->customer_info($this->auth_user_id);
        if ($user_info['approved'] == 1)
        {
            $this->menu_panel[1]['user_allow'] = '169';
        }

        $content['list_ads'] = $this->ads_content_model->create_list_content($this->auth_role,$list_ads);
        if($this->auth_role == 'customer')
        {
            $data['contents'] = $this->load->view('content/customer_content',$content, true);
        }
        else
        {
            $data['contents'] = $this->load->view('content/contents',$content, true);
        }

        $data['user_info'] = $this->User_model->get_user_info($this->auth_user_name);
        $data['breadcrumb'] = '<li><a href="/"><i class="fa fa-home"></i> Home</a></li>
                               <li><i class="fa fa-file-video-o active"></i> <span>Nội dung quảng cáo</span></li>';

        $data['menu_panel'] = $this->User_model->create_menu($this->menu_panel,$this->router->fetch_method(),$this->auth_level);
        $this->load->view('manage',$data);
    }

    public function clipupload()
    {
        $result = false;
        if(isset($_FILES['image_video_file']))
        {
            $valid_name = preg_match('/^([a-zA-Z0-9]+[_.-])*[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/', $_FILES['image_video_file']['name']);
            if (!$valid_name)
            {
                $msg = 'Tên tập tin video vui lòng sử dụng ký tự chuẩn không dấu, không khoảng cách và chỉ bao gồm a-z, A-Z, 0-9, _ hoặc -';
            }
        }
        if (isset($_FILES['image_video_file']) && $_FILES['image_video_file']['name'] != '')
        {
            $root = realpath($_SERVER["DOCUMENT_ROOT"]); //tmp dir: echo sys_get_temp_dir();
            $target_dir = $root."/adsclips/";
            $this->load->model('video_info_model');
            $sFileName = $_FILES['image_video_file']['name'];
            $sFileType = $_FILES['image_video_file']['type'];
            $sRealSize = $_FILES['image_video_file']['size'];
            $sFileSize = $this->video_info_model->bytesToSize1024($sRealSize);
            //$default_upload_max_filesize = ini_get("upload_max_filesize");
            $file_size_max = 50 * 1024 * 1024;
            //$allowed_extensions = array("webm", "mp4", "ogg");
            //$allowed_format = array("video/webm","video/mp4","video/ogg");
            $allowed_extensions = array("mp4");
            $allowed_format = array("video/mp4");
            $file_name_temp = explode(".", $sFileName);
            $extension = end($file_name_temp);
            if (!empty($sFileName))
            {
                if ( in_array($sFileType,$allowed_format) && ($sRealSize < $file_size_max) && in_array($extension, $allowed_extensions))
                {
                    if ($_FILES['image_video_file']['error'] > 0)
                    {
                        $msg = "Có lỗi trong quá trình truyền nội dung, vui lòng thử lại sau.";
                    }
                    else
                    {
                        $this->load->helper('string');
                        do { //tạo unique name
                            $unique_name = random_string('alnum', 16);
                            $name_to_saved = $target_dir.$unique_name.'.'.$extension;
                        } while (file_exists($name_to_saved));

                        move_uploaded_file($_FILES["image_video_file"]["tmp_name"], $name_to_saved);
                        $info = $this->video_info_model->get_video_info($target_dir,$unique_name.'.'.$extension);
                        $info['format'] = $sFileType;
                        $info['size'] = $sRealSize;
                        //$this->load->model('file_slipt_merge');
                        //$slipted = $this->file_slipt_merge->split_file($name_to_saved);
                        $this->load->model('ads_content_model');
                        if (isset($_POST['clipid'])&&$_POST['clipid']!='')
                        {
                            $clipid_upload = $_POST['clipid'];
                            $update_video = $this->ads_content_model->update_ads_content($info,$sFileName,$clipid_upload);
                        }
                        else
                        {
                            $clipid_upload = 'n/a';
                            $update_video = $this->ads_content_model->update_ads_content($info,$sFileName);
                        }

                        //if ($update_video === true && $slipted !== false)
                        if ($update_video === true)
                        {
                            $result = true;
                            $msg = "Tập tin: {$sFileName} đã được chuyển đến máy chủ \n
                                  Định dạng: {$sFileType} \n
                                  Dung lượng: {$sFileSize} ({$sRealSize} byte)";
                        }
                        else
                        {
                            $msg = "{$update_video}";
                        }
                    }
                }
                else
                {
                    $max_size = $this->video_info_model->bytesToSize1024($file_size_max);
                    $msg = "Định dạng $sFileType không được phép (chỉ dùng ".implode(",", $allowed_format).") hoặc dung lượng $sRealSize lớn hơn quy định, tối đa $max_size)";
                }
            }
            else
            {
                $msg = "Có lỗi trong quá trình truyền nội dung.";
            }
        }
        $this->content();
        if ($result == false)
        {
            if (file_exists($name_to_saved))
            {
                unlink($name_to_saved);
            }
            echo '<script type="text/javascript">alert("'.$msg.'");</script>';
        }
    }

}
