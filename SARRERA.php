<?php
include 'INIT.php';
?>
<!DOCTYPE html>
<html lang="eu">

<head>
  <meta charset="utf-8">
  <title>EJBE - Hasiera</title>
  <link rel="stylesheet" href="CSS_Erronka.css" />
  <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png" />
</head>

<body>
  <?php include 'HEADER.php'; ?>

  <div class="hero">
    <div class="hero-content" style="text-align: center;">
      <h1>Ongi etorri EJBEra</h1>
      <p>Teknologiari bigarren bizitza bat ematen diogu. Kalitatea, iraunkortasuna eta konfiantza.</p>
    </div>
  </div>

  <main class="main">
    <section>
      <h2 class="section-title">Gure Enpresa</h2>
      <p style="text-align: center; max-width: 800px; margin: 0 auto 40px;">
        <strong>EJBE (Eusko Jaurlaritzako Birgaitze Elektronikoa)</strong> enpresa espezializatua da
        ordenagailu eta mugikor erabiliak birgaitzen eta saltzen. Gure helburua teknologia irisgarriagoa
        egitea da, ingurumena zainduz eta ekonomia zirkularra sustatuz.
      </p>

      <div class="info-grid">
        <div class="info-card">
          <h3>Kokalekua</h3>
          <p><strong>Helbidea:</strong> Kale Nagusia, Ordizia</p>
          <p><strong>Herria:</strong> Ordizia (Gipuzkoa)</p>
          <p>Hurbildu gure dendara eta ezagutu gure produktuak bertatik bertara.</p>
        </div>

        <div class="info-card" style="border-left-color: #2a5c4a;">
          <h3>Zerbitzuak</h3>
          <p>Bermatutako birgaitze prozesuak, aholkularitza pertsonalizatua eta osagai elektronikoen aukera zabala.</p>
        </div>
      </div>
    </section>

    <section style="margin-top: 60px;">
      <h2 class="section-title">Non Gaude?</h2>
      <div style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <iframe src="https://www.google.com/maps?q=Kale%20Nagusia%2C%20Ordizia&output=embed" width="100%" height="450"
          style="border:0" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </section>
  </main>

  <?php include 'FOOTER.php'; ?>
</body>

</html>