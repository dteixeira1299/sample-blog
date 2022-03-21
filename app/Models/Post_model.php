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

        $post_code = generate_random_hash();

        $params = [
            $id_user,
            $title,
            $content,
            $post_code,
        ];

        $db->query(
            "INSERT INTO posts(id_user, title, content, post_code, created_at, updated_at) VALUES(" .
                "?, " .
                "?, " .
                "?, " .
                "?, " .
                "NOW(), " .
                "NOW() " .
                ")",
            $params
        );

        return [
            'status' => 'SUCCESS',
            'message' => 'SUCCESS',
            'post_code' => $post_code,
        ];
    }

    // ========================================================================
    public function get_post($post_code)
    {
        $db = db_connect();

        $params = [
            $post_code
        ];

        $results = $db->query(
            "SELECT * " .
                "FROM posts " .
                "WHERE post_code = ? AND deleted_at IS NULL",
            $params
        )->getResultObject();

        //check if there are results
        if (count($results) != 1) {
            return [
                'status' => 'ERROR',
                'message' => 'Post not found.',
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
    public function get_all_post()
    {
        $db = db_connect();

        $results = $db->query(
            "SELECT * FROM posts"
        )->getResultObject();

        return $results;
    }
}
