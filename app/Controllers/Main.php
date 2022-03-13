<?php

namespace App\Controllers;

class Main extends BaseController
{

    // ============================================================================
    public function index()
    {    
        $data['LNG'] = $this->LNG;

        return view('home', $data);
        
    }

    // ============================================================================
    public function change_language($lang = null)
    {
        // change the plataform language

        if(empty($lang)){
            return view('errors/html/error_404');
        }

        helper('cookie');

        // create the language cookie
        set_cookie('blog_lang', $lang, (86400*365));

        // redirect to main
        return redirect()->to('main')->withCookies();
    }




    
    // ============================================================================
    // USER
    // ============================================================================
    public function login_teste()
    {
        // TMP
        session()->set('user',[
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
