
<?php
require_once 'config.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=sanitize($_POST['name']); $email=sanitize($_POST['email']);
  $dept=sanitize($_POST['department']); $pass=$_POST['password']; $conf=$_POST['confirm'];
  if($pass!==$conf){$msg='Passwords do not match.';}
  else{
    $stmt=$conn->prepare("INSERT INTO INSTRUCTOR (Name,Email,Department,Password) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss',$name,$email,$dept,$pass);
    if($stmt->execute()){$msg='Account created. <a href=\'instructor_login.php\'>Login</a>'; }
    else{$msg='Error: '.$stmt->error;}
  }
}
$title='Instructor Sign Up'; include 'header.php'; ?>
<div class="row justify-content-center"><div class="col-md-6 card">
<h4 class="text-center mb-3">Instructor Sign Up</h4>
<?php if($msg):?><div class="alert alert-info"><?=$msg;?></div><?php endif;?>
<form method="post">
 <input name="name" class="form-control mb-2" placeholder="Full Name" required>
 <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
 <input name="department" class="form-control mb-2" placeholder="Department" required>
 <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
 <input name="confirm" type="password" class="form-control mb-3" placeholder="Confirm Password" required>
 <button class="btn btn-gradient w-100">Sign Up</button>
</form>
</div></div>
<?php include 'footer.php'; ?>
