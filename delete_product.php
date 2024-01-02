<?php
  require_once('includes/load.php');
  include_once("newheader.php");
  include_once("newNavbar.php");
?>
<?php
  $product = find_by_id('products',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('product.php');
  }
?>
<?php
  $delete_id = delete_by_id('products',(int)$product['id']);
  if($delete_id){
      $session->msg("s","Product deleted.");
      redirect('prdct.php');
  } else {
      $session->msg("d","Product deletion failed.");
      redirect('prdct.php');
  }
?>
