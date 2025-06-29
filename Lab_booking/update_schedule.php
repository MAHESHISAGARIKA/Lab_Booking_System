<?php
/* --------------------------------------------------
   Access control
-------------------------------------------------- */
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'TO') {
    header('Location: login.php');
    exit;
}

/* --------------------------------------------------
   DB & parameters
-------------------------------------------------- */
require_once 'config.php';

if (!isset($_GET['lab_id'])) {
    header('Location: dashboard.php');
    exit;
}
$lab_id = intval($_GET['lab_id']);

$error = $success = '';

/* --------------------------------------------------
   Handle “Add Slot” form
-------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date  = $_POST['date']       ?? '';
    $start = $_POST['start_time'] ?? '';
    $end   = $_POST['end_time']   ?? '';
    $avail = isset($_POST['is_available']) ? 1 : 0;

    /* basic validation */
    if (!$date || !$start || !$end) {
        $error = 'Fill in all fields.';
    } elseif (strtotime($end) <= strtotime($start)) {
        $error = 'End time must be after start time.';
    } else {
        /* correct INSERT — one VALUES list, five placeholders */
        $ins = $conn->prepare("
            INSERT INTO LAB_SCHEDULE
            (Lab_id, `Date`, `Start_time`, `End_time`, `Is_available`)
            VALUES (?,?,?,?,?)
        ");
        $ins->bind_param('isssi', $lab_id, $date, $start, $end, $avail);
        try {
            $ins->execute();
            $success = 'Schedule slot added.';
        } catch (mysqli_sql_exception $e) {
            $error = 'Insert failed: ' . $e->getMessage();
        }
    }
}

/* --------------------------------------------------
   Fetch existing slots for the table
-------------------------------------------------- */
$slots = $conn->query("
    SELECT *
    FROM   LAB_SCHEDULE
    WHERE  Lab_id = $lab_id
    ORDER  BY `Date` DESC, `Start_time`
");
?>

<?php
/* --------------------------------------------------
   Page header (uses your gradient navbar)
-------------------------------------------------- */
$title = "Schedule – Lab $lab_id";
include 'header.php';
?>

<h3 class="mb-3">Schedule – Lab <?= $lab_id; ?></h3>

<?php if ($error): ?>
  <div class="alert alert-danger"><?= $error; ?></div>
<?php elseif ($success): ?>
  <div class="alert alert-success"><?= $success; ?></div>
<?php endif; ?>

<table class="table table-bordered mb-4">
  <thead>
    <tr>
      <th>ID</th><th>Date</th><th>Start</th><th>End</th><th>Available</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($s = $slots->fetch_assoc()): ?>
      <tr>
        <td><?= $s['Schedule_id']; ?></td>
        <td><?= $s['Date']; ?></td>
        <td><?= $s['Start_time']; ?></td>
        <td><?= $s['End_time']; ?></td>
        <td><?= $s['Is_available'] ? 'Yes' : 'No'; ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<h5>Add Slot</h5>
<form method="post">
  <div class="row mb-3">
    <div class="col-md-3 mb-2">
      <input type="date" name="date" class="form-control" required>
    </div>
    <div class="col-md-2 mb-2">
      <input type="time" name="start_time" class="form-control" required>
    </div>
    <div class="col-md-2 mb-2">
      <input type="time" name="end_time" class="form-control" required>
    </div>
    <div class="col-md-3 mb-2 d-flex align-items-center">
      <input type="checkbox" name="is_available" class="form-check-input me-2" checked>
      <label class="form-check-label">Available</label>
    </div>
    <div class="col-md-2 mb-2">
      <button class="btn btn-primary w-100">Add</button>
    </div>
  </div>
</form>

<a href="dashboard.php" class="btn btn-secondary">Back</a>

<?php include 'footer.php'; ?>
