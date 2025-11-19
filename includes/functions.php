<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* Function for Remove escapes special
 /* characters in a string for use in an SQL statement
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
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
/*--------------------------------------------------------------*/
/* Function for Display Session Message
   Ex echo displayt_msg($message);
/*--------------------------------------------------------------*/
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
/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function total_price($totals){
   $sum = 0;
   $sub = 0;
   foreach($totals as $total ){
     $sum += $total['total_saleing_price'];
     $sub += $total['total_buying_price'];
     $profit = $sum - $sub;
   }
   return array($sum,$profit);
}
/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date($str){
     if($str)
      return date('F j, Y, g:i:s a', strtotime($str));
     else
      return null;
  }
/*--------------------------------------------------------------*/
/* Function for  Readable Make date time
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* Function for Creting random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}

/*--------------------------------------------------------------*/
/* Function for Get product sales history for forecasting
/*--------------------------------------------------------------*/
function get_product_sales_history($product_id, $limit = 30) {
  global $db;
  $sql = "SELECT DATE(s.date) as sale_date, SUM(s.qty) as total_qty
          FROM sales s
          WHERE s.product_id = '{$db->escape($product_id)}'
          GROUP BY DATE(s.date)
          ORDER BY s.date DESC
          LIMIT {$db->escape((int)$limit)}";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for Simple Moving Average forecasting
/*--------------------------------------------------------------*/
function simple_moving_average($sales_data, $periods) {
  if (count($sales_data) < $periods) return [];
  $forecasts = [];
  for ($i = $periods - 1; $i < count($sales_data); $i++) {
    $sum = 0;
    for ($j = $i - $periods + 1; $j <= $i; $j++) {
      $sum += $sales_data[$j]['total_qty'];
    }
    $forecasts[] = $sum / $periods;
  }
  return $forecasts;
}

/*--------------------------------------------------------------*/
/* Function for Exponential Smoothing forecasting
/*--------------------------------------------------------------*/
function exponential_smoothing($sales_data, $alpha = 0.3) {
  if (empty($sales_data)) return [];
  $forecasts = [];
  $forecast = $sales_data[0]['total_qty']; // initial forecast
  foreach ($sales_data as $sale) {
    $forecast = $alpha * $sale['total_qty'] + (1 - $alpha) * $forecast;
    $forecasts[] = $forecast;
  }
  return $forecasts;
}
function join_product_table(){
  global $db;
  $sql  = "SELECT p.id, p.name, p.upc, p.value_size, p.quantity, p.units_in_case, p.unit_cost, p.case_cost,
                  p.unit_retail, p.case_retail, p.gpm, p.photo, p.date,
                  c.name AS categorie, v.name AS vendor
           FROM products p
           LEFT JOIN categories c ON c.id = p.categorie_id
           LEFT JOIN vendors v ON v.id = p.vendor_id
           ORDER BY p.id DESC";
  return find_by_sql($sql);
}

?>
