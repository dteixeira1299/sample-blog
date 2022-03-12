<?php

namespace App\Libraries;

class Language
{

    protected $selected_language = null;
    protected $translations = null;

    public function __construct()
    {
        $language = 'pt';

        helper('cookie');

        if(has_cookie('blog_lang')) {
            
            $language = get_cookie('blog_lang');
            $this->selected_language = $language;

        } else {
            
            set_cookie('blog_lang', $language, (86400*365));

        }
    }

}