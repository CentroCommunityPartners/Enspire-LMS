(function ($) {

  /****************************************
   *            CONST & VARS              *
   ****************************************/

  const simpleLoadMore = '.simple-loadmore'
  const items = '.simple-loadmore-items'
  const button = '.simple-loadmore-button'

  if (!$(simpleLoadMore).length) return

  $(button).click(function (e) {
    e.preventDefault()

    const parent = $(this).closest(simpleLoadMore)
    const row_items = parent.find(items)
    const btn = parent.find(button)

    var query = JSON.parse($(this).attr('data-query'))

    var data = {
      'action': 'simple_loadmore',
      'query': query,
    }

    $.ajax({
      url: ajaxurl,
      data: data,
      type: 'POST',
      beforeSend: function () {
        btn.text('Loading...')
      },
      success: function (response) {
        if (response) {
          btn.text('Load more')
          btn.attr('data-query', JSON.stringify(response.query))
          $(row_items).append(response.posts)

          if (response.query.paged >= response.query.max_pages) {
            btn.hide()
          } else {
            btn.show()
          }

        }
      },
    })
  })

})(jQuery)