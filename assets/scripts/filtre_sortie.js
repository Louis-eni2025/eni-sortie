document.addEventListener('DOMContentLoaded', function(){

    let sorties = [...document.getElementsByClassName("ligne_sortie")];

    document.getElementById("form_sortie_nom").addEventListener("input", function(){
        gestionFiltres(sorties)
    })

    document.getElementById("form_sortie_campus").addEventListener("change", function(){
        gestionFiltres(sorties)
    })

    document.getElementById("form_sortie_date_fin").addEventListener("change", function(){
        gestionFiltres(sorties)
    })
    document.getElementById("form_sortie_date_debut").addEventListener("change", function(){
        gestionFiltres(sorties)
    })

    document.getElementById("form_sortie_organisateur").addEventListener("change", function(){
        gestionFiltres(sorties)
    })
    document.getElementById("form_sortie_non_inscrit").addEventListener("click", function(){
        document.getElementById("form_sortie_inscrit").checked = false;
        gestionFiltres(sorties)
    })
    document.getElementById("form_sortie_inscrit").addEventListener("click", function(){
        document.getElementById("form_sortie_non_inscrit").checked = false;
        gestionFiltres(sorties)
    })
    document.getElementById("form_sortie_passee").addEventListener("click", function(){
        gestionFiltres(sorties)
    })

    function gestionFiltres(sorties){
        reinitialiserAffichage();
        gestionFiltreCampus(sorties);
        gestionFiltreNom(sorties);
        gestionFiltreDate(sorties);
        gestionOrganisateur(sorties);
        gestionParticipant(sorties)
        gestionNonParticipant(sorties)
        gestionSortiePassee(sorties)
    }

    function gestionFiltreNom(sorties){
        let value = document.getElementById("form_sortie_nom").value;

        if(value !== "") {
            sorties.forEach(sortie => {
                if(!sortie.dataset.nom.toLowerCase().includes(value.toLowerCase())){
                    sortie.style.display = "none";
                }
            });
        }
    }
    function gestionFiltreDate(sorties){
        let dateDebut = Math.floor(Date.parse(document.getElementById("form_sortie_date_debut").value + " 00:00:00") / 1000);
        let dateFin = Math.floor(Date.parse(document.getElementById("form_sortie_date_fin").value + " 00:00:00") / 1000);

        if(dateDebut !== "") {
            sorties.forEach(sortie => {
                if(sortie.dataset.date < dateDebut){
                    sortie.style.display = "none";
                }
            });
        }

        if(dateFin !== "") {

            sorties.forEach(sortie => {
                if(sortie.dataset.date > dateFin){
                    sortie.style.display = "none";
                }
            });
        }

    }

    function gestionFiltreCampus(sorties){
        let value = document.getElementById("form_sortie_campus").value;

        if(value !== "") {
            sorties.forEach(sortie => {
                if(sortie.dataset.campus !== value){
                    sortie.style.display = "none";
                }
            });
        }
    }

    function reinitialiserAffichage(){
        sorties.forEach(sortie => {
            sortie.style.display = "";
        })
    }

    function gestionOrganisateur(sorties){
        let value = document.getElementById("form_sortie_organisateur").checked;

        if(value)
        {
            sorties.forEach(sortie => {
                if(sortie.dataset.organisateur === '0'){
                    sortie.style.display = "none";
                }
            });
        }
    }

    function gestionParticipant(sorties){
        let participant = document.getElementById("form_sortie_inscrit").checked;

        if(participant) {
            sorties.forEach(sortie => {
                if(sortie.dataset.participant === '0'){
                    sortie.style.display = "none";
                }
            });
        }

    }

    function gestionNonParticipant(sorties){
        let nonParticipant = document.getElementById("form_sortie_non_inscrit").checked;

        if(nonParticipant)
        {
            sorties.forEach(sortie => {
                if(sortie.dataset.participant === '1'){
                    sortie.style.display = "none";
                }
            });
        }
    }

    function gestionSortiePassee(sorties){
        let sortiePassee = document.getElementById("form_sortie_passee").checked;
        let formDateDebut= document.getElementById('form_sortie_date_debut');
        let formDateFin = document.getElementById('form_sortie_date_fin');

        const now = Date.now();
        const date = new Date(now - 24 * 60 * 60 * 1000); // Aujourd'hui - 1 jour
        const maxDate = date.toISOString().split('T')[0];

        if(sortiePassee)
        {
            sorties.forEach(sortie => {
                if(sortie.dataset.date > Math.floor(now / 1000)){
                    sortie.style.display = "none";
                }
            });

            formDateDebut.max = maxDate;
            formDateFin.max = maxDate;
        } else {
            formDateDebut.removeAttribute("max");
            formDateFin.removeAttribute("max");
        }
    }
})