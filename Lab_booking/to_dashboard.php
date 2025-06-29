<?php
/* Lab TO dashboard */
$to_id = $_SESSION['user_id'];

/* --- Top-level buttons --- */
echo '
<p>
  <a href="add_lab.php"  class="btn btn-outline-success me-2">➕ Add New Lab</a>
  <a href="log_usage.php" class="btn btn-outline-primary me-2">Log Usage</a>
  <a href="lab_usage.php" class="btn btn-outline-dark">Usage Reports</a>
</p>';

/* --- Labs managed by this TO --- */
echo '<h3 class="mb-3">Labs You Manage</h3>';

$stmt = $conn->prepare("SELECT * FROM LAB WHERE TO_id = ?");
$stmt->bind_param('i', $to_id);
$stmt->execute();
$labs = $stmt->get_result();

if ($labs->num_rows) {
    echo '<div class="row">';
    while ($lab = $labs->fetch_assoc()) : ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5><?= htmlspecialchars($lab['Lab_name']); ?></h5>
                    <p class="mb-2">Type: <?= $lab['Lab_type']; ?> &bullet; Cap: <?= $lab['Capacity']; ?></p>
                    <a href="manage_equipment.php?lab_id=<?= $lab['Lab_id']; ?>" class="btn btn-info btn-sm">Equipment</a>
                    <a href="update_schedule.php?lab_id=<?= $lab['Lab_id']; ?>" class="btn btn-warning btn-sm">Schedule</a>
                    <a href="manage_bookings.php?lab_id=<?= $lab['Lab_id']; ?>" class="btn btn-primary btn-sm">Bookings</a>
                </div>
            </div>
        </div>
    <?php endwhile;
    echo '</div>';
} else {
    echo '<p>No labs assigned.</p>';
}

/* --- Pending booking approvals --- */
echo '<hr><h3 class="mb-3">Pending Booking Requests</h3>';

$pend = $conn->prepare(
    "SELECT b.*, l.Lab_name, i.Name AS Instructor
     FROM LAB_BOOKING b
     JOIN LAB l       ON b.Lab_id = l.Lab_id
     JOIN INSTRUCTOR i ON b.Instructor_id = i.Instructor_id
     WHERE l.TO_id = ? AND b.Status = 'Pending'
     ORDER BY b.Booking_Date"
);
$pend->bind_param('i', $to_id);
$pend->execute();
$pending = $pend->get_result();

if ($pending->num_rows) {
    echo '
    <table class="table table-hover">
      <thead><tr>
        <th>ID</th><th>Lab</th><th>Instructor</th><th>Date</th>
        <th>Start</th><th>End</th><th>Action</th>
      </tr></thead><tbody>';
    while ($row = $pending->fetch_assoc()) {
        echo '<tr>
          <td>'.$row['Booking_id'].'</td>
          <td>'.$row['Lab_name'].'</td>
          <td>'.$row['Instructor'].'</td>
          <td>'.$row['Booking_Date'].'</td>
          <td>'.$row['Start_time'].'</td>
          <td>'.$row['End_time'].'</td>
          <td>
            <a class="btn btn-success btn-sm"
               href="update_booking_status.php?id='.$row['Booking_id'].'&s=Approved">Approve</a>
            <a class="btn btn-danger btn-sm"
               href="update_booking_status.php?id='.$row['Booking_id'].'&s=Rejected">Reject</a>
          </td>
        </tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>No pending requests.</p>';
}
?>
