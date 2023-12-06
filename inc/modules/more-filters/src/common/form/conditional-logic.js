/**
 * Conditional Logic
 */

window['esConditionalParents'] = {};

import {selectize_show, selectize_hide } from "../plugins/selectize";

(function ($) {

    function is_conditional_logic(field_id) {

        const field_data = esfields[field_id]

        for (const condition_row of field_data.conditionals) {
            let is_valid_row = true

            for (const condition of condition_row) {

                const input_value = get_value_by_name(condition.name)

                // console.log(condition.name + ': ' + input_value)

                let is_valid_condition = false

                switch (condition.operator) {
                    case '==':
                        is_valid_condition = (input_value == condition.value)
                        break

                    case '!=':
                        is_valid_condition = (input_value != condition.value)
                        break

                    case '==empty':
                        is_valid_condition = input_value == ''
                        break

                    case '!=empty':
                        is_valid_condition = input_value != ''
                        break

                    case '==contains':

                        if (Array.isArray(input_value)) {
                            is_valid_condition = input_value.includes(condition.value)
                        } else {
                            is_valid_condition = input_value.includes(condition.value)
                        }

                        break
                }

                // if one of conditions is false then all row condition is false
                if (!is_valid_condition) {
                    is_valid_row = false
                    break
                }
            }

            //if one of row of conitions is true (OR) then stop and return true
            if (is_valid_row) {
                return is_valid_row
            }

        }

        return false
    }

    function get_value_by_name(name) {
        return $(`[name="${name}"]`).val()
    }


    /**
     * Triggers
     */
    //field toggle
    $('.esl-field')
        .on('field:toggle', function (event) {
            const field_id = $(this).attr('id')
            if (is_conditional_logic(field_id)) {
                $(this).trigger('field:show')
            } else {
                $(this).trigger('field:hide')
            }
        })
        .on('field:hide', function (event) {
            const field = $(this)
            let input = field.find('.esl-input')


            //selectize hide
            if (input.hasClass('selectize')) {
                selectize_hide(input);
            }

            //editor hide
            if (field.hasClass('esl-field-editor')) {
                input = field.find('textarea')
                input.html('')
            }

            let input_name = input.attr('name')

            field.addClass('esl-hidden').prop('hidden', true)
            input.attr('disabled', 'disabled')
            input.val('')

            //toggle Visibility for field logical children
            if (esConditionalParents[input_name] !== undefined) {
                esConditionalParents[input_name].forEach(function (field_id) {
                    $('#' + field_id).trigger('field:toggle')
                })
            }
        })
        .on('field:show', function (event) {
            const field = $(this)
            const input = field.find('.esl-input')

            field.removeClass('esl-hidden').prop('hidden', false)
            field.find('[disabled]').removeAttr('disabled')

            //is selectize
            if (input.hasClass('selectize')) {
                selectize_show(input);
            }
        })

    /**
     * Init
     */

    //Set "esConditionalParents" variable from "esfields" variable
    for (const [field_id, field_data] of Object.entries(esfields)) {
        if ($.isEmptyObject(field_data.conditionals)) {
            continue
        }

        for (const conditions_row of field_data.conditionals) {
            for (const condition of conditions_row) {
                if (!esConditionalParents.hasOwnProperty(condition.name)) {
                    esConditionalParents[condition.name] = [field_id]
                } else {
                    if (!esConditionalParents[condition.name].includes(field_id)) {
                        esConditionalParents[condition.name].push(field_id)
                    }
                }
            }
        }
    }

    //Add event listeners for each conditionals fields
    for (const [parent_name, conditionalFieldsIds] of Object.entries(esConditionalParents)) {

        //init
        for (const field_id of conditionalFieldsIds) {
            $('#' + field_id).trigger('field:toggle')
        }

        $(`[name="${parent_name}"]`).on('change', function () {
            for (const field_id of conditionalFieldsIds) {
                $('#' + field_id).trigger('field:toggle')
            }
        })
    }


})(jQuery);