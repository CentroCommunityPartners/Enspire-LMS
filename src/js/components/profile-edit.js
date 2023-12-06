jQuery(document).ready(function ($) {

  $('.input-time input').change(function (e) {

    const p = $(this).closest('.input-time'),
      m = p.find('.unit-m input'),
      s = p.find('.unit-s input'),
      ms = p.find('.unit-ms input')

    var m_val = parseInt(m.val()),
      s_val = parseInt(s.val()),
      ms_val = parseInt(ms.val())

    m_val = m_val ? (m_val > 59 ? 59 : m_val) : 0
    s_val = s_val ? (s_val > 59 ? 59 : s_val) : 0
    ms_val = ms_val ? (ms_val > 999 ? 999 : ms_val) : 0

    m_val != 0 ? m.val(m_val) : ''
    s_val != 0 ? s.val(s_val) : ''
    ms_val != 0 ? ms.val(ms_val) : ''

    p.find('input[type="hidden"]').val(((m_val * 60 * 1000) + (s_val * 1000) + ms_val))

  })

  $('.input-special').change(function (e) {

    var unit = $(this).find('.input-special__unit').val()
    var value = $(this).find('.input-special__value').val()
    var hidden = $(this).find('.input-special__hidden')

    hidden.val(JSON.stringify({
      'value': value,
      'unit': unit,
    }))
  })

  $('.input-measurement').change(function (e) {

    const unit_cm = $(this).find('.unit-cm')
    const unit_ft = $(this).find('.unit-ft')
    var unit = $(this).find('.input-special__unit').val()
    var hidden = $(this).find('input[type="hidden"]')
    var value = ''

    if (unit == 'ft') {
      unit_ft.show()
      unit_cm.hide()

      var ft = unit_ft.find('.ft').val()
      var inc = unit_ft.find('.inc').val()

      if (ft && !inc) {
        value = ft
      }

      if (ft && inc) {
        value = ft + ',' + inc
      }

    } else if (unit == 'cm') {
      unit_cm.show()
      unit_ft.hide()
      value = unit_cm.find('input').val()
    }

    if (value) {
      hidden.val(JSON.stringify({
        'value': value,
        'unit': unit,
      }))
    } else {
      hidden.val('')
    }

  })

})