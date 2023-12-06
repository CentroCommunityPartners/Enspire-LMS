(function ($) {

    const signup = $('.es-signup')
    const signupHeader = signup.find('.es-signup__header')
    const signupContent = signup.find('.es-signup__content')

    const toggle = signupHeader.find('.toggle-js')
    const toggle_input = toggle.find('input')

    function toggle_signup_form() {
        const checked = !toggle_input.prop('checked')
        const plan_type = checked ? 'yearly' : 'monthly';

        signup.removeClass('monthly').removeClass('yearly');
        signup.addClass(plan_type)

        toggle_input.prop('checked', checked)
    }

    toggle.click(function (e) {
        e.preventDefault()
        toggle_signup_form()
    });

})(jQuery);