<?php
session_start();
$server = "localhost";
$user = "root";
$pass = "";
$db = "linkedin";

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) die("DB error");

$action = $_REQUEST['action'] ?? '';

if ($action == "signup") {
  $name = $_POST['name']; $email = $_POST['email']; $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $q = "INSERT INTO users(name,email,password) VALUES('$name','$email','$password')";
  echo $conn->query($q) ? "Signup successful!" : "Signup failed!";
}

elseif ($action == "login") {
  $email = $_POST['email']; $password = $_POST['password'];
  $r = $conn->query("SELECT * FROM users WHERE email='$email'");
  if ($r->num_rows > 0) {
    $u = $r->fetch_assoc();
    if (password_verify($password, $u['password'])) {
      $_SESSION['uid'] = $u['id']; $_SESSION['name'] = $u['name'];
      echo json_encode(["status"=>"success", "name"=>$u['name']]);
    } else echo json_encode(["status"=>"fail"]);
  } else echo json_encode(["status"=>"fail"]);
}

elseif ($action == "create_post" && isset($_SESSION['uid'])) {
  $uid = $_SESSION['uid']; $content = $_POST['content'];
  $q = "INSERT INTO posts(user_id, content) VALUES('$uid','$content')";
  echo $conn->query($q) ? "success" : "error";
}

elseif ($action == "get_posts") {
  $q = "SELECT posts.content, posts.created_at, users.name 
        FROM posts JOIN users ON users.id=posts.user_id
        ORDER BY posts.created_at DESC";
  $r = $conn->query($q);
  $posts = [];
  while($row=$r->fetch_assoc()) $posts[]=$row;
  echo json_encode($posts);
}

elseif ($action == "logout") {
  session_destroy();
  echo "logged_out";
}
?>
