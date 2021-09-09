<?php
function phpcodertech_delete () {
  $table_name = $wpdb->prefix . 'contactstable';
global $wpdb;

if (isset($_GET['del'])) {
  $del_id = $_GET['del'];
  $wpdb->query("DELETE FROM $table_name WHERE user_id='$del_id'");
  
  //echo "<script>location.replace('admin.php?page=crud.php');</script>";
}
}
