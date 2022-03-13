<?php

// ============================================================================
function check_session()
{
    // check if there is a user in the session
    return session()->has('user');
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