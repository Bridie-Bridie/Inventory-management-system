<?php
  require_once('includes/load.php');
function find_by_sql($sql)
{
  global $db;
  $result= $db->query($sql);
  $result_set= $db->while_loop($result);
 return $result_set;
}

function join_product_table(){
    global $db;
    $sql= " SELECT p.id, p.name, p.quantity, p.sale_price, p.date FROM products p ORDER BY p.id ASC";
   return find_by_sql($sql);
}

function find_all_sale(){
  global $db;
  $sql= "SELECT s.id, s.qty, s.price, s.date, p.name FROM sales s LEFT JOIN products p ON s.product_id= p.id ORDER BY s.date DESC";
  return find_by_sql($sql);
}

function find_sale_by_dates($start_date, $end_date){
  global $db;
  $start_date= date("Y-m-d", strtotime($start_date));
  $end_date = date("Y-m-d", strtotime($end_date));
  $sql= "SELECT s.date, p.name, p.sale_price, COUNT(s.product_id) AS total_records, SUM(s.qty) AS total_sales, SUM(p.sale_price * s.qty) AS total_saleing_price FROM sales s LEFT JOIN products p ON s.product_id= p.id WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}' GROUP BY DATE(s.date), p.name ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}

function  monthlySales($year,$month){
  global $db;
  $sql  = "SELECT sum(s.qty) as qty, DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name, SUM(p.sale_price * s.qty) AS total_saleing_price FROM sales s LEFT JOIN products p ON s.product_id = p.id WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}' GROUP BY DATE_FORMAT( s.date,  '%m' ),s.product_id";
  return find_by_sql($sql);
}

function  dailySales($year,$month,$day){
  global $db;
  $sql  = "SELECT sum(s.qty) as qty, DATE_FORMAT(s.date, '%Y-%m-%d') AS date,p.name, SUM(p.sale_price * s.qty) AS total_saleing_price FROM sales s LEFT JOIN products p ON s.product_id = p.id WHERE DATE_FORMAT(s.date, '%Y-%m-%d' ) = '{$year}-{$month}-{$day}' GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}

function prdctEdit(){
  if(empty($errors)){
    $p_name  = remove_junk($db->escape($_POST['product-title']));
    $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
    $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
    $query   = "UPDATE products SET";
    $query  .=" name ='{$p_name}', quantity ='{$p_qty}',";
    $query  .=" sale_price ='{$p_sale}'";
    $query  .=" WHERE id ='{$product['id']}'";
    $result = $db->query($query);
            if($result && $db->affected_rows() === 1){
              $session->msg('s',"Product updated ");
              redirect('prdct.php', false);
            } else {
              $session->msg('d',' Sorry failed to update!');
              redirect('edit_product.php?id='.$product['id'], false);
            }
  } else{
    $session->msg("d", $errors);
    redirect('edit_product.php?id='.$product['id'], false);
  }
}

function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

}

function find_by_id($table,$id){
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function current_user(){
  static $current_user;
  global $db;
  if(!$current_user){
     if(isset($_SESSION['user_id'])):
         $user_id = intval($_SESSION['user_id']);
         $current_user = find_by_id('users',$user_id);
    endif;
  }
return $current_user;
}

function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM newproject LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
}

function delete_by_id($table,$id){
    global $db;
    if(tableExists($table))
     {
      $sql = "DELETE FROM ".$db->escape($table);
      $sql .= " WHERE id=". $db->escape($id);
      $sql .= " LIMIT 1";
      $db->query($sql);
      return ($db->affected_rows() === 1) ? true : false;
     }
}

function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
}
  
function find_product_by_title($product_name){
  global $db;
  $p_name = remove_junk($db->escape($product_name));
  $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
  $result = find_by_sql($sql);
  return $result;
}

function find_all_product_info_by_title($title){
 global $db;
 $sql  = "SELECT * FROM products ";
 $sql .= " WHERE name ='{$title}'";
 $sql .=" LIMIT 1";
 return find_by_sql($sql);
}

function stock(){
  global $db;
  $sql= "SELECT name, quantity FROM products WHERE quantity<50";
  $result= find_by_sql($sql);
  return $result;
}

function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }

  function find_all($table) {
    global $db;
    if(tableExists($table))
    {
      return find_by_sql("SELECT * FROM ".$db->escape($table));
    }
 }
 
 function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}

function find_recent_product_added($limit){
  global $db;
  $sql   = " SELECT p.id,p.name,p.sale_price ";
  $sql  .= "FROM products p";
  $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}

function find_higest_saleing_product($limit){
  global $db;
  $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
  $sql .= " GROUP BY s.product_id";
  $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
  return $db->query($sql);
}

function test(){
  $sql="SELECT name FROM products";
  return find_by_sql($sql);
}

 ?>