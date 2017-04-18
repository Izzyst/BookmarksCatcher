<!DOCTYPE html>
<html>
<head>
<?php 
    session_start(); 
?>
    <meta charset="utf-8">
    <title>bookmarks catcher </title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
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
    <form  method="post" action="rejestracja.php">

        <div class="form-input">
            <input type=email name="mail" required placeholder="Email"/>
        </div>

        <?php       
        if(isset($_SESSION['err_mail']))
        {
            echo '<div class = "error">' .$_SESSION['err_mail'].'</div>';
            unset($_SESSION['err_mail']);
        }
        ?>

        <div class="form-input">
            <input type=password name="password" required placeholder="Password" />
        </div>

        <?php 
        if(isset($_SESSION['err_haslo']))
        {           
            echo '<div class = "error">' .$_SESSION['err_haslo'].'</div>';
            unset($_SESSION['err_haslo']);
        }

        ?>

        <div class="form-input">
            <input type=password name="password2" required placeholder="Repeat password" onchange="validate_pass('password','password2')" /></div>
            
        <?php 	
        if(isset($_SESSION['err_haslo']))
        {               
            echo '<div class = "error">' .$_SESSION['err_haslo'].'</div>';
            unset($_SESSION['err_haslo']);
        }
        ?>

       <!-- <div class="g-recaptcha" data-sitekey="6LeR_gwUAAAAAC-ZtQBMMiXtMb17064_RrcOt9PR"></div> -->
        
        <div class="form-input">
            <input type=submit value="sign in" class = "btn-login">
        </div>

       <!-- <div class="form-input">
            <a href ="test.php"><h5>wyswietl uzytk</h5></a>
        </div>-->

    </div>
    </form>

</body>
</html>
<?php
    session_start();
	/*if (isset($_POST['mail'])){

        $address=rand(0,1000000);
        $email = $_POST['mail'];
        $haslo = $_POST['password'];
        $haslo2 = $_POST['password2'];
        $allFine = true;

        echo $email."<br>".$haslo."<br>".$haslo2."<br>";
    }*/
?>