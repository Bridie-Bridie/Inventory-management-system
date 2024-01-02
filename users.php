<?php
  $page_title = 'All User';
  require_once('includes/load.php');
?>
<?php
 $all_users = find_all_user();
?>
<?php include_once('layouts/newheader.php'); ?>
<div class="prdctdiv"> 
  <?php echo display_msg($msg); ?>
  <h3>All Users</h3>
  <form method="post" action="add_user.php">
    <button type="submit" class="btn btn-outline-success">Add New</button>
  </form>
  <table>
  <tr>
    <th>#</th>
    <th>Name</th>
    <th>Username</th>
    <th>User Role</th>
    <th>Actions</th>
  </tr>
    <?php foreach($all_users as $a_users){ ?>
  <tr>
      <td class="text-center"><?php echo count_id(); ?></td>
      <td class="text-center"><?php echo $a_users['name']; ?></td>
      <td class="text-center"><?php echo $a_users['username']; ?></td>
          <td class="text-center"><?php echo remove_junk($a_users['group_name'])?></td>
                <td class="text-center">
                <div class="btn-group">
                <a href="edit_user.php?id=<?php echo (int)$a_users['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <a href="delete_user.php?id=<?php echo (int)$a_users['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
                </div>
          </td>  
    </tr>
      <?php } ?>
    </table>
</div>
  <?php include_once('layouts/newFooter.php'); ?>