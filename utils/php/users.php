<?php 
require_once "database.php";

// user functionalities
function signUp($data) {
  global $conn;

  // collect all data
  $firstName = ucfirst(htmlspecialchars($data["firstName"]));
  $lastName = ucfirst(htmlspecialchars($data["lastName"]));
  $username = strtolower(stripslashes($data["username"]));
  $email = htmlspecialchars($data["email"]);
  $birthday = htmlspecialchars($data["birthday"]);
  $bio = "";
  $profilePict = "default.jpg";
  $password = mysqli_real_escape_string($conn, $data["password"]);
  $confirmPassword = mysqli_real_escape_string($conn, $data["confirmPassword"]);
  $captcha  = $data["captcha"];

  // check duplicated username
  $existingUsername = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
  if (mysqli_fetch_assoc($existingUsername)) {
    $_SESSION["signUp"] = "Selected username already exists!";
    return false;
  }

  // check password confirmation
  if ($password !== $confirmPassword) {
    $_SESSION["signUp"] = "Password confirmation does not match!";
    return false;
  }

  // check captcha
  if ($_SESSION["captcha"] !== $captcha) {
    $_SESSION["signUp"] = "Captcha does not match!";
    return false;
  }

  // encrypt the password
  $password = password_hash($password, PASSWORD_DEFAULT);

  // perform insert
  $query = "INSERT INTO users (first_name, last_name, username, email, birthday, bio, profile_pict, password, user_created_at) 
            VALUES ('$firstName', '$lastName', '$username', '$email', '$birthday', '$bio', '$profilePict', '$password', NOW())";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function signIn($data) {
  global $conn;

  // collect all data
  $username = $data["username"];
  $password = $data["password"];

  // validation  & set user session
  $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
  if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row["password"])) {
      $userId = query("SELECT user_id FROM users WHERE username = '$username'")[0]["user_id"];
      $_SESSION["userId"] = $userId;
      $_SESSION["signIn"] = "Hello $username";
      return true;
    }
  }
  
  return false;
}

function profilePict() {
  // collect file data
  $fileName = $_FILES["profilePict"]["name"];
  $fileSize = $_FILES["profilePict"]["size"];
  $tmpName = $_FILES["profilePict"]["tmp_name"];

  // check if the file extensions valid
  $validFileExtensions = ["jpg", "jpeg", "png", "webp"];
  $fileExtension = explode(".", $fileName);
  $fileExtension = strtolower(end($fileExtension));

  if (!in_array($fileExtension, $validFileExtensions)) {
    echo "<script>alert(\"not valid file extensions!\")</script>"; 
    return false;   
  }

  // check allowed file size (max: 10 MB)
  if ($fileSize > 10000000) {
    echo "<script>alert(\"file size is too big!\")</script>";   
    return false;
  }

  // save file with unique name
  $uniqueFileName = uniqid() . ".$fileExtension";
  move_uploaded_file($tmpName, "data/profile-pictures/$uniqueFileName");

  return $uniqueFileName;
}

function editProfile($data) {
  global $conn;

  // collect all data
  $userId = htmlspecialchars($data["userId"]);
  $firstName = ucfirst(htmlspecialchars($data["firstName"]));
  $lastName = ucfirst(htmlspecialchars($data["lastName"]));
  $username = strtolower(stripslashes($data["username"]));
  $email = htmlspecialchars($data["email"]);
  $birthday = htmlspecialchars($data["birthday"]);
  $bio = htmlspecialchars($data["bio"]);

  // delete existing profile pict
  $existingProfilePict = $data["oldProfilePict"];
  if ($existingProfilePict != "default.jpg" 
      && $_FILES["profilePict"]["name"] != $existingProfilePict
      && !empty($_FILES["profilePict"]["name"])) {
    unlink("./data/profile-pictures/$existingProfilePict");
  }

  // set profile pict
  $profilePict = $existingProfilePict;
  if (!empty($_FILES["profilePict"]["name"])) {
    $profilePict = profilePict();
    if (!$profilePict) {
      return false;
    }
  }

  // perform update
  $query = "UPDATE users 
            SET 
              first_name = '$firstName',
              last_name  = '$lastName',
              username = '$username',
              email = '$email',
              birthday = '$birthday',
              bio = '$bio',
              profile_pict = '$profilePict'
            WHERE user_id = $userId";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function deleteAccount($userId) {
  global $conn;

  // perform delete
  $query = "DELETE FROM users WHERE user_id = $userId";
  mysqli_query($conn, $query);
  return mysqli_affected_rows($conn);
}
?>