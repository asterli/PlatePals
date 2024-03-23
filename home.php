<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="base.css" />
    <link rel="stylesheet" type="text/css" href="home.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/x-icon" href="/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Rufina:wght@700&display=swap" rel="stylesheet">
    <title>PlatePals - Find Your Favorite Recipes!</title>
  </head>
  <body>
    
    <nav class="rufina">
      <ul>
        <li class="rufina" id="home-name">PlatePals</li>
        <div id="end-nav">
          <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="/account/account.html">My Account</a></li>
          <li><a href="/account/signout.php">Sign Out</a></li>
          <?php else: ?>
          <li><a href="/login/login.php">Login</a></li>
          <li><a href="/signup/signup.php">Sign Up</a></li>
          <?php endif; ?>
        </div>
      </ul>
    </nav>
    <main>
      <div class="main-content">
        <h1 class="rufina">Find your favorite recipes!</h1>
        <img id="steak" src="images/steak.png" alt="Steak" />
      </div>
      <div class="gallery">
        <img id="chowder" src="images/chowder.png" alt="Clam Chowder" />
        <img id="seasoning" src="images/seasoning.png" alt="Seasoning" />
      </div>
    </main>
    <footer class="lato">
      <p>&copy; 2024 PlatePals Inc. All rights Reserved</p>
    </footer>
  </body>
</html>
