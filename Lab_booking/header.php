<?php
if(session_status()===PHP_SESSION_NONE){session_start();}
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=isset($title)?$title:'Lab Booking';?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg gradient-nav mb-4">
 <div class="container">
  <a class="navbar-brand text-white" href="dashboard.php">Lab Booking</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="nav">
    <ul class="navbar-nav ms-auto">
      <?php if(isset($_SESSION['user_type'])): ?>
        <li class="nav-item"><span class="nav-link text-white small"><?=$_SESSION['user_type'];?></span></li>
        <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
      <?php endif;?>
    </ul>
  </div>
 </div>
</nav>
<div class="container pb-5">
