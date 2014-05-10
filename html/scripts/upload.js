/**
 * Created by Michael on 02/05/14.
 */
var currentUploadArray = {};
$( document ).ready(function() {
    if (window.File && window.FileList && window.FileReader) {
        console.log('drag and drop enabled');
        $('#uploadMask').on(
            'dragover',
            function(e) {
                e.preventDefault();
                e.stopPropagation();
            }
        )
        $('#library').on(
            'dragenter',
            function(e) {
                //console.log('drag and drop entered');
                e.preventDefault();
                e.stopPropagation();
                $('#library').css("opacity","0.3");
                $('#uploadMask').show();
            }
        )
        $('#uploadMask').on(
            'dragleave',
            function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#library').css("opacity","1");
                $('#uploadMask').hide();
            }
        )
        $('#uploadMask').on(
            'drop',
            function(e){
                //console.log('drop!');
                $('#library').css("opacity","1");
                if(e.originalEvent.dataTransfer){
                    if(e.originalEvent.dataTransfer.files.length) {
                        e.preventDefault();
                        e.stopPropagation();
                        /*UPLOAD FILES HERE*/
                        if($('.comicCard').length == 0){
                            $('#noResults').remove();
                        }
                        upload(e.originalEvent.dataTransfer.files);
                    }
                }
                $('#uploadMask').hide();
            }
        );
    }
});

function upload(files){
    //console.log(files);
    var added = false;
    var exists = false;
    var series_id = makeid();

    $.each(files, function(key, value){
        //$( "#content" ).append("<div class='comicCard' data-series='" + value.name + "'><img src='http://placehold.it/185x287'><p>" + value.name + "</p></div>");
        var matchName = value.name.replace(/#[0-9]+/g,'').replace(/ V\s?[0-9]+/g,'').replace(/ Vol.\s?[0-9]+/g,'').replace(/\s*\(.*?\)\s*/g, '').replace(/(?:\.([^.]+))?$/,'');//.replace(/[0-9]/g, '');
        var startYearArray =  value.name.match(/Vol.\s?[0-9]+| V\s?[0-9]+/g,'');
        var startYear = '0000';
        if(startYearArray && startYearArray.length>0){
            startYear = startYearArray[0].replace(/[^0-9]/g,'');
        }
        $("#library .comicCard").each(function(index,item){
            var series = $(item).parent('a').data('series-name');

            if(matchName.toUpperCase() == series.toUpperCase()){
                exists = true;
                series_id = $(item).parent('a').data('series-id');
                if($(item).children('progress#'+ series_id).length==0){
                    $(item).children('img').after("<progress style='display:none;' id='" + series_id + "' value='0' max='100'>");
                    $(item).children('progress#'+ series_id).fadeIn();
                }
                return false;
            }
            if(matchName.toUpperCase()< series.toUpperCase()){
                $(item).parent('a').before("<a title='" + matchName + "' data-series-name='" + matchName + "' data-series-id='" + series_id + "'><div class='comicCard'><img src='http://placehold.it/185x287'><progress id='" + series_id + "' value='0' max='100'></progress><p>" + matchName + " (" + startYear + ")</p></div></a>");
                added = true;
                return false;
            }
        });
        if(!added && !exists){
            $( "#library" ).append("<a title='" + matchName + "' data-series-name='" + matchName + "' data-series-id='" + series_id + "'><div class='comicCard'><img src='http://placehold.it/185x287'><progress id='" + series_id + "' value='0' max='100'></progress><p>" + matchName+ " (" + startYear + ")</p></div>");
        }
        //$( "#content" ).append("<div class='comicCard' data-series-id='" + makeid() + "'><img src='http://placehold.it/185x287'><p>" + matchName + "</p></div>");
        currentUploadArray[series_id] = {};

        var comic_id = makeid();

        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(e){
            var complete = parseInt(Math.round(e.loaded / e.total * 100));
            currentUploadArray[series_id][comic_id] = complete;
            var progressComp = 0;
            $.each(currentUploadArray[series_id], function(index, item){
                progressComp += parseInt(item);
            });
            var progsize = 100 * (Object.size(currentUploadArray[series_id]));
            var finalThing = parseInt( progressComp / progsize * 100 );
            $('progress#' + series_id).val(finalThing);
            //console.log(currentUploadArray);
        });
        xhr.upload.addEventListener("load", function(e){
            // if()
            var progressComp = 0;
            $.each(currentUploadArray[series_id], function(index, item){
                progressComp += parseInt(item);
            });
            var progsize = 100 * (Object.size(currentUploadArray[series_id]));
            var finalThing = parseInt( progressComp / progsize * 100 );
            if(finalThing>=100){
                $('progress#' + series_id).fadeOut(function(){
                    $(this).remove();
                    currentUploadArray[series_id] = {};
                });
            }
        });

        xhr.open("POST", "upload.php", true);
        xhr.setRequestHeader("FILE_NAME", value.name);
        xhr.setRequestHeader("FILE_SIZE", value.size);
        xhr.setRequestHeader("SERIES_ID", series_id);
        xhr.setRequestHeader("COMIC_ID", comic_id);
        xhr.send(value);


    });
}
function makeid(j){
    j = typeof a !== 'undefined' ? j : 20;
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=1; i <= j; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};