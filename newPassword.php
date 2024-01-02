<?php
include_once("includes/load.php");
include_once("layouts/newheader.php");

$user = current_user();

if (isset($_POST['update'])) {
    $req_fields = array('new-password', 'old-password', 'id');
    validate_fields($req_fields);

    if (empty($errors)) {
        if (sha1($_POST['old-password']) !== current_user()['password']) {
            $session->msg('d', "Your old password does not match");
            redirect('newPassword.php', false);
        }

        $id = (int)$_POST['id'];
        $newPassword = remove_junk($db->escape($_POST['new-password']));

        // Password Complexity Requirements: Check for at least one uppercase, one lowercase, one digit, and one special character.
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $newPassword)) {
            $session->msg('d', "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
            redirect('newPassword.php', false);
        }

        // Password Length Restriction: Check that the new password is at least 8 characters long.
        if (strlen($newPassword) < 8) {
            $session->msg('d', "Password must be at least 8 characters long.");
            redirect('newPassword.php', false);
        }

        $newPasswordHashed = sha1($newPassword);
        $sql = "UPDATE users SET password = '{$newPasswordHashed}' WHERE id = '{$db->escape($id)}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            $session->logout();
            $session->msg('s', "Login with your new password.");
            redirect('index.php', false);
        } else {
            $session->msg('d', 'Sorry, failed to update password!');
            redirect('newPassword.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('newPassword.php', false);
    }
}
?>

<div class="prdctdiv">
    <h3>Change Password</h3>
    <div class="eduser">
        <form method="post" action="newPassword.php">
            <?php echo display_msg($msg); ?>
            <label for="oldPassword" class="control-label">Old Password</label>
            <input type="password" name="old-password" class="form-control" placeholder="Old Password"><br>
            <label for="newPassword">New Password</label>
            <input type="password" name="new-password" class="form-control" placeholder="New Password"><br>
            <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">
            <button type="submit" name="update">Change</button>
        </form>
    </div>
</div>

<?php include_once("layouts/newFooter.php"); ?>
