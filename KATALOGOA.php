<?php
include_once "db.php";
include_once "HEADER.php";
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

    if(isset($_GET["ordena"]) && $_GET["ordena"] === "desc"){
      $ordena =  "desc";
    }else{
      $ordena = "asc";
    }
    
    if (!isset($_GET["aukera"]) && !isset($_GET["bilaketa"])){
       $stmt = $pdo->query("SELECT * FROM produktuak ORDER BY izena $ordena");
    }else if($_GET["aukera"] == "guztiak" && $_GET["bilaketa"] == ""){
      $stmt = $pdo->query("SELECT * FROM produktuak ORDER BY izena $ordena");
    }else if(!empty($_GET["bilaketa"])){
      $bilaketa = $_GET["bilaketa"];

    if(!empty($_GET["aukera"]) && $_GET["aukera"] != "guztiak"){
    $aukera = $_GET["aukera"];
    $stmt = $pdo->query("SELECT * FROM produktuak WHERE mota = '$aukera' AND izena LIKE '%$bilaketa%' ORDER BY izena $ordena");
    }else{
    $stmt = $pdo->query("SELECT * FROM produktuak WHERE izena LIKE '%$bilaketa%' ORDER BY izena $ordena");
    }
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
    <?php include_once 'FOOTER.php'; ?>
  </body>
</html>
