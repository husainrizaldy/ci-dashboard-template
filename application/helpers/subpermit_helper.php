<?php

/**
 * TOKEN
 */
function xtoken() 
{
    $inc = get_instance();
    if (!$inc->session->userdata('xtoken') && empty($inc->session->userdata('xtoken'))) {
        $value = bin2hex(openssl_random_pseudo_bytes(32));
        $inc->session->set_userdata('xtoken', $value);
    }
}

function _check_ajax_token() 
{
    $CI = get_instance();
    if (!$CI->input->is_ajax_request()) {
        exit('No direct script access allowed');
    }
    if (!$CI->input->post('xtoken')) {
        throw new Exception('No token found!');
    }
    if (hash_equals($CI->input->post('xtoken'), $CI->session->userdata('xtoken')) === false) {
        throw new Exception('Token mismatch!');
    }
}

function publicUserAccess()
{
	$ci = get_instance();
    if (empty($ci->session->userdata('userSession'))) 
    {
        redirect('login');
    }
}
/** END OF TOKEN */

/**
 * RESPONSE JSON
 */
function _response_output($response = NULL, $code = 200) 
{
	$ci = get_instance();
	if ($code == 500 && $response === null) {
		$response = [
			'message' => [
				'title' => 'Peringatan!',
				'body' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
			]
		];
	}

	if ($response === null) {
		$response = [];
	}

	return $ci->output->set_status_header($code)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}
/** END OF RESPONSE JSON */

/**
 * ACCEESS MENU
 */
function main_access() 
{
    $ci = get_instance();

    if (empty($ci->session->userdata('userSession'))) 
    {
        redirect('login');
    } 
    else 
    {
        /**
         * Tables Menu :
         * 1. path          => app_menu_path
         * 2. content       => app_menu_content
         * 3. sub content   => app_menu_sub
         */
        $table_menu_path = 'app_menu_path';
        $table_menu_content = 'app_menu_content';
        $table_menu_sub = 'app_menu_sub';

        // role user from session
        $role_id = $ci->session->userdata('userSession')['user_role'];
        
        // uri segment
        $seg1 = $ci->uri->segment(1,FALSE);
        $seg2 = $ci->uri->segment(2,FALSE);

        if ($seg1 != FALSE) 
        {
            $q_path = $ci->db->select('id')->get_where($table_menu_path, ['path_key' => $seg1]);

            if ($q_path->num_rows() < 1) 
            {
                redirect('not-found');
            } 
            else 
            {
                $path = $q_path->row();
        
                if ($seg2 != FALSE) 
                {
                    $q_menu = $ci->db->select('id,status')
                                    ->from($table_menu_content)
                                    ->where('id_path',$path->id)
                                    ->where('menu_route',$seg2)
                                    ->or_where('menu_key',$seg2)
                                    ->get();
        
                    if ($q_menu->num_rows() < 1) 
                    {
                        redirect('not-found');
                    } 
                    else 
                    {
                        $menu = $q_menu->row();
                        if ($menu->status == 1) {
                            _check($role_id,$menu->id);
                        }
                        if ($menu->status == 0) {
                            redirect('not-found');
                        }
                        
                    } // end else
                } 
                else 
                {
                    $q_path_in_menu = $ci->db->select('id')->get_where($table_menu_content, ['id_path' => $path->id]);
    
                    if ($q_path_in_menu->num_rows() < 1) 
                    {
                        redirect('not-found');
                    } 
                    else 
                    {
                        $id_path_in_menu = $q_path_in_menu->row();
                        _check($role_id,$id_path_in_menu->id);
                    } // end else
    
                } // end else
            } // end else
            
        } 
        else 
        {
            redirect('dashboard');
        } // end else
    } // end else
}

function _check($id_role,$id_menu) 
{
    $ci = get_instance();
    $table_role = 'app_roles_access';
    $check = $ci->db->get_where($table_role, [
        'id_role' => $id_role, 
        'id_menu' => $id_menu
    ]);
    if ($check->num_rows() < 1) {
        redirect('not-found');
    }
}
/** END OF ACCESS MENU */

/**
 * DATA SESSION & APPS SETTINGS
 */

function currentDataUserSession($type) 
{
    $CI = get_instance();
    $userSession = $CI->session->userdata('userSession');
    $data_session = [
        'uid'      => isset($userSession['user_uid']) ? $userSession['user_uid'] : null,
        'role'     => isset($userSession['user_role']) ? $userSession['user_role'] : null,
        'code'     => isset($userSession['code_role']) ? $userSession['code_role'] : null,
        'email'    => isset($userSession['email']) ? $userSession['email'] : null,
        'picture'  => isset($userSession['user_picture']) ? $userSession['user_picture'] : 'default.png'
    ];

    if (array_key_exists($type, $data_session)) {
        return $data_session[$type];
    } else {
        return false;
    }
}

function dashboardProfile($type) 
{
    $CI = get_instance();
    $app_users = 'app_users';
    $uid = currentDataUserSession('uid');
    if (!$uid) {
        return '';
    }
    $datas = $CI->db->get_where($app_users,['uid' => $uid])->row_array();
    $data = [
        'name'          => !empty($datas['fullname']) ? $datas['fullname'] : null,
        'last_login'    => !empty($datas['last_login']) ? $datas['last_login'] : null
    ];
    if (array_key_exists($type, $data)) {
        return $data[$type];
    } else {
        return false;
    }
}

function users_template_settings() 
{
    $CI = get_instance();
    $tb_user_settings = 'app_users_layout_settings';
    $user_id = $CI->session->userdata('userSession')['user_uid'];
    if (empty($user_id)) {
        $layoutObject = new stdClass();
        $layoutObject->theme_mode   = 'light';
        $layoutObject->theme_layout = 'vertical';
        $layoutObject->sidebar      = 'light';
        $layoutObject->topbar       = 'dark';
        return $layoutObject;
    }
    $layout = $CI->db->get_where($tb_user_settings,['user_id' => $user_id])->row();
    return $layout;
}

/** END OF DATA SESSION & APPS SETTINGS */

/**
 * utility
 */

function generate_default_password() 
{
    $default_password = "123qweasd";
    return password_hash($default_password, PASSWORD_DEFAULT);
}

function convertDateToIndonesian($date) 
{
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober',11 => 'November', 12 => 'Desember'
    ];

    $tanggal = date('j', strtotime($date));
    $bulanIndex = date('n', strtotime($date));
    $tahun = date('Y', strtotime($date));

    return $tanggal . ' ' . $bulan[$bulanIndex] . ' ' . $tahun;
}

function convertSpacesToHyphensAndLowercase($inputString) {
    $inputString = trim(strtolower($inputString));
    if (strpos($inputString, ' ') !== false) {
        $inputString = str_replace(' ', '-', $inputString);
    }
    return $inputString;
}

function convertToFormattedStringWithPrefix($input) {
    $input = trim(strtolower($input));
    $input = preg_replace('/\s+/', '-', $input);
    $result = 't-' . $input;
    return $result;
}

/**
 * URL UPLOAD FILES ON DIFERENT PATH DIRECTORY
 */
function getDocRootPathByConstMedia($type) {
    $doc_constants = [
        'img_profile' => 'IMG_PROFILE',
    ];

    $ci_env = defined('ENVIRONMENT') ? ENVIRONMENT : 'development';
    if ($ci_env === 'development' || $ci_env === 'testing') {
        if (array_key_exists($type, $doc_constants)) {
            $doc_root_path = $_SERVER['DOCUMENT_ROOT'] . parse_url(constant($doc_constants[$type]), PHP_URL_PATH);
            return $doc_root_path;
        } else {
            return false;
        }
    } elseif ($ci_env === 'production') {
        if (!array_key_exists($type, $doc_constants)) {
            return false;
        }

		$current_dir_name = "apps.cms.com";
		$destination_dir_name = "cdn_media";

        $dir = $_SERVER['DOCUMENT_ROOT'];
        $media_dir = str_replace($destination_dir_name, $current_dir_name, $dir);
        $doc_root_path = $media_dir . parse_url(constant($doc_constants[$type]), PHP_URL_PATH);

        return $doc_root_path;
    } else {
        return false;
    }
}
