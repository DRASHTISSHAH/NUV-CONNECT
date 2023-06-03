<?php

session_start();

$info = (object)[];

// Check if logged in
if (!isset($_SESSION['userid'])) {
  $info->logged_in = false;
  echo json_encode($info);
  die;
}

// Include configuration and database files
require_once("php/config.php");
require_once("php/Database.php");

$DB = new Database();

$data_type = "";
if (isset($_POST['data_type'])) {
  $data_type = $_POST['data_type'];
}

if ($data_type === "send_message") {
  $user_id = $_POST['user_id'];
  $msg = $_POST['msg'];

  $arr = [
    'from_user_id' => $_SESSION['userid'],
    'to_user_id' => $user_id,
    'msg' => $msg,
    'read_status' => 0,
    'created_at' => date("Y-m-d H:i:s")
  ];

  $query = "INSERT INTO messages (from_user_id, to_user_id, msg, read_status, created_at) VALUES (:from_user_id, :to_user_id, :msg, :read_status, :created_at)";
  $DB->write($query, $arr);

  $info->status = true;
  echo json_encode($info);
} else {
  $info->status = false;
  echo json_encode($info);
}
