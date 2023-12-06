(function ($) {

  const wodsliders = $('.wodsteps-viewslider')

  if (wodsliders.length) {
    wodsliders.each(function () {

      const steps = $(this).children().clone()
      const viewListContent = $(this).closest('.wodsteps').find('.wodsteps-viewlist__content')

      steps.appendTo(viewListContent)

      $(this).owlCarousel({
        loop: false,
        margin: 0,
        nav: true,
        rewind: true,
        items: 1,
        autoHeight: true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        navText: ['<i class="icon-arrow-left"></i>', '<i class="icon-arrow-right"></i>'],
        onInitialized: carouselInitialized,
        onChanged: carouselChanged,
      })

      function carouselChanged (event) {
        var index = event.item.index + 1
        $(event.target).find('.num').text(index + '/' + event.item.count)
      }

      function carouselInitialized (event) {
        const el = $(event.target)
        const customNavHTML = '<div class="carousel-nav"><div class="carousel-nav-inner"><div class="carousel-nav-content"></div></div></div>'
        el.append(customNavHTML)
        const elNav = el.find('.carousel-nav-content')

        el.find('.owl-nav').prependTo(elNav)
        el.find('.owl-dots').prependTo(elNav)

        el.prepend('<span class="num" aria-hidden="true">1/' + event.item.count + '</span>')
      }

    })

  }

  $('.toggle-viewlist').click(function (e) {
    e.preventDefault()

    $(this).toggleClass('active')

    if ($(this).hasClass('active')) {
      $(this).text('Hide List View')
    } else {
      $(this).text('Show List View')
    }

    $(this).closest('.wodsteps-viewlist').find('.wodsteps-viewlist__content').toggleClass('show')
  })

})(jQuery)
