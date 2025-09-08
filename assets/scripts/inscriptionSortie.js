document.addEventListener('DOMContentLoaded', function(){
    let inscriptionsLinks = document.getElementsByClassName('sortie_inscription');

    Array.from(inscriptionsLinks).forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            let sortieId = this.dataset.id;
            fetch('/eni-sortir/public/api/inscription/' + sortieId, {
                method: 'get'

            }).then(response => response.json())
            .then(data => {
                console.log(data)

                location.reload();
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
            fetch('/eni-sortir/public/api/desinscription/' + sortieId, {
                method: 'get'

            }).then(response => response.json())
            .then(data => {
                console.log(data)
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        })
    })
})