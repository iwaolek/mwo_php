<?php
  session_start();
  $check_error = '';
  $username = $_SESSION['username'];
  $conn = mysqli_connect("localhost", "root", "", "oleksiewicz_szkola");
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  if (isset($_POST['student_send']) && $_POST["student_name"]!="" && $_POST["student_surname"]!="" && $_POST["student_id_class"]!="") {
    $student_name = $_POST['student_name'];
    $student_surname = $_POST['student_surname'];
    $student_id_class = $_POST['student_id_class'];
    $check = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `class` WHERE `id`='$student_id_class' AND `added_by`=(SELECT id FROM userdata WHERE username='$username');"));
    if ($check != 1){
      $check_error = 'Wystąpił błąd podczas wczytywania danych';
    } else {
      $check_error = '';
      $sql = "INSERT INTO student (name, surname, class_id, added_by) VALUES ('$student_name', '$student_surname', '$student_id_class' , (SELECT id FROM userdata WHERE username='$username'));";
      mysqli_query($conn, $sql);
    }
  }
  if (isset($_POST['class_send']) && $_POST["class_name"]!="") {
    $class_name = $_POST['class_name'];
    $sql = "INSERT INTO class (name, added_by) VALUES ('$class_name', (SELECT id FROM userdata WHERE username='$username'));";
    mysqli_query($conn, $sql);
  }
  if (isset($_POST['teacher_send']) && $_POST["teacher_name"]!="" && $_POST["teacher_surname"]!="" && $_POST["teacher_age"]!="") {
    $teacher_name = $_POST['teacher_name'];
    $teacher_surname = $_POST['teacher_surname'];
    $teacher_age = $_POST['teacher_age'];
    if (mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `subject` WHERE `added_by`=(SELECT id FROM userdata WHERE username='$username')")) > mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `teacher` WHERE `added_by`=(SELECT id FROM userdata WHERE username='$username')"))) {
      $check_error = '';
      $sql = "INSERT INTO teacher (id, name, surname, age, added_by) VALUES (NULL, '$teacher_name', '$teacher_surname', '$teacher_age', (SELECT id FROM userdata WHERE username='$username'));";
      mysqli_query($conn, $sql);
    } else {
      $check_error = 'Wystąpił błąd podczas wczytywania danych';
    }
  }
  if (isset($_POST['subject_send']) && $_POST["subject_name"]!="" && $_POST["subject_id"]!="") {
    $subject_name = $_POST['subject_name'];
    $subject_id = $_POST['subject_id'];
    $check = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `class` WHERE `id`='$subject_id' AND `added_by`=(SELECT id FROM userdata WHERE username='$username');"));
    if ($check != 1){
      $check_error = 'Wystąpił błąd podczas wczytywania danych';
    } else {
      $check_error = '';
      $sql = "INSERT INTO subject (id, name, class_id, added_by) VALUES (NULL, '$subject_name', '$subject_id', (SELECT id FROM userdata WHERE username='$username'));";
      mysqli_query($conn, $sql);
    }
  }
  if (isset($_POST['new_user_data']) && $_POST["password_user"]!="" && (($_POST["username_user"]!="") || ($_POST["name_user"]!="") || ($_POST["surname_user"]!="") || ($_POST["new_password_user"]!="") || ($_POST["age_user"]!="") || (isset($_POST["admin_user"])))) {
    $username = $_SESSION['username'];
    $password_user = sha1($_POST["password_user"] . 'nekbek_salt1');
    $username_user = $_POST['username_user'];
    $query = "SELECT * FROM userdata WHERE `username`='$username' and `password`='$password_user';";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($result);
    if ($count != 1){
      echo "Podano błędne dane";
      echo $_SESSION['username'];
      echo $_POST['password_user'];
      echo " ".$password_user;
      $check_error = "Twoje hasło jest błędne";
    } else {
      if (isset($_POST["username_user"])){
        $username = $_SESSION['username'];
        $query = "SELECT * FROM userdata WHERE username='$username_user';";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $count = mysqli_num_rows($result);
        if ($count == 1){
          $check_error = "Podany login jest już zajęty";
        } else {
          string_math();
        }
      } else {
        string_math();
      }
    }
  }
  function string_math(){
    $username_user = $_POST["username_user"];
    $sql2 = "";
    $conn = mysqli_connect("localhost", "root", "", "oleksiewicz_szkola");
    if (isset($_POST["name_user"])){
      if ($_POST["name_user"]!=''){
        $name_user = $_POST["name_user"];
        $sql2 .= "UPDATE userdata SET name='$name_user' WHERE username='" . $_SESSION['username'] . "'; ";
      }
    }
    if (isset($_POST["surname_user"])){
      if ($_POST["surname_user"]!=''){
        $surname_user = $_POST["surname_user"];
        $sql2 .= "UPDATE userdata SET surname='$surname_user' WHERE username='" . $_SESSION['username'] . "'; ";
      }
    }
    if (isset($_POST["new_password_user"])){
      if ($_POST["new_password_user"]!=''){
        $new_password_user = sha1($_POST["new_password_user"] . 'nekbek_salt1');
        $sql2 .= "UPDATE userdata SET password='$new_password_user' WHERE username='" . $_SESSION['username'] . "'; ";
      }
    }
    if (isset($_POST["age_user"])){
      if ($_POST["age_user"]!=''){
        $age_user = $_POST["age_user"];
        $sql2 .= "UPDATE userdata SET age='$age_user' WHERE username='" . $_SESSION['username'] . "'; ";
      }
    }
    if (isset($_POST["username_user"])){
      if ($_POST["username_user"]!=''){
        $username_user = $_POST["username_user"];
        $sql2 .= "UPDATE userdata SET username='$username_user' WHERE username='" . $_SESSION['username'] . "'; ";
      }
    }
    if (isset($_POST["admin_user"])){
      if ($_POST["admin_user"]!=''){
        $admin_user = $_POST["admin_user"];
        echo $admin_user;
        $sql2 .= "UPDATE userdata SET admin='$admin_user' WHERE username='" . $_SESSION['username'] . "'; ";
      }
    }
    mysqli_multi_query($conn, $sql2);
    header('Location: strona.php');
    if($_POST["username_user"]!=''){
      $_SESSION['username']=$username_user;
    }
    if (isset($_POST["new_password_user"])){
      if ($_POST["new_password_user"]!=''){
        header('Location: logout.php');
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
      if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
      }
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='style1.css'>
    <title>Wypisywanie</title>
    <style>
      table, td, th {  
        border: 1px solid #757575;
        text-align: left;
      }
      table {
        border-collapse: collapse;
        width: calc(auto+5px);
      }
      th, td {
        padding: 8px;
      }
      td, th{
        text-align: center;
      }
    </style>
</head>
<body>
  <container_alfa class = "form container_alfa">
  <container>
    <?php
      if (isset($_SESSION['username'])) {
        echo "<h2>Zalogowałeś się na konto:</h2><h1>". $_SESSION['username'] ."</h1>";
        echo "<a id='logout' href='logout.php'>Wyloguj się</a>";
        if ($check_error!=""){
          echo "<br><br>$check_error"; 
        }
      } else {
        header('Location: logout.php');
      }
    ?>
  </container>
  <h2>-----------------------------------------</h2>
  <form action='strona.php' method='post'>
    <h2>Studenci: </h2>
      <label id='text' for='login'>Imię: </label>
      <input id='number' class='small' type='text' name='student_name' placeholder='Wpisz imię'>
      <label id='text' for='login'>Nazwisko: </label>
      <input id='number' class='small' type='text' name='student_surname' placeholder='Wpisz nazwisko'>
      <label id='text' for='login'>Id klasy: </label>
      <input id='number' class='small' type='number' name='student_id_class' placeholder='Wpisz id klasy'><br>
      <input type='submit' name='student_send' class='btn' id='sumbit' value='Dodaj'>
  </form><br>
  <form action='strona.php' method='post'>
  <h3>-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -</h3>
    <h2>Klasy: </h2>
      <label id='text' for='login'>Nazwa: </label>
      <input id='number' class='small' type='text' name='class_name' placeholder='Wpisz nazwę klasy'><br>
      <input type='submit' name='class_send' class='btn' id='sumbit' value='Dodaj'>
  </form><br>
  <form action='strona.php' method='post'>
  <h3>-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -</h3>
    <h2>Nauczyciele: </h2>
      <label id='text' for='login'>Imię: </label>
      <input id='number' class='small' type='text' name='teacher_name' placeholder='Wpisz imię'>
      <label id='text' for='login'>Nazwisko: </label>
      <input id='number' class='small' type='text' name='teacher_surname' placeholder='Wpisz nazwisko'>
      <label id='text' for='login'>Wiek: </label>
      <input id='number' class='small' type='number' name='teacher_age' placeholder='Wpisz wiek'><br>
      <input type='submit' name='teacher_send' class='btn' id='sumbit' value='Dodaj'>
  </form><br>
  <form action='strona.php' method='post'>
  <h3>-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -</h3>
    <h2>Przedmioty: </h2>
      <label id='text' for='login'>Nazwa: </label>
      <input id='number' class='small' type='text' name='subject_name' placeholder='Wpisz nazwę klasy'>
      <label id='text' for='login'>Id klasy: </label>
      <input id='number' class='small' type='number' name='subject_id' placeholder='Wpisz nazwę klasy'><br>
      <input type='submit' name='subject_send' class='btn' id='sumbit' value='Dodaj'>
  </form>
  <h2>-----------------------------------------</h2>
  <form action='strona.php' method='post' class='display'>
    <h2>Zmień dane użytkownika: </h2>
      <label id='text' for='login'>Nowy login: </label>
      <input id='number' class='small' type='text' name='username_user' placeholder='Wpisz nazwę klasy'>
      <label id='text' for='login'>Nowe imie: </label>
      <input id='number' class='small' type='text' name='name_user' placeholder='Wpisz nazwę klasy'>
      <label id='text' for='login'>Nowe nazwisko: </label>
      <input id='number' class='small' type='text' name='surname_user' placeholder='Wpisz nazwę klasy'>
      <label id='text' for='login'>Nowe hasło: </label>
      <input id='number' class='small' type='text' name='new_password_user' placeholder='Wpisz nazwę klasy'>
      <label id='text' for='login'>Podaj bieżące hasło: </label>
      <input id='number' class='small' type='text' name='password_user' placeholder='Wpisz nazwę klasy'>
      <label id='text' for='login'>Nowy wiek: </label>
      <input id='number' class='small' type='number' name='age_user' placeholder='Wpisz nazwę klasy'>
      <label id='admin_label' for='login'>Admin: </label>
      <div id="admin_box">
        <div>Tak:<input id='number' class='radio' type='checkbox' name='admin_user' value='1' onclick="radio0()"></div>
        <div>Nie:<input id='number' class='radio' type='checkbox' name='admin_user' value='0' onclick="radio1()"></div>
      </div><br>
      <input type='submit' name='new_user_data' class='btn' id='sumbit' value='Dodaj'>
  </form>
  <h2>-----------------------------------------</h2>
  <?php
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM student WHERE added_by=(SELECT id FROM userdata WHERE username='$username');";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo "<br><table><tr><th colspan=3>Uczniowie:</th></tr>";
      echo "<tr><th>id</th><th>Imie</th><th>Nazwisko</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["surname"]."</td></tr>";
      }
      echo "</table>";
    }
    $sql = "SELECT * FROM class WHERE added_by=(SELECT id FROM userdata WHERE username='$username');";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo "<br><table><tr><th colspan=2>Klasy:</th></tr>";
      echo "<tr><th>id</th><th>Numer klasy</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td></tr>";
      }
      echo "</table>";
    }
    $sql = "SELECT * FROM teacher WHERE added_by=(SELECT id FROM userdata WHERE username='$username');";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo "<br><table><tr><th colspan=4>Nauczyciele:</th></tr>";
      echo "<tr><th>id</th><th>Imie</th><th>Nazwisko</th><th>Wiek</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["surname"]."</td><td>".$row["age"]."</td></tr>";
      }
      echo "</table>";
    }
    $sql = "SELECT * FROM subject WHERE added_by=(SELECT id FROM userdata WHERE username='$username');";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo "<br><table><tr><th colspan=3>Przedmioty:</th></tr>";
      echo "<tr><th>id</th><th>Nazwa</th><th>id klasy</th></tr>";
      while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td>".$row["class_id"]."</td></tr>";
      }
      echo "</table>";
    }
  ?>
  <container_alfa>
</body>
</html>