var Register = (function() {
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

      $.validator.addMethod(
        'regex',
        function(value, element, regexp) {
          return !regexp.test(value);
        },
        'Please check your input.'
      );

      /* Register form - Initialize Validation */
      $('#form-register').validate({
        errorClass: 'help-block animation-slideUp', // You can change the animation class for a different entrance animation - check animations page
        errorElement: 'label',
        errorPlacement: function(error, e) {
          $(e.parents('div.form-group').children()[0]).append(error);
        },
        highlight: function(e) {
          $(e)
            .closest('.form-group')
            .removeClass('has-success has-error')
            .addClass('has-error');
          $(e)
            .closest('.help-block')
            .remove();
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
          event.preventDefault();
          _thisRegister
            .registerUser()
            .then(function(result) {
              if (result.id !== undefined) {
                $('.alert-message').addClass('success');
                $('.alert-message-text').html('Completed user registration <br/> Please wait, logging in ...');
                // Sign User in
                setTimeout(_thisRegister.signUserIn, 5000);
              } else {
                $('.alert-message').addClass('error');
                $('.alert-message-text').html(result.message || 'error completing registration');
              }
            })
            .catch((error) => {
              $('.alert-message').addClass('error');
              if (error.status === 409) {
                $('.alert-message-text').html('Email address already used');
                $('.alert-message').addClass('warning');
              } else {
                $('.alert-message-text').html(error.message || 'error completing registration');
              }
            });
        },
        rules: {
          category: {
            required: true
          },
          title: {
            required: true
          },
          firstname: {
            required: true,
            regex: /&/g,
            minlength: 3
          },
          lastname: {
            required: true,
            regex: /&/g,
            minlength: 3
          },
          company_name: {
            required: true,
            regex: /&/g,
            minlength: 3
          },
          email_address: {
            required: true,
            email: true
          },
          agent_code: {
            required: true,
            minlength: 3
          },
          username: {
            required: true,
            minlength: 3
          },
          userpassword: {
            required: true,
            minlength: 5
          },
          confirmpassword: {
            required: true,
            equalTo: '#userpassword'
          }
        },
        messages: {
          category: {
            required: 'Select a category'
          },
          title: {
            required: 'Select a title'
          },
          firstname: {
            required: 'Please enter a firstname',
            regex: 'Please replace & with and',
            minlength: 'Please enter a firstname'
          },
          lastname: {
            required: 'Please enter a lastname',
            regex: 'Please replace & with and',
            minlength: 'Please enter a lastname'
          },
          company_name: {
            required: 'Please enter a valid company name',
            regex: 'Please replace & with and',
            minlength: 'Please enter a valid company name'
          },
          agent_code: {
            required: 'Please enter valid agent code or name',
            minlength: 'Please enter an agent code or name'
          },
          email_address: 'Please enter a valid email address',
          userpassword: {
            required: 'Please provide a password',
            minlength: 'Your password must be at least 5 characters long'
          },
          confirmpassword: {
            required: 'Please provide a password',
            minlength: 'Your password must be at least 5 characters long',
            equalTo: 'Please enter the same password as above'
          },
          username: {
            required: 'Please valid username'
          }
        }
      });
      if ($('#profile_details').length > 0) {
        _thisRegister.fields.profile = JSON.parse($('#profile_details').val());
      }
      if (_thisRegister.fields.pageName === 'user_profile' && $('#user_details').length > 0) {
        _thisRegister.fields.user = JSON.parse($('#user_details').val());
        _thisRegister.setUserCategory();
      }
      _thisRegister.loadDropdownLists();

      if ($('input.typeahead').length > 0) {
        Utility.setupProfileAutoComplete();
      }
    },

    loadDropdownLists: () => {
      Promise.all([
        _thisRegister.getTitlesList(),
        _thisRegister.getOccupationList(),
        _thisRegister.getCitiesList(),
        _thisRegister.getStatesList(),
        _thisRegister.getBankList(),
        _thisRegister.getSectorList()
      ])
        .then((values) => {
          [
            _thisRegister.fields.titles,
            _thisRegister.fields.occupations,
            _thisRegister.fields.cities,
            _thisRegister.fields.states,
            _thisRegister.fields.banks,
            _thisRegister.fields.sectors
          ] = values;
        })
        .then(() => {
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
          tin_number: $('#tin_number').val(),
          state: $('#state').val(),
          gsm_number: $('#gsm_number').val(),
          office_number: $('#office_number').val(),
          occupation: $('#occupation').val(),
          sector: $('#sector').val(),
          website: 'N/A', //$('#website').val(),
          contact_person: $('#contact_person').val(),
          bank_account_number: '0123456789',
          customer_bank: 'BNK0001',
          agent_code: $('#agent_code').val()
        }
      };
      // $('#bank_account_number').val()
      // $('#customer_bank').val()
      const url = api_urls.register;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          method: 'POST',
          url: url,
          data: user_data,
          // contentType: "application/json; charset=utf-8",
          success: function(msg) {
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
      let regexp = /&/g;
      let error_msg = '(replace & with and)';
      if ($('#profile_firstname').is(':visible') && regexp.test($('#profile_firstname').val())) {
        $('#profile_firstname').addClass('error');
        $($('#profile_firstname').prev()).append(`<span> ${error_msg} </span>`);
        return false;
      } else {
        $('#profile_firstname').removeClass('error');
        $($($('#profile_firstname').prev()).children()[0]).remove();
      }
      if ($('#profile_lastname').is(':visible') && regexp.test($('#profile_lastname').val())) {
        $('#profile_lastname').addClass('error');
        $($('#profile_lastname').prev()).append(`<span> ${error_msg} </span>`);
        return false;
      } else {
        $('#profile_lastname').removeClass('error');
        $($($('#profile_lastname').prev()).children()[0]).remove();
      }
      if ($('#profile_company_name').is(':visible') && regexp.test($('#profile_company_name').val())) {
        $('#profile_company_name').addClass('error');
        $($('#profile_company_name').prev()).append(`<span> ${error_msg} </span>`);
        return false;
      } else {
        $('#profile_company_name').removeClass('error');
        $($($('#profile_company_name').prev()).children()[0]).remove();
      }
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
          tin_number: $('#profile_tin_number').val(),
          state: $('#profile_state').val(),
          gsm_number: $('#profile_gsm_number').val(),
          office_number: $('#profile_office_number').val(),
          email_address: $('#profile_email_address').val(),
          occupation: $('#profile_occupation').val(),
          sector: $('#profile_sector').val(),
          website: $('#profile_website').val(),
          contact_person: $('#profile_contact_person').val(),
          bank_account_number: '0123456789',
          customer_bank: 'BNK0001',
          agent_code: $('#agent_code').val()
        };
        // $('#profile_bank_account_number').val()
        // $('#profile_customer_bank').val()
        let url = api_urls.registerProfile;
        let type = 'POST';
        let method = 'POST';
        if (_thisRegister.fields.pageName === 'kyc_profile' && _.isEmpty($('#profile_kyc').val())) {
          delete profile.userId;
          profile['created_by'] = _thisRegister.fields.profile.user_id;
        } else {
          url = `${api_urls.registerProfile}/${Utility.fields.selectedProfile.id}`;
          type = 'PUT';
          method = 'PUT';
        }
        const promise = new Promise(function(resolve, reject) {
          $.ajax({
            type: type,
            method: method,
            url: url,
            data: profile,
            // contentType: "application/json; charset=utf-8",
            success: function(msg) {
              resolve(msg);
            },
            error: function(err) {
              console.log(err);
              reject(err);
            }
          });
        });
        promise
          .then(function(result) {
            if (result.id !== undefined) {
              $('.alert-message').addClass('success');
              $('.alert-message-text').html('Completed user registration');
            } else {
              $('.alert-message').addClass('error');
              $('.alert-message-text').html(result.message || 'error completing registration');
            }
          })
          .catch((error) => {
            $('.alert-message').addClass('error');
            if (error.status === 409) {
              $('.alert-message-text').html('Profile already exist');
              $('.alert-message').addClass('warning');
            } else {
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
          email_address: $('#email_address').val(),
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
          website: 'N/A', //$('#website').val(),
          contact_person: $('#contact_person').val(),
          bank_account_number: '0123456789', //$('#bank_account_number').val(),
          customer_bank: 'BNK0001', // $('#customer_bank').val(),
          agent_code: $('#agent_code').val()
        };
        const url = `${api_urls.registerProfile}/${_thisRegister.fields.profile.id}`;
        const promise = new Promise(function(resolve, reject) {
          $.ajax({
            type: 'PUT',
            method: 'PUT',
            url: url,
            data: profile,
            // contentType: "application/json; charset=utf-8",
            success: function(msg) {
              resolve(msg);
            },
            error: function(err) {
              console.log(err);
              reject(err);
            }
          });
        });
        promise
          .then(function(result) {
            if (result.id !== undefined) {
              $('.alert-message').addClass('success');
              $('.alert-message-text').html('Profile changes successful');
            } else {
              $('.alert-message').addClass('error');
              $('.alert-message-text').html(result.message || 'error completing profile changes');
            }
          })
          .catch((error) => {
            $('.alert-message').addClass('error');
            if (error.status === 409) {
              $('.alert-message-text').html('Email address already used');
              $('.alert-message').addClass('warning');
            } else {
              $('.alert-message-text').html(error.message || 'error completing registration');
            }
          });
      }
    },

    onCategoryChange: () => {
      const default_elements = [
        'emailRow',
        'streetRow',
        'addressRow',
        'occupationRow',
        'contactPersonRow',
        'bankDetailsRow',
        'emailRow',
        'gsmRow'
      ];
      const individual_elements = ['titleRow', 'nameRow'];
      const company_elements = ['companyRow', 'companyRegRow', 'officeRow', 'tinRow', 'websiteRow'];

      $.each(default_elements, (i, v) => {
        $(`#${v}`).removeClass('hide_elements');
      });

      const category = $('#category').val();
      switch (category) {
        case 'Individual': {
          $.each(individual_elements, (i, v) => {
            $(`#${v}`).removeClass('hide_elements');
          });

          $.each(company_elements, (i, v) => {
            if (!$(`#${v}`).hasClass('hide_elements')) {
              $(`#${v}`).addClass('hide_elements');
            }
          });
          break;
        }
        case 'Corporate':
        case 'Government': {
          $.each(company_elements, (i, v) => {
            $(`#${v}`).removeClass('hide_elements');
          });

          $.each(individual_elements, (i, v) => {
            if (!$(`#${v}`).hasClass('hide_elements')) {
              $(`#${v}`).addClass('hide_elements');
            }
          });
          break;
        }
      }
    },

    getTitlesList: () => {
      const url = api_urls.titles;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: url,
          success: function(msg) {
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
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: url,
          success: function(msg) {
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
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: url,
          success: function(msg) {
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
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: url,
          success: function(msg) {
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
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: url,
          success: function(msg) {
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
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: url,
          success: function(msg) {
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
      $title =
        $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile'
          ? $('#title')
          : $('#profile_title');
      let selected = '';
      $.each(_thisRegister.fields.titles, (i, v) => {
        if (!_.isEmpty(_thisRegister.fields.profile)) {
          selected = _thisRegister.fields.profile.title === v.value ? 'selected' : '';
        }
        $title.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
      });
    },

    populateOccupations: () => {
      $occupation =
        $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile'
          ? $('#occupation')
          : $('#profile_occupation');
      let selected = '';
      $.each(_thisRegister.fields.occupations, (i, v) => {
        if (!_.isEmpty(_thisRegister.fields.profile)) {
          selected = _thisRegister.fields.profile.occupation === v.value ? 'selected' : '';
        }
        $occupation.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
      });
    },

    populateCities: () => {
      $city =
        $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile'
          ? $('#city')
          : $('#profile_city');
      let selected = '';
      $.each(_thisRegister.fields.cities, (i, v) => {
        if (!_.isEmpty(_thisRegister.fields.profile)) {
          selected = _thisRegister.fields.profile.city === v.value ? 'selected' : '';
        }
        $city.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
      });
    },

    populateStates: () => {
      $state =
        $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile'
          ? $('#state')
          : $('#profile_state');
      let selected = '';
      $.each(_thisRegister.fields.states, (i, v) => {
        selected = _thisRegister.fields.profile.state === v.value ? 'selected' : '';
        $state.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
      });
    },

    populateBanks: () => {
      $customerBank =
        $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile'
          ? $('#customer_bank')
          : $('#profile_customer_bank');
      let selected = '';
      $.each(_thisRegister.fields.banks, (i, v) => {
        selected = _thisRegister.fields.profile.customer_bank === v.value ? 'selected' : '';
        $customerBank.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
      });
    },

    populateSectors: () => {
      $occupationSector =
        $('#user_role_loggedIn').length == 0 && _thisRegister.fields.pageName !== 'kyc_profile'
          ? $('#sector')
          : $('#profile_sector');
      let selected = '';
      $.each(_thisRegister.fields.sectors, (i, v) => {
        selected = _thisRegister.fields.profile.sector === v.value ? 'selected' : '';
        $occupationSector.append(`<option value=${v.value} ${selected}> ${v.name} </option>`);
      });
    },

    signUserIn: () => {
      SignIn.validateUser()
        .then(function(result) {
          if (result.user.id !== undefined) {
            $('.alert-message').addClass('success');
            $('.alert-message-text').html('Logging you in ...');
            window.location.href = '/portal/home';
          } else {
            $('.alert-message').addClass('error');
            $('.alert-message-text').html(result.message || 'error signing in');
          }
        })
        .catch((error) => {
          $('.alert-message').addClass('error');
          if (error.status === 401) {
            $('.alert-message-text').html(error.responseJSON.message);
          } else {
            $('.alert-message-text').html(error.message || 'error signing in');
          }
        });
    },

    setProfileDetails: () => {
      if (!_.isEmpty($('#profile_kyc').val()) && !_.isEmpty(Utility.fields.selectedProfile)) {
        const kycProfile = Utility.fields.selectedProfile;
        $('#category').val(kycProfile.user_category);
        _thisRegister.onCategoryChange();
        $('#profile_title').val(kycProfile.title);
        $('#profile_firstname').val(kycProfile.firstname);
        $('#profile_lastname').val(kycProfile.lastname);
        $('#profile_othernames').val(kycProfile.othernames);
        $('#profile_date_of_birth').val(kycProfile.date_of_birth);
        $('#profile_company_name').val(kycProfile.company_name);
        $('#profile_company_reg_num').val(kycProfile.company_reg_num);
        $('#profile_street_address').val(kycProfile.street_address);
        $('#profile_city').val(kycProfile.city);
        $('#profile_lga').val(kycProfile.local_govt_area);
        $('#profile_state').val(kycProfile.state);
        $('#profile_office_number').val(kycProfile.office_number);
        $('#profile_gsm_number').val(kycProfile.gsm_number);
        $('#profile_email_address').val(kycProfile.email_address);
        $('#profile_occupation').val(kycProfile.occupation);
        $('#profile_sector').val(kycProfile.sector);
        $('#profile_website').val(kycProfile.website);
        $('#profile_contact_person').val(kycProfile.contact_person);
        $('#profile_bank_account_number').val(kycProfile.bank_account_number);
        $('#profile_customer_bank').val(kycProfile.customer_bank);
        $('#agent_code').val(kycProfile.agent_code);
      }
    }
  };
})();
const _thisRegister = Register;

var UserProfile = (function() {
  return {
    init: function() {
      console.log('Set User Profile');
      _thisUser.setScreen();
    },

    setScreen: () => {
      const default_elements = [
        'emailRow',
        'streetRow',
        'addressRow',
        'occupationRow',
        'contactPersonRow',
        'bankDetailsRow',
        'emailRow',
        'gsmRow'
      ];
      const individual_elements = ['titleRow', 'nameRow'];
      const company_elements = ['companyRow', 'companyRegRow', 'officeRow', 'tinRow', 'websiteRow'];

      $.each(default_elements, (i, v) => {
        $(`#${v}`).removeClass('hide_elements');
      });

      const category = _thisRegister.fields.profile.user_category;
      switch (category) {
        case 'Individual': {
          $.each(individual_elements, (i, v) => {
            $(`#${v}`).removeClass('hide_elements');
          });

          $.each(company_elements, (i, v) => {
            if (!$(`#${v}`).hasClass('hide_elements')) {
              $(`#${v}`).addClass('hide_elements');
            }
          });
          break;
        }
        case 'Corporate':
        case 'Government': {
          $.each(company_elements, (i, v) => {
            $(`#${v}`).removeClass('hide_elements');
          });

          $.each(individual_elements, (i, v) => {
            if (!$(`#${v}`).hasClass('hide_elements')) {
              $(`#${v}`).addClass('hide_elements');
            }
          });
          break;
        }
      }
    }
  };
})();
const _thisUser = UserProfile;
