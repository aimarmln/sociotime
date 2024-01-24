<?php 
require_once "database.php";

// comment functionalities
function comment($data) {
  global $conn;

  // collect all comment data
  $comment = htmlspecialchars($data["comment"]);
  $postId = htmlspecialchars($data["postId"]);
  $userId = htmlspecialchars($data["userId"]);
  
  // perform insert
  $query = "INSERT INTO comments (comment, user_id, post_id)
            VALUES ('$comment', $userId, $postId)";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function editComment($data) {
  global $conn;

  // collect all comment data
  $commentId = htmlspecialchars($data["commentId"]);
  $comment = htmlspecialchars($data["comment"]);

  // perform update
  $query = "UPDATE comments 
            SET 
              comment = '$comment',
              is_comment_edited = 1
            WHERE comment_id = $commentId";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function deleteComment($commentId) {
  global $conn;

  // perform delete
  $query = "DELETE FROM comments WHERE comment_id = $commentId";
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}
?>