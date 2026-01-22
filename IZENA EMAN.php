<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
</head>
<body>
    <?php include 'HEADER.php'; ?>
    <main>
        <form method="post">
            <div>
                <label for="izena">Izena</label>
                <input type="text" id="izena"  required disabled>
            </div>
            <div>
                <label for="abizena">Abizena</label>
                <input type="text" id="abizena" required disabled>
            </div>
            <div>
                <label for="telefonoa">Telefonoa</label>
                <input type="tel" id="telefonoa"  placeholder="+34 688 452 317" required disabled>          
            </div>
            <div>
                <label for="posta elektronikoa">Posta elektronikoa</label>
                <input type="email" id="posta_elektronikoa" placeholder="example@gmail.com" required disabled>
            </div>
            <div>
                <label for="pasahitza">Pasahitza</label>
                <input type="password" name="pasahitza" id="pasahitza" required disabled>
            </div>
            <div>
                <button type="reset">Ezabatu</button disabled>
                <button type="submit">Sortu</button disabled>
                
            </div>
        </form>
    </main>
    <?php include 'FOOTER.php'; ?>
</body>
</html>
