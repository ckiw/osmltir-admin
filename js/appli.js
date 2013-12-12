

function modifNavbar() {
    //modification de la navbar en fonction de l'état de la connection
    $.getJSON('login.php', {action: 'isConnected'}, function(rep) {
        if (rep.grade == 'user') {
            $('#navbar-content').append('<ul class="nav"><li><a href="index.html">Accueil</a></li><li><a href="galerie.html">Galerie</a></li><li><a href="http://www.osmltir.fr/blog/">Blog</a></li><li><a href="score.html">Score</a></li></ul><ul  class="nav pull-right"><li ><a href="user.html">' + rep.name + '</a></li><li ><a href="#" id="disconnect">&times;</a></li></ul>');
        } else if (rep.grade == 'admin') {
            $('#navbar-content').append('<ul class="nav"><li><a href="index.html">Accueil</a></li><li><a href="galerie.html">Galerie</a></li><li><a href="http://www.osmltir.fr/blog/">Blog</a></li><li><a href="score.html">Score</a></li></ul><ul  class="nav pull-right"><li ><a href="user.html">' + rep.name + '</a></li><li ><a href="admin.html">Admin</a></li><li ><a href="#" id="disconnect">&times;</a></li></ul>');
        } else {
            $('#navbar-content').append('<ul class="nav"><li><a href="index.html">Accueil</a></li><li><a href="galerie.html">Galerie</a></li><li><a href="http://www.osmltir.fr/blog/">Blog</a></li></ul><form method="get" action="login.php" class="navbar-form pull-right"><input name="action" value="connection" type="hidden"\><input name="email" type="text" class="span2" placeholder="Email"\><input name="password" type="password" class="span2" placeholder="Mot de passe"\><button type="submit" class="btn">Se connecter</button></form>');
        }
        $('#disconnect').click(function(e) {
            e.preventDefault();
            $.getJSON('login.php', {action: 'disconnect'}, function(rep) {
                $('#navbar-content').children().remove();
                $('#navbar-content').append('<ul class="nav"><li><a href="index.html">Accueil</a></li></ul><form method="get" action="login.php" class="navbar-form pull-right"><input name="action" value="connection" type="hidden"\><input name="email" type="text" class="span2" placeholder="Email"\><input name="password" type="password" class="span2" placeholder=Mot de passe\><button type="submit" class="btn">Submit</button></form>');
            });
        });

    });

}

function renderEvent(selector, event) {
    var dateTime = new Date(event.date);
    $(selector).after('<div class="event" style="color:' + event.color + '; background-color:' + event.backgroundColor + ';"><button data-id="' + event.id + '" type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>' + dateTime.toLocaleString() + '<br />Titre : </strong>' + event.title + '<br /><strong>Lien : </strong>' + event.url + '</div>');

}

function displayEvents() {
    //Affichage de la liste des events
    $.getJSON('EventManager.php', {action: 'select'}, function(data) {
        data.sort(function(a, b) {
            a.date.localeCompare(b.date);
            return a.date.localeCompare(b.date);
        });
        $(data).each(function(index, event) {
            renderEvent('#event-list h3', event);
        });

        $('.close').click(function(e) {
            e.preventDefault();
            $.getJSON('EventManager.php', {idEvent: $(this).data('id'), action: 'delete'}, function(msg) {
                $('#event-form').before('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + msg + '</div>');
            });
        });
    });
}


