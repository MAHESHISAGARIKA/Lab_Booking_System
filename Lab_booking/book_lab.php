<?php session_start();
if($_SESSION['user_type']!=='INSTRUCTOR'){header('Location: login.php');exit;}
require_once 'config.php';
if(!isset($_GET['lab_id'])){header('Location: dashboard.php');exit;}
$lab_id=intval($_GET['lab_id']);
$lab=$conn->query("SELECT * FROM LAB WHERE Lab_id=$lab_id AND Availability=TRUE")->fetch_assoc();
if(!$lab){die('Lab not available');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $course=sanitize($_POST['course_name']); $date=$_POST['date']; $start=$_POST['start_time']; $end=$_POST['end_time'];
 if(!$course||!$date||!$start||!$end){$error='All fields required';}
 elseif(strtotime($end)<=strtotime($start)){$error='End time must be after start';}
 else{
  $chk=$conn->prepare("SELECT 1 FROM LAB_BOOKING WHERE Lab_id=? AND Booking_Date=? AND Status IN('Pending','Approved') AND ((? BETWEEN Start_time AND End_time) OR (? BETWEEN Start_time AND End_time) OR (Start_time BETWEEN ? AND ?))");
  $chk->bind_param('isssss',$lab_id,$date,$start,$end,$start,$end); $chk->execute(); $chk->store_result();
  if($chk->num_rows){$error='Overlap';}
  else{
   $ins=$conn->prepare("INSERT INTO LAB_BOOKING (Course_name,Instructor_id,Lab_id,Booking_Date,Start_time,End_time) VALUES (?,?,?,?,?,?)");
   $ins->bind_param('siisss',$course,$_SESSION['user_id'],$lab_id,$date,$start,$end);
   if($ins->execute()){header('Location: dashboard.php');exit;} else {$error='Insert failed';}
  }
 }
}
$title='Book Lab'; include 'header.php';?>
<h3 class="mb-4">Book <?=htmlspecialchars($lab['Lab_name']);?></h3>
<?php if($error):?><div class="alert alert-danger"><?=$error;?></div><?php endif;?>
<form method="post">
<div class="row mb-3"><div class="col-md-6"><label>Course</label><input name="course_name" class="form-control" required></div>
<div class="col-md-6"><label>Date</label><input type="date" name="date" class="form-control" required></div></div>
<div class="row mb-3"><div class="col-md-6"><label>Start</label><input type="time" name="start_time" class="form-control" required></div>
<div class="col-md-6"><label>End</label><input type="time" name="end_time" class="form-control" required></div></div>
<button class="btn btn-success">Submit</button> <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include 'footer.php'; ?>
