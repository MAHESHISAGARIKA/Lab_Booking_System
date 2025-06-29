<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'TO') { header('Location: login.php'); exit; }
require_once 'config.php';

$to_id      = $_SESSION['user_id'];
$filter_lab = $_GET['lab_id']    ?? '';
$date_from  = $_GET['date_from'] ?? '';
$date_to    = $_GET['date_to']   ?? '';

/* ---------- base WHERE clause ---------- */
$types='i'; $params=[$to_id]; $where='l.TO_id=?';
if ($filter_lab) { $where.=' AND ul.Lab_id=?'; $types.='i'; $params[]=$filter_lab; }
if ($date_from){ $where.=' AND ul.Date>=?';    $types.='s'; $params[]=$date_from; }
if ($date_to)  { $where.=' AND ul.Date<=?';    $types.='s'; $params[]=$date_to; }

/* ---------- CSV export helpers ---------- */
function exportCsv($name,$head,$stmt,$types,$params){
  $stmt->bind_param($types,...$params); $stmt->execute();
  $rows=$stmt->get_result();
  header('Content-Type:text/csv');
  header("Content-Disposition:attachment; filename=\"$name\"");
  $out=fopen('php://output','w'); fputcsv($out,$head);
  while($r=$rows->fetch_assoc()) fputcsv($out,$r);
  exit;
}

/* Logs CSV */
if(isset($_GET['export'])){
  $s=$conn->prepare("
    SELECT ul.Log_id,l.Lab_name,i.Name Instructor,ul.Date,ul.CheckInTime,ul.CheckOutTime
    FROM USAGE_LOG ul
    JOIN LAB l ON ul.Lab_id=l.Lab_id
    JOIN LAB_BOOKING b ON ul.Booking_id=b.Booking_id
    JOIN INSTRUCTOR i ON b.Instructor_id=i.Instructor_id
    WHERE $where
    ORDER BY ul.Date DESC,ul.CheckInTime
  ");
  exportCsv('usage_logs.csv',
     ['Log ID','Lab','Instructor','Date','Check‑In','Check‑Out'],
     $s,$types,$params);
}

/* Equipment CSV */
if(isset($_GET['export_eq'])){
  $s=$conn->prepare("
    SELECT ue.LogEquip_id,ue.Log_id,ue.Booking_id,e.Name Equipment
    FROM USAGE_EQUIPMENT ue
    JOIN LAB_EQUIPMENT e ON ue.Equipment_id=e.Equipment_id
    JOIN USAGE_LOG ul ON ul.Log_id=ue.Log_id
    JOIN LAB l ON ul.Lab_id=l.Lab_id
    WHERE $where
    ORDER BY ue.LogEquip_id DESC
  ");
  exportCsv('equipment_usage.csv',
     ['UEID','Log ID','Booking ID','Equipment'],
     $s,$types,$params);
}

/* Lab list for dropdown */
$labStmt=$conn->prepare("SELECT Lab_id,Lab_name FROM LAB WHERE TO_id=?");
$labStmt->bind_param('i',$to_id); $labStmt->execute(); $labs=$labStmt->get_result();

/* main logs */
$log=$conn->prepare("
  SELECT ul.*,l.Lab_name,i.Name Instructor
  FROM USAGE_LOG ul
  JOIN LAB l ON ul.Lab_id=l.Lab_id
  JOIN LAB_BOOKING b ON ul.Booking_id=b.Booking_id
  JOIN INSTRUCTOR i ON b.Instructor_id=i.Instructor_id
  WHERE $where
  ORDER BY ul.Date DESC,ul.CheckInTime
");
$log->bind_param($types,...$params); $log->execute(); $logs=$log->get_result();

/* equipment rows (no extra filter needed here—they’re optional) */
$eq=$conn->query("
  SELECT ue.*,e.Name EqName
  FROM USAGE_EQUIPMENT ue
  JOIN LAB_EQUIPMENT e ON ue.Equipment_id=e.Equipment_id
  ORDER BY ue.LogEquip_id DESC
");
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Lab Usage Report</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg gradient-nav mb-4">
 <div class="container">
  <a class="navbar-brand text-white" href="dashboard.php">Lab Booking</a>
  <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="nav">
    <ul class="navbar-nav ms-auto"><li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li></ul>
  </div>
 </div>
</nav>

<div class="container pb-5">
 <a href="dashboard.php" class="btn btn-secondary mb-3">← Back</a>
 <h3 class="mb-3 text-primary">Lab Usage Report</h3>

 <!-- Filters -->
 <form method="get" class="row g-3 mb-4">
   <div class="col-md-3">
     <label class="form-label fw-semibold">Lab</label>
     <select name="lab_id" class="form-select">
       <option value="">-- All Labs --</option>
       <?php while($l=$labs->fetch_assoc()):?>
         <option value="<?=$l['Lab_id']?>" <?=$l['Lab_id']==$filter_lab?'selected':''?>><?=htmlspecialchars($l['Lab_name'])?></option>
       <?php endwhile;?>
     </select>
   </div>
   <div class="col-md-3">
     <label class="form-label fw-semibold">From</label>
     <input type="date" name="date_from" value="<?=htmlspecialchars($date_from)?>" class="form-control">
   </div>
   <div class="col-md-3">
     <label class="form-label fw-semibold">To</label>
     <input type="date" name="date_to" value="<?=htmlspecialchars($date_to)?>" class="form-control">
   </div>
   <div class="col-md-3 d-flex align-items-end">
     <!-- Only the Logs export button remains -->
     <button name="export" value="1" class="btn btn-gradient me-2">Export Logs CSV</button>
     <a href="lab_usage.php" class="btn btn-outline-secondary">Reset</a>
   </div>
 </form>

 <!-- Lab usage table -->
 <div class="card p-3 mb-5">
  <h5>Lab Usage</h5>
  <?php if($logs->num_rows):?>
  <table class="table table-bordered table-hover mb-0">
    <thead class="table-light"><tr>
      <th>#</th><th>Lab</th><th>Instructor</th><th>Date</th><th>Check‑In</th><th>Check‑Out</th>
    </tr></thead><tbody>
    <?php while($r=$logs->fetch_assoc()):?>
      <tr>
        <td><?=$r['Log_id']?></td>
        <td><?=htmlspecialchars($r['Lab_name'])?></td>
        <td><?=htmlspecialchars($r['Instructor'])?></td>
        <td><?=$r['Date']?></td>
        <td><?=$r['CheckInTime']?></td>
        <td><?=$r['CheckOutTime']?></td>
      </tr>
    <?php endwhile;?>
    </tbody>
  </table>
  <?php else:?>
   <p class="alert alert-warning mb-0">No usage logs found for the selected range.</p>
  <?php endif;?>
 </div>

 <!-- Equipment Usage Log table with its own export button -->
 <div class="card p-3">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Equipment Usage Log</h5>
    <form method="get" class="m-0">
      <!-- preserve filters -->
      <input type="hidden" name="lab_id"    value="<?=htmlspecialchars($filter_lab)?>">
      <input type="hidden" name="date_from" value="<?=htmlspecialchars($date_from)?>">
      <input type="hidden" name="date_to"   value="<?=htmlspecialchars($date_to)?>">
      <button name="export_eq" value="1" class="btn btn-sm btn-outline-primary">
        📤 Export Equipment CSV
      </button>
    </form>
  </div>

  <table class="table table-bordered table-sm">
    <thead class="table-light"><tr>
      <th>UEID</th><th>Log ID</th><th>Booking ID</th><th>Equipment</th>
    </tr></thead><tbody>
    <?php while($e=$eq->fetch_assoc()):?>
      <tr>
        <td><?=$e['LogEquip_id']?></td>
        <td><?=$e['Log_id']?></td>
        <td><?=$e['Booking_id']?></td>
        <td><?=htmlspecialchars($e['EqName'])?></td>
      </tr>
    <?php endwhile;?>
    </tbody>
  </table>
 </div>
</div>

<script>
document.querySelectorAll('select,input[type="date"]').forEach(el=>{
  el.addEventListener('change',()=>el.form.submit());
});
</script>
</body></html>
