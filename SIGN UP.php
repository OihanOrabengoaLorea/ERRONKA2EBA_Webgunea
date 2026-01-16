<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="SECONDS AGO LOGO.png"/>
</head>
<body>
   <header>
        <nav> 
         <div class="nav">
            <a href="MAIN PAGE.html">
               <img src="SECONDS AGO BG.png" height="125" width="125" alt="SecondsAgo logo"></a>
            <a href="LAST NEWS.html">LAST NEWS</a>
            <a href="CATALOG.html">CATALOG</a>
            <a href="COMPANY HISTORY.html">COMPANY HISTORY</a>
            <a href="MAIN PAGE.html">HOME</a>
            <a href="IZENA EMAN.html">
                <img src="Pais Vasco.png"  class="LOGO" height="80" width="80" alt="English logo">
                <a href="LOG IN.html">LOG IN</a>
            <a href="SIGN UP.html">SIGN UP</a>
            <a href="ORDER FORM.html">ORDER FORM</a>
        </div>
        </nav>
    </header> 
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
    
    <footer>
    <h3>COLLABORATIVE COMPANIES</h3>
     <div class="sarrera-grid">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/8/82/Dell_Logo.png" alt="Dell">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/Lenovo_Global_Corporate_Logo.png/960px-Lenovo_Global_Corporate_Logo.png" alt="Lenovo">
        <img class="logo-empresa" src="Apple logo.png" alt="Apple">
        <img class="logo-empresa" src="Microsoft logo.png" alt="Microsoft">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Fujitsu-Logo.svg/1024px-Fujitsu-Logo.svg.png" alt="Fujitsu">
        <img class="logo-empresa" src="https://images.icon-icons.com/2699/PNG/512/acer_logo_icon_169649.png" alt="Acer">
        <img class="logo-empresa" src="ASUS logo.png" alt="Asus">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Ingram_logo.jpg/1200px-Ingram_logo.jpg" alt="Ingram">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/IBM_logo.svg/960px-IBM_logo.svg.png" alt="IBM">
        <img class="logo-empresa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/Intel_logo_2023.svg/2560px-Intel_logo_2023.svg.png" alt="Intel">
    </div>
        <h3>SOCIAL NETWORKS</h3>
        <a href="#INSTAGRAM"><img src="Instagram logo.png"height="60"width="60"alt="Instagram"/></a>
        <a href="#YOUTUBE"><img src="YouTube logo.png" height="60" width="60" alt="YouTube"/></a>
        <a href="#X"><img src="X logo.png" height="60" width="60" alt="X"/></a>

        <div>
            <h3>ADDITIONAL INFORMATION</h3>
            <p><a href="#KOKAPENA">LOCATION</a></p>
            <p><a href="#KONTAKTUAK">CONTACTS</a></p>
            <p><a href="#ITZULKETA POLITIKA">RETURN POLICY</a></p>
            <p><a href="#SALMETA BALDINTZAK">TERMS OF SALE</a></p>
            <p><a href="#LEGE OHARRA ETA COOKIEN POLITIKA">LEGAL NOTICE AND COOKIE POLICY</a></p>
        </div>
    </footer>
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
