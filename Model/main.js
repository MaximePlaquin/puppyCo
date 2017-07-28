$(document).ready(function () {
    $('#formulaireC').on('submit', function (e) {
        var mail = $('#login-mail').val();
        var mdp = $('#login-password').val();


        var req = 'mail=' + mail + '&' + 'password=' + mdp;

        e.preventDefault();

        var $this = $(this);
        if (mail === '' || mdp === '') {
            alert('Les champs doivent êtres remplis');
        } else {
            $.ajax({
                url: $this.attr('action'),
                type: $this.attr('method'),
                data: req,
                success: function (data) {
                    console.log(data);
                    console.log('Requête Envoyé');
                    setTimeout(function(){ 
                    alert("Login correct"); 
                     window.location.href = "gestionUtilisateur.html";
                    }, 1000);
                },
                error: function (resultat, statut, erreur) {
                    console.log('erreur');
                    alert('Login incorrect');
                }
            });
        }
    });
});

 