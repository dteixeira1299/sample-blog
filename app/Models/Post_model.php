<?php

namespace App\Models;

use CodeIgniter\Model;

// ============================================================================
class Post_model extends Model
{
    // ========================================================================
    public function create_new_post($id_user, $title, $content)
    {

        // create new post

        $db = db_connect();

        $params = [
            $id_user,
            $title,
            $content,
        ];

        $db->query(
            "INSERT INTO posts(id_user, title, content, created_at, updated_at) VALUES(" .
            "?, " .
            "?, " .
            "?, " .
            "NOW(), " .
            "NOW(), " .
            ")",
            $params
        );

        return [
            'status' => 'SUCCESS',
            'message' => 'SUCCESS',
        ];
    }

}
