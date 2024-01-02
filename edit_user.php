<?php
  $page_title = 'Edit User';
  require_once('includes/load.php');
?>
<?php
  $e_user = find_by_id('users',(int)$_GET['id']);
  $groups  = find_all('user_groups');
  if(!$e_user){
    $session->msg("d","Missing user id.");
    redirect('users.php');
  }
?>

<?php
  if(isset($_POST['update'])) {
    $req_fields = array('name','username','level');
    validate_fields($req_fields);
    if(empty($errors)){
             $id = (int)$e_user['id'];
           $name = remove_junk($db->escape($_POST['name']));
       $username = remove_junk($db->escape($_POST['username']));
          $level = (int)$db->escape($_POST['level']);
          $password = remove_junk($db->escape($_POST['password']));
          $h_pass   = sha1($password);
            $sql = "UPDATE users SET name ='{$name}', username ='{$username}', user_level='{$level}', password='{$h_pass}' WHERE id='{$db->escape($id)}'";
         $result = $db->query($sql);
          if($result && $db->affected_rows() === 1){
            $session->msg('s',"Acount Updated ");
            redirect('edit_user.php?id='.(int)$e_user['id'], false);
          } else {
            $session->msg('d',' Sorry failed to updated!');
            redirect('edit_user.php?id='.(int)$e_user['id'], false);
          }
    } else {
      $session->msg("d", $errors);
      redirect('edit_user.php?id='.(int)$e_user['id'],false);
    }
  }
?>
<?php include_once('layouts/newheader.php'); ?>
<div class="prdctdiv">
  <?php echo display_msg($msg); ?>
    <h3>Edit User</h3>
    <div class="eduser">
    <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id'];?>">
        <label for="name">Name</label><br>
        <input type="text" class="form-control" name="name" value="<?php echo remove_junk($e_user['name']); ?>"><br>
        <label for="username">Username</label><br>
        <input type="text" class="form-control" name="username" value="<?php echo remove_junk($e_user['username']); ?>"><br>
        <label for="password">Password</label><br>
        <input type="password" class="form-control" name="password"><br>
        <label for="level">User Role</label><br>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group ):?>
                   <option <?php if($group['group_level'] === $e_user['user_level']) echo 'selected="selected"';?> value="<?php echo $group['group_level'];?>"><?php echo $group['group_name'];?></option>
                <?php endforeach;?>
                </select><br>
        <button type="submit" name="update">Edit</button>
    </form>
    </div>
</div>
<?php include_once('layouts/newFooter.php'); ?>    
