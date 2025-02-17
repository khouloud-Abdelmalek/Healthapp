document.addEventListener("DOMContentLoaded", function() {
    console.log("Le fichier script.js est bien chargé !");

    let btn = document.getElementById("btn-test");
    if (btn) {
        btn.addEventListener("click", function() {
            alert("Bouton cliqué !");
        });
    }
});
