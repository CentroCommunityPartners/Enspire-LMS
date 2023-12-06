

//Get Checkbox Values
import UrlManager from "./common/urlmanager";

function get_checkbox_input_value(el) {
    var terms = []

    el.find('input:checked').each(function (index) {
        if (terms.indexOf($(this).val()) === -1) {
            terms.push($(this).val())
        }
    })

    //sort terms
    if (terms.length > 1) terms.sort()

    return terms
}

//Hide/Show Button Load More
function button_load_more(el) {
    el.text('Load more')
    if (current_page >= max_pages) {
        el.hide()
    } else {
        el.show()
    }
}

/****************************************
 *            CONST & VARS              *
 ****************************************/
const row_items = '.ensp-ajax-items'
const row_filters = '.ensp-ajax-filters'
const filter_item = '.ensp-filter'

if (!$(row_items).length) return
let current_page = 1

var data = {
    'action': 'more_filters',
    'query_args': query_args,
    'page': current_page,
    'max_pages': max_pages,
    'taxonomies': {},
}

/****************************************
 *              AJAX ACTION             *
 ****************************************/

function ajax_applay_action(el) {
    data.taxonomies = {}
    var emptyFilters = true;

    //for all filters
    $(filter_item).each(function (i) {
        var url_param = $(this).data('slug')
        var input = $(this).data('input')
        var taxonomy = $(this).data('taxonomy')
        var val = ''


        switch (input) {
            case 'search':
                val = $(this).find('input').val()
                query_args.search = val

                if (val) {
                    UrlManager.updateParam(url_param, val)
                } else {
                    UrlManager.deleteParam(url_param)
                }

                break

            case 'select':
                val = $(this).find('option:selected').val()

                //set data.taxonomies
                if (!data.taxonomies[taxonomy]) {
                    data.taxonomies[taxonomy] = {}
                }

                if (val.length) {
                    data.taxonomies[taxonomy][url_param] = val
                    UrlManager.updateParam(url_param, val)
                } else {
                    UrlManager.deleteParam(url_param)
                }

                break
            case 'radio':
                val = $(this).find('input:checked').val()

                //set data.taxonomies
                if (!data.taxonomies[taxonomy]) {
                    data.taxonomies[taxonomy] = {}
                }

                data.taxonomies[taxonomy][url_param] = val

                UrlManager.updateParam(url_param, val)
                break
            case 'checkbox':
                var val = get_checkbox_input_value($(this))

                //set data.taxonomies
                if (!data.taxonomies[taxonomy]) {
                    data.taxonomies[taxonomy] = {}
                }

                data.taxonomies[taxonomy][url_param] = val

                //set url param
                if (val.length > 0) {
                    UrlManager.updateParam(url_param, val)
                } else {
                    UrlManager.deleteParam(url_param)
                }
                break
            case 'button':
                var val = $(this).find('a.button--active').prop('hash').substr(1)

                if (val != 'all') {
                    data.taxonomies[taxonomy] = val
                    UrlManager.updateParam(url_param, val)
                } else {
                    data.taxonomies[taxonomy] = []
                    UrlManager.deleteParam(url_param)
                }

                var active_filter_button = $(row_filters + ' a[href="#' + val + '"]')
                if (active_filter_button) {
                    $(row_filters + ' a').removeClass('button--active')
                    active_filter_button.addClass('button--active')
                }
                break
            default:

        }

        if (val.length) {
            emptyFilters = false
        }
    })

    // if (emptyFilters) {
    //   UrlManager.deleteParams()
    // }

    var parent = el.closest('.ensp-filter')

    /**
     * Ajax Populated Select on Change Parent
     * reset taxonomy param for child select
     */
    if (parent.hasClass('ensp-filter-ajax-populate')) {
        var taxonomy = parent.data('taxonomy')
        var id_populated = parent.data('populate-id')
        var el_populate = $('#' + id_populated)
        var url_param_populated = el_populate.data('slug')

        if (data.taxonomies[taxonomy][url_param_populated]) {
            delete data.taxonomies[taxonomy][url_param_populated]
        }
    }

    get_ajaxposts_update()
}

/****************************************
 *           APPLY AJAX ACTION          *
 ****************************************/
//button apply
$('#ensp-filter-apply').click(function (e) {
    e.preventDefault()
    ajax_applay_action($(this))
})

//search input
$('.ensp-filter-search input').on('keypress', function (e) {
    if (e.which != 13) {
        return
    } //if is enter
    ajax_applay_action($(this))
})

if ($(row_filters).data('apply') == 'onchange') {
    $(row_filters).on('change', 'input,select', function (e) {
        ajax_applay_action($(this))
    })
}

/****************************************
 *             FILTERS BUTTON           *
 ****************************************/
$('.morefilters').on('click', '.button-ajax-tag', function (e) {
    e.preventDefault()

    var term = $(this).data('hash')
    var parent = $(this).closest('[data-taxonomy]')
    var url_param = parent.data('slug')
    var taxonomy = parent.data('taxonomy')

    if (term) {
        data.taxonomies[taxonomy] = term
        UrlManager.updateParam(url_param, term)
    } else {
        term = 'all'
        data.taxonomies[taxonomy] = []
        UrlManager.deleteParam(url_param)
    }

    get_ajaxposts_update()

    var active_filter_button = $(row_filters + ' a[href="#' + term + '"]')
    if (active_filter_button) {
        $(row_filters + ' a').removeClass('button--active')
        active_filter_button.addClass('button--active')
    }

})

/****************************************
 *          Clear All Filters           *
 ****************************************/
$('#ensp-filter-clearall').click(function () {

    $(filter_item).each(function (i) {
        var input = $(this).data('input')
        var taxonomy = $(this).data('taxonomy')
        var url_param = $(this).data('slug')
        var default_value = $(this).data('default')

        switch (input) {
            case 'search':
                $(this).find('input').val('')
                query_args.search = ''
                break
            case 'button':
                //todo same how for select
                $(this).find('a').removeClass('button--active')
                $(this).find('a[href="#all"]').addClass('button--active')

                if (data.taxonomies[taxonomy][url_param]) {
                    delete data.taxonomies[taxonomy][url_param]
                }
                break
            case 'radio':
            case 'checkbox':
                //todo same how for select
                $(this).find('input:checked').prop('checked', false)

                if (data.taxonomies[taxonomy][url_param]) {
                    delete data.taxonomies[taxonomy][url_param]
                }
                break
            case 'select':

                if (typeof default_value !== typeof undefined && default_value !== false) {
                    $(this).find('select').val(default_value).change()
                    data.taxonomies[taxonomy][url_param] = default_value
                } else {
                    $(this).find('select').val('').change()
                    //$(this).find('option:selected').prop('selected', false)
                    if (data.taxonomies[taxonomy][url_param]) {
                        delete data.taxonomies[taxonomy][url_param]
                    }
                }
                break
        }
    })

    UrlManager.deleteParams()
    get_ajaxposts_update()
})

/****************************************
 *              AJAX ACTION             *
 ****************************************/
function get_ajaxposts_update() {
    current_page = 0
    data.page = current_page
    $.ajax({
        url: ajaxurl.ajaxurl,
        data: data,
        type: 'POST',
        beforeSend: function () {
            $('.ensp-ajax-content').addClass('loading-big')
        },
        success: function (response) {

            $('.ensp-ajax-content').removeClass('loading-big')

            if (response) {
                $(row_items + ' > *').remove()
                $(row_items).append(response.posts)
                current_page++
                data.page = current_page
                max_pages = response.max_pages
                button_load_more($('.button-loadmore'))
            }
        },
    })
}


//Infinite scroll
// $(window).on("scroll", function() {
//   var scrollHeight = $(document).height();
//   var scrollPos = $(window).height() + $(window).scrollTop();
//   if ((scrollHeight - scrollPos) / scrollHeight == 0) {
//     $('.button-loadmore').click();
//     console.log("bottom!");
//   }
// });

/****************************************
 *                  INIT                *
 ****************************************/

var search = location.search.substring(1)

$('.ensp-filter-button a[href="#all"]').addClass('button--active')

if (search) {
    var onLoadParams = JSON.parse('{"' + search.replace(/&/g, '","').replace(/=/g, '":"') + '"}', function (key, value) {
        return key === '' ? value : decodeURIComponent(value)
    })
    console.log(onLoadParams)
    Object.keys(onLoadParams).forEach(key => {

        var parent_filter = $(row_filters).find('[data-slug="' + key + '"]')
        var searchParams = decodeURI(onLoadParams[key])
        var input = parent_filter.data('input')

        switch (input) {
            case 'search':
                parent_filter.find('input').val(searchParams)
                break

            case 'radio':
                parent_filter.find('input').each(function (i) {
                    if (searchParams == $(this).val()) {
                        $(this).prop('checked', true)
                    }
                })
                break

            case 'checkbox':
                parent_filter.find('input').each(function (i) {
                    var arrParams = searchParams.split(',')
                    if (arrParams.includes($(this).val())) {
                        $(this).prop('checked', true)
                    }
                })
                break

            case 'select':
                parent_filter.find('option').each(function (i) {
                    if (searchParams == $(this).val()) {
                        $(this).prop('selected', true)
                    }
                })
                break

            case 'button':
                parent_filter.find('a').removeClass('button--active')
                parent_filter.find('a[href="#' + searchParams + '"]').addClass('button--active')
                break
        }

        if (parent_filter.hasClass('ensp-filter-ajax-populate')) {
            ajax_populate_fields(parent_filter)
        }
    })
} else {
    //populate on load if doesnt exist param in url
    $('.ensp-filter-ajax-populate').find('input,select').each(function (e) {
        var parent = $(this).closest('.ensp-filter-ajax-populate')
        ajax_populate_fields(parent)
    })
}

//Hide/Show Button Load More
button_load_more($('.button-loadmore'))

/****************************************
 *       AJAX POPULATING FIELDS         *
 ****************************************/

//populate on change
$('.ensp-filter-ajax-populate').change('input,select', function (e) {
    var parent = $(this).closest('.ensp-filter-ajax-populate')
    ajax_populate_fields(parent)
})

function ajax_populate_fields(parent) {
    var input = parent.data('input')
    var term = []
    var id_populated = parent.data('populate-id')
    var el_populated = $('#' + id_populated)
    var url_param_populated = el_populated.data('slug')

    switch (input) {
        case 'radio':
        case 'checkbox':
            term = parent.find('input:checked').val()
            break
        case 'select':
            term = parent.find('select').val()
            break
    }

    //Ajax
    var data = {
        'action': 'taxonomy_populate',
        'taxonomy': parent.data('taxonomy'),
        'term': term,
        'input': el_populated.data('input'),
    }

    $.ajax({
        url: ajaxurl.ajaxurl,
        data: data,
        type: 'POST',
        beforeSend: function () {
            $('.ensp-ajax-content').addClass('loading-big')
            el_populated.addClass('loading-small')
        },
        success: function (response) {
            if (response) {

                $('.ensp-ajax-content').removeClass('loading-big')
                el_populated.removeClass('loading-small')

                el_populated.find('.ensp-filter__list-item:not(.item-placeholder)').remove()
                el_populated.find('.ensp-filter__list').append(response)

                //Update URL Params
                UrlManager.updateParam(parent.data('slug'), term)
                UrlManager.deleteParam(url_param_populated)
            }
        },
    })
}