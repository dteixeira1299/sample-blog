<?php

namespace App\Controllers;

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

        helper('form');

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
                'rules' => 'required|min_length[10]|max_length[20]',
                'errors' => [
                    'required' => 'O campo é de preenchimento obrigatório.',
                    'min_length' => 'O nome deve ter entre 10 e 20 caracteres.',
                    'max_length' => 'O nome deve ter entre 10 e 20 caracteres.',
                ]
            ],
            'text_email' => [
                'rules' => 'required|valid_email|min_length[10]|max_length[50]',
                'errors' => [
                    'required' => 'O campo é de preenchimento obrigatório.',
                    'valid_email' => 'O campo tem que ser um email válido.',
                    'min_length' => 'O email deve ter entre 10 e 50 caracteres.',
                    'max_length' => 'O email deve ter entre 10 e 50 caracteres.',
                ]
            ],
            'text_password' => [
                'rules' => 'required|min_length[6]|max_length[16]',
                'errors' => [
                    'required' => 'O campo é de preenchimento obrigatório.',
                    'min_length' => 'A password deve ter entre 6 e 16 caracteres.',
                    'max_length' => 'A password deve ter entre 6 e 16 caracteres.',
                ]
            ],
            'text_repeat_password' => [
                'rules' => 'required|min_length[6]|max_length[16]',
                'errors' => [
                    'required' => 'O campo é de preenchimento obrigatório.',
                    'min_length' => 'A repetição da password deve ter entre 6 e 16 caracteres.',
                    'max_length' => 'A repetição da password deve ter entre 6 e 16 caracteres.',
                ]
            ],
        ]);

        if (!$validation) {
            printData($this->validator->getErrors());
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        printData('ok');

        // get post data
        $username = $this->request->getPost('text_username');
        $email = $this->request->getPost('text_email');
        $password = $this->request->getPost('text_password');
        $repeat_password = $this->request->getPost('text_repeat_password');
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
