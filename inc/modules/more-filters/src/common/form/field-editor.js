

(function ($) {

  if ($('.esl-field-editor').length) {
    const isInitedTinymce = setInterval(onInitTinymce, 100)

    function onInitTinymce () {
      const editor_k = tinymce.editors.length

      //if last editor is not initialized
      if (tinymce.editors[editor_k - 1]?.initialized != true) {
        return
      }

      //add for each editors on change function
      tinymce.editors.forEach(function (ed) {
        ed.targetElm.classList.add('esl-input')

        ed.onChange.add(function () {
          const val = ed.getContent();
          $('#' + ed.id).val(val).html(val)
        })
      })

      clearInterval(isInitedTinymce)
    }
  }


})(jQuery)