<?php
  include_once("includes/load.php");
  include_once("layouts/newheader.php");
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('prdct.php');
}
?>
<?php
 if(isset($_POST["product"])){
    $req_fields = array('product-title','product-quantity', 'saleing-price' );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product-title']));
       $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
       $query   = "UPDATE products SET name ='{$p_name}', quantity ='{$p_qty}', sale_price ='{$p_sale}' WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('prdct.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product.php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
   }

 }
?>

<div class="prdctdiv">
  <div>
<?php echo display_msg($msg); ?>
</div>
    <h3>Edit Product</h3>
<div class="eduser">
<form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
    <label for="title">Product Title</label><br>
    <input type="text" class="form-control" name="product-title" size="50" value="<?php echo remove_junk($product['name']);?>"><br>
    <label for="qty">Product Qty</label><br>
    <input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>"><br>
    <label for="qty">Selling price</label><br>
    <input type="text" class="form-control" name="saleing-price" placeholder="Selling Price" value="<?php echo remove_junk($product['sale_price']);?>"><br>
    <button type="submit" class="btn btn-outline-success" name="product">Update</button>
</form>
</div>
</div>