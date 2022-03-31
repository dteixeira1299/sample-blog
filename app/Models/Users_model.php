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
            if (empty($account->email_verified)) {
                return [
                    'status' => 'ERROR',
                    'message' => 'Email is not verified.',
                    'data' => $account,
                ];
            }

            // check if the account is soft deleted
            if (!empty($account->deleted_at)) {
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
        if (count($results) != 1) {
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
                "AND deleted_at IS NULL",
            $params
        )->getResultObject();

        //check if there are results
        if (count($results) != 1) {
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
    public function check_login($email, $password)
    {
        //check if login is ok
        $params = [
            $email
        ];

        $db = db_connect();

        $results = $db->query(
            "SELECT " .
                "id_user, " .
                "AES_DECRYPT(username, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) username, " .
                "AES_DECRYPT(email, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) email, " .
                "psw, " .
                "profile, " .
                "user_code " .
                "FROM users " .
                "WHERE AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) = email " .
                "AND deleted_at IS NULL " .
                "AND email_verified IS NOT NULL",
            $params
        )->getResultObject();

        //check if there are results
        if (count($results) != 1) {
            return [
                'status' => 'ERROR',
                'message' => 'Invalid login. User does not exists.',
            ];
        }

        //check if the password is ok
        $tmp_user = $results[0];
        if (!password_verify($password, $tmp_user->psw)) {
            return [
                'status' => 'ERROR',
                'message' => 'Invalid login. Wrong password.',
            ];
        }

        //login is ok
        return [
            'status' => 'SUCCESS',
            'message' => 'SUCCESS',
            'data' => $tmp_user,
        ];
    }

    // ========================================================================
    public function update_last_login($id_user)
    {
        // update the last login field in users table

        $params = [
            $id_user
        ];

        $db = db_connect();

        $db->query("UPDATE users SET last_login = NOW(), updated_at = NOW() WHERE id_user = ?", $params);
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

    // ========================================================================
    public function get_user($id_user)
    {
        $db = db_connect();

        $params = [
            $id_user
        ];

        $results = $db->query(
            "SELECT " .
                "AES_DECRYPT(username, UNHEX(SHA2('" . MYSQL_AES_KEY . "',512))) username " .
                "FROM users " .
                "WHERE id_user = ?",
            $params
        )->getResultObject();

        //check if there are results
        if (count($results) != 1) {
            return [
                'status' => 'ERROR',
                'message' => 'User not found.',
            ];
        } else {
            return [
                'status' => 'SUCCESS',
                'message' => 'SUCCESS',
                'data' => $results[0],
            ];
        }

        return $results;
    }

    // ========================================================================
    public function check_valid_account_for_password_recovery($user_email)
    {

        // check if the user_email is associated with a valid account
        // if ok, returns the user_code

        $params = [
            $user_email
        ];

        $db = db_connect();

        $results = $db->query(
            "SELECT " .
                "id_user, " .
                "AES_DECRYPT(username, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) username, " .
                "AES_DECRYPT(email, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) email, " .
                "user_code, " .
                "email_verified, " .
                "deleted_at " .
                "FROM users " .
                "WHERE AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) = email ",
            $params
        )->getResultObject();

        //check if there is an account with this email
        if (count($results) == 0) {
            return [
                'status' => 'ERROR',
                'message' => 'User not found.',
            ];
        }

        // sets the row
        $user = $results[0];

        //check if account has email verified
        if (empty($user->email_verified)) {
            return [
                'status' => 'ERROR',
                'message' => 'User account with email not verified.',
            ];
        }

        //check if the user account is deleted
        if (!empty($user->deleted_at)) {
            return [
                'status' => 'ERROR',
                'message' => 'User account was deleted in ' . $user->deleted_at . '.',
            ];
        }


        // user account is ok. return user_code
        return [
            'status' => 'SUCCESS',
            'message' => 'SUCCESS',
            'data' => $user,
        ];
    }

    // ========================================================================
    public function redefine_user_password($user_code, $new_password)
    {
        // check if the user_code exists
        $params = [
            $user_code
        ];

        $db = db_connect();
        $results = $db->query(
            "SELECT id_user, psw, email_verified, deleted_at FROM users " .
                "WHERE user_code = ? ",
            $params
        )->getResultObject();

        // check if the user account exists
        if (count($results) != 1) {
            return [
                'STATUS' => 'ERROR',
                'MESSAGE' => 'User account does not exists.'
            ];
        }

        // check if the password is defined
        if (empty($results[0]->psw)) {
            return [
                'STATUS' => 'ERROR',
                'MESSAGE' => 'User password is not defined.'
            ];
        }

        // check if the email is verified
        if (empty($results[0]->email_verified)) {
            return [
                'STATUS' => 'ERROR',
                'MESSAGE' => 'User email is not verified.'
            ];
        }

        // check if the user account is deleted
        if (!empty($results[0]->deleted_at)) {
            return [
                'STATUS' => 'ERROR',
                'MESSAGE' => 'User account is not deleted since ' . $results[0]->deleted_at . '.',
            ];
        }

        // update users password
        $params = [
            password_hash($new_password, PASSWORD_DEFAULT),
            $user_code,
        ];

        $db->query(
            "UPDATE users SET " .
                "psw = ?, " .
                "updated_at = NOW() " .
                "WHERE user_code = ? ",
            $params
        );


        // return success
        return [
            'STATUS' => 'SUCCESS',
            'MESSAGE' => 'User password updated with success.',
        ];
    }
}
