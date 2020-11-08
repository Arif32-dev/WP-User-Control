; (function ($) {
    const app = {
        userCreationForm: $('#pp_form'),

        init: () => {
            app.form_submission();
        },

        form_submission: () => {
        }

    };

    document.addEventListener('DOMContentLoaded', app.init);

})(jQuery);