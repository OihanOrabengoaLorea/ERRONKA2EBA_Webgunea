<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <title>Formularioa</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
    </head>
    <body>
    <?php
    include "header.php";
    ?>
    <main>
        <form action="#" method="post" class="formularioa">
            <div>
                <label for="bezeroa">Bezeroa</label>
                <input type="radio" name="Aukera"  value="Bezeroa" class="mota" required >
                <label for="hornitzailea">Hornitzailea</label>
                <input type="radio"  name="Aukera" value="Hornitzailea" class="mota" >
                
            </div>
            <div>
                <label for="izena">Izena</label>
                <input type="text" id="izena"  required disabled>
            </div>
            <div>
                <label for="abizena">Abizena</label>
                <input type="text" id="abizena" required disabled>
            </div>
            <div>
                <label for="harremanetarako pertsona">Harremanetarako pertsona</label>
                <input type="text" id="harremanetarako_pertsona" required disabled>
            </div>
            <div>
                <label for="posta elektronikoa">Posta elektronikoa</label>
                <input type="email" id="posta_elektronikoa" placeholder="example@gmail.com" required disabled>
            </div>
            <div>
                <label for="telefonoa">Telefonoa</label>
                <input type="tel" id="telefonoa"  placeholder="+34 688 452 317"disabled>
            
            </div>
            <div>
                <label for="enpresaren izena">Enpresaren izena</label>
                <input type="text" id="enpresaren_izena" required disabled>
            </div>
            <div>
                <label for="produktu mota">Produktu mota</label>
                <input type="text" id="produktu_mota" required disabled>
            </div>
            <div>
                <label for="produktu marka">Produktu marka</label>
                <input type="text" id="produktu_marka"  disabled>
            </div>

            <div>
                <label for="produktua">Produktua</label>
                <input type="text" id="produktua"  required disabled>
            </div>
            <div>
                <label for="produktu kopurua">Produktu kopurua</label>
                <input type="number" id="produktu_kopurua" min="1" disabled>
            </div>
            <div>
                <label for="Produktuaren deskribapena">Produktuaren deskribapena</label>
                <input type="text" id="produktuaren_deskribapena" required disabled>
            </div>
            <div>
                <label for="oharrak">Oharrak</label>
                <input type="text" id="oharrak" disabled>
            </div>
            <div>
                <button type="reset">Ezabatu</button>
                <button type="submit">Bidali</button>
                
            </div>
        </form>
    </main>
    <?php
    include "footer.php";
    ?>   
    </body>
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
          document.getElementById('telefonoa').disabled = false;
          document.getElementById('produktu_mota').disabled = false;
          document.getElementById('produktu_marka').disabled = false;
          document.getElementById('produktua').disabled = false;
          document.getElementById('oharrak').disabled = false;
          document.querySelector('input[type="reset"]').disabled = false;
          document.querySelector('input[type="submit"]').disabled = false;
        } 
        else if (aukera.value === 'Hornitzailea') {
          // Hornitzailea hautatzean: honako eremuak aktibatzen dira
          document.getElementById('harremanetarako_pertsona').disabled = false;
          document.getElementById('enpresaren_izena').disabled = false;
          document.getElementById('posta_elektronikoa').disabled = false;
          document.getElementById('telefonoa').disabled = false;
          document.getElementById('produktu_mota').disabled = false;
          document.getElementById('produktu_marka').disabled = false;
          document.getElementById('produktua').disabled = false;
          document.getElementById('produktu_kopurua').disabled = false;
          document.getElementById('produktuaren_deskribapena').disabled = false;
          document.getElementById('oharrak').disabled = false;
          document.querySelector('input[type="reset"]').disabled = false;
          document.querySelector('input[type="submit"]').disabled = false;
        }
    
      });
    });
  </script>
    
</html>