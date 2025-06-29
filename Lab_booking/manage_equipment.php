<?php session_start();
if($_SESSION['user_type']!=='TO'){header('Location: login.php');exit;}
require_once 'config.php'; if(!isset($_GET['lab_id'])){header('Location: dashboard.php');exit;}
$lab_id=intval($_GET['lab_id']); $error=$success='';
if(isset($_POST['action'])&&$_POST['action']=='add'){
 $name=sanitize($_POST['name']); $qty=intval($_POST['quantity']); $avail=isset($_POST['availability'])?1:0;
 $ins=$conn->prepare("INSERT INTO LAB_EQUIPMENT (Lab_id,Name,Quantity,Availability) VALUES (?,?,?,?)");
 $ins->bind_param('isii',$lab_id,$name,$qty,$avail); $ins->execute(); $success='Added';
}
if(isset($_GET['toggle'])){
 $eid=intval($_GET['toggle']); $conn->query("UPDATE LAB_EQUIPMENT SET Availability=NOT Availability WHERE Equipment_id=$eid AND Lab_id=$lab_id");
}
$items=$conn->query("SELECT * FROM LAB_EQUIPMENT WHERE Lab_id=$lab_id");
$title='Equipment'; include 'header.php';?>
<h3 class="mb-3">Equipment</h3>
<?php if($success):?><div class="alert alert-success"><?=$success;?></div><?php endif;?>
<table class="table table-bordered mb-3"><thead><tr><th>ID</th><th>Name</th><th>Qty</th><th>Avail</th><th>Action</th></tr></thead><tbody>
<?php while($i=$items->fetch_assoc()):?>
<tr><td><?=$i['Equipment_id'];?></td><td><?=$i['Name'];?></td><td><?=$i['Quantity'];?></td><td><?=$i['Availability']?'Yes':'No';?></td>
<td><a class="btn btn-sm btn-outline-secondary" href="?lab_id=<?=$lab_id;?>&toggle=<?=$i['Equipment_id'];?>">Toggle</a></td></tr>
<?php endwhile;?></tbody></table>
<h5>Add</h5><form method="post"><input type="hidden" name="action" value="add">
<div class="row mb-2"><div class="col-md-4"><input class="form-control" name="name" placeholder="Name" required></div>
<div class="col-md-3"><input class="form-control" type="number" name="quantity" placeholder="Qty" required></div>
<div class="col-md-3 mt-2"><input type="checkbox" class="form-check-input" name="availability" checked> Available</div>
<div class="col-md-2"><button class="btn btn-primary w-100">Add</button></div></div></form>
<a href="dashboard.php" class="btn btn-secondary">Back</a>
<?php include 'footer.php'; ?>
