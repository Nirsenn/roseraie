<?php
    session_start();
    include('connection.php');
    $_SESSION["path"] = $_COOKIE["imgpath"];
    $img = $_SESSION['path'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couleurs</title>
    <script src="https://kit.fontawesome.com/420e530b7e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <script defer src="js/traitement.js"></script>
</head>

<body style="overflow: auto;">
    <!-- début du header -->
    <header>
        <input type="checkbox" name="barre" id="barre">
        <label for="barre" class="fas fa-bars"></label>

        <a href="accueil.html" class="logo">Roseraie<span> ❁</span></a>

        <nav class="navbar">
            <a href="accueil.html">Accueil</a>
            <a href="about.html">À propos</a>
            <a href="roses.php">Roses</a>
            <a href="ajout.php">Ajouter</a>
        </nav>
    </header>
    <!--fin du header-->

    <div class="contain">
        <div class="title">
            <h1>Palette de couleurs</h1>
        </div>
    </div>
    <div class="paragraph"><p>Cliquez sur l'image pour obtenir une couleur où générez la palette en utilisant le bouton! 🙂</p></div>
    <div class="imgModif">
        <div class="boite">
            <img id="rosesImage" src="<?php echo $img;?>" alt="Photographie de roses" onclick="colorPick(event)"/>
        </div>
    
        <div class="traitement"><br><br>
            <!--Canvas copie de l'image et invisible-->
            <canvas id="myCanvas" style="display: none"></canvas>
            <!--Valeur rgb du pixel-->
            <div class="paragraph"><p id="pxValue"></p></div> <br>
            <!--Affiche la couleur du pixel cliqué-->
            <div id="pickedColor" style="width:200px; height:100px; margin-bottom:100px; display:block; border: 2px solid #000; border-radius: 5px;">
            </div>
            <form>
                <div class="form-ligne">
                <!--Cases pour la palette de couleur-->
                    <div class="paletteContainer">
                        <div id="case1" class="palette"></div>
                        <div id="case2" class="palette"></div>
                        <div id="case3" class="palette"></div>
                        <div id="case4" class="palette"></div>
                        <div id="case5" class="palette"></div>
                        <div id="case6" class="palette"></div>
                        <div id="case7" class="palette"></div>
                        <div id="case8" class="palette"></div>
                        <div id="case9" class="palette"></div>
                        <div id="case10" class="palette"></div>
                        <div id="case11" class="palette"></div>
                        <div id="case12" class="palette"></div>
                    </div>
                    <button type="button" id="paletteR" onclick="generePaletteCouleur(document.getElementById('rosesImage'))">Génerer la palette</button>
                </div>
            </form>
        </div>
    </div> 

    <!--début du footer-->
    <footer class="footer">
        <div class="row">
            <div class="footer-col">
                <h4>Me contacter</h4>
                <ul>
                    <li><a href="#">Nissrine Ben Ayou</a></li>
                </ul>
                <ul>
                    <li><a href="#">n.ben-ayou@univ-lyon2.fr</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <!--fin du footer-->
</body>
</html>