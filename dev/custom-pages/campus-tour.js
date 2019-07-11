(function ($) {

  const anchorLinkSelector = '.js-anchor-link';
  const scrollTime = 750;


  function clearActiveAnchorLinks() {
    $(anchorLinkSelector)
      .filter('.active')
      .removeClass('active');
  }

  function assignActiveAnchorLinks(hash) {
    $(anchorLinkSelector)
      .filter(`[href="${hash}"]`)
      .addClass('active');
  }

  function scrollToAnchor(hash) {
    const $anchor = $(hash);
    // Autoscroll to section on page and update document url
    // if this is a valid page anchor.
    if ($anchor.length) {
      $('html, body').animate({
        scrollTop: $anchor.offset().top
      }, scrollTime);

      if (history.pushState) {
        history.pushState(null, null, hash);
      } else {
        location.hash = hash;
      }
    }
  }


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
  });

}(jQuery));
