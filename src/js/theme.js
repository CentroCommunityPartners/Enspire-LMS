import './components/unsubscribe';
import './memberpress/signup';

jQuery(document).ready(function ($) {

  //vars
  const section_posts = $('.section-posts [class*="columns-"]')
  const related_posts = $('.related-posts [class*="columns-"]')
  const slider_posts = $('.slider-posts .post-items')
  const section_youtube = $('.section-youtube-lasts .sby_items_wrap')
  const team_slider = $('.team-slider > .wp-block-group__inner-container')

  //TABS
  const tabs = {
    init: function (el) {
      if (!el.length) return
      const controls = el.find('.tabs__control')
      controls.find('a:first').addClass('current')

      controls.find('a').click(function (e) {
        e.preventDefault()
        tabs.change($(this))
      })
    },
    change: function (link) {
      var _href = link.attr('href')
      $('.tabs__control a').removeClass('current')
      link.addClass('current')
      $('.tabs__content > div').hide()
      $(_href).fadeIn()
    },
  }

  //App
  const app = {
    mobile_menu: function () {

      $('.menu-toggle').on('click', function (event) {
        event.preventDefault()
        $('html').toggleClass('menu-open')
        // $(window).scrollTop(0)
      })

      if (window.innerWidth <= 890) {
        const hasChildren = $('.menu-item-has-children')

        hasChildren.click(function () {

          elActive = $(this)

          hasChildren.each(function () {
            if ($(this).attr('id') != elActive.attr('id')) {
              $(this).removeClass('active')
            }
          })

          elActive.toggleClass('active')
        })
      }

    },
    mobile_move_elements: function () {
      //move
      const parent = $('#primary-menu')

      if (window.innerWidth <= 890) {

        if (!parent.hasClass('has-moved')) {
          const profileButton = $('#primary-menu .profile-link')
          const loginButton = $('#primary-menu .button-transparent')

          //move
          if (profileButton.length > 0) {
            parent.prepend(profileButton)
          }

          if (loginButton.length > 0) {
            parent.prepend(loginButton)
          }

          parent.addClass('has-moved')
        }
      } else {
        if (parent.hasClass('has-moved')) {
          const profileButton = $('#primary-menu .profile-link')
          const loginButton = $('#primary-menu .button-transparent')

          //move
          if (profileButton.length > 0) {
            profileButton.appendTo(parent)
          }

          if (loginButton.length > 0) {
            parentloginButton.appendTo(parent)
          }
          parent.removeClass('has-moved')
        }
      }

    },
    slider_posts: function (el) {
      if (!el.length) return
      $(el).addClass('owl-carousel')
      $(el).owlCarousel({
        loop: true,
        margin: 15,
        nav: true,
        dots: true,
        items: 1,
        stagePadding: 0,
        navText: ['<i class="icon-arrow-left"></i>', '<i class="icon-arrow-right"></i>'],
        onInitialized: carouselInitialized,
        responsive : {
          640 : {
            items: 2,
          },
          901 : {
            items: 3,
          },
          1200 : {
            items: 4,
          }
        }
      })

      function carouselInitialized (event) {
        const el = $(event.target)
        const customNavHTML = '<div class="carousel-nav"><div class="carousel-nav-inner"><div class="carousel-nav-content"></div></div></div>'
        el.append(customNavHTML)
        const elNav = el.find('.carousel-nav-content')

        el.find('.owl-nav').prependTo(elNav)
        el.find('.owl-dots').prependTo(elNav)
      }
    },
    columns2slider: function (row, breakpoint) {
      if (!row.length) return

      if (window.innerWidth <= breakpoint) {
        row.each(function () {
          var count_items = $(this).children().length
          var isLoaded = $(this).hasClass('owl-loaded')
          if (count_items <= 1 || isLoaded) return

          $(this).addClass('columns2slider owl-carousel')
          $(this).owlCarousel({
            loop: true,
            margin: 15,
            nav: true,
            dots: false,
            items: 1,
            stagePadding: 25,
            navText: ['<i class="icon-arrow-left"></i>', '<i class="icon-arrow-right"></i>'],
          })
        })
      } else {
        row.removeClass('columns2slider owl-carousel')
        row.owlCarousel('destroy')
      }
    },
    team_slider: function (el) {
      if (el.length) {

        el.owlCarousel({
          loop: false,
          margin: 0,
          nav: true,
          rewind: true,
          items: 1,
          animateOut: 'fadeOut',
          animateIn: 'fadeIn',
          navText: ['<i class="icon-arrow-left"></i>', '<i class="icon-arrow-right"></i>'],
          onInitialized: carouselInitialized,
          onChanged: carouselChanged,
        })

        function carouselChanged (event) {
          const el = $(event.target)
          var index = event.item.index
          var current_item = el.find('.owl-item').eq(index)

          // first removing animation for all captions
          $('.team-member__name,.team-member__follow,.team-member__info').removeClass('animated fadeInUp')
          $('.wp-block-media-text__media').removeClass('animated zoomIn')

          current_item.find('.team-member__name,.team-member__info').addClass('animated fadeInUp')
          current_item.find('.wp-block-media-text__media').addClass('animated zoomIn')
        }

        function carouselInitialized (event) {
          const el = $(event.target)
          const customNavHTML = '<div class="carousel-nav"><div class="carousel-nav-inner"><div class="carousel-nav-content"></div></div></div>'
          el.append(customNavHTML)
          const elNav = el.find('.carousel-nav-content')

          el.find('.owl-nav').prependTo(elNav)
          el.find('.owl-dots').prependTo(elNav)
        }
      }
    },
    pricing_table: function () {
      const el = $('.membership-pricing-table')
      if (!el.length) return
      const buttons = el.find('.button[data-display]')
      buttons.click(function () {
        buttons.removeClass('active')
        $(this).addClass('active')
        el.attr('data-display', $(this).data('display'))
      })
    },
    open_checked_plan_tab: function () {
      const el = $('#rcp_subscription_levels')
      if (el.length) {
        const fields = $('.rcp_level__input')
        const rcp_tabs = el.find('.tabs')

        var active_id = fields.filter(function () {
          return $(this).prop('checked')
        }).closest('.rcp_plan').attr('id')

        var active_button = rcp_tabs.find('a[href="#' + active_id + '"]')

        tabs.change(active_button)
      }
    },
    es_expand: function () {
      if ($('.es-expand').length) {

        $('.es-expand').each(function () {
          var tableHeight = $(this).find('table').height()

          if (tableHeight > 170) {
            $(this).after('<a class="button button-plus es-expand-open">View All</a>')
          }

        })

        $('.es-expand-open').click(function (e) {
          e.preventDefault()

          if ($(this).hasClass('button-minus')) {
            $(this).text('View All').removeClass('button-minus').addClass('button-plus')
            $(this).prev().attr('style', '')
          } else {
            $(this).text('View less').removeClass('button-plus').addClass('button-minus')
            $(this).prev().attr('style', 'max-height:100%')
          }
        })
      }
    },
    mf_programs_subscribe: function () {
      if (!$('.rcp_form').length) return

      const isCompletePlan = $('#rcp_plan_complete input:checked')

      if (isCompletePlan.length) {
        $('.mf-programs-choice').show()
      }

      $('.rcp_plan input[name="rcp_level"]').change(function () {
        var plan = $(this).closest('.rcp_plan')
        var plan_id = plan.attr('id')

        if (plan_id != 'rcp_plan_complete') {
          $('.mf-programs-choice').hide()
          $('.mf-programs-choice input:radio').removeAttr('checked')
        } else {
          $('.mf-programs-choice').show()
        }
      })
    },
    init: function () {
      app.mobile_menu()
      app.mobile_move_elements()
      tabs.init($('.tabs'))
      app.open_checked_plan_tab() //Open checked Plan RCP Tab
      app.columns2slider(related_posts, 600)
      app.columns2slider(section_posts, 600)
      app.slider_posts(slider_posts)
      app.team_slider(team_slider)
      app.pricing_table()
      app.es_expand()
      app.mf_programs_subscribe()
    },
    load: function () {
      app.columns2slider(section_youtube, 767)
    },
    resize: function () {
      app.columns2slider(related_posts, 600)
      app.columns2slider(section_posts, 600)
      app.columns2slider(section_youtube, 767)
    },
  }

  app.init()
  window.onload = app.load()
  window.onresize = app.resize()

  $(window).resize(function () {
    app.mobile_move_elements()
  })

})

