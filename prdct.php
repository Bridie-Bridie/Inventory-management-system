<?php
    include_once("includes/load.php");
    include_once("layouts/newheader.php");
    $products=join_product_table();
?>
<div class="prdctdiv">
       <?php echo display_msg($msg); ?>
       <h3>All Products</h3>
      <div class="prdct_hd">
        <form method="post" action="add_product.php">
          <button type="submit" class="btn btn-outline-success">Add New</button>
        </form>
      </div>
        <table>
            <tr >
                <th>#</th>
                <th>Product_Name</th>
                <th>In Stock</th>
                <th>Price</th>
                <th>Product_Added</th>
                <th>Actions</th>
            </tr></br>
            <?php 
            foreach($products as $product):
            ?>
            <tr 
              <?php if(($product['quantity'])<50)
                   echo" style=\"color:red\"";
              ?>
            >
            <td class="text-center"><?php echo count_id();?></td>
                <td> <?php echo remove_junk($product['name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($product['quantity']);?></td>
                <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>  
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php
       if($product['quantity']>50) echo "<script>alert('Low Stock')</script>"
       ?>
<?php include_once('layouts/newfooter.php'); ?>