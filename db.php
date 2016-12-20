<?php

$db = new PDO('sqlite:database.sqlite', '', '', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
));
