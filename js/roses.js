var previewContainer = document.querySelector('.fleurs.preview'); //toutes les vignettes de roses
var previewBox = previewContainer.querySelectorAll('.preview');


document.querySelectorAll('.roses-container .fleur').forEach(fleur =>{ //pour chaque box avec image de rose
    fleur.onclick = () =>{ //Si on clique sur la boite
        previewContainer.style.display = 'flex'; //on affiche la boite de vignette
        var name = fleur.getAttribute('data-name'); //on obtient l'id de l'image
        previewBox.forEach(preview =>{
            var target = preview.getAttribute('data-target'); //on obtien l'id de toutes les vignettes
            if(name == target){ //on cherche celle qui correspond à l'image
                preview.classList.add('active'); //on l'affiche
            }
        });
    };
});

function getImgSrc(){ //stocke le chemin source de l'image dans le cookie imgpath pour qu'il soit utilisé dans couleurs.php
    var imageC = this.parentElement.querySelector("img");
    var imgPath = imageC.src;
    document.cookie = "imgpath = " + imgPath;
}

//obtenir le chemin source de l'image vignette quand l'utilisateur clique sur le bouton "examiner"
document.querySelectorAll(".buttons").forEach(button => { 
  button.addEventListener("click", getImgSrc)
})

//Pour fermer la vignette avec la croix
previewBox.forEach(close =>{
    close.querySelector('.fa-times').onclick = () =>{ //si on clique sur le X alors :
        close.classList.remove('active'); //la vignette n'est plus active
        previewContainer.style.display = 'none'; //on la rend invisible
    };
});

//Fonction pour les filtres de recherche
function filterRoses(category) {
    var buttons = document.querySelectorAll("button"); //valeur du filtre sélectionné
    var rose = document.querySelectorAll('.fleur'); //toutes les vignettes de la bdd
    buttons.forEach((button) => {
        if(category == button.innerText){ //Si le filtre est selectionné, il devient actif (couleur plus claire)
            button.classList.add("active");
        }
        else{
            button.classList.remove("active"); //sinon il ne l'est plus
        }
    });
    
    rose.forEach(variete => { //parcours chaque vignette
        if (category === 'all' || variete.getAttribute('mini-desc') === category || variete.getAttribute('couleurF').toLowerCase().includes(category)) { //Si filtre=='Tout' OU filtre==Type de la variété :
            variete.style.display = 'inline-block'; //on affiche l'image
        } else {
            variete.style.display = 'none'; //Sinon, on la cache
        }
    });
}
