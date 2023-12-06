(function ($) {

  const row_items = '.search-group-items'
  const button_loadmore = '.button-loadmore'

  if (!$(row_items).length) return

  /**
   * URL PROCESSING
   * @param param
   * @param val
   */

  function url_update_param (param, val) {
    if (val === undefined || !val) return

    var url = new URL(window.location.href)
    var search_params = url.searchParams
    search_params.sort()

    search_params.set(param, val)
    url.search = search_params.toString()
    url = url.toString()
    history.pushState('', '', url)
  }

  function url_delete_param (param) {
    if (param === undefined) return
    var url = new URL(window.location.href)
    var search_params = url.searchParams
    search_params.delete(param)
    url.search = search_params.toString()
    url = url.toString()
    history.pushState('', '', url)
  }

  const grouped_section = $('#search-group-all-post-types')

  function ensp_update_group_all_ajax () {
    var button = grouped_section.find(button_loadmore)
    var query_args = button.data('query')
    var el_items_list = grouped_section.find('.search-group-items')

    query_args.page = 1

    $.ajax({
      url: ajaxurl,
      data: {
        'action': 'search_grouped',
        'query_args': query_args,
      },
      type: 'POST',
      beforeSend: function () {
        button.text('Loading...')
      },
      success: function (response) {
        if (response) {

          el_items_list.find(' > *').remove()
          el_items_list.append(response.posts)

          query_args.max_page = response.max_pages

          if (response.max_pages > 1) {
            query_args.page = 2
          }

          button.attr('data-query', JSON.stringify(query_args))

          //Show/Hide Button
          button.text('Load more')
          if (query_args.page >= query_args.max_page) {
            button.hide()
          } else {
            button.show()
          }

        }
      },
    })
  }

  if (grouped_section.length) {
    var button = grouped_section.find('.button-loadmore')
    var query_args = button.data('query')
    var all_post_types = query_args.post_type
    var search_keywords_label = $('.search-keywords')
    var isEmptyFilters = true
    /**
     * Listiner Filters & update query param
     */
    $('.ensp-filter-input').change('select,input', function (e) {
      query_args = button.data('query')
      isEmptyFilters = false

      var tag = $(this).prop('tagName'),
        val = $(this).val(),
        filter = $(this).closest('.ensp-filter'),
        filterObj = JSON.parse(JSON.stringify(filter.data('filter'))),
        type = filterObj.type,
        get_param = filterObj.getparam

      if (type == 'post-type') {
        query_args.post_type = val
      }

      if (tag === 'BUTTON') {
        val = $(this).prev().val()
        type = 'search'
      }

      if (type == 'search') {
        query_args.s = val
        url_update_param(get_param, val)

        if (search_keywords_label.length) {
          search_keywords_label.text(val)
        }
      }

      if (!val) {
        url_delete_param(get_param)
      } else {
        url_update_param(get_param, val)
      }

      val = (val ? val : all_post_types)

      button.attr('data-query', JSON.stringify(query_args))

      if (!isEmptyFilters) {
        $('#ensp-filter-clearall').show()

      } else {
        $('#ensp-filter-clearall').hide()
      }

      ensp_update_group_all_ajax()
    })

  }

  /**
   * LoadMore
   */
  $(button_loadmore).click(function (e) {
    e.preventDefault()
    var button = $(this)
    var query_args = button.data('query')
    button.text('Loading...')
    var isEmptyFilters = true

    $.ajax({
      url: ajaxurl,
      data: {
        'action': 'search_grouped',
        'query_args': query_args,
      },
      type: 'POST',
      success: function (response) {
        if (response) {
          var el_items_list = button.closest('.search-group').find('.search-group-items')
          el_items_list.append(response.posts)

          query_args.page++
          query_args.max_page = response.max_pages
          button.attr('data-query', JSON.stringify(query_args))

          //Show/Hide Button
          button.text('Load more')
          if (query_args.page >= query_args.max_page) {
            button.hide()
          } else {
            button.show()
          }

        }
      },
    })
  })

  /**
   * CLEAR
   */

  /**
   * Clear All Filters
   */
  $('#ensp-filter-clearall').click(function () {
    isEmptyFilters = true

    $('.ensp-filter').each(function (i) {
      var filter = $(this),
        input = filter.find('.ensp-filter-input'),
        val = input.val(),

        filterObj = JSON.parse(JSON.stringify(filter.data('filter'))),
        type = filterObj.type,
        get_param = filterObj.getparam

      if (type == 'post-type') {
        query_args.post_type = all_post_types
        url_delete_param(get_param)
        input.prop('selectedIndex', 0)
      }

      if (type == 'search') {
        query_args.s = ''
        input.val('')
        url_delete_param(get_param)
        search_keywords_label.text('')
      }

      button.attr('data-query', JSON.stringify(query_args))
    })
    //todo default to be hiden from css
    $(this).hide()

    ensp_update_group_all_ajax()
  })

})(jQuery)

