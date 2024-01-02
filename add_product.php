<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  include_once("layouts/newheader.php");
?>
<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title' ,'product-quantity', 'saleing-price' );
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
     $date    = make_date();$query  = "INSERT INTO products (";
     $query .=" name,quantity,sale_price,date";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_qty}', '{$p_sale}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('prdct.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
   }

 }

?>
<div class="prdctdiv">
    <?php echo display_msg($msg); ?>
  <h3>Add New Product</h3>
  <div class="eduser">
  <form method="post">
    <input type="text" class="form-control" placeholder="Product Title" name="product-title" size="50"><br>
    <input type="text" class="form-control" placeholder="Qty" name="product-quantity"><br>
    <input type="text" class="form-control" placeholder="Selling Price" name="saleing-price"><br>
    <button type="submit" style="border-radius:5px" name="add_product">Add Product</button>
  </form>
  </div>
</div>