
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginRight = "250px";

        }
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginRight = "0";

        }


        function checkLogs() {
            //console.log("sending :", "/puppyCo/Model/rest.php?/user/connect/"+$("#email").val()+"/"+$("#mdp").val());
            $.ajax({
                url: "/puppyCo/Model/rest.php?/user/connect/"+$("#email").val()+"/"+$("#mdp").val(),
                type: "GET",
                success: function (data) {
                    alert(data);
                    closeNav();
                },
                error: function (resultat, statut, erreur) {
                    console.log('erreur', erreur);
                }
            });
        }




        function loadCategories() {
            $.ajax({
                url: "/puppyCo/Model/rest.php?/category/read",
                type: "GET",
                success: function (data) {
                    var dataJson = JSON.parse(data);
                    console.log(dataJson);
                    $(dataJson).each(function (index, element) {
                        $("#listCategories").append('<a href="/PuppyCo/view/gestionProduits.html?method=getByCategory&category='+element['nom']+'">'+element['nom']+'</a>');
                    });
                },
                error: function (resultat, statut, erreur) {
                    console.log('erreur', erreur);
                }
            });
        }








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

