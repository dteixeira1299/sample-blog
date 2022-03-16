<?php

namespace App\Controllers;

use App\Models\Users_model;

class Main extends BaseController
{

    // ============================================================================
    public function index()
    {
        $data['LNG'] = $this->LNG;

        return view('main/home', $data);
    }

    // ============================================================================
    public function change_language($lang = null)
    {
        // change the plataform language

        if (empty($lang)) {
            return view('errors/html/error_404');
        }

        helper('cookie');

        // create the language cookie
        set_cookie('blog_lang', $lang, (86400 * 365));

        // redirect to main
        return redirect()->to('main')->withCookies();
    }




    // ============================================================================
    // NEW USER
    // ============================================================================
    public function new_user()
    {
        //check session
        if (check_session()) {
            return redirect()->to('main');
        }

        //check if there are form validation errors
        if (session()->has('validation_errors')) {
            $data['validation_errors'] = session()->getFlashdata('validation_errors');
        }

        //check if there are login error
        if (session()->has('login_error')) {
            $data['login_error'] = session()->getFlashdata('login_error');
        }

        $data['LNG'] = $this->LNG;

        return view('main/new_user_frm', $data);
    }

    // ============================================================================
    public function new_user_submit()
    {
        // check session
        if (check_session()) {
            return redirect()->to('main');
        }

        // check if there was a post
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('main');
        }

        // -------------------------
        // form validation
        $validation = $this->validate([
            'text_username' => [
                'label' => $this->LNG->TXT('name'),
                'rules' => 'required|min_length[10]|max_length[20]',
                'errors' => [
                    'required' => $this->LNG->TXT('error_field_required'),
                    'min_length' => $this->LNG->TXT('error_field_min_length'),
                    'max_length' => $this->LNG->TXT('error_field_max_length'),
                ]
            ],
            'text_email' => [
                'label' => $this->LNG->TXT('email'),
                'rules' => 'required|valid_email|min_length[10]|max_length[50]',
                'errors' => [
                    'required' => $this->LNG->TXT('error_field_required'),
                    'valid_email' => $this->LNG->TXT('error_valid_email'),
                    'min_length' => $this->LNG->TXT('error_field_min_length'),
                    'max_length' => $this->LNG->TXT('error_field_max_length'),
                ]
            ],
            'text_password' => [
                'label' => $this->LNG->TXT('password'),
                'rules' => 'required|min_length[6]|max_length[16]|regex_match[/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*\W)/]',
                'errors' => [
                    'required' => $this->LNG->TXT('error_field_required'),
                    'min_length' => $this->LNG->TXT('error_field_min_length'),
                    'max_length' => $this->LNG->TXT('error_field_max_length'),
                    'regex_match' => $this->LNG->TXT('error_password_regex'),
                ]
            ],
            'text_repeat_password' => [
                'label' => $this->LNG->TXT('new_user_repeat_password'),
                'rules' => 'required|min_length[6]|max_length[16]|regex_match[/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*\W)/]|matches[text_password]',
                'errors' => [
                    'required' => $this->LNG->TXT('error_field_required'),
                    'min_length' => $this->LNG->TXT('error_field_min_length'),
                    'max_length' => $this->LNG->TXT('error_field_max_length'),
                    'regex_match' => $this->LNG->TXT('error_password_regex'),
                    'matches' => $this->LNG->TXT('error_repeat_password_not_matching'),
                ]
            ],
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // -------------------------
        // get post data
        $username = trim($this->request->getPost('text_username'));
        $email = trim(strtolower($this->request->getPost('text_email')));
        $password = $this->request->getPost('text_password');

        // tries to create the new user account
        $users_model = new Users_model();
        $results = $users_model->create_new_user_account($username, $email, $password);

        // check result
        if ($results['status'] == 'ERROR') {
            $error_message = $results['message'];

            //check what was the error
            if ($error_message == 'Email is not verified.') {

                // the email is not verified
                return redirect()->back()->withInput()->with('login_error', [
                    'error_message' => $this->LNG->TXT('new_account_error_1'),
                    'error_number' => 'unconfirmed email',
                    'id_user' => $results['data']->id_user,
                ]);
            } else if ($error_message == 'Account is deleted.') {

                // the account is deleted
                return redirect()->back()->withInput()->with('login_error', [
                    'error_message' => $this->LNG->TXT('new_account_error_2'),
                ]);
            } else if ($error_message == 'Email already an active account.') {

                // the account is active
                return redirect()->back()->withInput()->with('login_error', [
                    'error_message' => $this->LNG->TXT('new_account_error_3'),
                ]);
            }
        }

        //send the email to the new user to confirm the email address
        $data = [
            'email_address' => $email,
            'url' => site_url('main/verify_email/' . $results['user_code']),
        ];
        $this->send_email_to_verify_account($data);

        //display final page informing the new user that an email was sent
        $this->new_user_account_final_message($email);
    }

    // ============================================================================
    private function new_user_account_final_message($email)
    {

        //display new user account final message
        $data['LNG'] = $this->LNG;
        $data['email'] = $email;
        echo view('main/new_user_final_message', $data);
    }

    // ============================================================================
    private function send_email_to_verify_account($data)
    {
        //TMP TMP TMP TMP TMP TMP TMP TMP TMP TMP TMP TMP
        mail($data['email_address'], 'Confirmar o email.', 'Clique no link seguinte para verificar o seu email: ' . $data['url']);
    }

    // ============================================================================
    public function send_email_confirmation($enc_id_user = '')
    {
        // check session
        if (check_session()) {
            return redirect()->to('main');
        }

        //check if the id user is available
        if (empty($enc_id_user) || aes_decrypt($enc_id_user) == -1 || empty(aes_decrypt($enc_id_user))) {
            return redirect()->to('main');
        }

        //checks if the id_user is valid
        $id_user = aes_decrypt($enc_id_user);

        //loads model
        $users_model = new Users_model();
        $results = $users_model->get_unconfirmed_email_user_data($id_user);

        //checks if there was a error trying to get the users data
        if ($results['status'] == 'ERROR') {
            return redirect()->to('main');
        }


        $data = [
            'email_address' => $results['data']->email,
            'url' => site_url('main/verify_email/' . $results['data']->user_code),
        ];

        //send email to virify account
        $this->send_email_to_verify_account($data);

        //display final page informing the new user that an email was sent
        $this->new_user_account_final_message($data['email_address']);
    }

    // ============================================================================
    // EMAIL VERIFICATION
    // ============================================================================
    public function verify_email($user_code = '')
    {
        // try to verify email

        // check session
        if (check_session()) {
            return redirect()->to('main');
        }

        // check if the user_code is not empty
        if (empty($user_code)) {
            return redirect()->to('main');
        }

        // check the user_code in the database
        $users_model = new Users_model();
        $result = $users_model->verify_email($user_code);
        if ($result['status'] == 'ERROR') {
            return redirect()->to('main');
        }

        //email was verified with success
        $data['LNG'] = $this->LNG;
        return view('main/new_account_email_verified', $data);
    }


    // ============================================================================
    // LOGIN
    // ============================================================================
    public function login()
    {

        // check session
        if (check_session()) {
            return redirect()->to('main');
        }

        //check if there are form validation errors
        if (session()->has('validation_errors')) {
            $data['validation_errors'] = session()->getFlashdata('validation_errors');
        }

        //check if there are login error
        if (session()->has('login_error')) {
            $data['login_error'] = session()->getFlashdata('login_error');
        }

        //display login form
        $data['LNG'] = $this->LNG;
        return view('main/login_frm', $data);
    }

    // ============================================================================
    public function login_submit()
    {

        // check session
        if (check_session()) {
            return redirect()->to('main');
        }

        // check if there was a post
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('main');
        }

        // -------------------------
        // form validation
        $validation = $this->validate([
            'text_email' => [
                'label' => $this->LNG->TXT('email'),
                'rules' => 'required|valid_email|min_length[10]|max_length[50]',
                'errors' => [
                    'required' => $this->LNG->TXT('error_field_required'),
                    'valid_email' => $this->LNG->TXT('error_valid_email'),
                    'min_length' => $this->LNG->TXT('error_field_min_length'),
                    'max_length' => $this->LNG->TXT('error_field_max_length'),
                ]
            ],
            'text_password' => [
                'label' => $this->LNG->TXT('password'),
                'rules' => 'required|min_length[6]|max_length[16]|regex_match[/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*\W)/]',
                'errors' => [
                    'required' => $this->LNG->TXT('error_field_required'),
                    'min_length' => $this->LNG->TXT('error_field_min_length'),
                    'max_length' => $this->LNG->TXT('error_field_max_length'),
                    'regex_match' => $this->LNG->TXT('error_password_regex'),
                ]
            ],
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // -------------------------
        // get post data
        $email = trim(strtolower($this->request->getPost('text_email')));
        $password = $this->request->getPost('text_password');

        // loads model and check if login is ok
        $users_model = new Users_model();
        $results = $users_model->check_login($email, $password);

        // check if there was a login error
        if ($results['status'] == 'ERROR') {

            return redirect()->back()->withInput()->with('login_error', $this->LNG->TXT('login_error_message'));
        }
        
    }
















    // ============================================================================
    public function user_testes()
    {
        $users_model = new Users_model();
        $users = $users_model->get_all_users();

        printData($users);
    }





















    // ============================================================================
    // USER
    // ============================================================================
    public function login_teste()
    {
        // TMP
        session()->set('user', [
            'id_user' => 1,
            'username' => 'Diogo Teixeira',
            'email' => 'usuario@teste.com',
        ]);
    }

    // ============================================================================
    public function logout_teste()
    {
        session()->remove('user');
        return redirect()->to('main');
    }

    // ============================================================================
    public function session()
    {
        printData(session('user'));
    }
}
