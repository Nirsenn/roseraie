<?php
session_start();
require_once 'connection.php';
$sql = "SELECT photos.IdPhoto, photos.NomDossier, photos.NomFichier, varietes.Type, varietes.Commentaires, varietes.IdVariete, varietes.Nom, varietes.Couleur
FROM photos JOIN varietes ON varietes.IdVariete LIKE photos.NomDossier + '%' ORDER BY photos.NomDossier ASC;";
$all_roses = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roses</title>
    <script src="https://kit.fontawesome.com/420e530b7e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <script defer src="js/roses.js"></script>
</head>

<body>
    <!-- début du header -->
    <header>
        <input type="checkbox" name="barre" id="barre">
        <label for="barre" class="fas fa-bars"></label>

        <a href="accueil.html" class="logo">Roseraie<span> ❁</span></a>

        <nav class="navbar">
            <a href="accueil.html">Accueil</a>
            <a href="about.html">À propos</a>
            <a href="roses.php" id="currpage">Roses</a>
            <a href="ajout.php">Ajouter</a>
        </nav>
    </header>
    <!--fin du header--> 

    <main>
        <div class="title">
            <h1>Toutes les roses</h1>
        </div>
        <a href="#" class="toTop"><img src="images/circle-up.png" alt="Retourner au haut de page" style = "position: fixed; right: 0; top: 90%; width: 70px;"></a>
        <p id="yey"></p>

        <!--Filtrage par type--> 
        <div class="filters">
            <div class="paragraph"><h3>Filtres</h3>
            <div class="filter-buttons"> <div class="paragraph"><p>Par type</p></div>
                <button class="button-value" onclick="filterRoses('all')">Tout</button>
                <button class="button-value" onclick="filterRoses('Grimpant')">Grimpant</button>
                <button class="button-value" onclick="filterRoses('Buisson')">Buisson</button>
                <button class="button-value" onclick="filterRoses('Liane')">Liane</button>
                <button class="button-value" onclick="filterRoses('Grande fleur')">Grande fleur</button>
                <button class="button-value" onclick="filterRoses('Fleurs groupées')">Fleurs groupées</button>
                <button class="button-value" onclick="filterRoses('Polyantha')">Polyantha</button>
                <button class="button-value" onclick="filterRoses('Arbuste')">Arbuste</button>
                <button class="button-value" onclick="filterRoses('Couvre sol')">Couvre sol</button>
                <button class="button-value" onclick="filterRoses('Miniature')">Miniature</button> 
                 
                <div class="paragraph"><p>Par couleur</p></div>
                <button class="button-rouge" onclick="filterRoses('rouge')">rouge</button>
                <button class="button-jaune" onclick="filterRoses('jaune')">jaune</button>
                <button class="button-rose" onclick="filterRoses('rose')">rose</button>
                <button class="button-blanc" onclick="filterRoses('blanc')">blanc</button>
                <button class="button-orange" onclick="filterRoses('orange')">orange</button>
                <button class="button-violet" onclick="filterRoses('violet')">violet</button>
            </div>
        </div>

        
        <div classe="style-r"> <!--Les photographies de roses --> 
            <div class="container">
                <!--Vignettes afichées par lignes de 3--> 
                <div class="roses-container">
                    <?php
                    while($row = mysqli_fetch_assoc($all_roses)){ //Pour chaque image dans la BDD : 
                    ?>
                    <div class="fleur" data-name="<?php echo $row["IdPhoto"]; ?>" mini-desc="<?php echo $row["Type"];?>" couleurF="<?php echo $row["Couleur"];?>">
                        <img src="<?php echo "images/" . $row["NomDossier"] . "/" . $row["NomFichier"]; ?>" alt="Photographie de <?php echo $row["Nom"]; ?>">
                        <h3><?php echo $row["Nom"]; ?></h3>
                    </div>
                    <?php
                    }
                    ?>
                </div>

                <!--Fenêtre pop up--> 
                <div class="fleurs preview">
                    <?php
                    $sql = "SELECT photos.IdPhoto, photos.NomDossier, photos.NomFichier, varietes.Type, varietes.Commentaires, varietes.IdVariete, varietes.Nom, varietes.Couleur, varietes.DiametreFleur, varietes.HauteurMin, varietes.HauteurMax, varietes.LargeurMin, varietes.LargeurMax, varietes.Parfume, varietes.Maladies, varietes.NbPetales 
                    FROM photos JOIN varietes ON varietes.IdVariete LIKE photos.NomDossier + '%' ORDER BY photos.NomDossier ASC;";
                    $all_roses = $conn->query($sql);
                    while($row = mysqli_fetch_assoc($all_roses)){ //Pour chaque image dans la BDD : 
                    ?>
                    <div class="preview" data-target="<?php echo $row["IdPhoto"]; ?>">
                        <div class="previewG">
                            <i class="fas fa-times"></i>
                            <img src="<?php echo "images/" . $row["NomDossier"] . "/" . $row["NomFichier"];?>" alt="Photographie de <?php echo $row["Nom"]; ?>">
                            <h3><?php echo $row["Nom"] ;?></h3>
                            <div class="buttons">
                                <a href="couleurs.php" class="modification" onclick="<?php if (!isset($_SESSION["path"]) && isset($_COOKIE["imgpath"])){
                                                                                                $_SESSION["path"] = $_COOKIE["imgpath"];
                                                                                            } ?>">examiner</a>
                            </div>
                        </div>
                        <div> <br> <br>
                            <p><b>Type : </b><?php echo $row["Type"] ;?>     |     <b>Couleur : </b><?php echo $row["Couleur"] ; ?></p>
                            <p><b>Diamètre : </b><?php echo $row["DiametreFleur"] ; ?> cm     |     <b>Pétales : </b><?php echo $row["NbPetales"] ; ?></p>
                            <p><b>Hauteur : </b><?php echo $row["HauteurMin"] . "cm - " . $row["HauteurMax"] ; ?>cm     |     <b>Largeur : </b><?php echo $row["LargeurMin"] . "cm - " . $row["LargeurMax"] ; ?>cm </p>
                            <p><b>Parfumé : </b><?php echo $row["Parfume"] ; ?>     |     <b>Résistance : </b><?php echo $row["Maladies"] ; ?></p>
                            <p><b>Commentaire : </b><br><?php echo $row["Commentaires"] ; ?></p>
                        </div> 
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <br>
            </div>
        </div>
    </main>

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