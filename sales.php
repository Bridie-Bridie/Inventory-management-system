<?php
    include_once("includes/load.php");
    include_once("layouts/newheader.php");
    $sales=find_all_sale();
?>
<div class="prdctdiv">
    <?php echo display_msg($msg); ?>
    <h3>All Sales</h3>
  <div class="prdct_hd">
    <form method="post" action="newsale.php">
      <button type="submit" class="btn btn-outline-success">Add New</button>
    </form>
  </div>
  <table>
    <tr>
      <th>#</th>
      <th>Product_Name</th>
      <th>Qty</th>
      <th>Total</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($sales as $sale):?>
    <tr>
      <td class="text-center"><?php echo count_id();?></td>
      <td><?php echo remove_junk($sale['name']); ?></td>
      <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
      <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
      <td class="text-center"><?php echo $sale['date']; ?></td>
      <td class="text-center">
        <div class="btn-group">
          <a href="edit_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-warning btn-xs"  title="Edit" data-toggle="tooltip">
            <span class="glyphicon glyphicon-edit"></span>
          </a>
          <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
            <span class="glyphicon glyphicon-trash"></span>
          </a>
        </div>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php include_once('layouts/newfooter.php'); ?>