var Register = function() {
    const _this = this;
    return {
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

            /* Register form - Initialize Validation */
            $('#form-register').validate({
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
                    ReadyRegister.registerUser()
                    .then(function(result) {
                        console.log(result);
                        let response = JSON.parse(result);
                        if(response.id !== undefined) {
                            $('#statusTitle').html('Registration Form Submission Successful');
                            $('#statusMessage').html('The registration details have been successfully saved');
                        }
                        else {

                        }
                        // window.location = 'page_ready_callerform.html';
                        $('#modal-registration-form').modal('show');
                    });
                },
                rules: {
                    'title': {
                        required: true,
                        minlength: 2
                    },
                    'firstname': {
                        required: true,
                        minlength: 3
                    },
                    'lastname': {
                        required: true,
                        minlength: 3
                    },
                    'email_address': {
                        required: true,
                        email: true
                    },
                    'userpassword': {
                        required: true,
                        minlength: 5
                    },
                    'confirmpassword': {
                        required: true,
                        equalTo: '#userpassword'
                    }
                },
                messages: {
                    'title': {
                        required: 'Please select a title'
                    },
                    'firstname': {
                        required: 'Please enter a firstname',
                        minlength: 'Please enter a firstname'
                    },
                    'lastname': {
                        required: 'Please enter a lastname',
                        minlength: 'Please enter a lastname'
                    },
                    'email_address': 'Please enter a valid email address',
                    'userpassword': {
                        required: 'Please provide a password',
                        minlength: 'Your password must be at least 5 characters long'
                    },
                    'confirmpassword': {
                        required: 'Please provide a password',
                        minlength: 'Your password must be at least 5 characters long',
                        equalTo: 'Please enter the same password as above'
                    }
                }
            });
        },

        registerUser: function() {
            const data = $('#form-register').serialize();
            console.log(data);
            const agent_data = {
                first_name: $('#register-firstname').val(),
                last_name: $('#register-lastname').val(),
                email: $('#register-email').val(),
                password: $('#register-password').val(),
                role: 'client'
            };
            const url = call_agent_urls.register;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                        type: "POST",
                        method: "POST",
                        url: url,
                        data: agent_data,
                        //contentType: "application/json; charset=utf-8",
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
        }
    };
}();