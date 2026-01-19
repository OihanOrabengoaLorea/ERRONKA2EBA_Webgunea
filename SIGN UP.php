<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="SECONDS AGO LOGO.png"/>
</head>
<body>
    <?php include 'HEADER-ENG.php'; ?>
    <main>
        <form method="post">
            <div>
                <label for="bezeroa">Customer</label>
                <input type="radio" name="Aukera"  value="Bezeroa" class="mota" required >
                <label for="hornitzailea">Supplier</label>
                <input type="radio"  name="Aukera" value="Hornitzailea" class="mota" >
                
            </div>
            <div>
                <label for="izena">Name</label>
                <input type="text" id="izena"  required disabled>
            </div>
            <div>
                <label for="abizena">Surname</label>
                <input type="text" id="abizena" required disabled>
            </div>
              <div>
                <label for="enpresaren izena">Company name</label>
                <input type="text" id="enpresaren_izena" required disabled>
            </div>
                <div>
                <label for="telefonoa">Telephone</label>
                <input type="tel" id="telefonoa"  placeholder="+34 688 452 317" required disabled>          
            </div>
            <div>
                <label for="kokapena">Location</label>
                <input type="text" name="kokapena" id="kokapena" required disabled>
            </div>

            <div>
                <label for="posta elektronikoa">Email</label>
                <input type="email" id="posta_elektronikoa" placeholder="example@gmail.com" required disabled>
            </div>
            <div>
                <label for="pasahitza">Password</label>
                <input type="password" name="pasahitza" id="pasahitza" required disabled>
            </div>
            <div>
                <button type="reset">Reset</button disabled>
                <button type="submit">Create</button disabled>
                
            </div>
        </form>
    </main>
    <?php include 'FOOTER-ENG.php'; ?>
    <script>
    //  ROLAK bilatu (radio botoiak: Bezeroa / Hornitzailea)
    const rolAukerak = document.querySelectorAll('input[name="Aukera"]');

    // Formulario guztiko input-ak hartu
    // (text, email, tel, number, reset, submit)
    const eremuGuztiak = document.querySelectorAll(
      'input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="reset"], input[type="submit"]'
    );

    // Funtzioa: eremu guztiak desgaitzeko
    function desgaituDenak() {
      eremuGuztiak.forEach(e => e.disabled = true);
    }

    // Hasieran dena desgaituta (ezin da ezer idatzi)
    desgaituDenak();

    // Erabiltzaileak rola hautatzen duenean (radio bat aldatzean)
    rolAukerak.forEach(aukera => {
      aukera.addEventListener('change', () => {

        //  Lehenik, dena berriro desgaitu
        desgaituDenak();

        //  Aukeratutakoaren arabera aktibatu dagokion multzoa
        if (aukera.value === 'Bezeroa') {
          //  Bezeroa hautatzean: honako eremuak aktibatzen dira
          document.getElementById('izena').disabled = false;
          document.getElementById('abizena').disabled = false;
          document.getElementById('posta_elektronikoa').disabled = false;
          document.getElementById('pasahitza').disabled = false;
          document.querySelector('input[type="reset"]').disabled = false;
          document.querySelector('input[type="submit"]').disabled = false;
        } 
        else if (aukera.value === 'Hornitzailea') {
          // Hornitzailea hautatzean: honako eremuak aktibatzen dira
          document.getElementById('enpresaren_izena').disabled = false;
          document.getElementById('telefonoa').disabled = false;
          document.getElementById('kokapena').disabled = false;
          document.getElementById('posta_elektronikoa').disabled = false;
          document.getElementById('pasahitza').disabled = false;
          document.querySelector('input[type="reset"]').disabled = false;
          document.querySelector('input[type="submit"]').disabled = false;
        }
    
      });
    });
  </script>
</body>
</html>
