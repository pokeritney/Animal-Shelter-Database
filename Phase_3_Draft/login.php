<?php

require 'lib/url.php';
require 'classes/User.php';
require 'classes/Database.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new Database();
    $conn = $db->getConn();

    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {

        session_regenerate_id(true);

        $_SESSION['is_logged_in'] = true;
        $_SESSION['username'] = $_POST['username'];

        if (User::isVolunteer($conn, $_POST['username'])) {

            session_regenerate_id(true);

            $_SESSION['volunteer'] = true;

        }

        if (User::isEmployee($conn, $_POST['username'])) {

              session_regenerate_id(true);

              $_SESSION['employee'] = true;

       }
       if (User::isAdminUser($conn, $_POST['username'])) {

                session_regenerate_id(true);

                $_SESSION['adminuser'] = true;

      }
        redirect('/Phase_3/animal.php');

    } else {

        $error = "login incorrect";

    }
}

?>
<?php require 'lib/header.php'; ?>

<h2>Login</h2>

<?php if (! empty($error)) : ?>
    <p><?= $error ?></p>
<?php endif; ?>

<form method="post">

    <div class="form-group">
        <label for="username">Username</label>
        <input name="username" id="username">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <button>Log in</button>

</form>

<?php require 'lib/footer.php'; ?>
