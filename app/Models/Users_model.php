<?php

namespace App\Models;

use CodeIgniter\Model;

// ============================================================================
class Users_model extends Model
{
    // ========================================================================
    public function create_new_user_account($username, $email, $password)
    {
        // create new user account 

        $db = db_connect();

        // ----------------------------------------------------------
        // check if the user already exists
        $params = [
            $email
        ];

        $results = $db->query(
            "SELECT * " .
            "FROM users " .
            "WHERE AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) = email",
            $params
        )->getResultObject();

        if (count($results) != 0) {

            //check what is the account status

            $account = $results[0];

            // check if the account has an unconfirmed email
            if(empty($account->email_verified)) {
                return [
                    'status' => 'ERROR',
                    'message' => 'Email is not verified.',
                    'data' => $account,
                ];
            }

            // check if the account is soft deleted
            if(!empty($account->deleted_at)){
                return [
                    'status' => 'ERROR',
                    'message' => 'Account is deleted.',
                    'data' => $account,
                ];
            }

            return [
                'status' => 'ERROR',
                'message' => 'Email already an active account.',
                'data' => $account,
            ];
        }

        // ----------------------------------------------------------
        //add the new user account to the database
        $user_code = generate_random_hash();
        $params = [
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $user_code
        ];

        $db->query(
            "INSERT INTO users(username, email, psw, user_code, receive_newsletter, created_at, updated_at) VALUES(" .
            "AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))), " .
            "AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))), " .
            "?, " .
            "?, " .
            "NOW(), " .
            "NOW(), " .
            "NOW()" .
            ")",
            $params
        );

        return [
            'status' => 'SUCCESS',
            'message' => 'SUCCESS',
            'user_code' => $user_code,
        ];
    }

    // ========================================================================
    public function verify_email($user_code)
    {
        //place a datetime in the users email_verified, if the user exists
        $params = [
            $user_code
        ];

        //first check if there is a username with the specified user_code
        $db = db_connect();
        $results = $db->query("SELECT id_user FROM users WHERE user_code = ?", $params)->getResultObject();
        if(count($results)!=1){
            return [
                'status' => 'ERROR',
                'message' => 'User code does not exists.',
            ];
        }

        //verify user email
        $db->query("UPDATE users SET email_verified = NOW(), updated_at = NOW() WHERE user_code = ? AND email_verified IS NULL", $params);

        return [
            'status' => 'SUCCESS',
            'message' => 'Email with user_code: ' . $user_code . ' was verified with success.',
        ];

    }

    // ========================================================================
    public function get_unconfirmed_email_user_data($id_user)
    {
        //get the user that does not have already validate his account (email)
        $params = [
            $id_user
        ];

        $db = db_connect();
        $results = $db->query(
            "SELECT " .
            "AES_DECRYPT(email, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) email, " .
            "user_code " .
            "FROM users " .
            "WHERE 1 " .
            "AND id_user = ? " .
            "AND email_verified IS NULL " .
            "AND deleted_at IS NULL"
            ,$params)->getResultObject();

        //check if there are results
        if(count($results) != 1){
            return [
                'status' => 'ERROR',
                'message' => 'User account not found.',
            ];
        } else {
            return [
                'status' => 'SUCCESS',
                'message' => 'SUCCESS',
                'data' => $results[0],
            ];
        }

    }

    // ========================================================================
    public function get_all_users()
    {
        $db = db_connect();

        $results = $db->query(
            "SELECT " .
            "AES_DECRYPT(username, UNHEX(SHA2('" . MYSQL_AES_KEY . "',512))) username," .
            "AES_DECRYPT(email, UNHEX(SHA2('" . MYSQL_AES_KEY . "',512))) email," .
            "user_code " .
            "FROM users"
        )->getResultObject();

        return $results;
    }


}
