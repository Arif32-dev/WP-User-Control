; (function ($) {
    const app = {
        userCreationForm: $('#pp_form'),

        init: () => {
            app.events();
        },

        events: () => {
            app.userCreationForm.on('submit', app.form_submit)
        },

        form_submit: (e) => {
            e.preventDefault();
            let formData = $(e.currentTarget).serialize();
            $.ajax({
                url: file_url.admin_ajax,
                data: {
                    action: 'pp_form_submit',
                    form_data: formData
                },
                type: 'post',
                success: res => {
                    console.log(res);
                },
                error: err => {
                    alert('Something went wrong');
                }
            })
        }
    };

    document.addEventListener('DOMContentLoaded', app.init);

})(jQuery);