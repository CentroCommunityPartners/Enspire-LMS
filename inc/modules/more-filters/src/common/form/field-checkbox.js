(function ($) {

  function delay (callback, ms) {
    var timer = 0
    return function () {
      var context = this, args = arguments
      clearTimeout(timer)
      timer = setTimeout(function () {
        callback.apply(context, args)
      }, ms || 0)
    }
  }

  function parentChekcboxBubbleCheck (currentLi) {
    const parentUl = currentLi.closest('ul')
    const levelInputs = parentUl.find('li input')
    const prevLi = parentUl.closest('li')
    let is_checked = false

    if (parentUl.length) {
      levelInputs.each(function () {
        if ($(this).prop('checked')) {
          is_checked = true
          return
        }
      })
    }

    if (prevLi.length) {
      prevLi.find('> input').prop('checked', is_checked)
      parentChekcboxBubbleCheck(prevLi)
    }
  }

  function parentLiBubbleShow (currentLi) {
    const prevLi = currentLi.closest('ul').closest('li')
    if (prevLi.length) {
      prevLi.addClass('show')
      prevLi.find('.btn-toggle-ul').attr('aria-expanded', true)
      parentLiBubbleShow(prevLi)
    }
  }

  function onCheckbox (e) {
    const li = $(this).closest('li')

    //then click on parent check/uncheck all children
    if (li.hasClass('has-children')) {
      const is_checked = $(this).prop('checked')

      if (is_checked) {
        //if is true only near ul
        li.find(' > ul > li:visible > input').prop('checked', true)
      } else {
        //if is false all ul
        li.find('ul li input').prop('checked', false)
      }

    }

    //then click on child check/uncheck all parents of this child (recursive)
    parentChekcboxBubbleCheck(li)
  }

  function onToggle (e) {
    e.preventDefault()

    const expanded = ($(this).attr('aria-expanded') === 'true')
    const currentLI = $(this).closest('li')
    const elChildrenLi = currentLI.find('ul li.has-children')

    $(this).attr('aria-expanded', !expanded)

    //then close parent, close all children
    if (elChildrenLi.length && !expanded == false) {
      elChildrenLi.each(function () {
        $(this).find('.btn-toggle-ul').attr('aria-expanded', !expanded)
      })
    }
  }

  function strHighlight (s, search) {

    if (!search || !s) {
      return
    }

    const index = s.toLowerCase().indexOf(search.toLowerCase())
    let rs = ''

    if (index >= 0) {
      rs = s.substring(0, index) + '<span class=\'highlight\'>' + s.substring(index, index + search.length) + '</span>' + s.substring(index + search.length)
    }

    return rs
  }

  $('.esl-field-checkbox').on('change', 'input[type="checkbox"]', onCheckbox)
  $('.esl-field-checkbox').on('click', '.btn-toggle-ul', onToggle)

  function doSearch (input, elRootUl, allLi) {
    const search = input.val()

    if (search.length < 3) {
      elRootUl.removeClass('filterable')

      elRootUl.find('li.show').each(function () {
        $(this).find('.btn-toggle-ul[aria-expanded="true"]').attr('aria-expanded', false)
        $(this).removeClass('show')

        if ($(this).hasClass('highlighted')) {
          const label = $(this).find(' > label')
          label.html(label.text())
          $(this).removeClass('highlighted')
        }
      })

      $('.esl-list-search-wrap').removeClass('loading')
      return true
    }

    elRootUl.addClass('filterable')

    console.log('start')


    allLi.each(function () {
      console.log('in')
      const label = $(this).find(' > label')
      const highlighted = strHighlight(label.text(), search)

      if (highlighted) {
        $(this).addClass('show').addClass('highlighted')
        label.html(highlighted)

        parentLiBubbleShow($(this))

      } else {
        $(this).removeClass('show').removeClass('highlighted')
        label.html(label.text())
      }
    })

    console.log('end')

    return true
  }

  $('.esl-list-search-wrap').each(function () {
    const searchWrap = $(this)
    const searchInput = searchWrap.find('.esl-list-search')
    const elField = $(this).closest('.esl-field-checkbox')
    const elRootUl = elField.find('> ul')
    const allLi = elRootUl.find('li')
    const doneTypingInterval = 500  //time in ms, 0.5 seconds for example
    let typingTimer                //timer identifier

    //on keyup, start the countdown
    searchInput.on('keyup', function () {
      const input = $(this)
      clearTimeout(typingTimer)

      typingTimer = setTimeout(function () {
        doSearch(input, elRootUl, allLi)
      }, doneTypingInterval)

    })

    //on keydown, clear the countdown
    searchInput.on('keydown', function () { clearTimeout(typingTimer) })
  })

})(jQuery)