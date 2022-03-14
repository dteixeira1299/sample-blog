<?php

namespace App\Models;

use CodeIgniter\Model;

// ============================================================================
class Users_model extends Model
{
    // ========================================================================
    public function create_new_user_account($username, $email, $password)
    {

        $db = db_connect();

        // check if the user already exists
        $params = [
            $email
        ];

        $results = $db->query(
            "SELECT * FROM users " .
                "WHERE AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))) = email",
            $params
        )->getResultObject();

        if (count($results) != 0) {
            return [
                'status' => 'ERROR',
                'message' => 'This email is already in use.',
            ];
        }

        //add the new user account to the database
        $user_code = 'hkeq9sf3fe2samlez69k2m32ejs56g1g';
        $params = [
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $user_code
        ];

        $db->query(
            "INSERT INTO users(username, email, psw, user_code, created_at, updated_at) VALUES(" .
            "AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))), " .
            "AES_ENCRYPT(?, UNHEX(SHA2('" . MYSQL_AES_KEY . "', 512))), " .
            "?, " .
            "?, " .
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
