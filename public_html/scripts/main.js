/**
 * Created by Michael on 23/04/14.
 */

$(document).ready(function(){

    var History = window.History;

    if ( !History.enabled ) {
        // History.js is disabled for this browser.
        // This is because we can optionally choose to support HTML4 browsers or not.
        return false;
    }

    History.Adapter.bind(window,'statechange',function() { // Note: We are using statechange instead of popstate
        var State = History.getState();
        //$('#content').empty();
        //$('#content').load(State.url);
        console.log(State);
        $.get(State.url, function(response) {
            //$('#content').html($(response).find('#content').html());
            //$('#ajaxLoader2').hide();
            //console.log(response);
            /*$('#library').fadeOut(function(){
                $('body').append($(response).filter("#series"));
            });*/

        });
    });

    $( "#library" ).on('click', '.comicCard',function(e){
        e.preventDefault();
        if (e.shiftKey) {
            alert("shift+click");
        }
        History.pushState(null, "Comic Home - " + $(this).text(), $(this).parent('a').attr('href'));

    });
});