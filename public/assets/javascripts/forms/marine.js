var Marine = (function() {
  return {
    fields: {
      coverTypes: [],
      legend_marine_data: {},
      legend_marine_data_payment: [],
      legend_request_body: {},
      transactionDetails: {},
      legendData: {},
      legendResponse: {},
      selectedProfile: {},
      agentProfile: {},
      agentUserDetails: {},
      cities: {},
      locations: {},
      formData: {},
      savedEntry: {},
      existingPolicies: {},
      vehicleData: {},
      vehicleTransactionPayment: {}
    },
    init: function() {
      _this.fields.agentUserDetails = JSON.parse($('#user_details').val());
      _this.fields.agentProfile = JSON.parse($('#profile_details').val());
      _this.setupProfileAutoComplete();

      $('.datepicker')
        .datepicker({
          format: 'yyyy-mm-dd',
          startDate: moment().toDate()
        })
        .on('changeDate', (e) => {
          // `e` here contains the extra attributes
          console.log(e);
          // _this.calculateExpiryDate();
        });

      $('#rootwizard').bootstrapWizard({
        onTabClick: function(tab, navigation, index) {
          // return false;
        },
        onNext: function(tab, navigation, index) {
          console.log(`Boostrap Wizard ${index} - ${tab} - ${navigation}`);
          switch (index) {
            case 1: {
              if ($('#tab1form').valid()) {
                _this.wizardStepTwo();
                // return true;
              }
              break;
            }
            case 2: {
              _this.wizardStepThree();
              // return true;
              break;
            }
            case 3: {
              // if (
              //   _this.fields.vehicleTransactionPayment.responseStatus !== undefined &&
              //   _this.fields.vehicleTransactionPayment.responseStatus === 'success'
              // ) {
              //   _this.wizardStepFive();
              // } else {
              _this.wizardStepFour();
              // }
              // return true;
              break;
            }
            case 4:
              _this.wizardStepFive();
              // return true;
              break;
            case 5:
            case 6: {
              // Motor.wizardStepFour();
              // return true;
              break;
            }
            default: {
              break;
            }
          }

          return false;
        }
      });
      _this.populateRatesList();
      _this.populateCurrencies();
      _this.populatePackingTypes();
      _this.loadDropdownLists();

      $('#topbar a')[0].click();
    },

    setActive: () => {
      $('#topbar a').each((i, v) => {
        $(v).removeClass('active');
      });
      $(`#${event.target.id}`).addClass('active');

      if (event.target.id === 'newPolicy') {
        $('#newPolicySection').removeClass('hide_elements');
      }

      if (event.target.id === 'renewPolicy') {
        $('#renewPolicySection').removeClass('hide_elements');
        // _this.populateClientPolicies();
      }
    },

    setupProfileAutoComplete: () => {
      let $input = $('.typeahead');
      $input.typeahead({
        autoSelect: true,
        minLength: 3,
        delay: 400,
        source: function(query, process) {
          const url = api_urls.profileList;
          return $.ajax({
            url: `${url}/${query}`,
            dataType: 'json'
          }).done(function(response) {
            return process(response);
          });
        }
      });
      $input.change(function() {
        let current = $input.typeahead('getActive');
        _this.fields.selectedProfile = current.profile;
        if (current) {
          // Some item from your model is active!
          if (current.name == $input.val()) {
            // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
          } else {
            // This means it is only a partial match, you can either add a new item
            // or take the active if you don't want new items
          }
          // Fetch Exisiting Profile Policies
          Utility.getIndividualPolicyList(_this.fields.selectedProfile.id)
            .then((result) => {
              _this.fields.existingPolicies = result;
            })
            .catch((err) => {
              console.log(err);
            });
        } else {
          // Nothing is active so it is a new value (or maybe empty value)
        }
      });
    },

    loadDropdownLists: () => {
      Promise.all([_this.getCoverTypeList(), Utility.getCitiesList(), Utility.getLocationsList()]).then(
        (values) => {
          console.log(values);
          [_this.fields.coverTypes, _this.fields.cities, _this.fields.locations] = values;

          _this.populateCoverTypes();
          _this.populateLocations();
        },
        (error) => {
          console.log(error);
        }
      );
    },

    wizardStepOne: () => {
      // $('#rootwizard').bootstrapWizard('show', 2);
      // $('#rootwizard').bootstrapWizard('next');
    },
    wizardStepTwo: () => {
      const sumInsured = $('#sum_insured').val();
      const basicRate = $('#basic_rate').val();
      let transactionAmount = sumInsured.replace(/,/g, '');
      const premiumAmount = transactionAmount * (parseFloat(basicRate) / 100);
      _this.fields.formData = $('#tab1form').serializeJSON();
      _this.fields.formData['premium_amount'] = premiumAmount.toFixed(2);
      const fullname_lbl = `${_this.fields.selectedProfile.firstname} ${_this.fields.selectedProfile.lastname}`;
      $('#fullname_span').html(fullname_lbl);
      $('#cargo_type_span').html(_this.fields.formData.cargo_type);
      $('#insured_company_name_span').html(_this.fields.formData.insured_company_name);
      $('#start_period_span').html(_this.fields.formData.start_period);
      $('#end_period_span').html(_this.fields.formData.end_period);
      $('#cover_type_span').html(_this.fields.formData.cover_type);
      $('#sum_insured_span').html(`${_this.fields.formData.currency_type} ${_this.fields.formData.sum_insured}`);
      $('#premium_span').html(`${_this.fields.formData.currency_type} ${_this.fields.formData.premium_amount}`);
      $('#rootwizard').bootstrapWizard('show', 1);
    },
    wizardStepThree: () => {
      $('#rootwizard').bootstrapWizard('show', 2);
    },
    wizardStepFour: () => {
      // Switch Steps based on Payment Choice
      let vehicleRegNum = $('#vessel_reg_num').val();
      // Save Form Details
      _this.fields.vehicleData = {
        id: `${moment().format('YYYYMMDDHHmmss')}_${vehicleRegNum}`,
        profileId: _this.fields.selectedProfile.id,
        userId: _this.fields.agentUserDetails.id,
        registrationNumber: _this.fields.formData.vessel_reg_num,
        formDetails: _this.fields.formData
      };
      const operation = _.isEmpty(_this.fields.savedEntry) ? 'save' : 'update';
      if (!_.isEmpty(_this.fields.savedEntry)) {
        _this.fields.vehicleData['vehicleDetailsId'] = _this.fields.savedEntry.id;
        _this.fields.vehicleData['id'] = _this.fields.savedEntry.id;
      }
      Utility.saveVehicleDetails(operation, _this.fields.vehicleData)
        .then((result) => {
          _this.fields.savedEntry = result;
        })
        .catch((err) => {
          console.log(err);
        });
      // Setup Transaction Data
      const KOBO_MULTIPLIER = 100;
      const customerEmail = _this.fields.selectedProfile.email_address;
      const transactionAmount = parseFloat(_this.fields.formData.premium_amount, 2) * KOBO_MULTIPLIER;
      const transactionReference = Utility.generateTransactionId(customerEmail);
      _this.fields.transactionDetails = {
        userId: _this.fields.agentUserDetails.id,
        profileId: _this.fields.selectedProfile.id,
        transactionReference: transactionReference,
        customerEmail: customerEmail,
        sumInsured: _this.fields.formData.sum_insured.replace(/,/g, ''),
        transactionAmount: transactionAmount.toFixed(2),
        transactionDate: moment().format('YYYY-MM-DD HH:mm:ss'),
        paymentGateway: 'PayStack',
        responseStatus: 'initiate',
        responseReference: '',
        responseMessage: ''
      };

      let paymentOption = $('#marine_payment_method').val();
      switch (paymentOption) {
        case 'CADVICE': {
          console.log('Payment Option is Cadvice - ', paymentOption);
          return _this.creditNote();
          break;
        }
        case 'EPAY': {
          return _this.loadPayStack();
          break;
        }
      }
    },

    wizardStepFive: () => {
      $('.loading_icon').removeClass('hide_elements');
      $('#legendResponseMessage').html('');
      _this.fields.legendData = _this.setLegendData();
      _this
        .getPolicy(_this.fields.legendData)
        .then(function(result) {
          responseString = result.message;
          // responseString = "Client Number: 18-01520, Policy Number: PMI54/1/000791/L18, Certificate Number: 18/0000001794, Debit Note Number: 1824406PMI, Receipt Number: 5418/038892, Expiry Date: 13-DEC-19.";
          console.log(responseString);
          if (responseString.indexOf('Policy Number') > -1) {
            const responseArray = responseString.split(', ');
            $.each(responseArray, (i, v) => {
              const eachData = v.split(':');
              _this.fields.legendResponse[`${eachData[0]}`.replace(/\s/g, '')] = eachData[1].trim();
            });

            // Save Policy Details
            const policyDetails = _this.setPolicyDetails();
            Utility.savePolicyDetails(policyDetails);

            // Set the response html elements
            $('#client_number').html(_this.fields.legendResponse.ClientNumber);
            $('#policy_number').html(_this.fields.legendResponse.PolicyNumber);
            $('#certificate_number').html(_this.fields.legendResponse.CertificateNumber);
            $('#debit_note_number').html(_this.fields.legendResponse.DebitNoteNumber);
            $('#receipt_number').html(_this.fields.legendResponse.ReceiptNumber);
            $('#expiry_date').html(_this.fields.legendResponse.ExpiryDate);
            $('#legend_success_response').removeClass('hide');

            // Send Email
            const emailData = {
              client_number: _this.fields.legendResponse.ClientNumber,
              policy_number: _this.fields.legendResponse.PolicyNumber,
              certificate_number: _this.fields.legendResponse.CertificateNumber,
              debit_note_number: _this.fields.legendResponse.DebitNoteNumber,
              receipt_number: _this.fields.legendResponse.ReceiptNumber,
              expiry_date: _this.fields.legendResponse.ExpiryDate,
              email_address: _this.fields.selectedProfile.email_address,
              subject: 'Marine Insurance Policy'
            };
            Utility.sendPolicyEmail(emailData);
          } else {
            $('#legendResponseMessage').html(responseString);
          }
          $('.loading_icon').addClass('hide_elements');
        })
        .catch((error) => {
          console.log(error);
          $('.alert-message').addClass('error');
          if (error.status === 400) {
            $('.alert-message-text').html('One of the entered fields is invalid');
            $('.alert-message').addClass('warning');
          } else {
            $('.alert-message-text').html(error.message || 'error completing policy generation');
          }
          $('#legendResponseMessage').html(error.message || 'error completing policy generation');
          $('.loading_icon').addClass('hide_elements');
        })
        .finally(() => {
          $('.loading_icon').addClass('hide_elements');
        });
      return true;
    },

    loadPayStack: () => {
      const mobileNumber =
        _this.fields.agentProfile.gsm_number !== ''
          ? _this.fields.agentProfile.gsm_number
          : _this.fields.agentProfile.office_number;
      const handler = PaystackPop.setup({
        key: paystack.key,
        email: _this.fields.transactionDetails.customerEmail,
        amount: _this.fields.transactionDetails.transactionAmount,
        ref: _this.fields.transactionDetails.transactionReference,
        metadata: {
          custom_fields: [
            {
              display_name: 'Mobile Number',
              variable_name: 'mobile_number',
              value: `${mobileNumber}`
            }
          ]
        },
        callback: function(response) {
          console.log(response);
          console.log('success. transaction ref is ' + response.reference);
          _this.fields.transactionDetails.responseReference = response.transaction;
          _this.fields.transactionDetails.responseStatus = response.status;
          _this.fields.transactionDetails.responseMessage = response.message;
          console.log(_this.fields.transactionDetails);

          if (
            _this.fields.transactionDetails !== undefined &&
            !_.isEmpty(_this.fields.transactionDetails) &&
            _this.fields.transactionDetails.responseReference !== ''
          ) {
            return _this.onPaymentSuccess();
          }
          $('#rootwizard').bootstrapWizard('show', 2);
          $('#rootwizard').bootstrapWizard('next');
        },
        onClose: function() {
          console.log('window closed');
          $('#rootwizard').bootstrapWizard('show', 4);
          return false;
        }
      });
      handler.openIframe();
    },

    creditNote: () => {
      _this.fields.transactionDetails.paymentGateway = 'CADVICE';
      console.log('success. transaction ref is ' + _this.fields.transactionDetails.transactionReference);
      _this.fields.transactionDetails.responseReference = _this.fields.transactionDetails.transactionReference;
      _this.fields.transactionDetails.responseStatus = 'success';
      _this.fields.transactionDetails.responseMessage = 'Paid with Credit Note';
      console.log(_this.fields.transactionDetails);

      return _this.onPaymentSuccess();
    },

    onPaymentSuccess: () => {
      const vehicleTransactionPayment = {
        ..._this.fields.transactionDetails,
        vehicleTransactionDetailsId: _this.fields.vehicleData.id
      };
      Utility.saveVehiclePaymentDetails(vehicleTransactionPayment)
        .then((result) => {
          console.log(result);
          _this.fields.vehicleTransactionPayment = vehicleTransactionPayment;
          $('#rootwizard').bootstrapWizard('show', 3);
          $('#rootwizard').bootstrapWizard('next');
          return true;
        })
        .catch((err) => {
          console.log(err);
          return false;
        });
    },

    setLegendData: () => {
      const gender =
        _this.fields.selectedProfile.gender !== '' ? Utility.genders[_this.fields.selectedProfile.gender] : '';
      const name = _.isEmpty(_this.fields.selectedProfile.lastname)
        ? _this.fields.selectedProfile.company_name
        : _this.fields.selectedProfile.lastname;
      const client_class = Utility.clientClasses[_this.fields.selectedProfile.user_category];
      const paymentOption = $('#marine_payment_method').val();
      const data = {
        username: 'website',
        userpassword: 'website',
        firstname: _this.fields.selectedProfile.firstname,
        lastname: name,
        othernames: `${_this.fields.selectedProfile.firstname} ${_this.fields.selectedProfile.othernames}`,
        address: _this.fields.selectedProfile.street_address,
        city: 'ABA',
        contact_person: _this.fields.selectedProfile.contact_person,
        state: 'AB',
        title_id: _this.fields.selectedProfile.title,
        client_class: client_class,
        gsm_number:
          _this.fields.agentProfile.gsm_number !== ''
            ? _this.fields.agentProfile.gsm_number
            : _this.fields.agentProfile.office_number,
        office_number:
          _this.fields.agentProfile.gsm_number !== ''
            ? _this.fields.agentProfile.gsm_number
            : _this.fields.agentProfile.office_number,
        fax_number: _this.fields.agentProfile.fax_number === '' ? 'N/A' : _this.fields.agentProfile.fax_number,
        email_address: _this.fields.agentUserDetails.email_address,
        website: _this.fields.selectedProfile.website === '' ? 'N/A' : _this.fields.selectedProfile.website,
        company_reg_num: _this.fields.selectedProfile.company_reg_num,
        date_of_birth: _this.fields.selectedProfile.date_of_birth,
        lga: 'L17001',
        tin_number: _this.fields.selectedProfile.tin_number === '' ? 'N/A' : _this.fields.selectedProfile.tin_number,
        bvn:
          _this.fields.selectedProfile.bank_account_number === ''
            ? 'N/A'
            : _this.fields.selectedProfile.bank_account_number,
        bank_id: _this.fields.selectedProfile.customer_bank === '' ? 'N/A' : _this.fields.selectedProfile.customer_bank,
        account_number:
          _this.fields.selectedProfile.bank_account_number === ''
            ? 'N/A'
            : _this.fields.selectedProfile.bank_account_number,
        occupation: _this.fields.selectedProfile.occupation,
        sector: _this.fields.selectedProfile.sector,
        premium: (_this.fields.transactionDetails.transactionAmount / 100).toFixed(2),
        sum_insured: _this.fields.transactionDetails.sumInsured,
        vehicle_plate_number: $('#vessel_reg_num').val(),
        model: '',
        body: '',
        color: '',
        cubic_capacity: 0,
        number_of_seat: 0,
        engine_number: '',
        chasis_number: '',
        year_of_make: 0,
        year_of_purchase: 0,
        mode_of_payment: paymentOption === 'CADVICE' ? 'CADVICE' : 'CASH',
        policy_class: '002',
        risk_class: 'MCA',
        cover_type: $('#cover_type').val(),
        basic_rate: $('#basic_rate').val(),
        location: $('#location').val(),
        currency: 'NGN',
        company_bank: '310-0342', //$('#company_bank').val(),
        effective_date: $('#start_period').val(),
        expiry_date: $('#end_period').val(),
        voyage_from: $('#voyage_from').val(),
        voyage_to: $('#voyage_to').val(),
        packing_type: $('#packing_type').val(),
        vessel_name: $('#vessel_name').val(),
        conditions: $('#conditions').val(),
        excess: $('#excess')
          .val()
          .replace(/,/g, ''),
        conveyance: $('#conveyance').val(),
        description: $('#description').val(),
        term_of_insurance: $('#term_of_insurance').val(),
        payment_reference: 0, //_this.transactionDetails.transactionReference,
        vehicleTransactionDetailsId: _this.fields.savedEntry.id,
        policy_type: 'marine',
        gender: gender
      };
      return data;
    },

    resetLegendData: () => {
      _this.fields.legendData.firstname = '';
      _this.fields.legendData.lastname = '';
      _this.fields.legendData.othernames = '';
      _this.fields.legendData.address = '';
      _this.fields.legendData.city = '';
      _this.fields.legendData.contact_person = '';
      _this.fields.legendData.title_id = '';
      _this.fields.legendData.gsm_number = '';
      _this.fields.legendData.office_number = '';
      _this.fields.legendData.fax_number = '';
      _this.fields.legendData.email_address = '';
      _this.fields.legendData.company_reg_num = '';
      // Motor.legendData.date_of_birth = '';
      _this.fields.legendData.client_number = _this.fields.existingPolicies[0].client_number;
    },

    getPolicy: (legend_data) => {
      let url = api_urls.getpolicy;
      if (!_.isEmpty(_this.fields.existingPolicies)) {
        _this.resetLegendData();
        url = api_urls.getAdditionalPolicy;
      }
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          method: 'POST',
          url: url,
          data: legend_data,
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

    getCoverTypeList: () => {
      const url = api_urls.getcovertypes;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/marine`,
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

    populateRatesList: () => {
      $basicRate = $('#basic_rate');
      const rateRange = _.map(_.range(1, 51), function(i) {
        return i / 10;
      });
      rateRange.map((r) => {
        $basicRate.append(`<option value=${r.toFixed(1)}> ${r.toFixed(1)} </option>`);
      });
    },

    populateCurrencies: () => {
      $currencyType = $('#currency_type');
      const currencies = [
        'NGN',
        'USD',
        'GBP',
        'SWIS FRANC',
        'YEN',
        'CFA',
        'WAUA',
        'YUAN/RENMINBI',
        'RIYAL',
        'DANISH KRONA',
        'SDR',
        'ZAR',
        'EURO'
      ];
      currencies.map((c) => {
        $currencyType.append(`<option value=${c}> ${c} </option>`);
      });
    },

    populateCoverTypes: () => {
      $marineCoverTypes = $('#cover_type');
      $.each(_this.fields.coverTypes, (i, v) => {
        $marineCoverTypes.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populatePackingTypes: () => {
      $packingType = $('#packing_type');
      const packingtypes = [
        { id: '0', value: 'Containerized' },
        { id: '1', value: 'Non Containerized' }
      ];
      packingtypes.map((c) => {
        $packingType.append(`<option value=${c.id}> ${c.value} </option>`);
      });
    },

    populateLocations: () => {
      $location = $('#location');
      $.each(_this.fields.locations, (i, v) => {
        $location.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    getProfileInfo: function(query) {
      const url = api_urls.profileList;
      // const promise = new Promise(function(resolve, reject){
      const result = $.get(`${url}/${query}`);
      return result;
    },

    setPolicyDetails: () => {
      const policyDetails = {
        profileId: _this.fields.selectedProfile.id,
        userId: _this.fields.agentUserDetails.id,
        policyType: 'motor',
        coverType: $('#cover_type').val(),
        coverOption: '',
        coverAddOns: '',
        vehicleTransactionDetailsId: _this.fields.savedEntry.vehicle_details_id,
        ..._this.fields.legendResponse
      };
      return policyDetails;
    }
  };
})();
const _this = Marine;
