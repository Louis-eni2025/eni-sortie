document.addEventListener('DOMContentLoaded', function(){
    let inscriptionsLinks = document.getElementsByClassName('sortie_inscription');
    let myModal = new bootstrap.Modal(document.getElementById('modalRetourAction'), {});

    document.getElementById('modalRetourAction').addEventListener('hidden.bs.modal', event => {
        location.reload();
    })

    Array.from(inscriptionsLinks).forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            let sortieId = this.dataset.id;
            fetch('/eni-sortie/public/api/inscription/' + sortieId, {
                method: 'get'

            }).then(response => response.json())
            .then(data => {
                document.getElementById("modalRetourActionContent").innerText = data.message;
                myModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        })
    })

    let desinscriptionsLinks = document.getElementsByClassName('sortie_desinscription');

    Array.from(desinscriptionsLinks).forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            let sortieId = this.dataset.id;
            fetch('/eni-sortie/public/api/desinscription/' + sortieId, {
                method: 'get'

            }).then(response => response.json())
            .then(data => {
                document.getElementById("modalRetourActionContent").innerText = data.message;
                let myModal = new bootstrap.Modal(document.getElementById('modalRetourAction'), {});
                myModal.show();

                // location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        })
    })
})