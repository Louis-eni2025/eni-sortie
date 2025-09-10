document.addEventListener("DOMContentLoaded",async function(){
    console.log("ghelelefllael")
    let champsLieu = document.getElementById("creer_sortie_lieu")
    let lieuId = champsLieu.value
    try{
        const lieu = await getLieu(lieuId)
        const champsRue = document.getElementById("creer_sortie_rue")
        champsRue.value = lieu.rue;
        const champsCpo = document.getElementById("creer_sortie_code_postal")
        champsCpo.value = lieu.ville.codePostal;
        const champsLatitude = document.getElementById("creer_sortie_latitude")
        champsLatitude.value = lieu.latitude
        const champsLongitude = document.getElementById("creer_sortie_longitude")
        champsLongitude.value = lieu.longitude

    }catch (e){
        console.error(e)
    }
    champsLieu.addEventListener("change",async function(){
        let lieuId = this.value
        try{
            const lieu = await getLieu(lieuId)
            const champsRue = document.getElementById("creer_sortie_rue")
            champsRue.value = lieu.rue;
            const champsCpo = document.getElementById("creer_sortie_code_postal")
            champsCpo.value = lieu.ville.codePostal;
            const champsLatitude = document.getElementById("creer_sortie_latitude")
            champsLatitude.value = lieu.latitude
            const champsLongitude = document.getElementById("creer_sortie_longitude")
            champsLongitude.value = lieu.longitude

        }catch (e){
            console.error(e)
        }
    })
})

async function getLieu(id){

    try {
        const response = await fetch(`/eni-sortie/public/api/lieu/${id}`,{
            method:'GET',
            headers:{
                'Content-Type':'application/json',
            }
        });

        if(!response.ok){
            throw new Error(`Erreur http: ${response.status}  ${response.statusText}`)
        }

        const data = await response.json();
        return data;

    }catch (e){
        console.error('Erreur lors de la récupération du lieu',e);
        throw e;
    }

}

