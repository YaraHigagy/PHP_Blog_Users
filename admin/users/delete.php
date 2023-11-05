<?php
require '../models/User.php';

$user = new User();
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$query = $user->deleteUser($id);

if($query) {
    header("Location: list.php");
    exit;
} else {
    echo "Error with deleting, put a valid id number";
}