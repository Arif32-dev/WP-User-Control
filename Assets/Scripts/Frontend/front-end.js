; (function ($) {
    const app = {
        userCreationForm: $('#pp_form'),
        alertBox: $('#alert_box'),

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
                        app.alertBox.html(`
                                <div class="alert alert-success" role="alert">
                                    Account Created Successfully. Wait for admin response.
                                </div>
                            `).hide().slideDown();
                    else
                        app.alertBox.html(`
                                <div class="alert alert-danger" role="alert">
                                    ${res.replace(/"/g, "")}
                                </div>
                            `).hide().slideDown();

                },
                error: err => {
                    app.alertBox.html(`
                                <div class="alert alert-danger" role="alert">
                                    Something Went Wrong.
                                </div>
                            `).hide().slideDown();
                }
            })
        }
    };

    document.addEventListener('DOMContentLoaded', app.init);

})(jQuery);