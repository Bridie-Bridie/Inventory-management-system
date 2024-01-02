<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "newproject";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


  function count_id(){
  static $count = 1;
  return $count++;
  }

function remove_junk($str){
    $str = nl2br($str);
    $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
    return $str;
}

function read_date($str){
    if($str)
     return date('F j, Y', strtotime($str));
    else
     return null;
}

function total_price($totals){
    $sum = 0;
    foreach($totals as $total ){
      $sum += $total['total_saleing_price'];
    }
    return array($sum);
}
 
 function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}

function display_msg($msg =''){
  $output = array();
  if(!empty($msg)) {
     foreach ($msg as $key => $value) {
        $output  = "<div class=\"alert alert-{$key}\">";
        $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
        $output .= remove_junk(first_character($value));
        $output .= "</div>";
     }
     return $output;
  } else {
    return "" ;
  }
}

function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}

function find_all_pending_users() {
    global $db;
    $query = "SELECT * FROM users WHERE approved = 0";
    return $db->query($query);
}

?>