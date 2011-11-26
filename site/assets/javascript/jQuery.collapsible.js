$.fn.collapsible = function() {
    return $(this).each(function() {
        //define
        var collapsibleHeading = $('.collapsible-heading', this);
        var collapsibleContent = $('.collapsible-content', this);

        //modify markup & attributes
        collapsibleHeading
            .prepend('<span class="collapsible-heading-status"></span>')
            .wrapInner('<a href="#" class="collapsible-heading-toggle"></a>');

        //events
        collapsibleHeading
            .bind('collapse', function() {
            $(this)
                .addClass('collapsible-heading-collapsed')
                .find('.collapsible-heading-status').text('Show ');

            collapsibleContent.slideUp('slow', function() {
                $(this).addClass('collapsible-content-collapsed').removeAttr('style').attr('aria-hidden', true);
            });
        })
            .bind('expand', function() {
                $(this)
                    .removeClass('collapsible-heading-collapsed')
                    .find('.collapsible-heading-status').text('Hide ');

                collapsibleContent
                    .slideDown('slow', function() {
                    $(this).removeClass('collapsible-content-collapsed').removeAttr('style').attr('aria-hidden', false);
                });
            })
            .click(function() {
                if ($(this).is('.collapsible-heading-collapsed')) {
                    $(this).trigger('expand');
                }
                else {
                    $(this).trigger('collapse');
                }
                return false;
            })
            //.trigger('collapse');
    });
};