<?php
include_once "db.php";//db.php fitxategia hemen egongo balitz bezela
include_once "HEADER.php";//HEADER.php fitxategia hemen egongo balitz bezela
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Katalogoa</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
  </head>
  <body>
    <div class="filtro-container">
      <form class="katalogo" action="KATALOGOA.php" method="GET">
      <div class="aukera">
      <label for="aukera">Mota:</label>
      <select id="aukera" name="aukera">
        <option>guztiak</option>
        <option>mugikorra</option>
        <option>ordenagailu eramangarria</option>
        <option>tableta</option>
        <option>sagua</option>
        <option>teklatua</option>
        <option>monitorea</option>
        <option>inprimagailua</option>
        <option>biltegiratzea</option>
        <option>sarea</option>
        <option>erloju adimenduna</option>
        <option>aurikularrak</option>
        <option>kamera</option>
      </select>
      </div>
      <div class="ordena">
      <label>Ordena:</label>
      <input type="radio" id="asc" name="ordena" value="asc">
      <label for="asc">Behetik-gora</label>
      <input type="radio" id="desc" name="ordena" value="desc">
      <label for="desc">Goitik-behera</label>
      </div>
      <div class="bilaketa">
      <label for="bilaketa">Bilaketa</label>
      <input type="text" id="bilaketa" name="bilaketa">
      </div>
      <button>Bidali</button>
      </form>
    </div>
    <div class="container">
    <?php

    if(isset($_GET["ordena"]) && $_GET["ordena"] === "desc"){//ordena name-a daukan input eta selectetatik zerbait jasotzen bada eta jasotakoa desc bada
      $ordena =  "desc";//ordena aldagaiean desc gordeko da
    }else{//bestela
      $ordena = "asc";//ordena aldagaiean asc gordeko da
    }
    
    if (!isset($_GET["aukera"]) && !isset($_GET["bilaketa"])){//aukera eta bilaketa name-ak dauzkaten inputetatik ez bada ezer jasotzen
      $stmt = $pdo->query("SELECT * FROM produktuak ORDER BY izena $ordena");//produktu guztiak imprimatuko dira aukeratutako ordenarekin
    }else if($_GET["aukera"] == "guztiak" && $_GET["bilaketa"] == ""){//aukera name-a daukan inputak guztiak itzultzen badu eta bilaketa name-a daukan inputak hutsa itzuliz gero
      $stmt = $pdo->query("SELECT * FROM produktuak ORDER BY izena $ordena");//produktu guztiak imprimatuko dira aukeratutako ordenarekin
    }else if(!empty($_GET["aukera"]) && $_GET["aukera"] != "guztiak" && empty($_GET["bilaketa"])) {//aukera name-a daukan select-a itzultzen duena hutsik ez badago eta balioa guztiak ez bada eta bilaketa ez badu ezer itzultzen
    $aukera = $_GET["aukera"];//aukera aldagaiean aukera name-a daukan option-aren get balioa gordeko da
    $stmt = $pdo->query("SELECT * FROM produktuak WHERE mota = '$aukera' ORDER BY izena $ordena");
    }else if(!empty($_GET["bilaketa"])){//bilaketa name-a daukan inputa get bidez bidaltzen duena hutsik ez badago
      $bilaketa = $_GET["bilaketa"];//bilaketa aldagaiaren barnean get bidez lortutako bilaketa inputaren balioa jaso

    if(!empty($_GET["aukera"]) && $_GET["aukera"] != "guztiak"){//aukera name-a daukan select-ean get bidez lortutakoa hutsik ez badago eta balioa guztiak ez den bitartean
    $aukera = $_GET["aukera"];//aukera aldagaiaren barruan get bidez aukera name-a daukan selectetik lortutako option-a gordetzen da
    $stmt = $pdo->query("SELECT * FROM produktuak WHERE mota = '$aukera' AND izena LIKE '%$bilaketa%' ORDER BY izena $ordena");//aukeratutako optioneko motatako produktuak imprimatuko dira bilaketan sartutakoarekin koinziditzen duten bitartean, eta izenagatik ordenatuta daude
    }else{//bestela
    $stmt = $pdo->query("SELECT * FROM produktuak WHERE izena LIKE '%$bilaketa%' ORDER BY izena $ordena");//bilaketan sartutakoa daukaten produktuen izenak bakarrik agertuko dira, izenagatik ordenatuta
    }
}

    if (!isset($stmt) || $stmt === false) {
      $stmt = [];
    }
    foreach ($stmt as $row){//datu baseko kontsultatik ateratzen den lerro bakoitzaren datuak $row-en gordetzen da
        echo "<div>";//produktuaren div-a
        echo "<img src='" . "./ARGAZKIAK/" . $row["irudia"] . "'/>";//produktuaren argazkia ipintzen du baldin eta datu basean irudia zutabean eta argazkiak karpetan dauden irudien izenak berdinak diren
        echo "<h3>" . $row["izena"] . "</h3>";//Datu baseko taulako izena zutabean sartu eta idatzi
        echo "<p>" . $row["prezioa"] . "</p>";//Datu baseko taulako prezioa zutabean sartu eta idatzi
        echo "<button>Sartu saskira</button>";
        echo "</div>";//produktuaren div-a itxi
    }
    ?>
    </div>
    <?php include_once 'FOOTER.php'; ?> <!-- FOOTER.php fitxategia hemen egongo balitz bezela -->
  </body>
</html>
