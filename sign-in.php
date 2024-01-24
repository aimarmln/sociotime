<?php 
session_start();

if (isset($_SESSION["userId"])) {
  header("Location: index.php");
  exit;
}

require "./utils/php/users.php";
require "./utils/php/pages.php";

$motto = getRandomMotto("./data/mottos.json");

if (isset($_POST["signIn"])) {
  if (signIn($_POST)) {
    header("Location: index.php");
    exit;
  }
  $error = true;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sociotime | Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./utils/css/sign-in-up.css" />
    <link rel="stylesheet" href="./utils/css/scrollbar.css" />
  </head>
  <body class="overflow-y-scroll overflow-x-hidden">
    <div class="background">
        <img src="./data/assets/sign-in-up-bg.png" alt="bg-login">
    </div>
    <section class="container form">
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
                        <h4 class="text-white text-center">Sign In</h4>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" required />
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required />
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="mt-3 tregis">
                        <p>Don't have an account yet? <a href="sign-up.php">Sign Up</a></p>
                    </div>
                    <button type="submit" name="signIn" class="btn button-white mt-3">Sign In</button>
                </form>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <!-- Toast Message -->
    <?php if (isset($_SESSION["signUp"]) && $_SESSION["signUp"]) : ?>
      <div class="toast-container position-fixed bottom-0 mx-3 mb-3 rounded bg-white text-black">
        <div id="toast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
                New user added succesfully!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <script src="./utils/js/toast.js"></script>
      <?php unset($_SESSION["signUp"]) ?>
    <?php endif ?>

    <?php if (isset($error)) : ?>
      <div class="toast-container position-fixed bottom-0 mx-3 mb-3 rounded bg-white text-black">
        <div id="toast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
                Wrong username or password!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <script src="./utils/js/toast.js"></script>
    <?php endif ?>

    <?php if (isset($_SESSION["signOut"])) : ?>
      <div class="toast-container position-fixed bottom-0 mx-3 mb-3 rounded bg-white text-black">
        <div id="toast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
                Goodbye!
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <script src="./utils/js/toast.js"></script>
      <?php unset($_SESSION["signOut"]) ?>
    <?php endif ?>
  </body>
</html>