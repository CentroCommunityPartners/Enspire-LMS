
export function validation_field(field) {
    let error = ''
    let value, empty
    const fieldName = field.attr('name') ?? field.attr('id')
    const isRequired = field.hasClass('esl-required')

    let input = field.find('.esl-input')
    value = input.val()

    //is field editor
    if (field.hasClass('esl-field-editor')) {
        input = field.find('textarea')
        value = input.text()
    }

    if (isRequired) {
        empty = !Boolean(value?.length)
        if (empty) {
            error = `${fieldName} is required !`
        }
    }

    if (error) {
        field.addClass('esl-field-error')
        console.log(error)
    } else {
        field.removeClass('esl-field-error')
    }

    return error
}

export function validation_section(section) {
    let status = true
    const formFields = section.find('.esl-field:not(.esl-hidden)')

    formFields.each(function () {
        const field = $(this)
        const error = validation_field(field)
        if (error) {
            status = false
        }
    })

    return status
}