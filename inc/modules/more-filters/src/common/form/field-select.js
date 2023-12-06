import {selectize_show, selectize_hide, selectize_clear_options, selectize_add_options} from "../plugins/selectize";
import {search_category, serialize_categories_to_options} from "../helpers";


(function ($) {

    /**
     * Render functions
     */


    function render_select(category, field_data, index) {
        const label = category?.group_label ?? `Child ${index}`
        let options = serialize_categories_to_options(category?.children)

        if (!options) {
            return
        }

        options = render_select_options(options)

        return `
    <div class="esl-subfield esl-subfield-${index}">
      <label for="esl-field-${field_data.id}-${index}">${label}</label>
      <select id="esl-field-${field_data.id}-${index}" name="taxonomy-${field_data.taxonomy}" class="esl-input esl-input-${field_data.type}" data-taxonomy="${field_data.taxonomy}">
      <option value="" selected="" class="option-placeholder">Choose option</option>
      ${options}
      </select>
    </div>`
    }

    function render_select_options(options) {
        let fragment = ''

        if (options) {
            options.forEach(function (option) {
                fragment += `<option value="${option.value}">${option.text}</option>`
            })
        }

        return fragment
    }


    /**
     * Select Functions
     */
    function select_remove_options(select) {
        select.prop('disabled', true)
        select.find('option:not(.option-placeholder)').remove()

        selectize_clear_options($(this))
    }

    function select_add_options(select, categories) {

        const options = serialize_categories_to_options(categories)

        const fragment_options = render_select_options(options)
        select.prop('disabled', false)
        select.append(fragment_options)

        selectize_add_options($(this), options)
    }


    /**
     * Triggers
     */

    let elSelectize = $('.esl-field:not(.esl-hidden) select.selectize')

    if (elSelectize.length) {
        elSelectize.selectize()
    }

    /**
     * Hierarchical Select
     */
    $('.esl-field-select_hierarchical')
        .on('change', 'select', function () {

            let args = {
                'field': '',
                'field_id': '',
                'field_data': '',
                'subfields': '',
                'category_data': '',
                'subfield_current': '',
                'depth': ''
            }

            const field = $(this).closest('.esl-field-select_hierarchical')
            const field_id = field.attr('id')
            const field_data = esfields[field_id]
            const subfields = field.find('.esl-subfield')
            const category_data = search_category(field_data.categories, $(this).val())
            const subfield_current = $(this).closest('.esl-subfield')
            const depth = subfield_current.index()

            if (field_data.render_type == 'static') {

                //clear next selects
                subfields.each(function () {
                    if ($(this).index() > depth) {
                        const select = $(this).find('select')
                        select_remove_options(select);
                    }
                })

                //update next select
                if (category_data?.children) {
                    const next_field = subfield_current.next()
                    const next_select = next_field.find('select')
                    select_add_options(next_select, category_data.children);
                }

            }

            if (field_data.render_type == 'dynamic') {

                //remove selects
                subfields.each(function () {
                    if ($(this).index() > depth) {
                        $(this).remove()
                    }
                })


                //create new select
                const new_select = render_select(category_data, field_data, depth + 1)

                if (new_select) {
                    field.append(new_select)

                    //selectize
                    let elSelectize = field.find('select:not(.selectized)')

                    if (elSelectize.length) {
                        elSelectize.selectize()
                    }
                }
            }

        })

    // $('.esl-field-select_hierarchical .esl-subfield').on('select_hierarchical:subfield_remove', function (e) {
    //   const selectize = $(this).find('select.selectize').data('selectize')
    //   console.log(selectize)
    //   if(!!selectize){
    //     selectize.destroy()
    //   }
    //   const selectize2 = $(this).find('select.selectize').data('selectize')
    //   console.log(selectize2)
    // })

})(jQuery)
