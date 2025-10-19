<?php
    include('connection.php');
    if(isset($_POST['submit'])){
        //on verifie si la variete existe deja dans la base
        $errorMSG = "";
        $dateP = date("Y/m/d");
        $auteur = 2;
        $nomVariete = $_POST["Nom"];
        $nomColle = str_replace(' ', '', $nomVariete);
        $sql = "SELECT NomDossier FROM photos WHERE NomDossier LIKE '%$nomColle';";
        $result = mysqli_query($conn, $sql);
        $dejaPresent = mysqli_num_rows($result);
        if($nomVariete=="" || $_FILES['image']['size'] == 0){
            $errorMSG = "<h2>Erreur : Veuillez entrer un nom de variété et une image.</h2>";
        }
        else if($dejaPresent == 0){ //Si la variété n'est pas déjà présente
            //formatage du nom entré par l'utilisateur au format NomDossier de la table varietes ("123_NomDeVariete")

            //retire les accents
            $nomFleur = strtr(utf8_decode($_POST["Nom"]), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            //retire les apostrophes
            $nomFleur = str_replace("'", '', $nomFleur);
            //tout mettre en minuscule
            $nomFleur = strtolower($nomFleur);
            //mettre la chaine dans un tableau ou chaque espace donne une nouvelle colonne
            $chaine = explode(" ", $nomFleur);
            $nomFleur = "";
            for($i = 0; $i < count($chaine); $i++){ //pour chaque mot de la chaine
                $nomFleur = $nomFleur . ucfirst($chaine[$i]); //concaténer tous les mots dans une nouvelle chaine sans espaces et en mettant la premiere lettre en majuscule
            }

            //nouvel id variete = id maximum + 1
            $sql = "SELECT MAX(IdVariete) AS max_id FROM varietes;";
            $result = mysqli_query($conn, $sql);
            $id = mysqli_fetch_assoc($result);
            $max_id = $id['max_id'];
            $max_id++;

            //nouvel id photo
            $sql = "SELECT MAX(IdPhoto) AS max_idp FROM photos;";
            $result = mysqli_query($conn, $sql);
            $idp = mysqli_fetch_assoc($result);
            $max_idp = $idp['max_idp'];
            $max_idp++;

            //insertion de la photo dans la table photos
            $file_name = $_FILES['image']['name'];
            $tempname = $_FILES['image']['tmp_name'];
            $dossier = $max_id ."_" . $nomFleur;
            $folder = 'images/'. $max_id ."_" . $nomFleur;
            mkdir($folder);
            $folder = $folder . "/" . $file_name;
            $query = mysqli_query($conn, "Insert into photos (IdPhoto, NomFichier, NomDossier, SubjectDistance, Date, IdAuteur) values ('$max_idp', '$file_name', '$dossier', 'Unavailable', '$dateP', '$auteur')");  

            //insertion des données du formulaire dans la bdd
            function verif($formName){
                if (isset($_POST[$formName])) {
                    return $_POST[$formName];
                } else {
                    return NULL; //envoie NULL si le champs du formulaire est vide
                }
            }

            //vérifie tous les champs du formulaire
            $type = verif("Type");
            $couleur = verif("Couleur");
            $petales = verif("NbPetales");
            $hauteurMin = verif("HauteurMin");
            $hauteurMax = verif("HauteurMax");
            $largeurMin = verif("LargeurMin");
            $largeurMax = verif("LargeurMax");
            $diametre = verif("DiametreFleur");
            $parfum = verif("Parfume");
            $remontant = verif("Remontant");
            $maladie = verif("Maladies");
            $commentaire = verif("Commentaires");

            $query = mysqli_query($conn, "Insert into varietes (IdVariete, Nom, Type, Couleur, NbPetales, HauteurMin, HauteurMax, LargeurMin, LargeurMax, DiametreFleur, Parfume, Remontant, Maladies, Commentaires) values ('$max_id', '$nomVariete', '$type', '$couleur', '$petales', '$hauteurMin', '$hauteurMax', '$largeurMin', '$largeurMax', '$diametre', '$parfum', '$remontant', '$maladie', '$commentaire')");

        } else{ //Si la variété est déjà présente
            $row = mysqli_fetch_assoc($result);
            $dossier = $row["NomDossier"]; //on récupère seulement le nom du dossier déjà présent
            $file_name = $_FILES['image']['name'];
            $tempname = $_FILES['image']['tmp_name'];

            $folder = 'images/' . $dossier . "/" . $file_name;

            //insertion des données dans la table photos
            $query = mysqli_query($conn, "Insert into photos (NomFichier, NomDossier, SubjectDistance, Date, IdAuteur) values ('$file_name', '$dossier', 'Unavailable', '$dateP', '$auteur')");  
        }
 
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter</title>
    <script src="https://kit.fontawesome.com/420e530b7e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <script defer src="js/traitement.js"></script>
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
            <a href="roses.php">Roses</a>
            <a href="ajout.php" id="currpage">Ajouter</a>
        </nav>
    </header>
    <!--fin du header-->

    <div class="contain">
        <div class="title">
            <h1>Ajouter une image</h1>
        </div>
        <div id="message_line1" class="message">
            <?php  if(isset($_POST['submit'])){
                        global $tempname;
                        global $folder;
                        global $errorMSG;     
                        if(move_uploaded_file($tempname, $folder)){
                            $errorMSG = "<h2>L'image à été enregistrée</h2>";
                        }
                        echo $errorMSG; }?>
                    
        </div>
    </div>

    <div class="ajouter">
        <div class="nouvImage">
            <div class="imageContainer">
                <img id="inputImage" src="images/roses-rainbow.png" alt="Photographie de roses à ajouter à la base de données"/>
            </div>
        </div>

        <div class="inner">
            <!--Formulaire pour ajouter une image-->
            <form method="POST" enctype="multipart/form-data">
                <h3>Ajouter une image au site</h3>
                <div class="form-groupe">
                    <input type="text" id="Nom" name="Nom" placeholder="Nom De La Variété *" class="form-controle">
                    <select name="Type" class="form-controle">
                        <option value="" disabled selected>Type</option>
                        <option value="Arbuste">Arbuste</option>
                        <option value="Buisson">Buisson</option>
                        <option value="Couvre Sol">Couvre Sol</option>
                        <option value="Fleurs groupées">Fleurs groupées</option>
                        <option value="Floribunda">Floribunda</option>
                        <option value="Grande fleur">Grande fleur</option>
                        <option value="Grandiflora">Grandiflora</option>
                        <option value="Grimpant">Grimpant</option>
                        <option value="Hybrid Tea">Hybrid Tea</option>
                        <option value="Liane">Liane</option>
                        <option value="Miniature">Miniature</option>
                        <option value="Polyantha">Polyantha</option>
                    </select>
                </div>

                <div class="form-groupe">
                    <input type="text" name="Couleur" placeholder="Couleur" class="form-controle">
                    <input type="text" name="NbPetales" placeholder="Nombre de Pétales" class="form-controle">
                </div>

                <div class="form-groupe">
                    <input type="text" name="HauteurMin" placeholder="Hauteur min (cm)" class="form-controle">
                    <input type="text" name="HauteurMax" placeholder="Hauteur max (cm)" class="form-controle">
                </div>

                <div class="form-groupe">
                    <input type="text" name="LargeurMin" placeholder="Largeur min (cm)" class="form-controle">
                    <input type="text" name="LargeurMax" placeholder="Largeur max (cm)" class="form-controle">
                </div>

                <div class="form-groupe">
                    <input type="text" name="DiametreFleur" placeholder="Diamètre (cm)" class="form-controle">
                    <select name="Parfume" class="form-controle">
                        <option value="" disabled selected>Parfum</option>
                        <option value="Très parfumée">Très parfumé</option>
                        <option value="Parfumé">Parfumé</option>
                        <option value="Leger parfum">Léger parfum</option>
                        <option value="Non">Non</option>
                    </select>
                </div>
                
                <div class="form-ligne">
                    <div class="paragraph"><p>Remontant</p></div><br>
                    <input type="radio" id="RemontantOui" name="Remontant" value="Oui">
                    <label for="RemontantOui">Oui</label>
                    <input type="radio" id="RemontantNon" name="Remontant" value="Non">
                    <label for="RemontantNon">Non</label>
                </div> <br> <br> <br>

                <div class="form-ligne">
                    <select name="Maladies" class="form-controle">
                        <option value="" disabled selected>Résistance aux maladies</option>
                        <option value="Tres resistant">Très résistant</option>
                        <option value="Résistant aux maladies">Résistant</option>
                    </select>
                </div> <br>
                
                <div class="form-ligne">
                    <textarea name="Commentaires" rows="3" cols="50" placeholder=" Commentaire..." maxlength="85"></textarea>
                </div>

                <div class="form-groupe">
                <input type="file" id="imageInput" name="image" accept=".jpg" style="display: none;"> <br> <br>
                <label for="imageInput" class="customFileInput">Sélectionnez une image</label>
                <button type="submit" name="submit">Enregistrer</button>
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