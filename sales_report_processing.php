<?php 
    include_once("includes/load.php");
    include_once("layouts/newheader.php");
?>
<?php
if(isset($_POST['submit'])){
    $reqdates=array('start_date', 'end_date');
    if(empty($errors)){
        $start_date= remove_junk($db->escape($_POST['strtdate']));
        $end_date= remove_junk($db->escape($_POST['endate']));
        $results=   find_sale_by_dates($start_date, $end_date);
    }else{
        $session->msg("d", $errors);
      redirect('sales_report.php', false);
    }
}else{
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
}
?>
<html>
    <head>
        <title>sales_report</title>
    </head>
    <body>
        <?php
        if($results){
        ?>
        <div class="report">
        <div class="report_header"><b>
        <h1 >Sales Report</h1>
        <?php 
        if(isset($start_date)){ 
            echo $start_date;
        }?> 
        TILL DATE 
        <?php 
        if(isset($end_date)){
            echo $end_date;
        }?> </b>
        </div>
        <table >
            <tr >
                <th>Date</th>
                <th>Product Title</th>
                <th>Selling Price</th>
                <th>Total Qty</th>
                <th>TOTAL</th>
            </tr>
             <?php foreach($results as $result): ?>
           <tr>
              <td class=""><?php echo remove_junk($result['date']);?></td>
              <td class="desc">
                <h6><?php echo remove_junk(ucfirst($result['name']));?></h6>
              </td>
              <td class="text-right"><?php echo remove_junk($result['sale_price']);?></td>
              <td class="text-right"><?php echo remove_junk($result['total_sales']);?></td>
              <td class="text-right"><?php echo remove_junk($result['total_saleing_price']);?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="text-right">
               <td colspan="4" style="padding: 5"><strong>Grand Total</strong></td>
               <td><strong> Ksh.
               <?php echo number_format(total_price($results)[0], 2);?></strong>
               </td>
            </tr>
            <?php
                }else{
                $session->msg("d", "Sorry no sales has been found. ");
                redirect('sales_report.php', false);
                }
            ?>
            </div>
    </body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>

