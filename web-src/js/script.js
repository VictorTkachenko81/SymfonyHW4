/**
 * Created by victor on 12.12.15.
 */

$( document ).ready(function() {

    //action for pagination
    $( ".pagination li" ).click(function() {

        var loadPage = true;

        var toPage = $( this ).text();

        var minPage = parseInt($( ".pagination li .loadPage:first" ).text());
        var maxPage = parseInt($( ".pagination li .loadPage:last" ).text());
        var curPage = parseInt($( ".pagination li.active" ).text());

        if (toPage == '«') {
            if ( minPage == curPage ) {
                alert("You already on first page!");
                loadPage = false;
            }
            else toPage = curPage - 1;
        } else if (toPage == '»') {
            if ( maxPage == curPage ) {
                alert("You already on last page!");
                loadPage = false;
            }
            else toPage = curPage + 1;
        } else toPage = parseInt($( this ).text());

        if ( loadPage ) {
            $.ajax({
                url: $( "#more").attr( "url" ),
                data: {page: toPage},
                dataType: "html",
                method: "POST",
                statusCode: {
                    404: function() {
                        alert( "page not found" );
                    }
                }
            }).done(function( html ) {
                $( ".table-games tbody" ).html( html );
                $( ".pagination li" ).removeClass( "active" );
                $( "#page" + toPage).addClass( "active" );
            });
        }
    });


    //action for button showMore
    $( "#more" ).click(function() {

        var maxPage = parseInt($( "#more" ).attr( "max" ));
        var curPage = parseInt($( "#more" ).attr( "page" ));
        var nextPage = curPage + 1;

        if ( curPage < maxPage ) {
            $.ajax({
                url: $( "#more" ).attr( "url" ),
                data: {page: nextPage},
                dataType: "html",
                method: "POST",
                statusCode: {
                    404: function() {
                        alert( "page not found" );
                    }
                }
            }).done(function( html ) {
                $( ".table-games tbody" ).append( html );

                $( "#more" ).attr( "page", nextPage);
                if( nextPage == maxPage ) $( "#more").attr( "disabled", "disabled" );

                $( ".pagination li" ).removeClass( "active" );
                $( "#page" + nextPage).addClass( "active" );
            });
        }
    });


    //action for loading addition data when scroll down
    //attention! function has dependency from paginator
    $( window ).scroll(function() {

        var curPosition = $( window ).scrollTop();
        var wHeight = $( window ).height();
        var dHeight = $( document ).height();
        var toDown = dHeight - wHeight - curPosition;

        var maxPage = parseInt($( ".pagination li .loadPage:last" ).text());
        var curPage = parseInt($( ".pagination li.active" ).text());

        if ( (curPage < maxPage) && (toDown == 0)) {

            var toPage = curPage + 1;

            setTimeout(function(){
                $.ajax({
                    url: $( "#more").attr( "url" ),
                    data: {page: toPage},
                    dataType: "html",
                    method: "POST",
                    statusCode: {
                        404: function() {
                            alert( "page not found" );
                        }
                    }
                }).done(function( html ) {
                    $( ".table-games tbody" ).append( html );

                    $( ".pagination li" ).removeClass( "active" );
                    $( "#page" + toPage).addClass( "active" );
                });
            }, 1000);
        }
    });
});



