var SignIn = function() {
    const _this = this;
    return {
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

            /* SignIn form - Initialize Validation */
            $('#form-signin').validate({
                errorClass: 'help-block animation-slideUp', // You can change the animation class for a different entrance animation - check animations page
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    e.parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
                    $(e).closest('.help-block').remove();
                },
                success: function(e) {
                    if (e.closest('.form-group').find('.help-block').length === 2) {
                        e.closest('.help-block').remove();
                    } else {
                        e.closest('.form-group').removeClass('has-success has-error');
                        e.closest('.help-block').remove();
                    }
                },
                submitHandler: function(form) {
                    // $(form).ajaxSubmit();
                    event.preventDefault();
                    SignIn.validateUser()
                    .then(function(result) {
                        console.log(result);
                        if(result.user.id !== undefined) {
                            $('.alert-message').addClass('success');
                            $('.alert-message-text').html('Successful login')
                            // console.log('completed user registration');
                            window.location.href = '/sunu/home';
                        }
                        else {
                            $('.alert-message').addClass('error');
                            $('.alert-message-text').html(result.message || 'error signing in');
                        }
                    })
                    .catch(error => {
                            $('.alert-message').addClass('error');
                            if(error.status === 401) {
                                $('.alert-message-text').html(error.responseJSON.message);
                            }
                            else {
                                $('.alert-message-text').html(error.message || 'error signing in');
                            }
                    })
                },
                rules: {
                    'username': {
                        required: true,
                        minlength: 3
                    },
                    'userpassword': {
                        required: true,
                        minlength: 5
                    }
                },
                messages: {
                    'username': {
                        required: 'Please valid username'
                    },
                    'userpassword': {
                        required: 'Please provide a password',
                        minlength: 'Your password must be at least 5 characters long'
                    }
                }
            });
        },

        validateUser: function() {
            const user_data = {
                username: $('#username').val(),
                password: $('#userpassword').val(),
            };
            console.log(user_data);
            const url = api_urls.login;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                        type: "POST",
                        method: "POST",
                        url: url,
                        data: user_data,
                        // contentType: "application/json; charset=utf-8",
                        success: function(msg){
                            resolve(msg);
                        },
                        error: function(err) {
                            console.log(err);
                            reject(err);
                        }
                });
            });
            return promise;
        },
    };
}();