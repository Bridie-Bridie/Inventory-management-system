<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) {
     redirect('home.php', false);
    }
?>
<?php include_once('layouts/newheader.php'); ?>
<div class="login">
    <h2>Inventory  Login </h2>
    <div class="sublogin">
        <?php echo display_msg($msg); ?>
        <form method="post" action="newAuth.php">
            <label for="username">Username</label><br>
            <input type="text" class="form-control" name="username"><br>
            <label for="password">Password</label><br>
            <input type="password" class="form-control" name="password"><br>
            <button type="submit" name="submit"><span>LogIn</span></button>
            <a href="index.php" style="float:right; margin:10; text-decoration: none; color:red">Cancel</a>
            <!--a href="forgot_password.php" style="float:right; margin:10; text-decoration: none; color: blue;">Forgot Your Password?</a--->
        </form>
    </div>
</div>
<?php include_once('layouts/newfooter.php'); ?>