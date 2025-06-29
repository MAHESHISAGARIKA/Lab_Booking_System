<?php session_start();
if($_SESSION['user_type']!=='TO'){header('Location: login.php');exit;}
require_once 'config.php';
if(isset($_GET['id'],$_GET['s'])){
 $id=intval($_GET['id']); $s=sanitize($_GET['s']);
 $stmt=$conn->prepare("UPDATE LAB_BOOKING SET Status=? WHERE Booking_id=?");
 $stmt->bind_param('si',$s,$id); $stmt->execute();
}
header('Location: dashboard.php');?>