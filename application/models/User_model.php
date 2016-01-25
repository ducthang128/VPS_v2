<?php
/**
 * @author thangvd
 * @copyright 2015
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }
    public function create_menu($data=array(),$active,$for_user)
    {
        $html = '<ul class="sidebar-menu"><li class="header">Bảng điều khiển</li>';
        foreach ($data as $item_menu){
            if (strpos('level:'.$item_menu['user_allow'],strval($for_user)) > 0)
            {
                $href = $item_menu['href'];
                $icon = $item_menu['icon'];
                $name = $item_menu['name'];
                $status = (strpos($href,$active) > 0) ? 'class="active"' : '';
                $html .=  '<li '.$status.'><a href="'.$href.'"><i class="fa '.$icon.'"></i> <span>'.$name.'</span></a></li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }
    public function get_user_info($user){
        return (array)$this->auth_model->get_auth_data($user);
    }

    public function add_temporary_customer($info)
    {
        $where = "user_name='".$info['user_name']."' OR user_email='".$info['email']."'";
        $this->db->where($where);
        $exist = $this->db->get('users');
        if ($this->db->affected_rows() == 1)
        {
            $oder_id = $exist->row_array();
            return $oder_id['oder_id'];
        }
        else
        {
            $this->load->helper('string');
            do { //tạo id unique
                $oder_id = random_string('alnum', 16);
                $row_id_exist = $this->db->get_where('users', array('oder_id' => $oder_id), 1)->num_rows();
            } while ($row_id_exist > 0);
            $user_pass = random_string('alnum', 8);
            $create_user = $this->create_user($info['user_name'],$user_pass ,$info['email'],'customer');
            if ($create_user['result'] == true)
            {

                $data = array(
                    'first_name' => $info['first_name'],
                    'last_name' => $info['last_name'],
                    'user_company' => $info['company'],
                    'user_address' => $info['address'],
                    'user_phone' => $info['phone'],
                    'message' => $info['message'],
                    'oder_id' => $oder_id
                );
                $this->db->where('user_name', $info['user_name']);
                $this->db->where('user_email', $info['email']);
                $this->db->update('users', $data);
                $return['username'] = $info['user_name'];
                $return['password'] = $user_pass;
                $return['oder_id'] = $oder_id;
                $return['result'] = true;
            }
            else
            {
                $return['result'] = false;
            }
        $return['msg'] = $create_user['msg'];
        return $return;
        }
    }

    // -----------------------------------------------------------------------

    /**
     * Most minimal user creation. You will of course make your
     * own interface for adding users, and you may even let users
     * register and create their own accounts.
     *
     * The password used in the $user_data array needs to meet the
     * following default strength requirements:
     *   - Must be at least 8 characters long
     *   - Must have at least one digit
     *   - Must have at least one lower case letter
     *   - Must have at least one upper case letter
     *   - Must not have any space, tab, or other whitespace characters
     *   - No backslash, apostrophe or quote chars are allowed
     */
    public function create_user($user_name,$user_pass,$user_email,$user_level)
    {
        $exist_user_name = $this->get_user_info($user_name);
        if (count($exist_user_name) > 1)
        {
            $result['msg'] = 'User Name: '.$user_name.' is exist.';
            $result['result'] = false;
            return $result;
        }

        $exist_user_email = $this->get_user_info($user_email);
        if (count($exist_user_email) > 1)
        {
            $result['msg'] = 'User Email: '.$user_email.' is exist.';
            $result['result'] = false;
            return $result;
        }

        // Customize this array for your user
        if ($user_level == 'admin')
        {
            $level = '9';
        }
        elseif ($user_level == 'manager')
        {
            $level = '6';
        }
        else
        {
            $level = '1';
        }

        $user_data = array(
            'user_name'     => $user_name,
            'user_pass'     => $user_pass,
            'user_email'    => $user_email,
            'user_level'    => $level, // 9 if you want to login @ auth/index.
        );

        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );

        $validation_rules = array(
			array(
				'field' => 'user_name',
				'label' => 'user_name',
				'rules' => 'trim|required|max_length[12]'
			),
			array(
				'field' => 'user_pass',
				'label' => 'user_pass',
				'rules' => 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]',
			),
			array(
				'field' => 'user_email',
				'label' => 'user_email',
				'rules' => 'trim|required|valid_email'
			),
			array(
				'field' => 'user_level',
				'label' => 'user_level',
				'rules' => 'required|integer|in_list[1,6,9]'
			)
		);

		$this->form_validation->set_rules( $validation_rules );

		if( $this->form_validation->run() )
		{
			$user_data['user_salt']     = $this->authentication->random_salt();
			$user_data['user_pass']     = $this->authentication->hash_passwd($user_data['user_pass'], $user_data['user_salt']);
			$user_data['user_id']       = $this->_get_unused_id();
			$user_data['user_date']     = date('Y-m-d H:i:s');
			$user_data['user_modified'] = date('Y-m-d H:i:s');

            // If username is not used, it must be entered into the record as NULL
            if( empty( $user_data['user_name'] ) )
            {
                $user_data['user_name'] = NULL;
            }

			$this->db->set($user_data)
				->insert(config_item('user_table'));

			if ($this->db->affected_rows() == 1) {
				$result['msg'] = '<h1>Congratulations</h1>' . '<p>User ' . $user_data['user_name'] . ' was created.</p>';
                $result['result'] = true;

			}
		}
		else
		{
			$result['msg'] =  '<h1>User Creation Error(s)</h1>' . validation_errors();
            $result['result'] = false;
		}
        return $result;
    }

// --------------------------------------------------------------

    /**
     * Get an unused ID for user creation
     *
     * @return  int between 1200 and 4294967295
     */
    private function _get_unused_id()
    {
        // Create a random user id
        $random_unique_int = 2147483648 + mt_rand( -2147482447, 2147483647 );

        // Make sure the random user_id isn't already in use
        $query = $this->db->where('user_id', $random_unique_int)
            ->get_where(config_item('user_table'));

        if ($query->num_rows() > 0) {
            $query->free_result();

            // If the random user_id is already in use, get a new number
            return $this->_get_unused_id();
        }

        return $random_unique_int;
    }

}

?>