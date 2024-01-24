<?php 
require_once "database.php";

// post functionalities
function postImage() {
  // collect file data
  $fileName = $_FILES["image"]["name"];
  $fileSize = $_FILES["image"]["size"];
  $error = $_FILES["image"]["error"];
  $tmpName = $_FILES["image"]["tmp_name"];

  // if there is no image uploaded
  if ($error === 4) {
    return "-";
  }

  // check if the file extensions valid
  $validFileExtensions = ["jpg", "jpeg", "png", "webp"];
  $fileExtension = explode(".", $fileName);
  $fileExtension = strtolower(end($fileExtension));

  if (!in_array($fileExtension, $validFileExtensions)) {
    return false;   
  }

  // check allowed file size (max: 10 MB)
  if ($fileSize > 10000000) {
    return false;
  }

  // save file with unique name
  $uniqueFileName = uniqid() . ".$fileExtension";
  move_uploaded_file($tmpName, "data/user-posts/$uniqueFileName");

  return $uniqueFileName;
}

function post($data) {
  global $conn;

  // collect post data
  $caption = htmlspecialchars($data["caption"]);
  $userId = htmlspecialchars($data["userId"]);

  // upload image
  $image = postImage();
  if (!$image) {
    return false;
  }

  // perform insert
  $query = "INSERT INTO posts (image, caption, post_created_at, user_id)
            VALUES ('$image', '$caption', NOW(), $userId)";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function editPost($data) {
  global $conn;

  // collect post data
  $postId = htmlspecialchars($data["postId"]);
  $caption = htmlspecialchars($data["caption"]);

  // perform update
  $query = "UPDATE posts 
            SET 
              caption = '$caption',
              is_post_edited = 1
            WHERE post_id = $postId";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function deletePost($postId) {
  global $conn;

  // if they uploaded post image, delete it from data folder
  $existingPostImage = query("SELECT image FROM posts WHERE post_id = $postId")[0]["image"];
  if ($existingPostImage != "-") {
    unlink("./data/user-posts/$existingPostImage");
  }

  // perform delete
  $query = "DELETE FROM posts WHERE post_id = $postId";
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}
?>