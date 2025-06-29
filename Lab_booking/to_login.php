
<?php
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_type']==='TO'){header('Location: dashboard.php');exit;}
require_once 'config.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=sanitize($_POST['email']); $pass=$_POST['password'];
  $stmt=$conn->prepare("SELECT TO_id,Password FROM TECHNICAL_OFFICER WHERE Email=?");
  $stmt->bind_param('s',$email); $stmt->execute(); $stmt->store_result();
  if($stmt->num_rows){
    $stmt->bind_result($id,$pw); $stmt->fetch();
    if($pass===$pw){
      $_SESSION['user_id']=$id; $_SESSION['user_type']='TO';
      header('Location: dashboard.php');exit;
    }
  }
  $error='Invalid credentials';
}
$title='TO Login'; include 'header.php'; ?>
<div class="row justify-content-center"><div class="col-md-5 card">
<h4 class="text-center mb-3">Technical Officer Login</h4>
<?php if($error):?><div class="alert alert-danger"><?=$error;?></div><?php endif;?>
<form method="post">
 <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
 <input name="password" type="password" class="form-control mb-3" placeholder="Password" required>
 <button class="btn btn-gradient w-100">Sign In</button>
</form>
</div></div>
<?php include 'footer.php'; ?>
