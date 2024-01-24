<?php 
session_start();

if (!isset($_SESSION["userId"])) {
  header("Location: sign-in.php");
  exit;
}

require "./utils/php/posts.php";

$postId = $_GET["pid"];
$postUserId = query("SELECT user_id FROM posts WHERE post_id = $postId")[0]["user_id"];

if ($_SESSION["userId"] != $postUserId) {
  header("Location: index.php");
  exit;
}

if (deletePost($postId) > 0) {
  $_SESSION["deletePost"] = "Post deleted successfully!";
} else {
  $_SESSION["deletePost"] = "Post failed to delete!";
}
header("Location: profile.php");
?>