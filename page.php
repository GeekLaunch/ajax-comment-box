<?php

if (!isset($_GET['id'])) {
    header('Location: ./');
    die();
}

require_once 'db.php';

$id = $_GET['id'];

$q = $db->prepare('SELECT title, body FROM posts WHERE id=?');
$q->execute([$id]);

$post = $q->fetch();

echo '<h1>' . $post['title'] . '</h1>';
echo '<p>' . $post['body'] . '</p>';

?>

<h2>Comments</h2>

<div id="comments">

<?php

$comments_query = $db->prepare('SELECT users.name, comments.body FROM comments JOIN users ON users.id=comments.user_id WHERE comments.post_id=?');
$comments_query->execute([$id]);

while ($comment = $comments_query->fetch()) {
    echo '<p><b>' . $comment['name'] . '</b> - ' . $comment['body'] . '</p>';
}

?>

</div>

<br>

<select id="user-select">
<?php

$users_query = $db->prepare('SELECT id, name FROM users');
$users_query->execute();

while ($user = $users_query->fetch()) {
    echo '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
}

?>
</select>
<br>
<textarea id="comment-box"></textarea>
<br>
<button id="submit-comment" type="button">Comment</button>

<script>

let postId = <?= $id ?>;

let commentBox = document.getElementById('comment-box'),
    button = document.getElementById('submit-comment'),
    userSelect = document.getElementById('user-select'),
    comments = document.getElementById('comments');

button.addEventListener('click', function () {
    if (commentBox.value.trim().length == 0) {
        alert('comment must not be empty');
        return;
    }

    commentBox.setAttribute('disabled', 'disabled');
    button.setAttribute('disabled', 'disabled');
    userSelect.setAttribute('disabled', 'disabled');

    let fd = new FormData();
    fd.append('action', 'add_comment');
    fd.append('user_id', userSelect.selectedOptions[0].value);
    fd.append('post_id', postId);
    fd.append('body', commentBox.value);

    let xhr = new XMLHttpRequest();

    xhr.open('POST', 'action.php');

    xhr.onload = function () {
        if (xhr.responseText === '1') {
            commentBox.removeAttribute('disabled');
            button.removeAttribute('disabled');
            userSelect.removeAttribute('disabled');

            let p = document.createElement('p');
            p.innerHTML = '<b>' + userSelect.selectedOptions[0].textContent + '</b> - ' + commentBox.value;

            comments.appendChild(p);

            commentBox.value = '';
        }
    };

    xhr.send(fd);
});

</script>
