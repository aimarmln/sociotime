<?php 
session_start();

require "pages.php";

$searchUsers = searchUser($_GET["keyword"]);
?>
<?php if (count($searchUsers) === 0) : ?>
  <p class="text-center text-white">Oops! No users found for the username '<?= $_GET["keyword"]; ?>'</p>
<?php else : ?>
  <?php foreach ($searchUsers as $searchUser) : ?>
    <?php  
      $userId = $searchUser["user_id"];
      $uid = ($userId == $_SESSION["userId"]) ? "" : "?uid=$userId";
    ?>
    <a href="profile.php<?= $uid ?>" class="src-result">              
      <div class="user-card">
        <img
          src="https://static.vecteezy.com/system/resources/previews/008/442/086/non_2x/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg"
          class="profile-pct"
          alt="profile-pict"
        />
        <div class="user-info">
          <p><?= $searchUser["username"]; ?></p>
          <p><?= $searchUser["first_name"] . " " . $searchUser["last_name"]; ?></p>
        </div>
      </div>
    </a>
  <?php endforeach; ?>
<?php endif; ?>