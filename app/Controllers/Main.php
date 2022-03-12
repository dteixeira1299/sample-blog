<?php

namespace App\Controllers;

class Main extends BaseController
{

    // ============================================================================
    public function index()
    {    

        return view('home');
        
    }

    // ============================================================================
    public function change_language($lang = null)
    {
        // // change the plataform language

        // if(empty($lang)){
        //     return view('errors/html/error_404');
        // }

        // // create the language cookie
        // set_cookie('blog_lang', $lang, (86400*365));

        // // redirect to main
        // return redirect()->to('main');
    }

}
