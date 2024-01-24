<?php 
session_start();

if (!isset($_SESSION["userId"])) {
  header("Location: sign-in.php");
  exit;
}

require "./utils/php/pages.php";
require "./utils/php/posts.php";
require "./utils/php/users.php";

$user = getUser($_SESSION["userId"]);

if (isset($_POST["submitPost"])) {
  if (post($_POST) > 0) {
    $_SESSION["post"] = "Post uploaded successfully";
  } else {
    $_SESSION["post"] = "Post failed to upload";
  }
  header("Location: index.php");
  unset($_POST);
} 

if (isset($_POST["saveChanges"])) {
  if (editProfile($_POST) > 0) {
    $_SESSION["editProfile"] = "Profile updated successfully!";
  } elseif (editProfile($_POST) === 0) {
    $_SESSION["editProfile"] = "No changes!";
  } else {
    $_SESSION["editProfile"] = "Profile failed to update!";
  }
  header("Location: profile.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sociotime | Edit Profile</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link rel="stylesheet" href="./utils/css/post-detail.css" />
    <link rel="stylesheet" href="./utils/css/navbar.css" />
    <link rel="stylesheet" href="./utils/css/scrollbar.css" />
    <link rel="stylesheet" href="./utils/css/edit-profile.css" />
  </head>
  <body class="overflow-y-scroll">
    <!-- navbar -->
    <header>
      <nav class="navbar navbar-expand-lg fixed-top" data-bs-theme="dark">
        <div class="container">
          <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="./data/assets/logo.png" style="width: 50px; height: 50px" alt=""/>
          </a>
          <div id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link active" href="profile.php"
                  ><i class="bi bi-arrow-left"></i
                ></a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="/"
                  ><i class="bi bi-house-door-fill"></i
                ></a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="search.php"
                  ><i class="bi bi-search"></i
                ></a>
              </li>
              <li class="nav-item">
                <a 
                  class="nav-link active"
                  data-bs-toggle="modal"
                  data-bs-target="#postModal"
                  ><i class="bi bi-plus-square-fill"></i
                ></a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="profile.php"
                  ><i class="bi bi-person-fill"></i
                ></a>
              </li>
            </ul>
          </div>
          <a class="navbar-brand" href="sign-out.php" style="font-size: 25px"
            ><i class="bi bi-box-arrow-right"></i
          ></a>
        </div>
      </nav>
    </header>
    <div
      class="modal fade"
      id="postModal"
      tabindex="-1"
      aria-labelledby="postModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content p-2">
          <div class="modal-header" data-bs-theme="dark">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="align-items-center">
              <div class="profile-card align-items-center d-flex">
                <img
                  src="./data/profile-pictures/<?= $user["profile_pict"]; ?>"
                  class="profile-pct"
                  alt="profile-pict"
                />
                <p class="form-username"><?= $user["username"]; ?></p>
              </div>
              <form action="" method="POST" enctype="multipart/form-data" id="resizableForm">
                <input type="hidden" name="userId" value="<?= $_SESSION["userId"]; ?>">
                <div class="post-border mt-3 my-3">
                  <textarea
                    id="message"
                    name="caption"
                    rows="1"
                    placeholder="Type here"
                    required
                  ></textarea>
                </div>
                <div class="d-flex justify-content-start mb-3">
                  <!-- <input type="file" class="" id="fileInput" accept="image/*,video/*" onchange="previewFile()"> -->
                  <label class="btn-img-up" for="fileInput" id="custom-button">
                    <i class="bi bi-file-earmark-image"></i>
                  </label>
                  <input
                    type="file"
                    id="fileInput"
                    accept="image/*"
                    onchange="previewFile()"
                    name="image"
                  />
                </div>
                <div class="mb-3">
                  <div id="preview"></div>
                </div>
                <div class="d-flex justify-content-end">
                  <button type="submit" name="submitPost" class="button-cream">Post</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Navbar -->
    <!-- main content -->
    <main class="container">
      <section class="post-content container-fluid">
        <!-- post detail -->
        <div class="custom-card">
          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="userId" value="<?= $_SESSION["userId"]; ?>">
              <div class="d-flex justify-content-center mb-3">
                <img
                  src="./data/profile-pictures/<?= $user["profile_pict"]; ?>"
                  class="profile-title-pct"
                  alt="profile-pict"
                />
              </div>
              <h6 class="text-center mb-3">Current Profile Picture</h6>
              <div class="form-floating mb-3">
                <input type="file" id="profilePict" name="profilePict" class="border border-white w-100" />
                <input type="hidden" name="oldProfilePict" value="<?= $user["profile_pict"]; ?>" /> 
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="username" value="<?= $user["username"]; ?>" required />
                <label for="floatingInput">Username</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="First Name" name="firstName" value="<?= $user["first_name"]; ?>" required />
                <label for="floatingInput">First Name</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Last Name" name="lastName" value="<?= $user["last_name"]; ?>" required />
                <label for="floatingInput">Last Name</label>
              </div>
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="Email" name="email" value="<?= $user["email"]; ?>" required />
                <label for="floatingInput">Email</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Bio" name="bio" value="<?= $user["bio"]; ?>" required />
                <label for="floatingInput">Bio</label>
              </div>
              <div class="form-floating mb-3">
                <input type="date" class="form-control" id="floatingInput" placeholder="Birthday" name="birthday" value="<?= $user["birthday"]; ?>" required />
                <label for="floatingInput">Birthday</label>
              </div>
              <button type="submit" name="saveChanges" class="button-cream">Save Changes</button>
            </form>
          </div>
        </div>
      </section>
    </main>

  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
  ></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
  ></script>
  <script src="./utils/js/index.js"></script>
  </body>
</html>
