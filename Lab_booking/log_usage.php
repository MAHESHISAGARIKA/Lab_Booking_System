<?php
session_start();
require_once 'config.php';
if ($_SESSION['user_type'] !== 'TO') { header('Location: login.php'); exit; }

$title = 'Log Lab Usage';
include 'header.php';

$today = date('Y-m-d');
$msg = ''; $ok = false;

/* ---------- handle insert ---------- */
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    $lab_id     = (int)$_POST['lab_id'];
    $checkin    = $_POST['checkin'];
    $checkout   = $_POST['checkout'];
    $eq_ids     = $_POST['equipment_id'] ?? [];           // multi‑select array

    if (strtotime($checkout) <= strtotime($checkin)) {
        $msg = '❌ Check‑out must be after check‑in.';
    } else {
        $conn->begin_transaction();
        try {
            $ins = $conn->prepare("
              INSERT INTO USAGE_LOG (Booking_id, Lab_id, Date, CheckInTime, CheckOutTime)
              VALUES (?,?,?,?,?)
            ");
            $ins->bind_param('iisss', $booking_id,$lab_id,$today,$checkin,$checkout);
            $ins->execute();
            $log_id = $conn->insert_id;

            /* insert equipment rows */
            if ($eq_ids) {
                $qe = $conn->prepare("
                  INSERT INTO USAGE_EQUIPMENT (Log_id, Booking_id, Equipment_id)
                  VALUES (?,?,?)
                ");
                foreach ($eq_ids as $eid) {
                    $qe->bind_param('iii', $log_id,$booking_id,$eid);
                    $qe->execute();
                }
            }
            $conn->commit();
            $ok = true;
            $msg = "✅ Usage log #$log_id inserted.";
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            $msg = '❌ Insert failed: '.$e->getMessage();
        }
    }
}

/* ---------- today’s approved bookings ---------- */
$bk = $conn->prepare("
  SELECT b.Booking_id,b.Lab_id,l.Lab_name,b.Course_name,b.Start_time,b.End_time
  FROM LAB_BOOKING b
  JOIN LAB l ON b.Lab_id=l.Lab_id
  WHERE b.Booking_Date=? AND b.Status='Approved'
");
$bk->bind_param('s',$today);
$bk->execute();
$bookings = $bk->get_result();
?>
<h3 class="mb-4">Log Lab Usage – <?= $today ?></h3>
<?php if($msg):?>
  <div class="alert <?= $ok?'alert-success':'alert-danger' ?>"><?= $msg ?></div>
<?php endif;?>

<table class="table table-bordered align-middle">
 <thead class="table-light">
  <tr>
   <th>BID</th><th>Lab</th><th>Course</th><th>Start</th><th>End</th>
   <th>Equipment<br><small>(multi‑select)</small></th><th>Log Usage</th>
  </tr>
 </thead><tbody>
<?php
$eqCache=[];
while($r=$bookings->fetch_assoc()):
  if(!isset($eqCache[$r['Lab_id']])){
      $q=$conn->prepare("SELECT Equipment_id,Name FROM LAB_EQUIPMENT WHERE Lab_id=? ORDER BY Name");
      $q->bind_param('i',$r['Lab_id']); $q->execute();
      $eqCache[$r['Lab_id']] = $q->get_result()->fetch_all(MYSQLI_ASSOC);
  }
?>
<tr>
  <td><?= $r['Booking_id'] ?></td>
  <td><?= htmlspecialchars($r['Lab_name']) ?></td>
  <td><?= htmlspecialchars($r['Course_name']) ?></td>
  <td><?= $r['Start_time'] ?></td>
  <td><?= $r['End_time'] ?></td>
  <td>
    <select name="equipment_id[]" class="form-select form-select-sm" multiple
            form="f<?= $r['Booking_id'] ?>" required>
      <?php foreach($eqCache[$r['Lab_id']] as $e): ?>
        <option value="<?= $e['Equipment_id'] ?>"><?= htmlspecialchars($e['Name']) ?></option>
      <?php endforeach;?>
    </select>
  </td>
  <td>
   <form method="post" id="f<?= $r['Booking_id'] ?>" class="row g-1">
     <input type="hidden" name="booking_id" value="<?= $r['Booking_id'] ?>">
     <input type="hidden" name="lab_id"     value="<?= $r['Lab_id'] ?>">
     <div class="col"><input type="time" name="checkin"  class="form-control form-control-sm" required></div>
     <div class="col"><input type="time" name="checkout" class="form-control form-control-sm" required></div>
     <div class="col"><button class="btn btn-sm btn-success">Log</button></div>
   </form>
  </td>
</tr>
<?php endwhile;?>
</tbody></table>

<a href="lab_usage.php" class="btn btn-outline-primary mt-3">View Usage Report</a>
<a href="dashboard.php"   class="btn btn-secondary mt-3">Back</a>
<?php include 'footer.php'; ?>
