<?php 
session_start();

if (isset($_SESSION["userId"])) {
  header("Location: index.php");
  exit;
}

require "./utils/php/users.php";
require "./utils/php/pages.php";

$motto = getRandomMotto("./data/mottos.json");

if (isset($_POST["signUp"])) {
  if (signUp($_POST) > 0) {
    $_SESSION["signUp"] = true;
    header("Location: sign-in.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sociotime | Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./utils/css/sign-in-up.css" />
    <link rel="stylesheet" href="./utils/css/scrollbar.css" />
  </head>
  <body class="overflow-y-scroll overflow-x-hidden">
    <div class="background">
        <img src="./data/assets/sign-in-up-bg.png" alt="bg-login">
    </div>
    <section class="container form mb-4">
        <div class="card">
            <div class="card-body" data-bs-theme="dark">
                <form action="" method="post">
                    <div class="form-header">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="./data/assets/logo.png" class="mx-3" alt="Logo" style="width:50px; height:50px; ">
                            <p class="app-name">Sociotime</p>
                        </div>
                        <p class="motto"><?= $motto; ?>.</p>
                    </div>
                    <div class="mb-3">
                        <h4 class="text-white text-center">Sign Up</h4>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" placeholder="First Name" name="firstName" required />
                        <label for="floatingInput">First Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Last Name" name="lastName" required />
                        <label for="floatingInput">Last Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" required />
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="floatingInput" placeholder="Birthday" name="birthday" required />
                        <label for="floatingInput">Birthday</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" required />
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required />
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="confirmPassword" required />
                        <label for="floatingPassword">Confirm Password</label>
                    </div>
                    <img src="./utils/php/captcha.php" alt="Captcha" />
                    <div class="form-floating mb-3">
                        <input
                        type="text"
                        class="form-control"
                        id="captcha"
                        placeholder="Captcha"
                        name="captcha"
                        />
                        <label for="captcha">Captcha</label>
                    </div>
                    <div class="tregis">
                        <p>Already have an account? <a href="sign-in.php">Sign In</a></p>
                    </div>
                    <button type="submit" name="signUp" class="btn button-white mt-3">Sign Up</button>
                </form>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <!-- Toast Message -->
    <?php if (isset($_SESSION["signUp"])) : ?>
      <div class="toast-container position-fixed bottom-0 mx-3 mb-3 rounded bg-white text-black">
        <div id="toast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              <?= $_SESSION["signUp"]; ?>
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <script src="./utils/js/toast.js"></script>
      <?php unset($_SESSION["signUp"]); ?>
    <?php endif; ?>
    </body>
</html>