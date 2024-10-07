<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		xtoken();
		publicUserAccess();
		$this->load->model('apps/profile_model');
		$this->load->model('apps/user_model');
		$this->load->model('tables_model','tables');
		$this->load->library('form_validation');
	}

    public function index()
    {
        $this->template->load('layout/template','application/profile/user-profile');
    }
    public function x_profile_service()
    {
        _check_ajax_token();
        if ($this->input->post('token') && $this->input->post('token')=='get_profile_data') 
		{
			$result = $this->profile_model->getUsersProfile();
			if ($result->status) {
				$response = [
					'status'   	=> TRUE,
					'result'	=> $result->data
				];
				_response_output($response);
			} else {
				_response_output(NULL,500);
			}
		}
		elseif ($this->input->post('token') && $this->input->post('token')=='save_profile')
        {
            $dl = $this->input->post('datalog');
			parse_str($dl,$data);

            $user_uid = currentDataUserSession('uid');
            if (!$user_uid) {
				_response_output(NULL,500);
				return;
			}
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('fullname', 'Nama lengkap', 
				'required|trim',
				array('required'=>'%s harus diisi.'));
            $this->form_validation->set_rules('phone', 'Nomor telepon', 
				'required|trim|regex_match[/^[0-9]{11,13}$/]',
				array(
					'required'		=> '%s harus diisi.',
					'regex_match' 	=> '%s tidak valid.'
				));
            $this->form_validation->set_error_delimiters('<div class="invalid-tooltip">','</div>');
            if ($this->form_validation->run() == false) {
                
                $response = [
                    'status'   => FALSE,
                    'error_type' => 'error-validation',
                    'message'  => array()
                ];
                foreach ($data as $key => $value) {
                    $response['message'][$key] = form_error($key);
                }
                _response_output($response);
            } else {
                $rows = [
                    'fullname'      => $data['fullname'],
                    'phone'         => $data['phone'],
                    'address'  		=> $data['address']
                ];
				$params = ['user_id'	=> $user_uid ];

                $result = $this->tables->updateRowData($rows,$params,$this->tables->app_users_profile);
                if ($result) {
                    $response = [
                        'status'    => TRUE,
                        'message'   => [
							'title' => 'Update info',
							'body' 	=> 'Profile diperbahrui'
						]
                    ];
                    _response_output($response);
					
				} else {
					_response_output(NULL,500);
				}
            }
        }
		elseif ($this->input->post('token') && $this->input->post('token')=='update_email')
        {
            $dl = $this->input->post('datalog');
			parse_str($dl,$data);

            $user_uid = currentDataUserSession('uid');
            if (!$user_uid) {
				_response_output(NULL,500);
				return;
			}
            $this->form_validation->set_data($data);
			$this->form_validation->set_rules('email', 'Email', 
                [['validate_user_email',
					function($str) use ($user_uid) {
						return $this->profile_model->_callback_check_email($str,$user_uid);
					}
				]]
            );
            $this->form_validation->set_error_delimiters('<div class="invalid-tooltip">','</div>');
            if ($this->form_validation->run() == false) {
                
                $response = [
                    'status'   => FALSE,
                    'error_type' => 'error-validation',
                    'message'  => array()
                ];
                foreach ($data as $key => $value) {
                    $response['message'][$key] = form_error($key);
                }
                _response_output($response);
            } else {
                $rows = ['email' => $data['email']];
				$params = ['uid' => $user_uid ];

                $result = $this->tables->updateRowData($rows,$params,$this->tables->app_users);
                if ($result) {
                    $response = [
                        'status'    => TRUE,
                        'message'   => [
							'title' => 'Update info',
							'body' 	=> 'Email diperbahrui'
						]
                    ];
                    _response_output($response);
				} else {
					_response_output(NULL,500);
				}
            }
        }
		elseif ($this->input->post('token') && $this->input->post('token')=='change_password')
        {
            $dt = $this->input->post('datalog');
			parse_str($dt,$data);

            $user_uid = currentDataUserSession('uid');
            if (!$user_uid) {
				_response_output(NULL,500);
				return;
			}

			$this->form_validation->set_data($data);
			$this->form_validation->set_rules('current_password', 'password saat ini',
				array(
					'required',
					array(
						'current_password_callable',
						function( $str ) use ( $user_uid ){
							return $this->user_model->checkPassword($str, $user_uid); 
						}
					)
				));
			$this->form_validation->set_rules('new_password', 'password baru', 
				'required|min_length[6]|trim|alpha_numeric',
				array(
					'required'	=> 'mohon mengisi %s.',
					'min_length' => 'minimal 6 karakter',
					'alpha_numeric'	=> '%s hanya boleh berisi angka dan huruf.'
				));
			$this->form_validation->set_rules('confirm_password', 'Konfirmasi password baru', 
				'required|min_length[6]|trim|matches[new_password]',
				array(
					'required'	=> 'silahkan %s.',
					'matches' 	=> '%s tidak cocok'
				));
            $this->form_validation->set_error_delimiters('<div class="invalid-tooltip">','</div>');
			if ($this->form_validation->run() == false) {
				$response = [
					'status'   => FALSE,
					'error_type' => 'error-validation',
					'message'  => array()
				];
				foreach ($data as $key => $value) {
					$response['message'][$key] = form_error($key);
				}
				_response_output($response);
			} else {
				$password = password_hash($data['new_password'], PASSWORD_DEFAULT);
				$datas = ['password' => $password ];
				$params = ['uid' => $user_uid];
				$result = $this->tables->updateRowData($datas,$params,$this->tables->app_users);
				if ($result) {
					$response = [
						'status'   => TRUE,
						'message'  => [
							'title' => 'Update info',
							'body' => 'Berhasil mengganti password'
						]
					];
					_response_output($response);
				} else {
					_response_output(NULL,500);
				}
			}
        }
        elseif ($this->input->post('token') && $this->input->post('token')=='save_layout_settings')
        {
			$dt = $this->input->post('datalog');
			parse_str($dt,$data);

            $user_uid = currentDataUserSession('uid');
            if (!$user_uid) {
				_response_output(NULL,500);
				return;
			}

			$datas = [
				'theme_mode' 	=> $data["layout-mode"], 
				'theme_layout' 	=> $data["layout"], 
				'sidebar' 		=> $data["sidebar-color"], 
				'topbar' 		=> $data["topbar-color"]
            ];
			$params = ['user_id' => $user_uid];
			$result = $this->tables->updateRowData($datas,$params,$this->tables->app_users_layout_settings);
			if ($result) {
				$response = [
					'status'   => TRUE,
					'message'  => [
						'title' => 'Update info',
						'body' => 'Tampilan diperbahrui'
					]
				];
				_response_output($response);
			} else {
				_response_output(NULL,500);
			}
        }
        elseif ($this->input->post('token') && $this->input->post('token')=='upload_picture')
		{

			$user_uid = currentDataUserSession('uid');
            if (!$user_uid) {
				_response_output(NULL,500);
				return;
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . parse_url(IMG_PROFILE, PHP_URL_PATH);
			if (!empty($_FILES)) {
				$date = date('YmdHis');
                $uniqid = uniqid();
                $filename = $uniqid.'-profile-'.$date;
                $config = [
                    'upload_path'       => $filePath,
                    'file_name'         => $filename,
                    'allowed_types'     => 'png|jpg',
                    'max_size'          => 5048,
                ];
				$this->load->library('upload',$config);
				if ($this->upload->do_upload("file_docs")) {
					$fileData = $this->upload->data();
                    $dataUploadName = $fileData['file_name'];

					$rows = [
						'picture' => $dataUploadName
					];
					$params = ['user_id' => $user_uid];
					$result = $this->tables->updateRowData($rows,$params,$this->tables->app_users_profile);
					if ($result) {
						$response = [
							'status'    => TRUE,
							'message'   => [
								'title' => 'Update info',
								'body' 	=> 'Foto berhasil diperbahrui'
							]
						];
						_response_output($response);
						
					} else {
						_response_output(NULL,500);
					}
				} else {
					$response = [
                        'message'   => [
                            'title' => 'Gagal',
                            'body'  => this->upload->display_errors()
                        ]
                    ];
                    _response_output($response,500);
                    return;
				}
			} else {
				_response_output(NULL,500);
				return;
			}
		}
    }
}

// $this->form_validation->set_rules('email', 'Email', array(
// 	'required',
// 	'valid_email',
// 	array(
// 		'check_email',
// 		function($str) use ($user_uid) {
// 			return $this->Profile_model->checkEmail($str,$user_uid);
// 		}
// 	)
// ));

/* End of file: Profile.php */
