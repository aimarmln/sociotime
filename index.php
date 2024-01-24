<?php 
session_start();

if (!isset($_SESSION["userId"])) {
  header("Location: sign-in.php");
  exit;
}

require "./utils/php/pages.php";
require "./utils/php/posts.php";

[$activePage, $totalPages, $posts] = pagination(3);
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
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sociotime | Home</title>
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
    <link rel="stylesheet" href="./utils/css/index.css" />
    <link rel="stylesheet" href="./utils/css/navbar.css" />
    <link rel="stylesheet" href="./utils/css/scrollbar.css" />
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
        <?php if (count($posts) == 0) : ?>
          <p class="mt-3">No posts yet</p>
        <?php else : ?>
          <?php foreach ($posts as $post) : ?>
            <div class="custom-card">
              <div class="card-body">
                <div class="profile-header-card d-flex justify-content-between align-items-center">
                  <div class="profile-card align-items-center d-flex">
                    <img
                      src="./data/profile-pictures/<?= $post["profile_pict"] ?>"
                      class="profile-pct"
                      alt="profile-pict"
                    />
                    <p><?= $post["username"]; ?></p>
                    <p><?= $post["time_ago"]; ?> <?= ($post["is_post_edited"] == 1) ? "(edited)" : ""; ?></p>
                  </div>
                </div>
                <div class="post-border mt-3 my-3">
                  <p class="card-text">
                    <?= $post["caption"]; ?>
                  </p>
                </div>
                <?php if ($post["image"] != "-") : ?>
                  <a href="post-detail.php?pid=<?= $post["post_id"]; ?>">
                    <img
                      src="./data/user-posts/<?= $post["image"]; ?>"
                      class="card-img-top"
                    />
                  </a>
                <?php endif ?>
                <div class="d-flex justify-content-between mt-3 footer">
                  <div class="my-2">                    
                    <?php if (countComment($post["post_id"]) === 0) : ?>
                      <p>No comments yet</p>
                    <?php else : ?>
                      <p><?= countComment($post["post_id"]) . " comments" ?></p>
                    <?php endif ?>
                  </div>
                  <div>
                    <a href="post-detail.php?pid=<?= $post["post_id"]; ?>" class="btn btn-comment" data-bs-theme="dark">
                      <i class="bi bi-chat-dots"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <?php endforeach ?>
            <?php endif ?>
      </section>
    </main>

    <!-- pagination -->
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item <?= ($activePage > 1) ? "" : "disabled"?>">
          <a class="page-link bg-transparent text-white" href="?page=<?= $activePage - 1; ?>">&laquo;</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <?php if ($i == $activePage) : ?>
            <li class="page-item active"><a class="page-link bg-transparent text-white" href="?page=<?= $i; ?>"><?= $i; ?></a></li>
          <?php else : ?>
            <li class="page-item "><a class="page-link bg-transparent text-white" href="?page=<?= $i; ?>"><?= $i; ?></a></li>
          <?php endif; ?>
        <?php endfor; ?>
        <li class="page-item <?= ($activePage < $totalPages) ? "" : "disabled"?>">
          <a class="page-link bg-transparent text-white" href="?page=<?= $activePage + 1; ?>">&raquo;</a>
        </li>
      </ul>
    </nav>

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

  <!-- Toast Message -->
  <?php if (isset($_SESSION["post"])) : ?>
    <div class="toast-container position-fixed bottom-0 mx-3 mb-3 rounded bg-white text-black">
      <div id="toast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">
              <?= $_SESSION["post"]; ?>
          </div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
    <script src="./utils/js/toast.js"></script>
    <?php unset($_SESSION["post"]); ?>
  <?php endif ?>

  <?php if (isset($_SESSION["signIn"])) : ?>
    <div class="toast-container position-fixed bottom-0 mx-3 mb-3 rounded bg-white text-black">
      <div id="toast" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">
              <?= $_SESSION["signIn"]; ?>
          </div>
          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
    <script src="./utils/js/toast.js"></script>
    <?php unset($_SESSION["signIn"]) ?>
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
