<?php
   include_once("includes/load.php");
    if (!$session->isUserLoggedIn(true)){
        redirect('login.php', false);
    }
?>
<?php
 $products_sold   = find_higest_saleing_product('5');
 $recent_products = find_recent_product_added('5');
 $recent_sales    = find_recent_sale_added('5')
?>
<?php
    include_once("layouts/newheader.php");
?>
<div class="prdctdiv">
<?php echo display_msg($msg); ?>
<?php
$qty='SELECT COUNT(id) FROM products WHERE quantity<50';
if($qty){
echo "<script type='text/javascript'>alert('Low stock restock it 
immedietly');</script>";
} 
?>

<div class="row">
   <div class="col-md-4">
     <div class="panel panel-default">
       <div class="panel-heading">
           <h3>Highest Selling Products</h3>
       </div>
       <div class="panel-body">
         <table>
          <thead>
           <tr>
             <th>Title</th>
             <th>Total Sold</th>
             <th>Total Quantity</th>
           <tr>
          </thead>
          <tbody>
            <?php foreach ($products_sold as  $product_sold): ?>
              <tr>
                <td><?php echo remove_junk(first_character($product_sold['name'])); ?></td>
                <td><?php echo (int)$product_sold['totalSold']; ?></td>
                <td><?php echo (int)$product_sold['totalQty']; ?></td>
              </tr>
            <?php endforeach; ?>
          <tbody>
         </table>
       </div>
     </div>
   </div>
   <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
            <h3>LATEST SALES</h3>
        </div>
        <div class="panel-body">
          <table>
       <thead>
         <tr>
           <th>Product Name</th>
           <th>Date</th>
           <th>Total Sale</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach ($recent_sales as $recent_sale): ?>
         <tr>
           <td>
             <?php echo remove_junk(first_character($recent_sale['name'])); ?>
           </a>
           </td>
           <td><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
           <td>Ksh.<?php echo remove_junk(first_character($recent_sale['price'])); ?></td>
        </tr>

       <?php endforeach; ?>
       </tbody>
     </table>
    </div>
   </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3>Recently Added Products</h3>
      </div>
      <div class="panel-body">

        <table>
            <tr>
                <th>Title</th>
                <th>Price</th>
            </tr>
      <?php foreach ($recent_products as  $recent_product): ?>
        <tr><td>
        <h4 class="list-group-item-heading">
                <?php echo remove_junk(first_character($recent_product['name']));?>
      </td>
      <td>
                 Ksh.<?php echo (int)$recent_product['sale_price']; ?>
                  </span>
                </h4>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
 </div>
</div>

</div>
<a href="newsale.php">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3>New sale</h3>
      </div>
    </div>
  </div>
</a> 
<a href="add_product.php">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3>New product</h3>
      </div>
    </div>
  </div>
</a>            
<?php include_once("layouts/newfooter.php"); ?>
