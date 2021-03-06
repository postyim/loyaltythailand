<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *  @Auther : Adisorn Lamsombuth
 *  @Email : postyim@gmail.com
 *  @Website : esabay.com 
 */

/**
 * Description of authentication
 *
 * @author R-D-6
 */
class Authentication extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('language'));
        $this->load->model('authentication/Authentication_model');
    }


    public function forgotpassword() {

        $data = array(
            'web_title' => TITLE
            );
        $this->template->load('template_1', 'authentication/forgotpassword', $data);
    }

    function check_login() {
        $rs = $this->Authentication_model->check_login();
        $data = array(
            'error' => array(
                'status' => $rs['status'],
                'redirect' => (isset($rs['redirect']) ? $rs['redirect'] : 0),
                'message' => (isset($rs['message']) ? $rs['message'] : 0),
                'message_info' => (isset($rs['message_info']) ? $rs['message_info'] : 0),
                'id' => (isset($rs['id']) ? $rs['id'] : 0),
                )
            );
        echo json_encode($data);
    }

    public function forgotpassword2() {
        $this->load->view('authentication/frontend/forgotpassword');
    }

    public function check_forgotpassword() {

        $rs = $this->Authentication_model->check_forgotpassword();
        $data = array(
            'error' => array(
                'status' => $rs['status'],
                'redirect' => (isset($rs['redirect']) ? $rs['redirect'] : 0),
                'message' => (isset($rs['message']) ? $rs['message'] : 0),
                'message_info' => (isset($rs['message_info']) ? $rs['message_info'] : 0),
                'id' => (isset($rs['id']) ? $rs['id'] : 0),
                )
            );
        echo json_encode($data);

    }

    public function changepassword() {
        if (!$this->Authentication_model->check_changepassword(base64url_decode($this->uri->segment(4)))) {
            $str = '<div class="alert alert-warning">URL การเปลี่ยนรหัสผ่านไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง</div>';
        } else {
            $str = NULL;
        }
        $data = array(
            'title' => 'Change Password : INSIDE IT DISTRIBUTION',
            'menu_left' => $this->load->view('templates/frontend/sidebar', NULL, TRUE),
            'error' => $str
            );
        $this->template->load('frontend/master', 'authentication/frontend/changepassword', $data);
    }

    public function register() {
        $data = array(
            'title' => 'Register : E-Office System Management 2014',
            'menu_left' => $this->load->view('templates/frontend/sidebar', NULL, TRUE),
            'province' => $this->Common_model->get_province(),
            'info' => $this->Contents_model->get_edit(8)
            );
        if ($this->input->is_ajax_request()) {
            $this->load->view('authentication/frontend/register', $data);
        } else {
            $this->template->load('frontend/master', 'authentication/frontend/register', $data);
        }
    }

    function logout() {
        $this->ion_auth->logout();
        redirect('login', 'refresh');
    }

    public function activate() {

        $data = array(
            'web_title' => TITLE
            );
        $this->template->load('template_1', 'home/guest/login', $data);
    }

    public function confirm_email() {
        $rs = $this->Authentication_model->activate();
        $data = array(
            'error' => array(
                'status' => $rs['status'],
                'redirect' => (isset($rs['redirect']) ? $rs['redirect'] : 0),
                'message' => (isset($rs['message']) ? $rs['message'] : 0),
                'message_info' => (isset($rs['message_info']) ? $rs['message_info'] : 0),
                'id' => (isset($rs['id']) ? $rs['id'] : 0),
                )
            );
        echo json_encode($data);
    }

    public function reset_password($code = NULL)
    {
        $this->lang->load('auth', 'thailand');
        $this->load->library('form_validation');
        if (!$code)
        {
            show_404();
        }

        $user = $this->forgotten_password_check($code);
        if ($user)
        {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

            if ($this->form_validation->run() == false)
            {
                // display the form
                // set the flash data error message if there is one
                $data = array();
                $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $data['new_password'] = array(
                    'name' => 'new',
                    'id'   => 'new',
                    'type' => 'password',
                    'pattern' => '^.{'.$data['min_password_length'].'}.*$',
                    );
                $data['new_password_confirm'] = array(
                    'name'    => 'new_confirm',
                    'id'      => 'new_confirm',
                    'type'    => 'password',
                    'pattern' => '^.{'.$data['min_password_length'].'}.*$',
                    );
                $data['user_id'] = array(
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                    );
                $data['csrf'] = $this->_get_csrf_nonce();
                $data['code'] = $code;

                // render
                $this->_render_page('authentication/auth/reset_password', $data);
            }
            else
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
                {

                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));

                }
                else
                {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change)
                    {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("login", 'refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('authentication/reset_password/' . $code, 'refresh');
                    }
                }
            }
        }
        else
        {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("authentication/auth/forgot_password", 'refresh');
        }
    }

    public function forgotten_password_check($code) {
        $query = $this->db->get_where('users',array('forgotten_password_code'=>$code));
        if ($query->num_rows()<=0) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        } else {
            $row = $query->row();
            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
                //Make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $row->forgotten_password_time > $expiration) {
                    //it has expired
                    $this->ion_auth->clear_forgotten_password_code($code);
                    $this->ion_auth->set_error('password_change_unsuccessful');
                    return FALSE;
                }
            }
            return $row;
        }
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

        public function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
        {

            $this->viewdata = (empty($data)) ? $this->data: $data;

            $view_html = $this->load->view($view, $this->viewdata, $returnhtml);

        if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
    }

}
