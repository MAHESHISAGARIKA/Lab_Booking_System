<?php
session_start();
require_once 'config.php';

if ($_SESSION['user_type'] !== 'TO') {
    header('Location: login.php');
    exit;
}

$title = "Add New Lab";
include 'header.php';

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = sanitize($_POST['lab_name']);
    $type       = sanitize($_POST['lab_type']);
    $capacity   = intval($_POST['capacity']);
    $available  = isset($_POST['availability']) ? 1 : 0;
    $to_id      = $_SESSION['user_id'];               // this TO becomes the manager

    if (!$name || !$type || !$capacity) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO LAB (Lab_name, Lab_type, Capacity, Availability, TO_id)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssiii', $name, $type, $capacity, $available, $to_id);
        if ($stmt->execute()) {
            $success = 'Lab added successfully!';
        } else {
            $error = 'Insert failed: ' . $conn->error;
        }
    }
}
?>

<h3 class="mb-4">Add New Lab</h3>
<?php if ($error):   ?><div class="alert alert-danger"><?= $error   ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

<form method="post" class="row g-3 mb-4">
  <div class="col-md-6">
    <label>Lab Name *</label>
    <input type="text" name="lab_name" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label>Lab Type *</label>
    <input type="text" name="lab_type" class="form-control" required>
  </div>
  <div class="col-md-2">
    <label>Capacity *</label>
    <input type="number" name="capacity" class="form-control" required>
  </div>
  <div class="col-md-2 form-check mt-4">
    <input class="form-check-input" type="checkbox" name="availability" id="avail" checked>
    <label class="form-check-label" for="avail">Available</label>
  </div>
  <div class="col-12">
    <button class="btn btn-success">Add Lab</button>
    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
  </div>
</form>

<?php include 'footer.php'; ?>
