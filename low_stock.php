<?php
 include_once('includes/load.php'); 
 include_once('layouts/newheader.php');
?>
<div class='prdctdiv'>
<h3>Low Stock</h3>
<table>
      <?php $stock=stock();?>
         <tr>
            <th>Product Item</th>
            <th>Quantity</th>
        </tr>
        <tr>
            <?php foreach($stock as $stocks){ ?>
            <td class="text-center"><?php echo $stocks['name']; ?></td>
            <td class="text-center"><?php echo $stocks['quantity']; ?></td>
        </tr>
        <?php } ?>
        
        </tr>
</table>

