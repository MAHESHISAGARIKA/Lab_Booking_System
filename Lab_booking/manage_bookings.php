<?php session_start();
if($_SESSION['user_type']!=='TO'){header('Location: login.php');exit;}
require_once 'config.php'; if(!isset($_GET['lab_id'])){header('Location: dashboard.php');exit;}
$lab_id=intval($_GET['lab_id']);
$stmt=$conn->prepare("SELECT b.*,i.Name AS Instructor FROM LAB_BOOKING b JOIN INSTRUCTOR i ON b.Instructor_id=i.Instructor_id WHERE b.Lab_id=? ORDER BY Booking_Date DESC");
$stmt->bind_param('i',$lab_id); $stmt->execute(); $rows=$stmt->get_result();
$title='Bookings'; include 'header.php';?>
<h3 class="mb-3">Bookings Lab <?=$lab_id;?></h3>
<table class="table table-bordered"><thead><tr><th>ID</th><th>Instructor</th><th>Date</th><th>Start</th><th>End</th><th>Status</th></tr></thead><tbody>
<?php while($r=$rows->fetch_assoc()):?>
<tr><td><?=$r['Booking_id'];?></td><td><?=$r['Instructor'];?></td><td><?=$r['Booking_Date'];?></td><td><?=$r['Start_time'];?></td><td><?=$r['End_time'];?></td><td><?=$r['Status'];?></td></tr>
<?php endwhile;?></tbody></table>
<a href="dashboard.php" class="btn btn-secondary">Back</a>
<?php include 'footer.php'; ?>
