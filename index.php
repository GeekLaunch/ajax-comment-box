<?php

require_once './db.php';

?>

<h1>Welcome!</h1>

<?php

$q = $db->prepare('SELECT id, title FROM posts');

$q->execute();

while ($post = $q->fetch()) {
    echo '<a href="page.php?id=' . $post['id'] . '">' . $post['title'] . '</a><br>';
}
