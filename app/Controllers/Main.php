<?php

namespace App\Controllers;

use App\Models\Post_model;
use App\Models\Users_model;

class Main extends BaseController
{

    // ============================================================================
    public function index()
    {
        $post_model = new Post_model();
        $results = $post_model->get_all_post();

        $data = [
            'LNG' => $this->LNG,
            'posts' => $results,
        ];

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

        // redirect to the same page
        return redirect()->to(session()->_ci_previous_url)->withCookies();
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

        // creates the user session
        $this->set_user_session($results['data']);

        // redirect to the main page
        return redirect()->to('main');
    }

    // ============================================================================
    private function set_user_session($user_data)
    {
        //creates the user session
        session()->set('user', [
            'id_user' => $user_data->id_user,
            'username' => $user_data->username,
            'email' => $user_data->email,
            'profile' => $user_data->profile,
            'user_code' => $user_data->user_code,
        ]);

        // updates the last_login in the users table
        $users_model = new Users_model();
        $users_model->update_last_login($user_data->id_user);
    }


    // ============================================================================
    // LOGOUT
    // ============================================================================
    public function logout()
    {
        // check if there is a session
        if (!check_session()) {
            return redirect()->to('main');
        }

        // deletes the user from the session
        session()->remove('user');

        return redirect()->to('main');
    }

    // ============================================================================
    // RECOVERY PASSWORD
    // ============================================================================
    public function recovery_password()
    {
        // check session
        if (check_session()) {
            return redirect()->to('main');
        }

        //check if there are form validation errors
        if (session()->has('validation_errors')) {
            $data['validation_errors'] = session()->getFlashdata('validation_errors');
        }

        //display create post page
        $data['LNG'] = $this->LNG;
        return view('main/recovery_password_frm', $data);

    }

    // ============================================================================
    public function recovery_password_submit()
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
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // -------------------------
        // get post data
        $email = trim(strtolower($this->request->getPost('text_email')));

        // check if email is associated with a valid account
        $users_model = new Users_model();
        $results = $users_model->check_valid_account_for_password_recovery($email);


        // check results
        if($results['status'] == 'ERROR') {
            // log
        } else {
            // send email for password recovery
            // $this->send_email_to_recovery_password($results['data']);
        }

        //display final message
        $data['LNG'] = $this->LNG;
        $data['user_data'] = $results['data'];
        return view('main/recovery_password_final_message', $data);
        
    }

    // ============================================================================
    private function send_email_to_recovery_password($data)
    {
        //TMP TMP TMP TMP TMP TMP TMP TMP TMP TMP TMP TMP
        mail($data['email_address'], 'Confirmar o email.', 'Clique no link seguinte para verificar o seu email: ' . $data['url']);
    }

    // ============================================================================
    public function redifine_password($user_code = '')
    {

        // check session
        if (check_session()) {
            return redirect()->to('main');
        }

        // check if the user_code is not empty
        if (empty($user_code) || strlen($user_code) != 32) {
            return redirect()->to('main');
        }

        die('ok');

    }

    // ============================================================================
    // NEW POST
    // ============================================================================
    public function new_post()
    {
        // check session
        if (!check_session()) {
            return redirect()->to('main');
        }

        //check if there are form validation errors
        if (session()->has('validation_errors')) {
            $data['validation_errors'] = session()->getFlashdata('validation_errors');
        }

        //display create post page
        $data['LNG'] = $this->LNG;
        return view('main/new_post_frm', $data);
    }

    public function new_post_submit()
    {
        // check session
        if (!check_session()) {
            return redirect()->to('main');
        }

        // check if there was a post
        if ($this->request->getMethod() != 'post') {
            return redirect()->to('main');
        }

        // -------------------------
        // form validation
        $validation = $this->validate([
            'text_post_title' => [
                'label' => $this->LNG->TXT('title'),
                'rules' => 'required|min_length[10]|max_length[50]',
                'errors' => [
                    'required' => $this->LNG->TXT('error_field_required'),
                    'min_length' => $this->LNG->TXT('error_field_min_length'),
                    'max_length' => $this->LNG->TXT('error_field_max_length'),
                ]
            ]
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // -------------------------
        // get post data
        $text_post_title = $this->request->getPost('text_post_title');
        $text_post_message = $this->request->getPost('text_post_message');

        // loads model and create new post
        $post_model = new Post_model();
        $results = $post_model->create_new_post(session('user')['id_user'], $text_post_title, $text_post_message);

        // check if there was a error
        if ($results['status'] != 'SUCCESS') {

            return redirect()->to('main');
        }

        // redirect to the post page
        return redirect()->to('main/posts/' . $results['post_code']);
    }

    public function posts($post_code = '')
    {

        // check if the post_code is not empty
        if (empty($post_code)) {
            return redirect()->to('main');
        }

        // loads model and show the post
        $post_model = new Post_model();
        $results = $post_model->get_post($post_code);

        // check if there was a error
        if ($results['status'] == 'ERROR') {
            return redirect()->to('main');
        }

        // get author
        $users_model = new Users_model();
        $author = $users_model->get_user($results['data']->id_user);

        // check if there was a error
        if ($author['status'] == 'ERROR') {
            return redirect()->to('main');
        }

        //display post page
        $data = [
            'LNG' => $this->LNG,
            'id_post' => $results['data']->id_post,
            'id_user' => $author['data']->username,
            'title' => $results['data']->title,
            'content' => $results['data']->content,
            'created_at' => $results['data']->created_at,
            'updated_at' => $results['data']->updated_at,
        ];

        return view('main/post', $data);
    }


    public function session()
    {
        printData(session()->get());
    }
}
