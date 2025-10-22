const canvas = document.createElement("canvas"); //canvas pour couleurs.php
const ctx = canvas.getContext("2d"); //contexte pour le canvas
const inputImage = document.getElementById("inputImage"); //image dans ajouter.php
const rosesImage = document.getElementById("rosesImage"); //image dans couleurs.php
const xMin = 0, xMax=255; //initialisation des valeurs pour la fonction generePaletteCouleur
const yMin = 0, yMax=255;
const zMin = 0, zMax=255;



imageInput.addEventListener("change",function(){ //affichage de l'image de l'input utilisateur
    let file = this.files[0];
    let reader = new FileReader();
    reader.onload = function(e) {
        inputImage.src = e.target.result;
        let image = new Image();
        image.src = inputImage.src;
    }
    reader.readAsDataURL(file);
});


function generePaletteCouleur(img){ //génère la palette de couleurs
    let couleurs = getimageCouleurs(img); //obtiens toutes les couleurs de l'image
    const tailleZone = 110;
    let forteDensite = zonesDensite(couleurs,tailleZone);//obtiens les 12 zones aux couleurs les plus denses
    const couleurMoy = forteDensite.map(([i,j,k])=>{ //pour chaque cube
        const rMin = i*tailleZone+xMin; //on calcule les bornes min et max pour r g et b
        const rMax = rMin+tailleZone;
        const gMin = j*tailleZone+yMin;
        const gMax = gMin+tailleZone;
        const bMin = k*tailleZone+zMin;
        const bMax = bMin + tailleZone;
        const couleursZones = couleurs.filter(([r,g,b])=> //isole les pixels [r, g, b] qui tombent à l’intérieur de ce cube.
            r>=rMin && r<rMax &&
            g>=gMin && g<gMax &&
            b>=bMin && b<bMax
        );
        const sum = couleursZones.reduce(([rSum,gSum,bSum],[r,g,b])=>[ //additionne séparément toutes les composantes rgb
            rSum + r,
            gSum + g,
            bSum + b
        ],[0,0,0]);
        return sum.map(x=>x/couleursZones.length); // moyenne des couleurs du cube
    });

    fillColorPalette(couleurMoy); //renvoie un tableau [r, g, b] de la teinte moyenne des 12 cubes
};

function getimageCouleurs(img){ //Renvoie la valeur de chaque pixel de l'image au format [r, g, b]
    canvas.width = img.width; // on ajuste la taille du canvas à celle de l'image
    canvas.height = img.height;
    ctx.drawImage(img,0,0); //l'image est peinte sur le canvas
    const imageData = ctx.getImageData(0,0,canvas.width,canvas.height); //on récupère les valeurs rgba de chaque pixel sur le canvas
    const data = imageData.data;
    const imageCouleurs = [];
    for (let i=0;i<data.length;i+=4){ //De 4 en 4 pour ignorer les valeurs alpha (opacité) qui reste constante
        const r=data[i];
        const g=data[i+1];
        const b = data[i+2];
        imageCouleurs.push([r,g,b]); //on ajoute dans imageCouleurs la valeur rgb de chaque pixel de l'image
    }
    return imageCouleurs;
};

function zonesDensite(couleurs,tailleZone,n=12){ //repérer les zones de plus forte densité de couleurs
    const xLength = Math.ceil((xMax-xMin)/tailleZone); //divise l’espace en cubes 3D (grille de voxels) pour répartir les couleurs dedans.
    const yLength = Math.ceil((yMax-yMin)/tailleZone);
    const zLength = Math.ceil((zMax-zMin)/tailleZone);
    const counts = new Array(xLength);
    for (let i=0;i<xLength;i++){
        counts[i] = new Array(xLength); //tableau 3D counts[i][j][k] où chaque case = nb couleurs tombant dans ce cube [i, j, k].
        for(let j=0;j<yLength;j++){
            counts[i][j] = new Array(zLength).fill(0);
        }
    }
    for (let p=0;p<couleurs.length;p++){//pour chaque couleur 
        const [r,g,b] = couleurs[p];
        const i= Math.floor((r-xMin)/tailleZone); //Chaque couleur est localisée dans le cube qui lui correspond
        const j= Math.floor((g-yMin)/tailleZone);
        const k= Math.floor((b-zMin)/tailleZone);

        counts[i][j][k]++; // on incrémente le compteur de ce cube.
    }
    let indicesAndCounts = []; //liste avec les coordonnées de chaque cube et leur densité (nombre de couleurs).
    for (let i=0;i<xLength;i++){
        for (let j=0;j<yLength;j++){
            for (let k=0;k<zLength;k++){
                indicesAndCounts.push({index:[i,j,k],count:counts[i][j][k]});
            }
        }
    }
    indicesAndCounts.sort((a,b)=>b.count-a.count); //tri du plus dense au moins dense, et on garde les 12 premiers.
    return indicesAndCounts.slice(0,n).map(x=>x.index); //on renvoie les 12 cubes les plus denses de l'image
};

function fillColorPalette(couleurMoy){ //affiche chaque couleur moyenne rgb dans les 12 <div> de couleurs.php (case1, case2 etc)
    let colorCount = couleurMoy.reduce((acc,rgb)=>acc+rgb.every(value=>isNaN(value)),0); //compte le nb d’éléments invalides dans couleurMoy

    for(let i=1;i<=couleurMoy.length;i++){//pour chaque <div> case dans couleurs.php
        let divId = "case"+i;
        let div = document.getElementById(divId); //récupère la <div>
        let color = couleurMoy[i-1]; //récupère la couleur correspondante
        div.style.backgroundColor = `rgb(${color[0]},${color[1]},${color[2]})`; //la case prend la couleur comme fond
        div.title = `rgb(${Math.round(color[0])},${Math.round(color[1])},${Math.round(color[2])})`; //affiche la valeur rgb au survol de la case
        if(isNaN(color[0]))
            div.style.width = "0%"; //Si la couleur de la case est invalide, on la rend invisible
        else
         div.style.width = (100/(12-colorCount))+"%"; //sinon on répartit les cases à une largeur équitable
    }
};



function colorPick(event){ //obtiens la valeur rgb du pixel cliqué sur l'image
    var canvas = document.getElementById("myCanvas");
    var context = canvas.getContext("2d");
    canvas.width = rosesImage.width;
    canvas.height = rosesImage.height;
    context.drawImage(rosesImage, 0, 0); //créé un canvas identique à l'image
    var canvasX = event.offsetX; //valeur x du pixel cliqué à la souris sur l'image
    var canvasY = event.offsetY; //valeur y du pixel cliqué à la souris sur l'image
    var pixelData = context.getImageData(canvasX, canvasY, 1, 1); //obtiens la couleur du pixel cliqué
    imgData = pixelData.data;
    let pixelColor = "rgb(" + imgData[0] + ", " + imgData[1] + ", " + imgData[2] + ")";
    document.getElementById("pickedColor").style.backgroundColor = pixelColor; //on donne la couleur cliqué au div pickedColor de couleurs.php
    document.getElementById("pxValue").innerText = pixelColor; //on ajoute dans la balise <p> la valeur du pixel au format rgb
}