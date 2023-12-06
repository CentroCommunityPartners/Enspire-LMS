jQuery(document).ready(function ($) {


    $('#pum-88772').on('pumAfterClose', () => location.reload());

    window.unsubscribePopup = {
        id: 88772,
        el: $('#pum-88772'),
        step: 0,
        subID: 0,
        nextStep: function () {
            this.step++;
            this.el.find('.popmake-content > ul > li').removeClass('active')
            this.el.find($('.popmake-content > ul > li')[this.step]).addClass('active')
            if (this.step > 0) $('.pum-close').show();
            return this;
        },
        unsubscribe: function (button) {
            if (!this.subID) {
                console.log('Undefined sub_id');
                return;
            }

            console.log(window.unsubscribePopup.subID)

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/wp-admin/admin-ajax.php',
                data: {
                    action: 'mft_cancel_subscription',
                    'id': window.unsubscribePopup.subID
                },
                beforeSend: function () {
                    button.addClass('loading');
                },
                success: function (rs) {
                    button.removeClass('loading');

                    if (rs.success) {
                        unsubscribePopup.nextStep();
                        return;
                    }

                    if (rs.error) {
                        alert(rs.error);
                    } else {
                        alert("I can't do it, something is wrong!");
                    }

                    window.unsubscribePopup.close();
                },
            })
        },
        close: function () {
            PUM.close(window.unsubscribePopup.id);
        }
    };

    $('a.mepr-account-cancel').click(function (e) {
        e.preventDefault();
        PUM.open(window.unsubscribePopup.id);
        const url_string = $(this).attr('href');
        const url = new URL(url_string);
        window.unsubscribePopup.subID = url.searchParams.get("cancel_sub");
    });

    $('#mft-cancel-subscription').click(function (e) {
        e.preventDefault();
        window.unsubscribePopup.unsubscribe($(this))
    });


    //BTWB Events
    $('.bywb-subscribe,.bywb-cancel-subscribe').on('click', function () {
        //todo for new membership

        // $.ajax({
        //   type: 'post',
        //   dataType: 'json',
        //   url: '/wp-admin/admin-ajax.php',
        //   data: {
        //     action: 'rcp_toggle_bywb_subscription',
        //   },
        //   success: function (response) {
        //     console.log('reload')
        //     location.reload()
        //   },
        // })
    });


    //Unsubscribe Events
    $('.popmake.theme-56229 a').on('click', function (event) {
        event.preventDefault()

        var href = $(this).attr('href')

        if (href) {
            window.location.href = href
        }
        return false
    })


});
