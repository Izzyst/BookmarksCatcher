<?php
    session_start();// dzieki temu mozliwosc skorzystanie ze zmiennych globalnych $_SESSION
    if((isset($_COOKIE['zalogowany'])) && ($_COOKIE['zalogowany']==true))
    {
        header('Location:bookmarks_catcher.php');
        exit(); // żeby nie wykonywały sie niepotrzebne ponizsze komendy
    }


?>

<!DOCTYPE html>
<html lang="pl">

<head>

    <meta charset="utf-8">
    <title>bookmarks catcher</title>
    <!--powiazanie z plikiem css -->
    <link rel="stylesheet" type="text/css" href="style/style.css" />
    <link rel="stylesheet" type="text/css" href="style/fonts.css" />

</head>

<body>
    <div id="logo">
        <b>BOOKMARKS <BR></b>
        <i>catcher.</i>
    </div>

    <div class="container">
    <form action="login.php" method=post>

      <?php
            if(isset($_SESSION['blad']))
        {
            echo $_SESSION['blad'];
            unset($_SESSION['blad']);
        }

        ?>

        <div class="form-input">
            <input type=email name="mail" id="" required placeholder="Email">

        </div>


        <div class="form-input">
            <input type=password name="password" id="" required placeholder="Password">
        </div>

        
        <div class="form-input">
            <input type=submit value="log in" class = "btn-login" name="btn">
        </div>


        <div class="form-input">
            <a href ="registration.php"><p>Sign in</h5></a>
        </div>


</form>

<script>
//do testów :D
  //  document.getElementsByName("mail")[0].value="1@1.com";
  //  document.getElementsByName("password")[0].value="12345678";
  //  document.getElementsByName("btn")[0].click();
</script>

</body>
</html>