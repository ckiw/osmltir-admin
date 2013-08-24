$(document).ready(function() {
    function renderEvent(selector, event) {
        var dateTime = new Date(event.date);
        $(selector).after('<div class="event ' + event.eventClass + '"><strong>' + dateTime.toLocaleString() + '<br />Titre : </strong>' + event.title + '<br /><strong>Lieu : </strong>' + event.location + '</div>');
    }

    //Affichage de la liste des events
    $.get('data-event.json', function(data) {
        data.sort(function(a, b) {
            a.date.localeCompare(b.date);
            return a.date.localeCompare(b.date);
        });
        $(data).each(function(index, event) {
            renderEvent('#event-list h3', event);
        });
    });
});

