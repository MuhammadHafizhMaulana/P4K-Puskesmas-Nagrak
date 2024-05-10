<?php
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] !== 'login'){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: #feffc7;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="#">Home</a>
        <a class="nav-link" href="donor_darah.php">Donor Darah</a>
        <a class="nav-link" href="#">Pricing</a>
        <a class="nav-link" href="profile.php" >Profile</a>
        <a class="nav-link" href="proses/logout.php" >Logout</a>
      </div>
    </div>
  </div>
</nav>
    <!-- <div class="con d-flex justify-content-center ">
        <div class="box ">
            <h1>Home</h1>
            <a href="home2.php" class="btn btn-primary" >lanjut</a><br><br><br>
            <a href="proses/logout.php" class="btn btn-primary">Logout</a>
        </div>
    </div> -->
</body>
</html>