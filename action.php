<?php

if (!isset($_POST['action'])) {
    header('Location: ./');
    die();
}

require_once 'db.php';

$action = $_POST['action'];

if ($action == 'add_comment') {
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $body = $_POST['body'];
    $q = $db->prepare('INSERT INTO comments (post_id, user_id, body) VALUES (?, ?, ?)');
    if ($q->execute([$post_id, $user_id, $body])) {
        echo 1;
        die();
    }
}

echo 0;
