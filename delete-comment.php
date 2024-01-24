<?php 
session_start();

if (!isset($_SESSION["userId"])) {
  header("Location: sign-in.php");
  exit;
}

require "./utils/php/comments.php";

$commentId = $_GET["cid"];
$commentUserId = query("SELECT user_id FROM comments WHERE comment_id = $commentId")[0]["user_id"];

if ($_SESSION["userId"] != $commentUserId) {
  header("Location: index.php");
  exit;
}

$postId = query("SELECT post_id FROM comments WHERE comment_id = $commentId")[0]["post_id"];

if (deleteComment($commentId) > 0) {
  $_SESSION["deleteComment"] = "Comment deleted successfully!";
} else {
  $_SESSION["deleteComment"] = "Comment failed to delete!";
}
header("Location: post-detail.php?pid=$postId");
?>