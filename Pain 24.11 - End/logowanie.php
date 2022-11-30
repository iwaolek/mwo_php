<?php
  session_start();
  $login = false;
  $conn = mysqli_connect("localhost", "root", "", "oleksiewicz_szkola");
  if ($conn->connect_error) {
    die("Nie podlłączono bazy danych");
  }
  if (isset($_POST['submit']) && $_POST['login']!="" && $_POST['password']!="") {
    $conn = mysqli_connect("localhost", "root", "", "oleksiewicz_szkola");
    $username_user = $_POST['login'];
    $password_user = sha1($_POST['password'] . 'nekbek_salt1');
    $query = "SELECT * FROM `userdata` WHERE `username`='$username_user' and `password`='$password_user';";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($result);
    if ($count != 1){
      header('Location: logowanie.php');
      session_destroy();
    } else {
      $_SESSION['username'] = $username_user;
      header('Location: strona.php');
    }
  }
?>
<!DOCTYPE html>
<html lang='en'>

<head>
  <meta charset='UTF-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <link rel='stylesheet' href='style1.css'>
  <title>Logowanie</title>
</head>
<body>
  <container class='form'>
    <form action='logowanie.php' method='post'>
      <h1 id='number' for='login'>Login: </h1>
      <input id='number' class='small' type='text' name='login' id='login' placeholder='Wpisz login'>
      <h1 id='number' for='domena'>Hasło: </h1>
      <input id='number' class='small' type='text' name='password' id='password' placeholder='Wpisz hasło'><br>
      <input type='submit' name='submit' class='btn' id='sumbit' value='Zaloguj'><br>
      <a href="rejestracja.php"><p id='number' for='login'>Formularz rejestracji</p></a>
    </form>
    <?php
    if (isset($_POST['submit']) && $login == false) {
      echo '<h4>Podałeś błędne dane</h4>';
    }
    ?>
  </container>
</body>
</html>