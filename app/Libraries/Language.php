<?php

namespace App\Libraries;

class Language
{

    public $selected_language = null;
    protected $translations = null;
    protected $available_languages = ['pt_PT', 'en_US'];


    // ========================================================================
    public function __construct()
    {

        // define the language
        if (isset($_COOKIE['blog_lang'])) {
            $language = $_COOKIE['blog_lang'];
        } else {
            $language = 'pt_PT';
        }

        //check if the language is available
        if (!in_array($language, $this->available_languages)) {
            $language = 'pt_PT';
        }

        $this->selected_language = $language;

        //load the translations
        $this->translations = require_once(dirname(__FILE__) . '/../../languages/' . $this->selected_language . '.php');
    }

    // ========================================================================
    public function TXT($key, $values = [])
    {
        if (!key_exists($key, $this->translations)) {

            return '(...)';
        } else {
            if (empty($values)) {
                return $this->translations[$key];
            } else {
                // replace ^ by each value in values[]
                $str = str_split($this->translations[$key]);
                $str_final = '';
                $index = 0;
                foreach ($str as $char) {
                    if ($char != '^') {
                        $str_final .= $char;
                    } else {
                        $str_final .= $values[$index++];
                    }
                }
                return $str_final;
            }
        }
    }
    
}
