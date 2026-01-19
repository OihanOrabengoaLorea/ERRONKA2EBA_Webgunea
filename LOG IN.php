<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
     <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="SECONDS AGO LOGO.png"/>
</head>
<body>
    <?php include 'HEADER-ENG.php'; ?>

<main>
    <form action="#" method="post">
         <div>
                <label for="posta elektronikoa">Email</label>
                <input type="email" id="posta_elektronikoa" placeholder="example@gmail.com" required>
         </div>
           <div>
                <label for="pasahitza">Password</label>
                <input type="password" name="pasahitza" id="pasahitza" required>
            </div>
            <div>
                <button type="reset">Reset</button>
                <button type="submit">Enter</button>
                
            </div>

    </form>
</main>
<?php include 'FOOTER-ENG.php'; ?>
    
</body>
</html>