document.addEventListener('DOMContentLoaded', function(){
    let publicationLinks = document.getElementsByClassName('sortie_publication');
    let myModal = new bootstrap.Modal(document.getElementById('modalRetourAction'), {});

    document.getElementById('modalRetourAction').addEventListener('hidden.bs.modal', event => {
        location.reload();
    })

    Array.from(publicationLinks).forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            let sortieId = this.dataset.id;
            fetch('/eni-sortie/public/api/publier/' + sortieId, {
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

})