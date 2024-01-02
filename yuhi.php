<?php 
include_once("includes/load.php");
$count=test();
?>
<div style="border: solid black 1px;">
    <table>
        <tr>
            <?php foreach($count as $counts){?>
            <td><?php echo $counts; ?></td>
            <?php } ?>
        </tr>
    </table>
</div>
