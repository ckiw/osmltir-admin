$(document).ready(function() {
    function renderEvent(selector, event) {
        var dateTime = new Date(event.date);
        $(selector).after('<div class="event" style="color:' + event.color + '; background-color:'+ event.backgroundColor+';"><strong>' + dateTime.toLocaleString() + '<br />Titre : </strong>' + event.title + '<br /><strong>Lien : </strong>' + event.url + '</div>');
    }

    //Affichage de la liste des events
    $.getJSON('getEvent.json.php', function(data) {
        data.sort(function(a, b) {
            a.date.localeCompare(b.date);
            return a.date.localeCompare(b.date);
        });
        $(data).each(function(index, event) {
            renderEvent('#event-list h3', event);
        });
    });
});

