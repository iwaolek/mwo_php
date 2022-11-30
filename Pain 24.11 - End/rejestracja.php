<?php
  session_start();
  if (isset($_POST['submit'])){
    $error = true;
  }
  if (isset($_POST['submit']) && $_POST['login']!="" && $_POST['password']!="" && $_POST['imie']!="" && $_POST['nazwisko']!="" && $_POST['wiek']!="") {
    $conn = mysqli_connect("localhost", "root", "", "oleksiewicz_szkola");
    if ($conn->connect_error) {
      die("Nie podlłączono bazy danych");
    }
    $username_user = $_POST['login'];
    $password_user = sha1($_POST['password'] . 'nekbek_salt1');
    $name_user = $_POST['imie'];
    $surname_user = $_POST['nazwisko'];
    $age_user = $_POST['wiek'];
    $admin_user = $_POST['admin_user'];
    $query_1 = "SELECT * FROM `userdata` WHERE `username`='$username_user';";
    $result = mysqli_query($conn, $query_1) or die(mysqli_error($conn));
    $count = mysqli_num_rows($result);
    if ($count == 1){
      $error = true;
    } else {
      $query_2 = "INSERT INTO userdata (username, password, name, surname, age, admin) VALUES ('$username_user', '$password_user', '$name_user', '$surname_user', '$age_user', '$admin_user')";
      $error = false;
      if (!mysqli_query($conn, $query_2)) {
        die('Próba dodania do bazy danych, nie powiodła się');
      };
    } 
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <script>
      function radio0(){
        const radio0 = document.getElementsByClassName('radio')[0];
        const radio1 = document.getElementsByClassName('radio')[1];
        if(radio1.checked == true) {
            radio1.checked = false;
        }
      };
      function radio1(){
        const radio0 = document.getElementsByClassName('radio')[0];
        const radio1 = document.getElementsByClassName('radio')[1];
        if(radio0.checked == true) {
            radio0.checked = false;
        }
      };
  </script>
  <meta charset='UTF-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Rejestracja</title>
  <link rel='stylesheet' href='style1.css'>
</head>
<body>
  <container class='form'>
    <form action='rejestracja.php' method='post'>
      <h1 id='number' for='login'>Login: </h1>
      <input id='number' class='small' type='text' name='login' id='login' placeholder='Wpisz login'>
      <h1 id='number' for='domena'>Hasło: </h1>
      <input id='number' class='small' type='text' name='password' id='password' placeholder='Wpisz hasło'>
      <h1 id='number' for='imie'>Imie: </h1>
      <input id='number' class='small' type='text' name='imie' id='login' placeholder='Wpisz imię'>
      <h1 id='number' for='nazwisko'>Nazwisko: </h1>
      <input id='number' class='small' type='text' name='nazwisko' id='login' placeholder='Wpisz nazwisko'>
      <h1 id='number' for='wiek'>Wiek: </h1>
      <input id='number' class='small' type='number' name='wiek' id='login' placeholder='Wpisz wiek'>
      <h1 id='admin_label ' for='login'>Admin: </h1>
      <div id="admin_box">
        <div>Tak:<input id='number' class='radio' type='checkbox' name='admin_user' value='1' onclick="radio0()"></div>
        <div>Nie:<input id='number' class='radio' type='checkbox' name='admin_user' value='0' onclick="radio1()"></div>
      </div><br>
      <input type='submit' name='submit' class='btn' id='sumbit' value='Zarejestruj'><br>
      <a href="logowanie.php"><p id='number' for='login'>Formularz logowania</p></a>
      <?php
      if (isset($_POST['submit']) && $error == true){
        echo '<h4>Błąd w rejestracji</h4>';
      } else if (isset($_POST['submit'])) {
        echo '<h4>Pomyślnie zarejestrowano</h4>';
      }
      ?>
    </form>
  </container>
</body>
</html>