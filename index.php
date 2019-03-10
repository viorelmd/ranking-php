<?php
    include('config.php');
    $page = isset($_GET['p']) ? $_GET['p'] : 1 ;
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?=$nameWebsite;?> Ranking</title>
    <meta name="description" content="Ranking of the best Transformice server called <?=$nameWebsite;?>.">
    <meta name="viewport" content="user-scalable=no, initial-scale = 1, minimum-scale = 1, maximum-scale = 1, width=device-width">
    <meta name="theme-color" content="#222222">
    <meta name="msapplication-navbutton-color" content="#222222">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="robots" content="noindex, nofollow">
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/css/1.27.0.css">
    <script src="/js/1.27.0.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js'></script>
</head>

<body onload="chargerPage();">
    <div id="contenant-corps-et-footer">
        <div id="popup"></div>
                <!--[if lt IE 7]><p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p><![endif]-->
                <div id="barre_navigation" class="navbar navbar-fixed-top navbar-inverse">
                    <div class="navbar-inner">
                        <div class="container-fluid menu-principal ltr">
                            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
                            <a class="brand lien-titre " href="/"><img src="/img/logo_41x18.png" /> <?=$nameWebsite;?> Ranking</a>
                        </div>
                        <div class="container-fluid menu-secondaire">
                            <div class="row-fluid">
                                <div class="span12" id="pages">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="espace_barre_navigation" class="hidden-phone hidden-tablet"></div>
                <script type="text/javascript">
                    majTailleEspaceNavbar();
                </script>
                    <div id="corps" class="corps clear container">
                        <div class="row">
                            <div class="span12">
                                <div class="cadre vertical-center header-rfc">
                                    <p class="" align="center"> <span class="titre-mode-rfc">Ranking</span>
                                        <br> </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span12">
                                <div class="cadre cadre-forum text-align-center">
                                    <table class="table-datatable table-striped table-rfc" id="table">
                                        <thead>
                                            <tr>
                                                <th class="table-sessions-td-header"></th>
                                                <th class="table-sessions-td-header">Username</th>
                                                <th class="table-sessions-td-header">Cheeses</th>
                                                <th class="table-sessions-td-header">Firsts</th>
                                                <th class="table-sessions-td-header">Bootcamps</th>
                                                <th class="table-sessions-td-header">Shaman Saves</th>
                                                <th class="table-sessions-td-header">Hard Mode Saves</th>
                                                <th class="table-sessions-td-header">Divine Mode Saves</th>
                                            </tr>
                                        </thead>
                                        <tbody id="players"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function() {
                            bindAjax();
                        });
                    </script>
                    <footer id="footer" class="footer">
                        <div class="container-fluid">
                            <div class="row-fluid">
                                <div class="span2"> &copy; <?=$nameWebsite;?> 2019 </div>
                            </div>
                        </div>
    </div>
    </footer>
    </div>
    <script type="text/javascript">
        var socket = io('http://<?=$ipServer;?>:<?=$portServer;?>');
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        socket.on('connect', function (data) {
            socket.emit('rank', {"page" : <?=$page?>});
        });
        socket.on('receiveRanking', function (data) {
            socket.close();
            var table = $('#table').DataTable();
            if (<?=$page?> > data["totalPage"]) {
                $("#popup").append('<div id="popup_resultat_requete" class="modal hide fade ltr"><div class="modal-header"><h3>Information</h3> </div><div class="modal-body"><p> The request contains one or more invalid parameters. </p></div><div class="modal-footer"><button type="button" class="btn btn-info" data-dismiss="modal">Ok</button></div></div>');
                $('#popup_resultat_requete').modal('toggle');
            }else {
                $(".dataTables_info").html("Showing 1 to 20 of 20 entries");
                var id = (<?=$page?>-1)*20;
                var text = '';
                text += '<div class="groupe-boutons-barre-gauche hidden-desktop ltr "> </div><div class="groupe-boutons-barre-droite "> <form id="formulaire_pagination" class="form-pagination" action="./" method="GET" autocomplete="off"> <div class="cadre-pagination btn-group ltr">';

                if (<?=$page?> != 1) {
                    text += '<a class="btn btn-inverse " href="./?p=1">«</a><a class="btn btn-inverse " href="./?p=<?=$page - 1?>">‹</a>';
                }
                text += '<a class="btn btn-inverse" href="#" active disabled> <?=$page?> / ' + data['totalPage'] + ' </a> <input class="input-pagination" type="number" name="p" min="1" max="' + data['totalPage'] + '" value="1" onkeypress="if (event.keyCode==13){jQuery(\'#formulaire_pagination\').submit();};"> <button class="btn btn-inverse" onclick="jQuery(\'#formulaire_pagination\').submit();">GO</button>';
                if (<?=$page?> != data["totalPage"]) {
                    text += '<a class="btn btn-inverse " href="./?p=<?=$page + 1?>">&rsaquo;</a> <a class="btn btn-inverse " href="./?p='+ data["totalPage"] +'">&raquo;</a>';
                }
                text += '</div></form> </div>';
                $("#pages").append(text);
                data["ranks"].forEach(function(user){
                    id = id + 1;
                    table.row.add( [
                        id, '<div class="cadre-auteur-message cadre-auteur-message-court element-composant-auteur display-inline-block"> <div class="btn-group bouton-nom max-width"> <a class="dropdown-toggle highlightit" data-toggle="dropdown" href="#"> <span class="element-bouton-profil bouton-profil-nom cadre-type-auteur-joueur "> <img src="img/icones/16/on-offbis'+ user[8] +'.png" alt=""> ' + user[0] + '</span></span> </a> <ul class="dropdown-menu menu-contextuel pull-left"> <table> <tr> <td class="cellule-menu-contextuel"> <ul class="liste-menu-contextuel"> <li class="nav-header"> ' + user[0] + '<span class="nav-header-hashtag">#' + user[1] + '</span></li><li><a class="element-menu-contextuel" href="./p/' + user[0] + '"><img src="./img/icones/16/1profil.png" class="espace-2-2" alt="">Profile</a></li></ul>', '<img class="img32" src="img/rfc/souris/normale.png" alt=""> ' + numberWithCommas(user[2]) + ' ', '<img class="img32" src="img/rfc/souris/normale.png" alt=""> ' + numberWithCommas(user[3]) + ' ', '<img class="img32" src="img/rfc/souris/normale.png" alt=""> ' + numberWithCommas(user[4]) + ' ', '<img class="img32" src="img/rfc/souris/turquoise.png" alt=""> ' + numberWithCommas(user[5]) + ' ', '<img class="img32" src="img/rfc/souris/verte.png" alt=""> ' + numberWithCommas(user[6]) + ' ', '<img class="img32" src="img/rfc/souris/rouge.png" alt=""> ' + numberWithCommas(user[7]) + ' '
                    ] ).draw();
                });
            }
        });
    </script>
    <script type="text/javascript">
        parserDates();
        majCadresMessage();
        verifieOrdreUl();

        //	var language = window.navigator.userLanguage || window.navigator.language;

        jQuery.cookieBar({
            fixed: true,
            bottom: true,
            policyButton: true,
            message: 'This website uses cookies to improve your user experience.',
            acceptText: 'I understand',
            policyText: 'Privacy Policy',
            policyURL: 'privacy'
        });
    </script>
</body>

</html>