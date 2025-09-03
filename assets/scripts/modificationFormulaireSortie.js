document.addEventListener("DOMContentLoaded",function(){
    let champsLieu = document.getElementById("sortie_lieu")

    champsLieu.addEventListener("change",function(){
        let lieuId = this.value
        console.log(lieuId)
    })
})

async function getLieu(id){

}

