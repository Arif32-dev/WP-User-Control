; (function ($) {
    const app = {
        userCreationForm: $('#pp_form'),
        alertBox: $('#wpuc_alert_box'),

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
                    action: 'wpuc_form_submit',
                    form_data: formData
                },
                type: 'post',
                success: res => {
                    console.log(res);
                    if (res == 'success')
                        app.alertBox.removeClass('wpuc_fail').addClass('wpuc_success').html(`
                                    Account Created Successfully.
                            `).hide().slideDown();
                    else
                        app.alertBox.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    ${res.replace(/"/g, "")}
                            `).hide().slideDown();

                },
                error: err => {
                    app.alertBox.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    Something Went Wrong.
                            `).hide().slideDown();
                }
            })
        }
    };

    document.addEventListener('DOMContentLoaded', app.init);

})(jQuery);