(function ($) {

  $.fn.formDraft = function () {
    const form = $(this)
    let form_id = form.attr('id')

    if (!form_id) {
      form_id = form.attr('name')
    }

    if (!form_id) {
      return this
    }

    //find all fields
    const fields = form.find('input,select,textarea')

    return {
      save: function () {

        //fields init
        fields.each(function () {
          const field_id = $(this).attr('id')
          const store_key = form_id + '_' + field_id
          let store_val = localStorage.getItem(store_key)

          if (store_val === null || store_val === undefined) {
            return
          }

          if ($(this).hasClass('selectized')) {
            const selectize = $(this).data('selectize')
            if (!!selectize) selectize.setValue(store_val)
          } else {
            $(this).val(store_val)
          }

        })

        //fields change
        fields.change(function () {
          const field_id = $(this).attr('id')
          const store_key = form_id + '_' + field_id

          localStorage.setItem(store_key, $(this).val())
        })

        //if exists editors
        if (form.find('.wp-editor-area').length) {
          const isInitedTinymce = setInterval(onInitTinymce, 100)

          function onInitTinymce () {
            const editor_k = tinymce.editors.length

            //if last editor is not initialized
            if (tinymce.editors[editor_k - 1]?.initialized != true) {
              return
            }

            tinymce.editors.forEach(function (ed) {
              const field_id = ed.id
              const store_key = form_id + '_' + field_id
              const store_val = localStorage.getItem(store_key)

              ed.onChange.add(function () {
                const value = ed.getContent()
                localStorage.setItem(store_key, value)
                form.find('#' + field_id).val(value)
              })

              if (store_val != undefined) {
                ed.setContent(store_val)
              }
            })

            clearInterval(isInitedTinymce)
          }
        }

        return this
      },
      clear: function () {
        fields.each(function () {
          const field_id = $(this).attr('id')
          const store_key = form_id + '_' + field_id
          localStorage.removeItem(store_key)
        })

        return this
      },
    }

  }

}(jQuery))