<?php include 'header.php'; ?>
<style>
.portal-hero {
  min-height: 70vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #e0f2ff, #b3e5fc, #81d4fa);
  color: #003b6f;
  border-radius: 0.75rem;
  padding: 2rem;
  text-align: center;
}
.portal-hero h1 {
  font-weight: 700;
  font-size: 2.5rem;
  margin-bottom: 1rem;
}
.portal-hero p {
  font-size: 1.1rem;
  margin-bottom: 2rem;
}
.portal-hero .btn {
  width: 220px;
  font-size: 1.1rem;
  padding: 0.75rem 1rem;
  border-radius: 10px;
}
</style>

<div class="portal-hero shadow mb-4">
  <h1>Instructor Portal</h1>
  <p>Welcome Instructor! Choose your action below:</p>
  <div class="d-flex flex-column gap-3">
    <a href="instructor_login.php" class="btn btn-primary">Sign In</a>
    <a href="instructor_signup.php" class="btn btn-outline-primary">Sign Up</a>
  </div>
</div>
<?php include 'footer.php'; ?>
