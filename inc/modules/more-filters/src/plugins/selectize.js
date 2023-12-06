export function selectize_instance(select) {
    return select.data('selectize')
}

export function selectize_show(select) {
    const selectize = select.data('selectize')

    if (!selectize) {
        select.selectize()
    } else {
        selectize.enable()
    }

}

export function selectize_hide(select) {
    const selectize = select.data('selectize')

    if (!!selectize) {
        selectize.clear()
       selectize.disable()
    }

}

export function selectize_clear_options(select) {
    const selectize = select.data('selectize')

    if (!!selectize) {
        selectize.clear()
        selectize.clearOptions()
        selectize.disable()
    }
}


export function selectize_add_options(select, options) {
    const selectize = select.data('selectize')

    if (!!selectize) {
        selectize.addOption(options)
        selectize.enable()
        selectize.refreshOptions()
    }
}

