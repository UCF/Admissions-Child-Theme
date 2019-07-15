(function ($) {

  const anchorLinkSelector = '.js-anchor-link';
  const anchorSelector = '.js-anchor';
  const mapMarkerSelector = '.map-marker';
  const mapMarkerTextSelector = '.js-marker-tooltip-text';
  const scrollTime = 750; // ms
  const mapVisibilityMinimumWidth = 992; // px


  // Returns any active anchor links.
  function getActiveAnchorLinks() {
    return $(anchorLinkSelector).filter('.active');
  }

  // De-activates any active anchor links.
  function clearActiveAnchorLinks() {
    const $activeAnchorLinks = getActiveAnchorLinks();
    if ($activeAnchorLinks.length) {
      $activeAnchorLinks.removeClass('active');
    }
  }

  // Returns the active anchor.
  function getActiveAnchor() {
    const $activeAnchorLinks = getActiveAnchorLinks();
    if ($activeAnchorLinks.length) {
      return $($activeAnchorLinks.first().attr('href'));
    }
    return null;
  }

  // Activates anchor links for the current visible anchor.
  function assignActiveAnchorLinks(hash) {
    $(anchorLinkSelector)
      .filter(`[href="${hash}"]`)
      .addClass('active');
  }

  // Scrolls to the anchor specified.
  function scrollToAnchor(hash) {
    const $anchor = $(hash);
    // Autoscroll to section on page and update document url
    // if this is a valid page anchor.
    if ($anchor.length) {
      $('html, body').animate({
        scrollTop: $anchor.offset().top + 1
      }, scrollTime);

      if (history.pushState) {
        history.pushState(null, null, hash);
      } else {
        location.hash = hash;
      }
    }
  }

  // Initializes inview watching for anchors on the page
  function anchorScrollWatch() {
    if ($(window).width() >= mapVisibilityMinimumWidth) {
      const $anchors = $(anchorSelector);
      const $visibleAnchors = $anchors.filter(function () {
        return $(this).isInViewport();
      });

      if ($visibleAnchors.length) {
        const $activeAnchor = $visibleAnchors.first();
        if ($activeAnchor !== getActiveAnchor()) {
          clearActiveAnchorLinks();
          assignActiveAnchorLinks(`#${$activeAnchor.attr('id')}`);
        }
      } else {
        clearActiveAnchorLinks();
      }
    }
  }


  // Returns whether or not a given element is visible in the viewport
  $.fn.isInViewport = function () {
    const elementTop = $(this).offset().top;
    const elementBottom = elementTop + $(this).outerHeight();

    const viewportTop = $(window).scrollTop();
    const viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
  };


  // Initializes anchor links
  $.fn.anchorLink = function () {

    this.each((index, elem) => {

      const $elem = $(elem);

      $elem.on('click', (event) => {
        const hash = $elem.attr('href');
        if (hash.substring(0, 1) === '#') {
          event.preventDefault();
          clearActiveAnchorLinks();
          assignActiveAnchorLinks(hash);
          scrollToAnchor(hash);
        }
      });

    });

    return this;

  };


  $(document).ready(() => {
    $(anchorLinkSelector).anchorLink();
    $(mapMarkerSelector).tooltip({
      title: function () {
        return $(this).find(mapMarkerTextSelector).text();
      }
    });
  });

  $(window).on('scroll resize', anchorScrollWatch);

}(jQuery));
