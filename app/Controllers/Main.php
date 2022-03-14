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
        if(session()->has('validation_errors')){
            $data['validation_errors'] = session()->getFlashdata('validation_errors');
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
        $username = $this->request->getPost('text_username');
        $email = $this->request->getPost('text_email');
        $password = $this->request->getPost('text_password');

        // tries to create the new user account
        $users_model = new Users_model();
        $results = $users_model->create_new_user_account($username, $email, $password);

        // check result
        if($results['status'] == 'ERROR'){
            die('ups');
        } else {
            printData($results);
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
