<?php

function TXT($key) 
{

    // sets the default language
    $lang = 'pt';

    //check if there is a language cookie
    if(has_cookie('blog_lang')){

        // set the language
        $lang = get_cookie('blog_lang');

    } else {

        // create the language cookie
        set_cookie('blog_lang', $lang, (86400*365));

    }

    // check if the language file exists
    if(!file_exists(dirname(__FILE__) . '/../../languages/' . $lang . '.php')) {
        echo view('errors/html/error_404.php');
        return;
    }

    // load language file
    $language_items = require(dirname(__FILE__) . '/../../languages/' . $lang . '.php');

    if(key_exists($key, $language_items)) {
        return $language_items[$key];
    } else {
        return '(...)';
    }

}