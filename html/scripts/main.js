/**
 * Created by Michael on 23/04/14.
 */
var router = new staterouter.Router();

function library(){
    $('#menuContainer').fadeIn();
    $( "body" ).animate({backgroundColor:"#F5F5F5"},1000);

    if ($('#library').is(':empty')){
        console.log('load it up sucka!');
        var State = this;
        $.get(State.url, function(response) {
            $('#reader').fadeOut();
            $('#series').fadeOut(function(){
                $('#library').fadeIn().append($(response).filter("#library").html());
            });
        });
    }else{
        console.log('already here so just show it again... sucka!');
        $('#reader').fadeOut();
        $('#series').fadeOut(function(){
            $('#library').fadeIn();
        });
    }
}
function series(id){
    $('#menuContainer').fadeIn();
    $( "body" ).animate({backgroundColor:"#F5F5F5"},1000);
    console.log(id);

    currentlyLoadedSeriesId =  $('#series').attr('data-series-id');
    if(id==currentlyLoadedSeriesId){
        $('#reader').fadeOut();
        $('#library').fadeOut(function(){
            $('#series').fadeIn();
        });
    }else{
        var State = this;
        $('#series').empty();
        $.get(State.url, function(response) {
            $('#reader').fadeOut();
            $('#library').fadeOut(function(){
                $('#series').fadeIn().attr('data-series-id',id).append($(response).filter("#series").html());
            });
        });
    }
}
function reader(id){
    $( "body" ).animate({backgroundColor:"#000000"},1000);
    currentlyLoadedComicId =  $('#reader').attr('data-comic-id');

    if(id==currentlyLoadedComicId){
        $('#series').fadeOut();
        $('#library').fadeOut(function(){
            $('#menuContainer').fadeOut();
            $('#reader').fadeIn();
        });
    }else{
        //var State = this;
        console.log('yo!');
        $('#reader').empty();
        $('#series').fadeOut();
        $('#library').fadeOut(function(){
            $('#menuContainer').fadeOut();
        });
        $('#reader').fadeIn().attr('data-comic-id',id);
        var pageCount = 21
        var imageArray = [];
        for( var i = 1; i <= pageCount; i++){
            imageArray.push('/image.php?id=' + id + '&page=' + i);
        }
        $.each( imageArray, function(key, value){
            $('#reader').append("<img class='comicPage' src='" + value + "'>");
        });


        /*$.get(State.url, function(response) {
            $('#series').fadeOut();
            $('#library').fadeOut(function(){
                $('#menuContainer').fadeOut();
                //$('#reader').fadeIn().attr('data-comic-id',id).append($(response).filter("#reader").html());
                //Fetch Page Count
                var pageCount = 21
                var imageArray = [];
                for( var i = 1; i <= pagecount; i++){
                    imageArray.push('/image.php?id=' + comic_id + '&page=' + i);
                }
                $.each( imageArray, function(key, value){
                    $('#reader').append("<img src='" + value + "'>");
                });
            });
        });*/
    }

}
router
    .route('/library.php', library)
    .route('/s/:id',series)
    .route('/c/:id',reader);
$(document).ready(function(){

    var History = window.History;

    if ( !History.enabled ) {
        // History.js is disabled for this browser.
        // This is because we can optionally choose to support HTML4 browsers or not.
        return false;
    }

    router.perform();

    /*History.Adapter.bind(window,'statechange',function() { // Note: We are using statechange instead of popstate
        var State = History.getState();
        //$('#content').empty();
        //$('#content').load(State.url);
        console.log(State);
        $.get(State.url, function(response) {
            //$('#content').html($(response).find('#content').html());
            //$('#ajaxLoader2').hide();
            //console.log(response);
            $('#library').fadeOut(function(){
                $('body').append($(response).filter("#series"));
            });

        });
    });*/

    $( "#library" ).on('click', '.comicCard',function(e){
        e.preventDefault();
        if (e.shiftKey) {
            alert("shift+click");
        }
        //History.pushState(null, "Comic Home - " + $(this).text(), $(this).parent('a').attr('href'));
        var series_id = $(this).parent('a').data('series-id');
        var series_title = $(this).text();
        router.navigate('/s/' + series_id,{}, "Comic Home - " + series_title );

    });

    $( "#series" ).on('click', '.comicCard',function(e){
        e.preventDefault();
        if (e.shiftKey) {
            alert("shift+click");
        }
        //History.pushState(null, "Comic Home - " + $(this).text(), $(this).parent('a').attr('href'));
        var comic_id = $(this).parent('a').data('comic-id');
        //var series_title = $(this).text();
        router.navigate('/c/' + comic_id,{}, "Comic Home - ");

    });
});