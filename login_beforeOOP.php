<?php
//Storing the signed-in user data
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Connect to DB
    $conn = mysqli_connect("localhost", "root", "", "blog");
    if(! $conn) {
        echo mysqli_connect_error;
        exit;
    }

    //Escape any special characters to avoid SQL Injection
    $email = mysqli_escape_string($conn, $_POST['email']);
    $password = sha1($_POST['password']);

    //Select
    $query = "SELECT * FROM `users` WHERE `email` = '".$email."' AND `password` = '".$password."' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        header("Location: admin/users/list.php");
        exit;
    } else {
        $error = 'Invalid email or password';
    }

    //Close the connection
    mysqli_free_result($result);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <title>Admin :: Add User</title>
    </head>
    <body>
        <form class="m-4" method="post">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email"
                        value = "<?= (isset($_POST['email'])) ? $_POST['email'] : '' ?>">
                <!-- <small><?php //if(in_array("email", $error_fields)) echo "* Please enter a valid email"; ?></small> -->
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                <!-- <small><?php //if(in_array("password", $error_fields)) echo "* Please enter a password not less than 6 characters"; ?></small> -->
            </div>
            <br>
            <button class="btn btn-primary mt-3" type="submit" name="submit">Login</button>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>