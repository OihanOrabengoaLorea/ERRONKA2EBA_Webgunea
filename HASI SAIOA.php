<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasi saioa</title>
     <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="SECONDS AGO LOGO.png"/>
</head>
<body>
<?php include 'HEADER.php'; ?>
<main>
    <form action="#" method="post">
         <div>
                <label class="login" for="posta elektronikoa">Posta elektronikoa</label>
                <input class="input_login" type="email" id="posta_elektronikoa" placeholder="example@gmail.com" required>
         </div>
           <div>
                <label class="login" for="pasahitza">Pasahitza</label>
                <input class="input_login" type="password" name="pasahitza" id="pasahitza" required>
            </div>
            <div>
                <button type="reset">Ezabatu</button>
                <button type="submit">Sartu</button>
                
            </div>

    </form>
</main>
<?php include 'FOOTER.php'; ?>
    
</body>
</html>