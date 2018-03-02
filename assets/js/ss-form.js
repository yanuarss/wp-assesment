(function (e) {
    e(window.jQuery, window, document);
})(function ($, window, document) {

    class SSForm {
        constructor() {

            this.form();
        }

        form() {

            // if ($('.ss-form form').length < 1)
            //     return;

            $('.ss-form form').validate({
                rules: {
                    ss_name: 'required',
                    ss_message: 'required',
                    ss_form_action_field: 'required',
                    ss_email: {
                        required: true,
                        email: true
                    },
                }
            });
        }
    }

    $(document).ready(function(e) {
        new SSForm();
    });
});
