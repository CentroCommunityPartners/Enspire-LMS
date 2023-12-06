import UrlManager from './common/urlmanager';
import './common/form/conditional-logic';
import './common/form/field-select';
import './common/form/validation';

(function ($) {

    $('.morefilters').each(function () {

        const wrap = $(this)
        const wrapContent = $(this).find('.ensp-ajax-content')
        const wrapFilters = $(this).find('.ensp-ajax-filters')
        const formFilters = $(this).find('.esl-form')
        const wrapItems = $(this).find('.ensp-ajax-items')
        const filterItems = $(this).find('.ensp-filter')
        const btnLoadMore = $(this).find('.button-loadmore')
        const btnSubmit = $(this).find('button[type="submit"]')
        const btnReset = $(this).find('button[type="reset"]')

        /**
         * {
         *  search: '',
         *  max_pages: 100,
         *  query: { paged: 1, post_type: 'post' ...},
         *  taxonomies: [
         *               0: {taxonomy: 'wod_phase', terms: ['phase-3']}
         *               1: {taxonomy: 'wod_week', terms: ['3']}
         *              ]
         *  }
         */

        let store = wrapContent.data('store');

        /******************************************
         * Helpers
         * ****************************************/

        function toggle_button_load_more() {
            if (store.paged >= store.max_pages || store.max_pages == 0) {
                btnLoadMore.addClass('disabled').hide()
            } else {
                btnLoadMore.removeClass('disabled').show()
            }
        }

        function toggle_button_reset(){
            if( isEmptyForm(formFilters) ){
                btnReset.addClass('disabled').hide()
            }else{
                btnReset.removeClass('disabled').show()
            }
        }

        function get_field_object_by_name(name) {

            let obj = {};

            Object.entries(window['esfields']).forEach(function (item) {
                if (item[1].name == name) {
                    obj = item[1];
                    return;
                }
            });

            return obj;
        }

        function isEmptyForm(form) {
            const formData = form.serializeArray()
            let isEmpty = true;

            formData.forEach((v) => {
                if (v.value) {
                    isEmpty = false;
                }
                return;
            });

            return isEmpty;
        }

        function resetForm(form) {
            form.trigger('reset')
            form.find('input:text, input:password, input:file').val('').removeAttr('value')
            form.find('textarea').val('').html('')
            form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
            form.find('select').val('').find('option:selected').removeAttr('selected');
        }


        /**
         * Store
         */
        function update_store_attr() {
            wrapContent.data('store', store)
        }

        function update_store_taxonomies(taxonomy, value) {
            if (!taxonomy) {
                return;
            }

            const terms = Array.isArray(value) ? value : [value];
            let item_key = undefined;


            store.taxonomies.forEach(function (item, key) {
                if (item.taxonomy == taxonomy) {
                    item_key = key;
                    return;
                }
            });

            //update existing
            if (item_key != undefined && value.length) {
                store.taxonomies[item_key].terms = terms;
            }

            //delete existing
            if (item_key != undefined && !value.length) {
                store.taxonomies = store.taxonomies.filter((v, i) => {
                    return item_key != i
                })
            }

            //add new
            if (item_key == undefined && value.length) {
                store.taxonomies.push({'taxonomy': taxonomy, 'terms': terms})
            }

            update_store_attr()
        }

        function update_store_search(value) {
            store.search = value;
            update_store_attr()
        }

        function clear_store() {
            store.taxonomies = [];
            store.meta = [];
            store.search = '';
            update_store_attr()
        }


        /******************************************
         * Form
         * ****************************************/

        //todo submit form event
        function submit_form_filters(e) {
            e.preventDefault();
        }


        /**
         * Ajax Get Posts
         * @param context loadmore or refresh
         */

        function ajax_get_posts(context = 'loadmore') {

            $.ajax({
                url: ajaxurl.ajaxurl,
                data: {
                    'action': 'more_filters',
                    'store': store,
                    'context': context
                },
                type: 'POST',
                beforeSend: function () {
                    console.log(store)

                    if (context == 'refresh') {
                        wrapContent.addClass('loading-big')
                    }

                    btnLoadMore.addClass('disabled').text('Loading...')
                },
                success: function (response) {
                    console.log(response)

                    if (context == 'refresh') {
                        wrapContent.removeClass('loading-big')
                    }

                    btnLoadMore.text('Load More')

                    if (!response) {
                        console.log('No response')
                        return;
                    }

                    if (response.error) {
                        console.log(response.error)
                    }

                    if (context == 'refresh') {
                        wrapItems.html('');
                    }

                    wrapItems.append(response.posts)
                    store.paged = response.paged
                    store.max_pages = response.max_pages
                    update_store_attr();
                    toggle_button_load_more();
                },
            });

            toggle_button_reset();
        }


        /******************************************
         * Events
         * ****************************************/
        //toggle button reset
        toggle_button_reset();

        //load more
        btnLoadMore.click(function (e) {
            e.preventDefault();
            ajax_get_posts('loadmore');
        })

        //reset event
        btnReset.click(function (e) {
            e.preventDefault();

            clear_store()

            ajax_get_posts('refresh')

            UrlManager.deleteParms()

            resetForm(formFilters)

            toggle_button_reset();
        });

        //On Submit Form
        // if (btnSubmit.length) {
        //     formFilters.submit(submit_form_filters)
        // }

        //On Change Input
        if (!btnSubmit.length) {
            formFilters.on('change', '.esl-input', function (e) {
                e.preventDefault();

                const name = $(this).attr('name')
                const value = $(this).val()
                const fieldObj = get_field_object_by_name(name);
                const fieldSlug = fieldObj?.slug ?? '';
                const taxonomy = name.substring(0, 9) == 'taxonomy-' ? name.slice(9) : '';

                //update url
                if (value) {
                    UrlManager.updateParam(fieldSlug, value)
                } else {
                    UrlManager.deleteParam(fieldSlug)
                }

                if (taxonomy) {
                    //is taxonomy
                    update_store_taxonomies(taxonomy, value);
                } else if (name == 'search') {
                    //is search
                    update_store_search(value)
                } else {
                    //is meta
                }

                ajax_get_posts('refresh');
            })
            formFilters.submit((e) => e.preventDefault())
        }

    });

})(jQuery)