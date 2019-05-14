var Register = function() {
    return {
        fields: {
            pageName: '',
            titles: [],
            occupations: [],
            cities: [],
            states: [],
            banks: [],
            sectors: [],
            profile: {},
            user: {}
        },
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

             /* Configure Date picker */
             $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                startDate: new Date(1920, 01, 01),
                endDate: new Date()
            });

            _thisRegister.fields.pageName = $('#page_name').val();

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
                    _thisRegister.registerUser()
                    .then(function(result) {
                        console.log(result);
                        if(result.id !== undefined) {
                            $('.alert-message').addClass('success');
                            $('.alert-message-text').html('Completed user registration <br/> Please wait, logging in ...');
                            // console.log('completed user registration');
                            // Sign User in 
                            setTimeout(_thisRegister.signUserIn, 5000);
                        }
                        else {
                            $('.alert-message').addClass('error');
                            $('.alert-message-text').html(result.message || 'error completing registration');
                        }
                    })
                    .catch(error => {
                            $('.alert-message').addClass('error');
                            if(error.status === 409) {
                                $('.alert-message-text').html('Email address already used');    $('.alert-message').addClass('warning'); 
                            }
                            else {
                                $('.alert-message-text').html(error.message || 'error completing registration');
                            }
                    })
                },
                rules: {
                    'category': {
                        required: true
                    },
                    'title': {
                        required: true
                    },
                    'firstname': {
                        required: true,
                        minlength: 3
                    },
                    'lastname': {
                        required: true,
                        minlength: 3
                    },
                    'company_name': {
                        required: true,
                        minlength: 3
                    },
                    'email_address': {
                        required: true,
                        email: true
                    },
                    'agent_code': {
                        required: true,
                        minlength: 3
                    },
                    'username': {
                        required: true,
                        minlength: 3
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
                    'category': {
                        required: 'Select a category'
                    },
                    'title': {
                        required: 'Select a title'
                    },
                    'firstname': {
                        required: 'Please enter a firstname',
                        minlength: 'Please enter a firstname'
                    },
                    'lastname': {
                        required: 'Please enter a lastname',
                        minlength: 'Please enter a lastname'
                    },
                    'company_name': {
                        required: 'Please enter a valid company name',
                        minlength: 'Please enter a valid company name'
                    },
                    'agent_code': {
                        required: 'Please enter valid agent code or name',
                        minlength: 'Please enter an agent code or name'
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
                    },
                    'username': {
                        required: 'Please valid username'
                    }
                }
            });
            console.log($('#profile_details').val());
            console.log($('#user_details').val());
            if ($('#profile_details').length > 0) {
                _thisRegister.fields.profile = JSON.parse($('#profile_details').val());
            }
            if (_thisRegister.fields.pageName === 'user_profile' && $('#user_details').length > 0) {
                _thisRegister.fields.user = JSON.parse($('#user_details').val());
                _thisRegister.setUserCategory();
            }
            _thisRegister.loadDropdownLists();
        },

        loadDropdownLists: () => {
            Promise.all([_thisRegister.getTitlesList(), _thisRegister.getOccupationList(), _thisRegister.getCitiesList(), _thisRegister.getStatesList(), _thisRegister.getBankList(), _thisRegister.getSectorList()]).then(values => {
                console.log(values);
                [_thisRegister.fields.titles, _thisRegister.fields.occupations, _thisRegister.fields.cities, _thisRegister.fields.states, _thisRegister.fields.banks, _thisRegister.fields.sectors] = values;
            }).then(() => {
                _thisRegister.populateTitles();
                _thisRegister.populateOccupations();
                _thisRegister.populateCities();
                _thisRegister.populateStates();
                _thisRegister.populateBanks();
                _thisRegister.populateSectors();
            });
        },

        registerUser: () => {
            const user_data = {
                user_category: $('#category').val(),
                email_address: $('#email_address').val(),
                username: $('#username').val(),
                password: $('#userpassword').val(),
                role: $('#user_role').val(),
                profile: {
                    user_category: $('#category').val(),
                    title: $('#title').val(),
                    firstname: $('#firstname').val(),
                    lastname: $('#lastname').val(),
                    othernames: $('#othernames').val(),
                    date_of_birth: $('#date_of_birth').val(),
                    company_name: $('#company_name').val(),
                    company_reg_num: $('#company_reg_num').val(),
                    email_address: $('#email_address').val(),
                    street_address: $('#street_address').val(),
                    city: $('#city').val(),
                    local_govt_area: $('#lga').val(),
                    state: $('#state').val(),
                    gsm_number: $('#gsm_number').val(),
                    office_number: $('#office_number').val(),
                    occupation: $('#occupation').val(),
                    sector: $('#sector').val(),
                    website: $('#website').val(),
                    contact_person: $('#contact_person').val(),
                    bank_account_number: $('#bank_account_number').val(),
                    customer_bank: $('#customer_bank').val(),
                    agent_code: $('#agent_code').val()
                }
            };
            console.log(user_data);
            const url = api_urls.register;
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

        registerProfile: () => {
           if ($('#form-register').valid()) {
                const profile = {
                    user_category: $('#category').val(),
                    userId: $('#profile_user_id').val(),
                    title: $('#profile_title').val(),
                    firstname: $('#profile_firstname').val(),
                    lastname: $('#profile_lastname').val(),
                    othernames: $('#profile_othernames').val(),
                    date_of_birth: $('#profile_date_of_birth').val(),
                    company_name: $('#profile_company_name').val(),
                    company_reg_num: $('#profile_company_reg_num').val(),
                    street_address: $('#profile_street_address').val(),
                    city: $('#profile_city').val(),
                    local_govt_area: $('#profile_lga').val(),
                    state: $('#profile_state').val(),
                    gsm_number: $('#profile_gsm_number').val(),
                    office_number: $('#profile_office_number').val(),
                    email_address: $('#profile_email_address').val(),
                    occupation: $('#profile_occupation').val(),
                    sector: $('#profile_sector').val(),
                    website: $('#profile_website').val(),
                    contact_person: $('#profile_contact_person').val(),
                    bank_account_number: $('#profile_bank_account_number').val(),
                    customer_bank: $('#profile_customer_bank').val(),
                    agent_code: $('#agent_code').val()
                };

                if (_thisRegister.fields.pageName === 'kyc_profile') {
                    delete profile.userId;
                    profile['created_by'] = _thisRegister.fields.profile.user_id;
                }
                console.log(profile);
                const url = api_urls.registerProfile;
                const promise = new Promise(function(resolve, reject){
                    $.ajax({
                            type: "POST",
                            method: "POST",
                            url: url,
                            data: profile,
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
                promise.then(function(result) {
                    console.log(result);
                    if(result.id !== undefined) {
                        $('.alert-message').addClass('success');
                        $('.alert-message-text').html('Completed user registration')
                    }
                    else {
                        $('.alert-message').addClass('error');
                        $('.alert-message-text').html(result.message || 'error completing registration');
                    }
                })
                .catch(error => {
                        $('.alert-message').addClass('error');
                        if(error.status === 409) {
                            $('.alert-message-text').html('Profile already exist');    
                            $('.alert-message').addClass('warning'); 
                        }
                        else {
                            $('.alert-message-text').html(error.message || 'error completing registration');
                        }
                });
            }
        },

        updateProfile: () => {
            if ($('#form-register').valid()) {
                 const profile = {
                     title: $('#title').val(),
                     firstname: $('#firstname').val(),
                     lastname: $('#lastname').val(),
                     othernames: $('#othernames').val(),
                     date_of_birth: $('#date_of_birth').val(),
                     company_name: $('#company_name').val(),
                     company_reg_num: $('#company_reg_num').val(),
                     street_address: $('#street_address').val(),
                     city: $('#city').val(),
                     local_govt_area: $('#lga').val(),
                     state: $('#state').val(),
                     gsm_number: $('#gsm_number').val(),
                     office_number: $('#office_number').val(),
                     occupation: $('#occupation').val(),
                     sector: $('#sector').val(),
                     website: $('#website').val(),
                     contact_person: $('#contact_person').val(),
                     bank_account_number: $('#bank_account_number').val(),
                     customer_bank: $('#customer_bank').val(),
                     agent_code: $('#agent_code').val()
                 };
                 console.log(profile);
                 const url = `${api_urls.registerProfile}/${_thisRegister.fields.profile.id}`;
                 const promise = new Promise(function(resolve, reject){
                     $.ajax({
                             type: "PUT",
                             method: "PUT",
                             url: url,
                             data: profile,
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
                 promise.then(function(result) {
                     console.log(result);
                     if(result.id !== undefined) {
                         $('.alert-message').addClass('success');
                         $('.alert-message-text').html('Profile changes successful')
                     }
                     else {
                         $('.alert-message').addClass('error');
                         $('.alert-message-text').html(result.message || 'error completing profile changes');
                     }
                 })
                 .catch(error => {
                         $('.alert-message').addClass('error');
                         if(error.status === 409) {
                             $('.alert-message-text').html('Email address already used');    
                             $('.alert-message').addClass('warning'); 
                         }
                         else {
                             $('.alert-message-text').html(error.message || 'error completing registration');
                         }
                 });
             }
         },

        onCategoryChange: () => {
            const default_elements = ['emailRow', 'streetRow', 'addressRow', 'occupationRow', 'websiteRow', 'contactPersonRow', 'bankDetailsRow', 'emailRow'];
            const individual_elements = ['titleRow', 'nameRow', 'gsmRow'];
            const company_elements = ['companyRow', 'companyRegRow', 'officeRow']

            $.each(default_elements, (i,v) => {
                $(`#${v}`).removeClass('hide_elements');
            })
            
            console.log($('#category').val());
            const category = $('#category').val();
            switch (category) {
                case 'Individual': {
                    $.each(individual_elements, (i,v) => {
                        console.log($(`#${v}`));
                        $(`#${v}`).removeClass('hide_elements');
                    });

                    $.each(company_elements, (i,v) => {
                        if (!$(`#${v}`).hasClass('hide_elements')) {
                            $(`#${v}`).addClass('hide_elements');
                        }
                    })
                    break;
                }
                case 'Corporate':
                case 'Government': {
                    $.each(company_elements, (i,v) => {
                        $(`#${v}`).removeClass('hide_elements');
                    });

                    $.each(individual_elements, (i,v) => {
                        if (!$(`#${v}`).hasClass('hide_elements')) {
                            $(`#${v}`).addClass('hide_elements');
                        }
                    })
                    break;
                }
            }
        },

        getTitlesList: () => {
            const url = api_urls.titles;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                    type: "GET",
                    method: "GET",
                    url: url,
                    success: function(msg){
                        resolve(msg);
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
            return promise;
        },

        getCitiesList: () => {
            const url = api_urls.cities;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                    type: "GET",
                    method: "GET",
                    url: url,
                    success: function(msg){
                        resolve(msg);
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
            return promise;
        },

        getOccupationList: () => {
            const url = api_urls.occupations;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                    type: "GET",
                    method: "GET",
                    url: url,
                    success: function(msg){
                        resolve(msg);
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
            return promise;
        },

        getStatesList: () => {
            const url = api_urls.states;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                    type: "GET",
                    method: "GET",
                    url: url,
                    success: function(msg){
                        resolve(msg);
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
            return promise;
        },

        getBankList: () => {
            const url = api_urls.banks;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                    type: "GET",
                    method: "GET",
                    url: url,
                    success: function(msg){
                        resolve(msg);
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
            return promise;
        },

        getSectorList: () => {
            const url = api_urls.sectors;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                    type: "GET",
                    method: "GET",
                    url: url,
                    success: function(msg){
                        resolve(msg);
                    },
                    error: function(err) {
                        reject(err);
                    }
                });
            });
            return promise;
        },

        setUserCategory: () => {
            if (!_.isEmpty(_thisRegister.fields.user)) {
                $('#category').val(_thisRegister.fields.user.user_category);
            }
        },

        populateTitles: () => {
            $title = $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile' ? $('#title') : $('#profile_title');
            let selected = '';
            $.each(_thisRegister.fields.titles, (i,v) => {
                if (!_.isEmpty(_thisRegister.fields.profile)) {
                    selected = _thisRegister.fields.profile.title === v.value ? 'selected' : '';
                }
                $title.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
            })
        },

        populateOccupations: () => {
            $occupation = $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile' ? $('#occupation') : $('#profile_occupation');
            let selected = '';
            $.each(_thisRegister.fields.occupations, (i,v) => {
                if (!_.isEmpty(_thisRegister.fields.profile)) {
                    selected = _thisRegister.fields.profile.occupation === v.value ? 'selected' : '';
                }
                $occupation.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
            })
        },

        populateCities: () => {
            $city = $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile' ? $('#city') : $('#profile_city');
            let selected = '';
            $.each(_thisRegister.fields.cities, (i,v) => {
                if (!_.isEmpty(_thisRegister.fields.profile)) {
                    selected = _thisRegister.fields.profile.city === v.value ? 'selected' : '';
                }
                $city.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
            })
        },

        populateStates: () => {
            $state = $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile' ? $('#state') : $('#profile_state');
            let selected = '';
            $.each(_thisRegister.fields.states, (i,v) => {
                selected = _thisRegister.fields.profile.state === v.value ? 'selected' : '';
                $state.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
            })
        },

        populateBanks: () => {
            $customerBank = $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile' ? $('#customer_bank') : $('#profile_customer_bank');
            let selected = '';
            $.each(_thisRegister.fields.banks, (i,v) => {
                selected = _thisRegister.fields.profile.customer_bank === v.value ? 'selected' : '';
                $customerBank.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
            })
        },

        populateSectors: () => {
            $occupationSector = $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile' ? $('#sector') : $('#profile_sector');
            let selected = '';
            $.each(_thisRegister.fields.sectors, (i,v) => {
                selected = _thisRegister.fields.profile.sector === v.value ? 'selected' : '';
                $occupationSector.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
            })
        },

        signUserIn: () => {
            SignIn.validateUser()
            .then(function(result) {
                console.log(result);
                if(result.user.id !== undefined) {
                    $('.alert-message').addClass('success');
                    $('.alert-message-text').html('Logging you in ...')
                    // console.log('completed user registration');
                    window.location.href = '/portal/home';
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
        }
    };
}();
const _thisRegister = Register;