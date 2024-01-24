<?php 
require_once "database.php";

// get random motto for sign in and sign up page
function getRandomMotto($json) {
  $mottos = json_decode(file_get_contents($json), true);
  $id = rand(1, count($mottos));

  foreach ($mottos as $motto) {
    if ($motto["id"] == $id) {
      return $motto["motto"];
    }
  }
}

// page functionalities
function pagination($dataPerPage) {
  $query = "SELECT * 
            FROM users u 
            JOIN posts p ON u.user_id = p.user_id";
  
  $totalData = count(query($query));
  $totalPages = ceil($totalData / $dataPerPage);
  $activePage = (isset($_GET["page"])) ? $_GET["page"] : 1;
  $startData = ($dataPerPage * $activePage) - $dataPerPage;

  $limitedQuery = "SELECT
                    u.profile_pict,
                    u.username,
                    p.post_id,
                    p.image,
                    p.caption,
                    p.is_post_edited,
                    p.post_created_at,
                    CASE
                        WHEN TIMESTAMPDIFF(SECOND, p.post_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(SECOND, p.post_created_at, NOW()), 's ago')
                        WHEN TIMESTAMPDIFF(MINUTE, p.post_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, p.post_created_at, NOW()), 'm ago')
                        WHEN TIMESTAMPDIFF(HOUR, p.post_created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, p.post_created_at, NOW()), 'h ago')
                        WHEN TIMESTAMPDIFF(DAY, p.post_created_at, NOW()) < 7 THEN CONCAT(TIMESTAMPDIFF(DAY, p.post_created_at, NOW()), 'd ago')
                        ELSE DATE_FORMAT(p.post_created_at, '%Y-%m-%d %H:%i:%s')
                    END AS time_ago,
                    (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count
                  FROM users u
                  JOIN posts p ON u.user_id = p.user_id
                  ORDER BY post_id DESC
                  LIMIT $startData, $dataPerPage";

  return [$activePage, $totalPages, query($limitedQuery)];
}

// get user
function getUser($userId) {
  $query = "SELECT 
              profile_pict, 
              username, 
              first_name, 
              last_name,
              email,
              bio,
              birthday
            FROM users
            WHERE user_id = $userId";

  return query($query)[0];
}

// get user posts
function getUserPosts($userId) {
  $query = "SELECT
              u.profile_pict,
              u.username,
              p.post_id,
              p.image,
              p.caption,
              p.is_post_edited,
              p.post_created_at,
              CASE
                  WHEN TIMESTAMPDIFF(SECOND, p.post_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(SECOND, p.post_created_at, NOW()), 's ago')
                  WHEN TIMESTAMPDIFF(MINUTE, p.post_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, p.post_created_at, NOW()), 'm ago')
                  WHEN TIMESTAMPDIFF(HOUR, p.post_created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, p.post_created_at, NOW()), 'h ago')
                  WHEN TIMESTAMPDIFF(DAY, p.post_created_at, NOW()) < 7 THEN CONCAT(TIMESTAMPDIFF(DAY, p.post_created_at, NOW()), 'd ago')
                  ELSE DATE_FORMAT(p.post_created_at, '%Y-%m-%d %H:%i:%s')
              END AS time_ago,
              (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count
            FROM users u
            JOIN posts p ON u.user_id = p.user_id
            WHERE u.user_id = $userId
            ORDER BY p.post_id DESC";

  return query($query);
}

// get post detail
function getPostDetail($postId) {
  $postQuery = "SELECT
                  u.profile_pict,
                  u.username,
                  p.post_id,
                  p.image,
                  p.caption,
                  p.is_post_edited,
                  p.post_created_at,
                  CASE
                    WHEN TIMESTAMPDIFF(SECOND, p.post_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(SECOND, p.post_created_at, NOW()), 's ago')
                    WHEN TIMESTAMPDIFF(MINUTE, p.post_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, p.post_created_at, NOW()), 'm ago')
                    WHEN TIMESTAMPDIFF(HOUR, p.post_created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, p.post_created_at, NOW()), 'h ago')
                    WHEN TIMESTAMPDIFF(DAY, p.post_created_at, NOW()) < 7 THEN CONCAT(TIMESTAMPDIFF(DAY, p.post_created_at, NOW()), 'd ago')
                    ELSE DATE_FORMAT(p.post_created_at, '%Y-%m-%d %H:%i:%s')
                  END AS time_ago,
                  (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count
                FROM users u
                JOIN posts p ON u.user_id = p.user_id
                WHERE p.post_id = $postId";

  $commentsQuery = "SELECT 
                      u.user_id,
                      u.profile_pict,
                      u.username,
                      c.comment_id,
                      c.comment,
                      c.is_comment_edited,
                      c.comment_created_at,
                      CASE
                        WHEN TIMESTAMPDIFF(SECOND, c.comment_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(SECOND, c.comment_created_at, NOW()), 's ago')
                        WHEN TIMESTAMPDIFF(MINUTE, c.comment_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, c.comment_created_at, NOW()), 'm ago')
                        WHEN TIMESTAMPDIFF(HOUR, c.comment_created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, c.comment_created_at, NOW()), 'h ago')
                        WHEN TIMESTAMPDIFF(DAY, c.comment_created_at, NOW()) < 7 THEN CONCAT(TIMESTAMPDIFF(DAY, c.comment_created_at, NOW()), 'd ago')
                        ELSE DATE_FORMAT(c.comment_created_at, '%Y-%m-%d %H:%i:%s')
                      END AS time_ago
                    FROM posts p
                      JOIN comments c ON p.post_id = c.post_id
                      JOIN users u ON c.user_id = u.user_id
                    WHERE p.post_id = $postId ORDER BY c.comment_id";
  
  $postDetail = query($postQuery)[0];
  $comments = query($commentsQuery);

  return [$postDetail, $comments];
}

// comment counter
function countComment($postId) {
  $query = "SELECT * 
            FROM posts p 
              JOIN comments c ON p.post_id = c.post_id 
            WHERE p.post_id = $postId";

  return count(query($query));
}

// for edit comment
function getComment($commentId) {
  $query = "SELECT 
              u.user_id,
              u.profile_pict,
              u.username,
              c.comment_id,
              c.comment,
              c.is_comment_edited,
              c.comment_created_at,
              CASE
                WHEN TIMESTAMPDIFF(SECOND, c.comment_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(SECOND, c.comment_created_at, NOW()), 's ago')
                WHEN TIMESTAMPDIFF(MINUTE, c.comment_created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, c.comment_created_at, NOW()), 'm ago')
                WHEN TIMESTAMPDIFF(HOUR, c.comment_created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, c.comment_created_at, NOW()), 'h ago')
                WHEN TIMESTAMPDIFF(DAY, c.comment_created_at, NOW()) < 7 THEN CONCAT(TIMESTAMPDIFF(DAY, c.comment_created_at, NOW()), 'd ago')
                ELSE DATE_FORMAT(c.comment_created_at, '%Y-%m-%d %H:%i:%s')
              END AS time_ago
            FROM posts p
              JOIN comments c ON p.post_id = c.post_id
              JOIN users u ON c.user_id = u.user_id
            WHERE c.comment_id = $commentId";
  
  return query($query)[0];
}

// random five users for search page
function randomUsers($userId, $limit) {
  $query = "SELECT 
              user_id, 
              profile_pict, 
              username,
              first_name, 
              last_name
            FROM users 
            WHERE user_id != $userId 
            ORDER BY RAND() 
            LIMIT $limit";

  return query($query);
}

function searchUser($username) {
  $query = "SELECT 
              user_id, 
              profile_pict, 
              username,
              first_name, 
              last_name
            FROM users 
            WHERE username LIKE '%$username%'  ";
                 
  return query($query);
}
?>