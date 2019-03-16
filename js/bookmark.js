/**
 * Created by igasi on 5/24/16.
 */

/* Script Bookmark.
 ========================================================================= */

/*==DRUPAL BEHAVIOR ==*/
(function ( $, Drupal, drupalSettings) {

    // Bookmark system logic.

    function bookmarkConfig(){
        var config = {
            "uri_resource": "/bookmarks/",
            "format": "?_format=json",
            "selector": "#block-bookmarkblock a.ll-bookmark",
            "icon_enable": "/modules/letraslibres/bookmarks/images/bookmark-active.svg",
            "icon_disable": "/modules/letraslibres/bookmarks/images/bookmark.svg"
        };

        return config;
    }

    /**
     * Get CSRF Token from rest/session/token service.
     * @param callback
     */
    function getCsrfToken(callback) {

        jQuery
            .get(Drupal.url('rest/session/token'))
            .done(function (data) {
                var csrfToken = data;
                callback(csrfToken);
            });
    }

    /**
     * POST bookmark.
     * @param csrfToken
     * @param bookmark
     */
    function postBookmark(csrfToken, bookmark) {

        var config = bookmarkConfig();

        jQuery.ajax({
            url: config.uri_resource + "add" + config.format,
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            data: JSON.stringify(bookmark),
            success: function (bookmark) {
                //console.log(bookmark);
                // Change icon to enable.
                $(config.selector).addClass("active");
                $(config.selector + ' img').attr("src", config.icon_enable);
                $(config.selector).attr("bookmarkid", bookmark.id[0].value);
            }
        });
    }

    /**
     * GET bookmark by id.
     * @param articleId
     */
    function getBookmark(articleId){


        if (articleId){
            var config = bookmarkConfig();

            var url = config.uri_resource + articleId + config.format;
            //console.log(url);

            jQuery.ajax({
                url: url,
                method: 'GET',
                success: function (bookmark) {
                    //console.log(bookmark, "Success bookmark");
                    $(config.selector).addClass("active")
                        .attr("bookmarkid", bookmark.id[0].value)
                        .append('<img alt="Bookmark" src="'+ config.icon_enable +'">');
                },
                error: function (msg){
                    //console.log(msg, "Error bookmark");
                    $(config.selector).removeClass("active")
                        .append('<img alt="Bookmark" src="'+ config.icon_disable +'">');
                }
            });

        }

    }


    /**
     * DELETE a bookmark.
     * @param csrfToken
     * @param bookmarkId
     */
    function deleteBookmark(csrfToken, bookmarkId){

        var config = bookmarkConfig();
        var url = config.uri_resource + bookmarkId + config.format;
        //console.log(url);
        jQuery.ajax({
            url: url,
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function () {
                //console.log('Bookmark deleted.');
                // Change icon to disable.
                $(config.selector).removeClass("active").attr("bookmarkid", "");
                $(config.selector + ' img').attr("src", config.icon_disable);
            }
        });

    }


    function getArticleId(){
        return drupalSettings.statistics.data.nid;
    }

    function getUserId(){
        return drupalSettings.user.uid;
    }

    function getBookmarkId(){
        var config = bookmarkConfig();

        return $(config.selector).attr("bookmarkid");
    }

/*
    getCsrfToken(function (csrfToken) {
        postNode(csrfToken, newNode);
    });
*/


    /**
     * Bookmarks initial behavior.
     * @type {{attach: Drupal.behaviors.bookmark.attach}}
     */
    Drupal.behaviors.Bookmarks = {
        attach: function (context, settings) {

            // Get general configuration of bookmarks.
            var config = bookmarkConfig();

            // Define a base markup for bookmarks in articles.
            //$(context).find('.page-article .node--type-article').once('Bookmarks').each(function () {
            //    $(config.markup, context).insertBefore('.article-text-wrapp');
            //    $(config.markup, context).insertAfter('.page-article section.col-md-9 .bottom-share');
            //});

            // Determinate if the current article was bookmarked for User logged.
            // And set the initial state.
            //console.log("Added bookmark js. Yay!!");

            $(context).find(config.selector).once('Bookmarks').each(function () {
                if (getUserId() > 0){

                    // Know if the current article if bookmarked.
                    // And sets a base markup.
                    getBookmark(getArticleId());

                }
            });

            $(config.selector, context).click(function () {
                //console.log("Event click", $(this));

                // Delete bookmarked.
                if ($(this).hasClass("active")){

                    //console.log(getBookmarkId(), "local bookmark id");

                    getCsrfToken(function (csrfToken) {
                        deleteBookmark(csrfToken, getBookmarkId());
                    });

                // Create bookmarked.
                }else{

                    var newBookmark = {
                        "user_id":[{
                            "target_id": getUserId()
                        }],
                        "name":[{
                            "value": ""
                        }],
                        "nodeid_bookmarked":[{
                            "target_id": getArticleId()
                        }],
                        "path_bookmarked":[{
                            "value": ""
                        }]
                    };

                    getCsrfToken(function (csrfToken) {
                        postBookmark(csrfToken, newBookmark);
                    });
                }


            });


        }
    };

})( jQuery, Drupal, drupalSettings);
/*== end behavior ==*/
