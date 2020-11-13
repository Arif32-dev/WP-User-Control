; (function ($) {
    const app = {
        userCreationForm: $('#pp_form'),
        login_form: $('#pp_form_login'),
        alertBox: $('#wpuc_alert_box'),
        login_alert_box: $('#wpuc_alert_box_login'),

        init: () => {
            app.events();
        },

        events: () => {
            app.userCreationForm.on('submit', app.form_submit);
            app.login_form.on('submit', app.user_login);
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
                    if (res == 'success') {
                        app.alertBox.removeClass('wpuc_fail').addClass('wpuc_success').html(`
                                    Account Created Successfully.
                            `).hide().slideDown();
                    } else {
                        app.alertBox.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    ${res.replace(/"/g, "")}
                            `).hide().slideDown();
                    }
                },
                error: err => {
                    app.alertBox.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    Something Went Wrong.
                            `).hide().slideDown();
                }
            })
        },
        user_login: (e) => {
            e.preventDefault();
            let formData = $(e.currentTarget).serialize();
            $.ajax({
                url: file_url.admin_ajax,
                data: {
                    action: 'wpuc_form_login',
                    form_data: formData
                },
                type: 'post',
                success: res => {
                    console.log(res);
                    if (res == 'success') {
                        app.login_alert_box.removeClass('wpuc_fail').addClass('wpuc_success').html(`
                                    Logged In Successfully,
                                    `).hide().slideDown(600, function () {
                            location.reload();
                        });
                    } else {
                        app.login_alert_box.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    ${res.replace(/"/g, "")}
                            `).hide().slideDown();
                    }
                },
                error: err => {
                    app.login_alert_box.removeClass('wpuc_success').addClass('wpuc_fail').html(`
                                    Something Went Wrong.
                            `).hide().slideDown();
                }
            })
        }
    };

    document.addEventListener('DOMContentLoaded', app.init);

})(jQuery);