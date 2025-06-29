<?php
echo '<h3 class="mb-3">Available Labs</h3>';
$res=$conn->query("SELECT * FROM LAB WHERE Availability=TRUE");
echo '<div class="row">';
while($lab=$res->fetch_assoc()):?>
<div class="col-md-4 mb-3"><div class="card h-100"><div class="card-body">
<h5 class="card-title"><?=htmlspecialchars($lab['Lab_name']);?></h5>
<p>Type: <?=$lab['Lab_type'];?><br>Capacity: <?=$lab['Capacity'];?></p>
<a href="book_lab.php?lab_id=<?=$lab['Lab_id'];?>" class="btn btn-success">Book</a>
</div></div></div>
<?php endwhile; echo '</div><hr>';

$uid=$_SESSION['user_id'];
$stmt=$conn->prepare("SELECT * FROM LAB_BOOKING WHERE Instructor_id=? ORDER BY Booking_Date DESC");
$stmt->bind_param('i',$uid); $stmt->execute(); $rows=$stmt->get_result();
echo '<h3 class="mb-3">My Bookings</h3>';
if($rows->num_rows){
 echo '<table class="table table-striped"><thead><tr><th>ID</th><th>Lab</th><th>Date</th><th>Start</th><th>End</th><th>Status</th></tr></thead><tbody>';
 while($r=$rows->fetch_assoc()):
  echo '<tr><td>'.$r['Booking_id'].'</td><td>'.$r['Lab_id'].'</td><td>'.$r['Booking_Date'].'</td><td>'.$r['Start_time'].'</td><td>'.$r['End_time'].'</td><td>'.$r['Status'].'</td></tr>';
 endwhile; echo '</tbody></table>';
}else echo '<p>No bookings yet.</p>';
?>