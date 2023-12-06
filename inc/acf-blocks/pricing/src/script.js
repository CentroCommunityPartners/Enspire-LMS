(function ($) {

  $('.pricing-plan').each(function () {
    const plan = $(this)
    const toggle = $(this).find('.toggle input')

    toggle.change(function () {
      const checked = $(this).prop('checked')
      const plan_type = checked ? 'yearly' : 'monthly'
      plan.attr('data-plan', plan_type)
    })

  })

})(jQuery)