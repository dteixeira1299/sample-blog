<?php

namespace App\Libraries;

class Language
{

    protected $selected_language = null;
    protected $translations = null;
    protected $available_languages = ['pt', 'en'];


    // ========================================================================
    public function __construct()
    {

        // define the language
        if(isset($_COOKIE['blog_lang'])) {
            $language = $_COOKIE['blog_lang'];
        } else {
            $language = 'pt';
        }

        //check if the language is available
        if(!in_array($language, $this->available_languages)){
            $language = 'pt';
        }

        $this->selected_language = $language;

        //load the translations
        $this->translations = require_once(dirname(__FILE__) . '/../../languages/' . $this->selected_language . '.php');

    }

    // ========================================================================
    public function TXT($key)
    {
        if(!key_exists($key, $this->translations)){
            return '(...)';
        } else {
            return $this->translations[$key];
        }
    }

}