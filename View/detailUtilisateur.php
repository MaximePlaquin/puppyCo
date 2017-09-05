<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Detail utilisateur</title>
    <link rel="stylesheet" href="styleDetailUtil.css">
</head>

<body>
    <div class="login">
        <div class="login-form">
            <div class="login-screen">
                <div class="control-group">
                    <input type="text" class="login-field" placeholder="mail" id="login-mail">
                    <label class="login-field-icon fui-user" for="login-mail">Mail</label>
                </div>

                <div class="control-group">
                    <input type="text" class="login-field" placeholder="status" id="login-status">
                    <label class="login-field-icon fui-lock" for="login-status">status</label>
                </div>
                <div class="control-group">
                    <input type="text" class="login-field" placeholder="address" id="login-address">
                    <label class="login-field-icon fui-lock" for="login-address">address</label>
                </div>
                <div class="control-group">
                    <input type="text" class="login-field" placeholder="type_cb" id="login-typecb">
                    <label class="login-field-icon fui-lock" for="login-typecb">type_cb</label>
                </div>
                <div class="control-group">
                    <input type="text" class="login-field" placeholder="num-cb" id="login-numcb">
                    <label class="login-field-icon fui-lock" for="login-numcb">num_cb</label>
                </div>
                <div class="control-group">
                    <input type="text" class="login-field" placeholder="crypto" id="login-crypto">
                    <label class="login-field-icon fui-lock" for="login-crypto">crypto</label>
                </div>
                <div class="control-group">
                    <input type="text" class="login-field" placeholder="city" id="login-city">
                    <label class="login-field-icon fui-lock" for="login-city">city</label>
                <div class="control-group">
                    <input type="text" class="login-field" placeholder="postal code" id="login-postalcode">
                    <label class="login-field-icon fui-lock" for="login-postalcode">code postal</label>
                </div>
                </div>
                <input type="" class="btn btn-primary btn-large btn-block" value="Annuler" onclick='window.location.href = "gestionUtilisateur.html"' />
                <input type="submit" class="btn btn-primary btn-large btn-block" value="supprimer" onclick="deleteUser()" />
                <input type="submit" class="btn btn-primary btn-large btn-block" value="Valider les changements" onclick="updateUser()" />
            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-2.2.4.min.js"integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="crossorigin="anonymous"></script>
    <script>
        var userId;
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
            function deleteUser(){
                $.ajax({
                    url: "/puppyCo/Model/rest.php?/user/delete/"+getUrlParameter('id'),
                    type: "DELETE",
                    success: function (data) {
                        console.log(data);
                        alert('utilisateur supprimé !');
                        window.location.href = "gestionUtilisateur.html";
                    },
                    error: function (resultat, statut, erreur) {
                        console.log('erreur');
                    }
                });
            }
               function updateUser(){
                $.ajax({
                    url: "/puppyCo/Model/rest.php?/user/update/"+userId+"/"+$('#login-mail').val()+"/"+$('#login-status').val()+"/"+$('#login-address').val()+"/"+$('#login-typecb').val()+"/"+$('#login-numcb').val()+"/"+$('#login-crypto').val()+"/"+$('#login-postalcode').val()+"/"+$('#login-city').val(),
                    type: "PUT",
                    success: function (data) {
                        alert('utilisateur mis à jour !');
                        window.location.href = "gestionUtilisateur.html";
                    },
                    error: function (resultat, statut, erreur) {
                        console.log('erreur');
                    }
                });
            }
        $(document).ready(function () {
            userId = getUrlParameter('id');
            $.ajax({
                url: "/puppyCo/Model/rest.php?/user/read/"+getUrlParameter('id'),
                type: "GET",
                success: function (data) {
                    var dataJson = JSON.parse(data);
                    console.log(dataJson);
                    $(dataJson).each(function(index, element){
                        $('#login-mail').val(element['MAIL']);
                        $('#login-status').val(element['STATUS']);
                        $('#login-address').val(element['ADDRESS']);
                        $('#login-typecb').val(element['TYPE_CB']);
                        $('#login-numcb').val(element['NUM_CB']);
                        $('#login-postalcode').val(element['POSTAL_CODE']);
                        $('#login-crypto').val(element['CRYPTO']);
                        $('#login-city').val(element['CITY']);
                    });
                },
                error: function (resultat, statut, erreur) {
                    console.log('erreur', erreur);
                }
            });

        });

    </script>
</body>

</html>