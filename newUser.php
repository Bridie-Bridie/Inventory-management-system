<?php
    $page_title="Add New User";
    include_once('includes/load.php');
    include_once('layouts/newheader.php');
    $groups = find_all('user_groups');
?>
<?php
  if(isset($_POST['newUser'])){

   $req_fields = array('full-name','username','password','level' );
   validate_fields($req_fields);

   if(empty($errors)){
           $name   = remove_junk($db->escape($_POST['full-name']));
       $username   = remove_junk($db->escape($_POST['username']));
       $password   = remove_junk($db->escape($_POST['password']));
       $user_level = (int)$db->escape($_POST['level']);
               // Password complexity check
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            $session->msg('d', "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
            redirect('newUser.php', false);
        }

        // Password length check
        if (strlen($password) < 8) {
            $session->msg('d', "Password must be at least 8 characters long.");
            redirect('newUser.php', false);
        }
       $password = sha1($password);
        $query = "INSERT INTO users (";
        $query .="name,username,password,user_level,";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$username}', '{$password}', '{$user_level}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"User account has been creted! ");
          redirect('newUser.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create account!');
          redirect('newUser.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('newUser.php',false);
   }
 }
?>
<div class="prdctdiv">
  <?php echo display_msg($msg); ?>
    <h3>Add New User</h3>
    <div class="add_user">
    <form method="post" action="newUser.php">
        <label for="full-name">Name<br>
        <input type="text" name="full-name"><br>
        <label for="username">Username<br>
        <input type="text" name="username" placeholder="username"><br>
        <label for="password">Password<br>
        <input type="password" name="password"><br>
        <label for="level">User Role<br>
        <select class="form-control" name="level">
        <?php foreach ($groups as $group ):?>
                  <option value="<?php echo $group['group_level'];?>">
                    <?php echo $group['group_name'];?>
                  </option>
        <?php endforeach;?>
        </select><br>
        <button type="submit" name="newUser">Add</button>
    </form>
    </div>
</div>
<?php include_once('layouts/newFooter.php'); ?>`