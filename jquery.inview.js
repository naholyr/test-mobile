/**
 * author Christopher Blum
 *    - based on the idea of Remy Sharp, http://remysharp.com/2009/01/26/element-in-view-event-plugin/
 *    - forked from http://github.com/zuk/jquery.inview/
 */
(function ($) {
    function getViewportSize() {
        var mode, domObject, size = { height: window.innerHeight, width: window.innerWidth };

        // if this is correct then return it. iPad has compat Mode, so will
        // go into check clientHeight/clientWidth (which has the wrong value).
        if (!size.height) {
            mode = document.compatMode;
            if (mode || !$.support.boxModel) { // IE, Gecko
                domObject = mode === 'CSS1Compat' ?
                    document.documentElement : // Standards
                    document.body; // Quirks
                size = {
                    height: domObject.clientHeight,
                    width:  domObject.clientWidth
                };
            }
        }

        return size;
    }

    function getViewportOffset() {
        return {
            top:  window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop,
            left: window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft
        };
    }

    function checkInView() {
        var elements = [], elementsLength, i = 0, viewportSize, viewportOffset, expando = $.expando;

        // naughty, but this is how it knows which elements to check for
        $.each($.cache, function(i, cacheObj) {
            var events = cacheObj.events;
            if (!events) {
                // needed for jQuery 1.5+
                cacheObj = this[expando];
                events = cacheObj && cacheObj.events;
            }
            
            if (events && events.inview) {
                if (events.live) {
                    var context = $(cacheObj.handle.elem);
                    $.each(events.live, function() {
                        if (this.origType.substr(0, 6) === 'inview') {
                            elements = elements.concat(context.find(this.selector).toArray());
                        }
                    });
                } else {
                    elements.push(cacheObj.handle.elem);
                }
            }
        });
        
        elementsLength = elements.length;
        if (elementsLength) {
            viewportSize   = getViewportSize();
            viewportOffset = getViewportOffset();

            for (; i<elementsLength; i++) {
                // Ignore elements that are not in the DOM tree
                if (!$.contains(document.documentElement, elements[i])) {
                  continue;
                }

                var $element      = $(elements[i]),
                    elementSize   = { height: $element.height(), width: $element.width() },
                    elementOffset = $element.offset(),
                    inView        = $element.data('inview'),
                    visiblePartX,
                    visiblePartY,
                    visiblePartsMerged;
                if (elementOffset.top + elementSize.height > viewportOffset.top &&
                    elementOffset.top < viewportOffset.top + viewportSize.height &&
                    elementOffset.left + elementSize.width > viewportOffset.left &&
                    elementOffset.left < viewportOffset.left + viewportSize.width) {
                    visiblePartX = (viewportOffset.left > elementOffset.left ?
                        'right' : (viewportOffset.left + viewportSize.width) < (elementOffset.left + elementSize.width) ?
                        'left' : 'both');
                    visiblePartY = (viewportOffset.top > elementOffset.top ?
                        'bottom' : (viewportOffset.top + viewportSize.height) < (elementOffset.top + elementSize.height) ?
                        'top' : 'both');
                    visiblePartsMerged = visiblePartX + "-" + visiblePartY;
                    if (!inView || inView !== visiblePartsMerged) {
                        $element.data('inview', visiblePartsMerged).trigger('inview', [true, visiblePartX, visiblePartY]);
                    }
                } else if (inView) {
                  $element.data('inview', false).trigger('inview', [false]);
                }
            }
        }
    }

    // Use setInterval in order to also make sure this captures elements within
    // "overflow:scroll" elements or elements that appeared in the dom tree due to
    // dom manipulation and reflow
    // old: $(window).scroll(checkInView);
    //
    // By the way, iOS (iPad, iPhone, ...) seems to not execute, or at least delays
    // intervals while the user scrolls. Therefore the inview event might fire a bit late there
    setInterval(checkInView, 250);
})(jQuery);
