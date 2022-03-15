<?php

// ============================================================================
function check_session()
{
    // check if there is a user in the session
    return session()->has('user');
}

// ============================================================================
function aes_encrypt($value)
{
    return bin2hex(openssl_encrypt($value,'aes-256-cbc',AES_KEY,OPENSSL_RAW_DATA,AES_IV));
}

// ============================================================================
function aes_decrypt($value)
{
    // check if total number of chars is even
    if(strlen($value) % 2 != 0) {
        return -1;
    }

    return openssl_decrypt(hex2bin($value),'aes-256-cbc',AES_KEY,OPENSSL_RAW_DATA,AES_IV);
}

// ============================================================================
function generate_random_hash()
{
    // generate a random 32 chars hash
    return md5(sha1(uniqid()));
}


// ============================================================================
function printData($data, $die = true)
{
    //display data for debugging

    echo '<pre>';

    if(is_object($data) || is_array($data))
    {
        print_r($data);
    } else {
        echo $data;
    }

    if($die)
    {
        die(PHP_EOL . 'TERMINADO' . PHP_EOL);
    }

}