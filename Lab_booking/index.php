<?php include 'header.php'; ?>
<style>
.index-hero {
  min-height: 85vh;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4rem;
  padding: 2rem;
  background: #f4f9ff;
  border-radius: 1rem;
  margin-top: 2rem;
}

.index-left {
  max-width: 500px;
}
.index-left h1 {
  font-size: 2.5rem;
  color: #004080;
  font-weight: bold;
  margin-bottom: 1rem;
}
.index-left p {
  font-size: 1.1rem;
  color: #333;
  margin-bottom: 2rem;
}
.index-left a.btn {
  width: 200px;
  margin: 0.5rem 0;
}

.index-right img {
  max-width: 100%;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}
@media(max-width:768px){
  .index-hero { flex-direction: column; text-align: center; gap: 2rem; }
  .index-left a.btn { width: 100%; }
}
</style>

<div class="container index-hero shadow">
  <div class="index-left">
    <h1>Welcome to Lab Booking System</h1>
    <p>Book labs efficiently and manage resources with ease. Please choose your portal to continue.</p>
    <a href="instructor_portal.php" class="btn btn-primary">Instructor Portal</a><br>
    <a href="to_portal.php" class="btn btn-outline-primary">Technical Officer Portal</a>
  </div>
  <div class="index-right">
    <img src="images/university.jpg" alt="University">
  </div>
</div>

<?php include 'footer.php'; ?>
