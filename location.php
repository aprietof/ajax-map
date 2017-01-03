<?php
  // You can simulate a slow server with sleep
  // sleep(2);

  session_start();

  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }

  // CHECK IF IS AN AJAX REQUEST
  if(!is_ajax_request()) { exit; }

  // EXTRACT ZIPCODE
  $zipcode = isset($_POST["zipcode"]) ? $_POST["zipcode"] : "usa" ;

  $_SESSION['zipcode'] = $zipcode;

  // SET RESPONSE
  echo $zipcode;
?>
