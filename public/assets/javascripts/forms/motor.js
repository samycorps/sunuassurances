var Motor = (function() {
  return {
    fields: {
      moveNext: false,
      vehicleDetails: {},
      transactionDetails: {},
      colours: [],
      vehicleBodies: [],
      vehicleModels: [],
      coverTypes: [],
      sectors: [],
      states: [],
      riskClasses: [],
      banks: [],
      companyBanks: [],
      legendData: {},
      legendResponse: {},
      vehicleTransactionDetails: {},
      individualPolicyList: [],
      coverOption: '',
      coverAddOns: {},
      formData: {},
      savedEntry: {},
      policyAlreadyExists: false,
      activeTab: 'newPolicy',
      renewalDetails: {},
      policyDetails: {}
    },
    clientClasses: {
      Individual: 'I',
      Company: 'C',
      Corporate: 'C',
      Government: 'G'
    },
    titles: {
      Mr: '001',
      Mrs: '002',
      Miss: '003',
      Master: '004',
      Ms: '005',
      Chief: '006',
      Clergy: '007',
      Dr: '008',
      'Mr & Mrs': '009'
    },
    init: function() {
      moveNext = false;
      vehicleDetails = {};
      /*
       *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
       */
      $('.datepicker')
        .datepicker({
          format: 'yyyy-mm-dd',
          startDate: moment().toDate()
        })
        .on('changeDate', (e) => {
          // `e` here contains the extra attributes
          console.log(e);
          Motor.calculateExpiryDate();
        });

      $("input[data-type='currency']").on({
        keyup: function() {
          Motor.formatCurrency($(this));
        },
        blur: function() {
          Motor.formatCurrency($(this), 'blur');
        }
      });

      $('#rootwizard').bootstrapWizard({
        onTabClick: function(tab, navigation, index) {
          return false;
        },
        onNext: function(tab, navigation, index) {
          console.log(index);

          switch (index) {
            case 1: {
              if ($('#tab1form').valid()) {
                Motor.wizardStepOne();
              }
              break;
            }
            case 2: {
              return Motor.wizardStepTwo();
            }
            case 3: {
              if ($('#tab3form').valid()) {
                Motor.wizardStepThree();
              }
              break;
            }
            case 4:
            case 5:
            case 6: {
              Motor.wizardStepFour();
              return true;
              // break;
            }
            default: {
              break;
            }
          }

          return false;
        }
      });

      _this.getIndividualPolicyList().then(
        (result) => {
          _this.fields.individualPolicyList = result;
          if (!_.isEmpty(_this.fields.individualPolicyList)) {
            _this.fields.policyAlreadyExists = true;
            // _this.populateClientPolicies();
            $('#existingPolicyDiv').removeClass('hide_elements');
            $('#existingClientNumberDiv').removeClass('hide_elements');
          }
        },
        (error) => {
          console.log(error);
        }
      );

      Motor.setEffectiveAndExpiryDates();
      Motor.loadDropdownLists();
      Motor.populateYearsList();
      Motor.validateTab1Form();
      Motor.validateTab2Form();
      Motor.validateTab3Form();
      Motor.addValidationRules();
      Motor.setCustomerProfile();
    },

    setEffectiveAndExpiryDates: () => {
      const effective_date = moment().format('YYYY-MM-DD');
      const expiry_date = moment()
        .add(1, 'years')
        .subtract(1, 'days')
        .format('YYYY-MM-DD');
      $('#vehicle_effective_date').val(effective_date);
      $('#vehicle_expiry_date').val(expiry_date);
    },

    setActive: () => {
      $('#topbar a').each((i, v) => {
        $(v).removeClass('active');
      });
      $(`#${event.target.id}`).addClass('active');
      _this.fields.activeTab = event.target.id;
      if (event.target.id === 'newPolicy' && $('#newAndAdditionalPolicySection').hasClass('hide_elements')) {
        $('#newAndAdditionalPolicySection').removeClass('hide_elements');
        $('#renewPolicySection').addClass('hide_elements');
        _this.fields.coverOption = event.target.id === 'newPolicy' ? 'new' : 'additional';
        if (!$('#existingClientSection').hasClass('hide_elements')) {
          $('#existingClientSection').addClass('hide_elements');
        }
      }

      if (event.target.id === 'renewPolicy' && $('#renewPolicySection').hasClass('hide_elements')) {
        // $('#renewPolicySection').removeClass('hide_elements');
        // $('#newAndAdditionalPolicySection').addClass('hide_elements');
      }
    },

    loadDropdownLists: () => {
      Promise.all([
        Motor.getColoursList(),
        Motor.getVehicleBodyList(),
        Motor.getCoverTypeList(),
        Motor.getSectorList(),
        Motor.getStateList(),
        Motor.getRiskClassList(),
        Motor.getBankList(),
        Motor.getCompanyBankList(),
        Motor.getVehicleModelList()
      ]).then(
        (values) => {
          console.log(values);
          [
            Motor.fields.colours,
            Motor.fields.vehicleBodies,
            Motor.fields.coverTypes,
            Motor.fields.sectors,
            Motor.fields.states,
            Motor.fields.riskClasses,
            Motor.fields.banks,
            Motor.fields.companyBanks,
            _this.fields.vehicleModels
          ] = values;

          // Remove Additional Policy Section
          // if (!_.isEmpty(_this.fields.individualPolicyList)) {
          //     $('#additionalPolicy').removeClass('hide_elements');
          //     $('#existing_policy_number').val(_this.fields.individualPolicyList[0].policy_number);
          // }
        },
        (error) => {
          console.log(error);
        }
      );
    },
    setCustomerProfile: () => {
      const userRole = $('#user_role_loggedIn')
        .val()
        .toLowerCase();
      if (userRole === 'client') {
        Utility.fields.selectedProfile = JSON.parse($('#user_profile').val());
      }
    },
    wizardStepOne: () => {
      $('.alert-message-text').html('');
      $('.alert-message').removeClass('error');
      console.log('User Role ', $('#user_role_loggedIn').val());
      const userRole = $('#user_role_loggedIn')
        .val()
        .toLowerCase();
      if (userRole === 'agent') {
        if (_thisBroker.fields.insurancePolicyType === 'new') {
          $('.loading_icon').removeClass('hide_elements');
          _this.fetchVehicleDetails();
        } else if (_thisBroker.fields.insurancePolicyType === 'renew') {
          _this.getPolicyEnquiryDetails().then((result) => {
            // console.log(result);
            $('.loading_icon').addClass('hide_elements');
            _thisBroker.fillVehicleDetails(result.message);
          });
        }
      } else if (userRole === 'client') {
        $('.loading_icon').removeClass('hide_elements');
        if (_this.fields.activeTab === 'newPolicy') {
          _this.fetchVehicleDetails();
        } else {
          _this.getPolicyEnquiryDetails().then((result) => {
            // console.log(result);
            $('.loading_icon').addClass('hide_elements');
            _this.fillVehicleDetails(result.message);
          });
        }
      } else {
        _this
          .getExistingVehicleDetails()
          .then((result) => {
            if (_.isEmpty(result)) {
              $('.loading_icon').removeClass('hide_elements');
              _this.fetchVehicleDetails();
            } else {
              Motor.fields.vehicleDetails = {
                licenseInfo: {
                  color: result.colour,
                  model: result.vehicle_model,
                  registrationNumber: result.registration_number,
                  chasisNumber: result.chasis_number,
                  engineNumber: result.engine_number,
                  vehicleMakeName: result.vehicle_make,
                  vehicleStatus: result.vehicle_status,
                  isssueDate: result.issue_date,
                  expiryDate: result.expiry_date
                },
                otherDetails: {
                  vehicleModel: result.vehicle_model,
                  vehicleDetailsId: result.id,
                  vehicleBody: result.vehicle_body,
                  vehicleCubicCapacity: result.vehicle_cubic_capacity,
                  vehicleNumOfSeats: result.vehicle_num_of_seats,
                  vehicleYearOfMake: result.year_of_make,
                  vehicleYearOfPurchase: result.year_of_purchase,
                  vehiclePurchasePrice: result.purchase_price,
                  vehicleStateOfPurchase: result.state_of_purchase,
                  contactPerson: result.contact_person,
                  bankAccountBvn: result.bank_account_bvn,
                  bankAccountNumber: result.bank_account_number,
                  customerBank: result.customer_bank_name,
                  companyBank: result.company_bank_name,
                  sector: result.sector,
                  effectiveDate: result.effective_date
                },
                payment: result.payment,
                policy: result.policy
              };
              _this.populateVehicleDetailsScreen();
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },

    wizardStepTwo: () => {
      // console.log($('#tab2form').valid());
      if ($('#tab2form').valid()) {
        // const vehicleTransactionDetails = {..._this.fields.vehicleDetails.licenseInfo};
        // vehicleTransactionDetails['profileId'] = Utility.fields.selectedProfile.id;
        // vehicleTransactionDetails['vehicleModel'] = $('#vehicle_make_model').val();
        // vehicleTransactionDetails['vehicleBody'] = $('#vehicle_body').val();
        // vehicleTransactionDetails['vehicleColour'] = $('#vehicle_color').val();
        // vehicleTransactionDetails['vehicleCubicCapacity'] = $('#vehicle_cubic').val();
        // vehicleTransactionDetails['vehicleNumOfSeats'] = $('#vehicle_number_of_seats').val();
        // vehicleTransactionDetails['vehicleNumOfSeats'] = $('#vehicle_number_of_seats').val();
        // vehicleTransactionDetails['vehicleYearOfMake'] = $('#vehicle_year_make').val();
        // vehicleTransactionDetails['vehicleYearOfPurchase'] = $('#vehicle_year_purchase').val();
        // vehicleTransactionDetails['vehiclePurchasePrice'] = $('#vehicle_purchase_price').val().replace(/,/g, '');
        // vehicleTransactionDetails['vehicleStateOfPurchase'] = $('#vehicle_purchase_state').val();
        // vehicleTransactionDetails['contactPerson'] = Utility.fields.selectedProfile.contact_person;
        // vehicleTransactionDetails['bankAccountBvn'] = "09876543211"; //$('#bank_verification_number').val();
        // vehicleTransactionDetails['bankAccountNumber'] = $('#bank_account_number').val();
        // vehicleTransactionDetails['customerBank'] = Utility.fields.selectedProfile.customer_bank;
        // vehicleTransactionDetails['companyBank'] = "310-0342"; //$('#company_bank').val();
        // vehicleTransactionDetails['occupationSector'] = Utility.fields.selectedProfile.sector;
        // vehicleTransactionDetails['vehicleEffectiveDate'] = $('#vehicle_effective_date').val();

        _this.fields.formData = $('#tab2form').serializeJSON();
        // Save Form Details
        let vehicleRegNum = $('#vehicle_reg_num').val();
        const vehicleData = {
          id: `${moment().format('YYYYMMDDHHmmss')}_${vehicleRegNum}`,
          profileId: Utility.fields.selectedProfile.id,
          registrationNumber: $('#vehicle_reg_num').val(),
          formDetails: _this.fields.formData
        };
        const operation = _.isEmpty(_this.fields.savedEntry) ? 'save' : 'update';
        if (!_.isEmpty(_this.fields.savedEntry)) {
          vehicleData['vehicleDetailsId'] = _this.fields.savedEntry.vehicle_details_id;
        }
        Utility.saveVehicleDetails(operation, vehicleData)
          .then((result) => {
            console.log(result);
            _this.fields.savedEntry = result;
          })
          .catch((err) => {
            console.log(err);
          });
        // console.log(vehicleTransactionDetails);
        // _this.saveVehicleDetails(vehicleTransactionDetails);
      }

      return $('#tab2form').valid();
    },
    wizardStepThree: () => {
      $('#rootwizard').bootstrapWizard('show', 3);
      const KOBO_MULTIPLIER = 100;
      const mobileNumber =
        Utility.fields.selectedProfile.gsm_number.trim() !== ''
          ? Utility.fields.selectedProfile.gsm_number
          : Utility.fields.selectedProfile.office_number;
      const customerEmail = Utility.fields.selectedProfile.email_address;
      const coverageAmount = $('#coverage_amount').html();
      let transactionAmount = coverageAmount.replace(/,/g, '');
      transactionAmount = parseFloat(transactionAmount, 2) * KOBO_MULTIPLIER;
      const transactionReference = Motor.generateTransactionId(customerEmail);
      Motor.transactionDetails = {
        userId: Utility.fields.selectedProfile.user_id,
        profileId: Utility.fields.selectedProfile.id,
        transactionReference: transactionReference,
        customerEmail: customerEmail,
        transactionAmount: transactionAmount.toFixed(2),
        transactionDate: moment().format('YYYY-MM-DD HH:mm:ss'),
        paymentGateway: 'PayStack',
        responseStatus: 'initiate',
        responseReference: '',
        responseMessage: ''
      };
      // Set Payment Information Tab
      $('#transactionReference').html(transactionReference);
      $('#transactionAmount').html(coverageAmount);
      $('#transactionDate').html(Motor.transactionDetails.transactionDate);
      const handler = PaystackPop.setup({
        key: paystack.key,
        email: customerEmail,
        amount: transactionAmount.toFixed(2),
        ref: transactionReference,
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
          Motor.transactionDetails.responseReference = response.transaction;
          Motor.transactionDetails.responseStatus = response.status;
          Motor.transactionDetails.responseMessage = response.message;
          console.log(Motor.transactionDetails);
          // $('#rootwizard').bootstrapWizard('show', 4);
          $('#rootwizard').bootstrapWizard('next');
          $('.previous').css('display', 'none');
          // Motor.wizardStepFour();
        },
        onClose: function() {
          console.log('window closed');
          // $('#rootwizard').bootstrapWizard('show', 4);
        }
      });
      handler.openIframe();
    },
    wizardStepFour: () => {
      const userRole = $('#user_role_loggedIn')
        .val()
        .toLowerCase();
      if (
        _this.transactionDetails !== undefined &&
        !_.isEmpty(_this.transactionDetails) &&
        _this.transactionDetails.responseReference !== ''
      ) {
        const vehicleTransactionPayment = {
          ..._this.transactionDetails,
          vehicleTransactionDetailsId:
            _this.fields.savedEntry !== undefined && _this.fields.savedEntry.vehicle_details_id !== undefined
              ? _this.fields.savedEntry.vehicle_details_id
              : 0
        };
        _this.saveVehiclePaymentDetails(vehicleTransactionPayment);
      }
      if (Motor.transactionDetails.responseStatus === 'initiate') {
        $('#paymentSwitchStatus').html('retry payment ...');
        Motor.wizardStepThree();
        return false;
      }
      console.log('Call Legend');
      $('.loading_icon').removeClass('hide_elements');
      const effectiveDate = $('#vehicle_effective_date').val();
      const expiryDate = moment(effectiveDate, 'YYYY-MM-DD')
        .add(1, 'years')
        .subtract(1, 'days')
        .format('YYYY-MM-DD');
      const user_category =
        Utility.fields.selectedProfile.user_category === ''
          ? 'Individual'
          : Utility.fields.selectedProfile.user_category;
      const client_class = Motor.clientClasses[user_category];
      // const bank = client_class === 'I' ? $('#customer_bank').val() : $('#company_bank').val();
      const risk_class = client_class === 'I' ? 'PMI' : 'PMC';
      const cover_type = client_class === 'I' ? 'PM/T/PMI' : 'PM/T/PMC';
      const title_id = Utility.fields.selectedProfile.title;
      Motor.legendData = $('#renewPolicy').hasClass('active')
        ? userRole === 'client'
          ? _this.setRenewalData()
          : _thisBroker.setLegendRenewalData()
        : _this.setLegendData();
      console.log(Motor.legendData);
      // Added to handle client with existing policy trying to generate insurance on another car
      if (
        !$('#newAndAdditionalPolicySection').hasClass('hide_elements') &&
        ($('#new_additional_policy').prop('checked') ||
          (_this.fields.policyAlreadyExists && _this.fields.activeTab === 'newPolicy'))
      ) {
        _this.resetLegendData();
      }
      $('.loading_icon').removeClass('hide_elements');
      let promise;
      if ($('#renewPolicy').hasClass('active')) {
        promise = _thisCommon.getRenewalPolicy(Motor.legendData);
      } else {
        promise = Motor.getPolicy(Motor.legendData);
      }
      promise
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
            _this.savePolicyDetails();

            // Set the response html elements
            $('#client_number').html(_this.fields.legendResponse.ClientNumber);
            $('#policy_number').html(_this.fields.legendResponse.PolicyNumber);
            $('#certificate_number').html(_this.fields.legendResponse.CertificateNumber);
            $('#debit_note_number').html(_this.fields.legendResponse.DebitNoteNumber);
            $('#receipt_number').html(_this.fields.legendResponse.ReceiptNumber);
            $('#expiry_date').html(_this.fields.legendResponse.ExpiryDate);
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
    },

    fetchVehicleDetails: () => {
      Motor.getVehicleDetails()
        .then(function(result) {
          console.log(result);
          xmlDoc = $.parseXML(result);
          $xml = $(xmlDoc);
          responseString = $xml
            .find('string')
            .text()
            .replace(/\n|\r/g, '');
          const newData = `<string>${responseString}</string>`;
          const innerValues = $.parseXML(newData);
          const $innerXml = $(innerValues);
          const chasisNumber = $innerXml.find('ChasisNo').text();
          const engineNumber = $innerXml.find('EngineNo').text();
          Motor.fields.vehicleDetails = {
            licenseInfo: {
              color: $innerXml.find('Color').text(),
              model: $innerXml.find('Model').text(),
              registrationNumber: $innerXml.find('RegistrationNo').text(),
              chasisNumber: chasisNumber.replace(/O/g, '0').replace(/I/g, '1'),
              engineNumber: engineNumber.replace(/O/g, '0').replace(/I/g, '1'),
              vehicleMakeName: $innerXml.find('VehicleMakeName').text(),
              vehicleStatus: $innerXml.find('VehicleStatus').text(),
              isssueDate: $innerXml
                .find('IsssueDate')
                .text()
                .replace('T', ' '),
              expiryDate: $innerXml
                .find('ExpiryDate')
                .text()
                .replace('T', ' ')
            },
            response: {
              responseCode: $innerXml.find('ResponseCode').text(),
              responseMessage: $innerXml.find('ResponseMessage').text()
            }
          };
          console.log(Motor.fields.vehicleDetails);
          if (Motor.fields.vehicleDetails.response && Motor.fields.vehicleDetails.response.responseMessage === 'ok') {
            // Populate the vehicle details screen
            _this.populateVehicleDetailsScreen();
          } else {
            $('.alert-message').addClass('error');
            $('.alert-message-text').html(
              Motor.fields.vehicleDetails.response.responseMessage || 'error fetching vehicle details'
            );
          }
        })
        .catch((error) => {
          console.log(error);
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('error fetching vehicle details');
          reject('failed!');
        })
        .finally(() => {
          $('.loading_icon').addClass('hide_elements');
        });
    },

    fillVehicleDetails: (vehicleDetails) => {
      if (vehicleDetails.indexOf('Colour Code') <= -1) {
        $('.alert-message-text').html(vehicleDetails);
        $('.alert-message').addClass('error');
        return false;
      }
      let vehicleData = {};
      const data = vehicleDetails.split(',');
      console.log(data);
      data.forEach((d) => {
        let element = d.split(':');
        vehicleData[element[0].trim()] = element[1].trim();
      });
      console.log(vehicleData);
      _this.fields.renewalDetails = {
        colorCode: vehicleData['Colour Code'],
        colorName: vehicleData['Colour Name'],
        coverType: vehicleData['Cover Type'],
        cubicCapacity: vehicleData['Cubic Capacity'],
        currency: vehicleData['Currency'],
        currentPremium: vehicleData['Current Premium'],
        effectiveDate: vehicleData['Effective Date'],
        expiryDate: vehicleData['Expiry Date'],
        makeModel: vehicleData['Make/Model'],
        policyNumber: vehicleData['Policy Number'],
        clientNumber: $('#existing_policy_number').val(),
        purchaseYear: vehicleData['Purchase Year'],
        renewalPremium: vehicleData['Renewal Premium'],
        riskClass: vehicleData['Risk Class'],
        seat: vehicleData['Seat'],
        stateRegion: vehicleData['State/Region'],
        stickerNumber: vehicleData['Sticker Number'],
        sumInsured: vehicleData['Sum Insured'],
        vehicleBodyCode: vehicleData['Vehicle Body Code'],
        vehicleTypeCode: vehicleData['Vehicle Type Code'],
        registrationNumber: $('#vehicle_reg_num').val(),
        chasisNumber: vehicleData['Chassis Number'],
        engineNumber: vehicleData['Engine Number'],
        clientName: vehicleData['Client Name'],
        yearOfMake: vehicleData['Year of Make']
      };
      const premium = parseFloat(_this.fields.renewalDetails.renewalPremium) / 100;
      $('#coverage_amount').html(Motor.formatNumber(`${premium}`));
      $('#insurance_class').prop('disabled', 'disabled');
      console.log(_this.fields.renewalDetails);
      $('#rootwizard').bootstrapWizard('show', 2);
    },

    setLegendData: () => {
      const name = _.isEmpty(Utility.fields.selectedProfile.lastname)
        ? Utility.fields.selectedProfile.company_name
        : Utility.fields.selectedProfile.lastname;
      const user_category =
        Utility.fields.selectedProfile.user_category === ''
          ? 'Individual'
          : Utility.fields.selectedProfile.user_category;
      const client_class = Motor.clientClasses[user_category];
      const effectiveDate = $('#vehicle_effective_date').val();
      const expiryDate = moment(effectiveDate, 'YYYY-MM-DD')
        .add(1, 'years')
        .subtract(1, 'days')
        .format('YYYY-MM-DD');
      const vehicleDetailsId = _.isEmpty(_this.fields.savedEntry)
        ? `${moment().format('YYYYMMDDHHmmss')}_${$('#vehicle_reg_num').val()}`
        : _this.fields.savedEntry.vehicle_details_id;
      const data = {
        username: 'website',
        userpassword: 'website',
        firstname: Utility.fields.selectedProfile.firstname,
        lastname: name,
        othernames: `${Utility.fields.selectedProfile.firstname} ${Utility.fields.selectedProfile.othernames}`,
        address: Utility.fields.selectedProfile.street_address,
        city: 'ABA', //Utility.fields.selectedProfile.city,
        contact_person: Utility.fields.selectedProfile.contact_person,
        state: 'AB', //Utility.fields.selectedProfile.state,
        title_id: Utility.fields.selectedProfile.title,
        client_class: client_class,
        gsm_number:
          Utility.fields.selectedProfile.gsm_number.trim() !== '' ? 'N/A' : Utility.fields.selectedProfile.gsm_number,
        office_number:
          Utility.fields.selectedProfile.office_number === '' ? 'N/A' : Utility.fields.selectedProfile.office_number,
        fax_number:
          Utility.fields.selectedProfile.fax_number === '' ? 'N/A' : Utility.fields.selectedProfile.fax_number,
        email_address: Utility.fields.selectedProfile.email_address,
        website: Utility.fields.selectedProfile.website === '' ? 'N/A' : Utility.fields.selectedProfile.website,
        company_reg_num: Utility.fields.selectedProfile.company_reg_num,
        date_of_birth: Utility.fields.selectedProfile.date_of_birth,
        lga: 'L17001',
        tin_number:
          Utility.fields.selectedProfile.tin_number === '' ? 'N/A' : Utility.fields.selectedProfile.tin_number,
        bvn: '09876543211',
        bank_id: Utility.fields.selectedProfile.customer_bank,
        account_number: Utility.fields.selectedProfile.bank_account_number,
        occupation: Utility.fields.selectedProfile.occupation,
        sector: Utility.fields.selectedProfile.sector,
        premium: Motor.transactionDetails.transactionAmount,
        sum_insured: '0',
        vehicle_plate_number: $('#vehicle_reg_num').val(),
        model: $('#vehicle_make_model').val(),
        body: $('#vehicle_body').val(),
        color: $('#vehicle_color').val(),
        cubic_capacity: $('#vehicle_cubic').val(),
        number_of_seat: $('#vehicle_number_of_seats').val(),
        engine_number: $('#vehicle_engine_number').val(),
        chasis_number: $('#vehicle_chasis_number').val(),
        year_of_make: $('#vehicle_year_make').val(),
        year_of_purchase: $('#vehicle_year_purchase').val(),
        mode_of_payment: 'CASH',
        policy_class: $('#policy_class').val(),
        risk_class: client_class === 'I' ? 'PMI' : 'PMC',
        cover_type: client_class === 'I' ? 'PM/T/PMI' : 'PM/T/PMC',
        basic_rate: '0',
        location: '',
        currency: 'NGN',
        company_bank: '310-0342', //$('#company_bank').val(),
        effective_date: effectiveDate,
        expiry_date: expiryDate,
        payment_reference: Motor.transactionDetails.transactionReference,
        vehicleTransactionDetailsId: vehicleDetailsId,
        policy_type: 'motor',
        client_number: ''
      };
      return data;
    },

    resetLegendData: () => {
      Motor.legendData.firstname = '';
      Motor.legendData.lastname = '';
      Motor.legendData.othernames = '';
      Motor.legendData.address = '';
      Motor.legendData.city = '';
      Motor.legendData.contact_person = '';
      //   Motor.legendData.state = '';
      Motor.legendData.title_id = '';
      Motor.legendData.gsm_number = '';
      Motor.legendData.office_number = '';
      Motor.legendData.fax_number = '';
      Motor.legendData.email_address = '';
      Motor.legendData.company_reg_num = '';
      Motor.legendData.date_of_birth = '';
      Motor.legendData.client_number = $('#existing_client_number').val();
    },

    setRenewalData: () => {
      let effective_date = _this.fields.renewalDetails.expiryDate.substring(0, 9);
      effective_date = moment(effective_date, 'DD-MMM-YY').format('YYYY-MM-DD');
      expiry_date = moment(effective_date, 'YYYY-MM-DD')
        .add(1, 'years')
        .format('YYYY-MM-DD');
      agent_code = $('#agent_code').val();
      const vehicleDetailsId = _.isEmpty(_this.fields.savedEntry)
        ? `${moment().format('YYYYMMDDHHmmss')}_${$('#vehicle_reg_num').val()}`
        : _this.fields.savedEntry.vehicle_details_id;
      const data = {
        policy_number: _this.fields.renewalDetails.policyNumber,
        client_number: _this.fields.renewalDetails.clientNumber,
        premium: _this.fields.renewalDetails.renewalPremium,
        vehicle_plate_number: _this.fields.renewalDetails.registrationNumber,
        state: _this.fields.renewalDetails.stateRegion,
        model: _this.fields.renewalDetails.vehicleTypeCode,
        body: _this.fields.renewalDetails.vehicleBodyCode,
        color: _this.fields.renewalDetails.colorCode,
        cubic_capacity: _this.fields.renewalDetails.cubicCapacity,
        number_of_seat: _this.fields.renewalDetails.seat,
        engine_number: _this.fields.renewalDetails.engineNumber,
        chasis_number: _this.fields.renewalDetails.chasisNumber,
        year_of_make: _this.fields.renewalDetails.yearOfMake,
        year_of_purchase: _this.fields.renewalDetails.purchaseYear,
        mode_of_payment: 'CASH',
        agent_name: agent_code,
        cover_type: _this.fields.renewalDetails.coverType,
        currency: _this.fields.renewalDetails.currency,
        company_bank: '310-0342',
        effective_date: effective_date,
        expiry_date: expiry_date,
        payment_reference: Motor.transactionDetails.transactionReference,
        vehicleTransactionDetailsId: vehicleDetailsId
      };
      return data;
    },

    saveVehicleDetails: (vehicleTransactionDetails) => {
      const url =
        _this.fields.vehicleDetails.otherDetails !== undefined
          ? `${api_urls.vehicletransactiondetails}/${_this.fields.vehicleDetails.otherDetails.vehicleDetailsId}`
          : api_urls.vehicletransactiondetails;
      const formType = _this.fields.vehicleDetails.otherDetails !== undefined ? 'PUT' : 'POST';
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: formType,
          url: url,
          // contentType: "application/json; charset=utf-8",
          data: vehicleTransactionDetails,
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
        .then((result) => {
          console.log(result);
          vehicleTransactionDetails['vehicleTransactionDetailsId'] = result.id;
          _this.fields.vehicleTransactionDetails = vehicleTransactionDetails;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    saveVehiclePaymentDetails: (vehiclePaymetDetails) => {
      const url = api_urls.vehicletransactionpayment;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          url: url,
          // contentType: "application/json; charset=utf-8",
          data: vehiclePaymetDetails,
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
        .then((result) => {
          console.log(result);
        })
        .catch((err) => {
          console.log(err);
        });
    },

    savePolicyDetails: () => {
      const vehicleDetailsId = _.isEmpty(_this.fields.savedEntry)
        ? `${moment().format('YYYYMMDDHHmmss')}_${$('#vehicle_reg_num').val()}`
        : _this.fields.savedEntry.vehicle_details_id;
      //   const coverType =   _.isEmpty($('#insurance_class').val()) ?
      const url = api_urls.vehicletransactionpolicy;
      if (!_.isEmpty(_this.fields.legendResponse)) {
        const policyDetails = {
          profileId: $('#profile_id').val(),
          userId: $('#user_id').val(),
          policyType: 'motor',
          coverType: $('#insurance_class').val(),
          coverOption: _this.fields.coverOption,
          coverAddOns: JSON.stringify(_this.fields.coverAddOns),
          vehicleTransactionDetailsId: vehicleDetailsId,
          ..._this.fields.legendResponse
        };
        const promise = new Promise(function(resolve, reject) {
          $.ajax({
            type: 'POST',
            url: url,
            // contentType: "application/json; charset=utf-8",
            data: policyDetails,
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
          .then((result) => {
            _this.fields.policyDetails = result;
            console.log(result);
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },

    populateVehicleDetailsScreen: () => {
      $('#vehicle_engine_number').val(Motor.fields.vehicleDetails.licenseInfo.engineNumber);
      $('#vehicle_chasis_number').val(Motor.fields.vehicleDetails.licenseInfo.chasisNumber);
      Motor.populateColorList();
      Motor.populateVehicleBodies();
      Motor.populateVehicleModels();
      Motor.populateSectors();
      Motor.populateStates();
      Motor.populateCoverTypes();
      Motor.populateRiskClasses();
      Motor.populateBanks();
      Motor.populateCompanyBanks();

      if (_this.fields.vehicleDetails.otherDetails !== undefined) {
        $('#vehicle_make_model').val(Motor.fields.vehicleDetails.licenseInfo.model);
        $('#vehicle_body').val(_this.fields.vehicleDetails.otherDetails.vehicleBody);
        $('#vehicle_color').val(_this.fields.vehicleDetails.licenseInfo.color);
        $('#vehicle_cubic').val(_this.fields.vehicleDetails.otherDetails.vehicleCubicCapacity);
        $('#vehicle_number_of_seats').val(_this.fields.vehicleDetails.otherDetails.vehicleNumOfSeats);
        $('#vehicle_year_make').val(_this.fields.vehicleDetails.otherDetails.vehicleYearOfMake);
        $('#vehicle_year_purchase').val(_this.fields.vehicleDetails.otherDetails.vehicleYearOfPurchase);
        $('#vehicle_purchase_price').val(_this.fields.vehicleDetails.otherDetails.vehiclePurchasePrice);
        $('#vehicle_purchase_state').val(_this.fields.vehicleDetails.otherDetails.vehicleStateOfPurchase);
        $('#contact_person').val(_this.fields.vehicleDetails.otherDetails.contactPerson);
        $('#bank_verification_number').val(_this.fields.vehicleDetails.otherDetails.bankAccountBvn);
        $('#bank_account_number').val(_this.fields.vehicleDetails.otherDetails.bankAccountNumber);
        $('#customer_bank').val(_this.fields.vehicleDetails.otherDetails.customerBank);
        $('#company_bank').val(_this.fields.vehicleDetails.otherDetails.companyBank);
        $('#occupation_sector').val(_this.fields.vehicleDetails.otherDetails.sector);
        $('#vehicle_effective_date').val(_this.fields.vehicleDetails.otherDetails.effectiveDate);
      }
      let paymentSuccess = [];
      if (_this.fields.vehicleDetails.payment !== undefined && _this.fields.vehicleDetails.payment !== null) {
        paymentSuccess = _this.fields.vehicleDetails.payment.filter((p) => p.response_status === 'success');
      }

      if (!_.isEmpty(paymentSuccess)) {
        $('.alert-message').addClass('error');
        let messageText = `Transaction with successful payment reference ${
          paymentSuccess[0].transaction_reference
        } already exist <br/>`;
        // check for successful policy
        const policyDetails = _this.fields.vehicleDetails.policy;
        if (policyDetails !== undefined && policyDetails.policy_number !== undefined) {
          messageText = `${messageText} Policy #: ${policyDetails.policy_number} already exist `;
        } else {
          messageText = `${messageText} <a href="../motor-policy/${
            _this.fields.vehicleDetails.otherDetails.vehicleDetailsId
          }">Click to retry obtaining policy information</a>`;
        }
        $('.alert-message-text').html(messageText);
      } else {
        $('#rootwizard').bootstrapWizard('show', 1);
      }
    },

    onClassChange: () => {
      const numDaysInYear = moment.duration({ years: 1 }).asDays();
      const comprehensive_ratio = 0.04;
      const third_party_amount = 5000;
      const third_party_fire_theft = 10000;
      const tracking_display_threshold = 3000000;
      const insurance_class = $('#insurance_class').val();
      const vehicle_purchase_price = $('#vehicle_purchase_price')
        .val()
        .replace(/,/g, '');
      const vehicle_value =
        vehicle_purchase_price && !isNaN(vehicle_purchase_price) ? parseFloat(vehicle_purchase_price).toFixed(2) : 0.0;
      let coverage_amount = vehicle_value * comprehensive_ratio;
      let comprehensiveAddOns = {};
      switch (insurance_class) {
        case 'comprehensive': {
          coverage_amount = vehicle_value * comprehensive_ratio;
          //Display Messaging
          if ($('#comprehensive_message').hasClass('hide')) {
            $('#comprehensive_message').removeClass('hide');
            $('#third_party_message')
              .removeClass('hide')
              .addClass('hide');
          }

          if ($('#comprehensive_types_row').hasClass('hide')) {
            $('#comprehensive_types_row').removeClass('hide');
          }
          if ($('#comprehensive_addons_row').hasClass('hide')) {
            $('#comprehensive_addons_row').removeClass('hide');
          }
          if (
            $('#comprehensive_addons_tracking').hasClass('hide') &&
            vehicle_purchase_price > tracking_display_threshold
          ) {
            $('#comprehensive_addons_tracking').removeClass('hide');
          }

          // Calculate Type
          let expiry_date = '';
          const comprehensive_type = $('#comprehensive_type').val();
          if (comprehensive_type === 'quartely') {
            coverage_amount = coverage_amount / 4;
            expiry_date = moment()
              .add(parseInt(numDaysInYear / 4), 'days')
              .format('YYYY-MM-DD');
            $('#vehicle_expiry_date').val(expiry_date);
          } else if (comprehensive_type === 'half_year') {
            coverage_amount = coverage_amount / 2;
            expiry_date = moment()
              .add(parseInt(numDaysInYear / 2), 'days')
              .format('YYYY-MM-DD');
            $('#vehicle_expiry_date').val(expiry_date);
          } else {
            expiry_date = moment()
              .add(1, 'years')
              .subtract(1, 'days')
              .format('YYYY-MM-DD');
            $('#vehicle_expiry_date').val(expiry_date);
          }

          // Calculate Add Ons
          if ($('#comprehensive_addons_flood').is(':checked')) {
            if (!('flood_extension' in comprehensiveAddOns)) {
              Object.assign(comprehensiveAddOns, { flood_extension: (coverage_amount * (0.5 / 100)).toFixed(2) });
            }
          } else {
            if ('flood_extension' in comprehensiveAddOns) {
              delete comprehensiveAddOns['flood_extension'];
            }
          }
          //Riot
          if ($('#comprehensive_addons_riot').is(':checked')) {
            if (!('riot' in comprehensiveAddOns)) {
              Object.assign(comprehensiveAddOns, { riot: (coverage_amount * (0.5 / 100)).toFixed(2) });
            }
          } else {
            if ('riot' in comprehensiveAddOns) {
              delete comprehensiveAddOns['riot'];
            }
          }
          //Excess
          if ($('#comprehensive_addons_excess').is(':checked')) {
            if (!('excess_buy_back' in comprehensiveAddOns)) {
              Object.assign(comprehensiveAddOns, { excess_buy_back: (coverage_amount * (0.5 / 100)).toFixed(2) });
            }
          } else {
            if ('excess_buy_back' in comprehensiveAddOns) {
              delete comprehensiveAddOns['excess_buy_back'];
            }
          }
          //Tracking
          if ($('#comprehensive_addons_tracking_option').is(':checked')) {
            if (!('tracking' in comprehensiveAddOns)) {
              Object.assign(comprehensiveAddOns, { tracking: 0.0 });
            }
          } else {
            if ('tracking' in comprehensiveAddOns) {
              delete comprehensiveAddOns['tracking'];
            }
          }
          for (const key in comprehensiveAddOns) {
            coverage_amount = coverage_amount + parseFloat(comprehensiveAddOns[key]);
          }

          break;
        }
        case 'third_party': {
          comprehensiveAddOns = {};
          coverage_amount = third_party_amount;
          //Display Messaging
          if ($('#third_party_message').hasClass('hide')) {
            $('#third_party_message').removeClass('hide');
            $('#comprehensive_message')
              .removeClass('hide')
              .addClass('hide');
          }

          if (!$('#comprehensive_types_row').hasClass('hide')) {
            $('#comprehensive_types_row').addClass('hide');
          }
          if (!$('#comprehensive_addons_row').hasClass('hide')) {
            $('#comprehensive_addons_row').addClass('hide');
          }
          if (!$('#comprehensive_addons_tracking').hasClass('hide')) {
            $('#comprehensive_addons_tracking').addClass('hide');
          }
          break;
        }
        case 'third_party_fire_theft': {
          comprehensiveAddOns = {};
          coverage_amount = third_party_fire_theft;
          //Display Messaging
          if ($('#third_party_message').hasClass('hide')) {
            $('#third_party_message').removeClass('hide');
            $('#comprehensive_message')
              .removeClass('hide')
              .addClass('hide');
          }
          if (!$('#comprehensive_types_row').hasClass('hide')) {
            $('#comprehensive_types_row').addClass('hide');
          }
          if (!$('#comprehensive_addons_row').hasClass('hide')) {
            $('#comprehensive_addons_row').addClass('hide');
          }
          if (!$('#comprehensive_addons_tracking').hasClass('hide')) {
            $('#comprehensive_addons_tracking').addClass('hide');
          }

          break;
        }
      }
      _this.fields.coverAddOns = comprehensiveAddOns;
      $('#coverage_amount').html(Motor.formatNumber(`${coverage_amount}`));
    },

    getExistingVehicleDetails: function() {
      const url = api_urls.vehicletransactiondetails;
      const vehicle_reg_num = $('#vehicle_reg_num').val();
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${vehicle_reg_num}`,
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

    getExistingVehicleDetailsById: function(id) {
      const url = api_urls.getTransactionDetails;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${id}`,
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

    getExistingVehicleDetailsByRegistrationNumber: function(id) {
      const url = api_urls.gettransactiondetailsbyregistration;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${id}`,
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

    getVehicleDetails: function() {
      const url = api_urls.autoRegValidation;
      const vehicle_reg_num = $('#vehicle_reg_num').val();
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${vehicle_reg_num}`,
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

    getPolicyEnquiryDetails: function() {
      $('.loading_icon').removeClass('hide_elements');
      const url = api_urls.enquirypolicynumber;
      const vehicle_reg_num = $('#vehicle_reg_num').val();
      const existing_policy_number = $('#existing_policy_number').val();
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          method: 'POST',
          url: url,
          data: {
            policyNumber: existing_policy_number,
            registrationNumber: vehicle_reg_num
          },
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

    getColoursList: () => {
      const url = api_urls.colours;
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

    getVehicleBodyList: () => {
      const url = api_urls.vehiclebodies;
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

    getVehicleModelList: () => {
      const url = api_urls.vehiclemodels;
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

    getCoverTypeList: () => {
      const url = api_urls.getcovertypes;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/motor`,
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

    getRiskClassList: () => {
      const url = api_urls.riskclasses;
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

    getStateList: () => {
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

    getCompanyBankList: () => {
      const url = api_urls.companybanks;
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

    getIndividualPolicyList: () => {
      const url = api_urls.individualPolicyList;
      const profileId = $('#profile_id').val();
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${profileId}`,
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

    populateColorList: () => {
      $vehicleColor = $('#vehicle_color');
      $.each(Motor.fields.colours, (i, v) => {
        $vehicleColor.append(`<option value=${v.value}> ${v.name} </option>`);
      });
      $('#vehicle_color option')
        .filter(function() {
          return $.trim($(this).text()) == Motor.fields.vehicleDetails.licenseInfo.color.toUpperCase();
        })
        .attr('selected', 'selected');
    },

    populateYearsList: () => {
      $selectPurchaseYear = $('#vehicle_year_purchase');
      $selectMakeYear = $('#vehicle_year_make');
      const currentYear = parseInt(moment().format('YYYY'));
      for (i = currentYear; i >= currentYear - 30; --i) {
        $selectPurchaseYear.append(`<option value=${i}> ${i} </option>`);
        $selectMakeYear.append(`<option value=${i}> ${i} </option>`);
      }
    },

    populateVehicleBodies: () => {
      $vehicleBody = $('#vehicle_body');
      $.each(Motor.fields.vehicleBodies, (i, v) => {
        $vehicleBody.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateVehicleModels: () => {
      $vehicleModels = $('#vehicle_make_model');
      $.each(_this.fields.vehicleModels, (i, v) => {
        $vehicleModels.append(`<option value=${v.value}> ${v.name} </option>`);
      });
      $('#vehicle_make_model option')
        .filter(function() {
          return $.trim($(this).text()) == Motor.fields.vehicleDetails.licenseInfo.model.toUpperCase();
        })
        .attr('selected', 'selected');
    },

    populateSectors: () => {
      // $occupationSector = $('#occupation_sector');
      // $.each(Motor.fields.sectors, (i,v) => {
      //     $occupationSector.append(`<option value=${v.value}> ${v.name} </option>`);
      // })
    },

    populateStates: () => {
      $vehiclePurchaseState = $('#vehicle_purchase_state');
      $.each(Motor.fields.states, (i, v) => {
        $vehiclePurchaseState.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateRiskClasses: () => {
      $vehicleRiskClass = $('#vehicle_risk_class');
      $.each(Motor.fields.riskClasses, (i, v) => {
        $vehicleRiskClass.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateCoverTypes: () => {
      $vehicleCoverTypes = $('#vehicle_cover_type');
      $.each(Motor.fields.coverTypes, (i, v) => {
        $vehicleCoverTypes.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateBanks: () => {
      $customerBank = $('#customer_bank');
      $.each(Motor.fields.banks, (i, v) => {
        $customerBank.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateCompanyBanks: () => {
      $companyBank = $('#company_bank');
      $.each(Motor.fields.companyBanks, (i, v) => {
        $companyBank.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateClientPolicies: () => {
      $policy_table = $('#policy_table tbody');
      $policy_table.empty();
      $.each(_this.fields.individualPolicyList, (i, v) => {
        const markup = `<tr>
                    <td>${v.vehicle_transaction_details_id}</td>
                    <td class="text-semibold text-dark">${v.cover_type}</td>
                    <td class="text-semibold text-dark">${v.policy_number}</td>
                    <td class="text-center">${v.certificate_number}</td>
                    <td class="text-center">${v.expiry_date}</td>
                    <td class="text-center"><button class="btn btn-primary" onclick="_this.gotoPage(${
                      v.vehicle_transaction_details_id
                    })"><i class="fa fa-repeat"> Renew </button></td>
                    <td class="text-center"><button class="btn btn-primary" onclick="_this.printPage(${i})"><i class="fa fa-print"> Print </button></td>
                </tr>`;
        $policy_table.append(markup);
      });
    },

    getPolicy: (legend_data) => {
      let url = api_urls.getpolicy;
      if (!_.isEmpty(_this.fields.individualPolicyList)) {
        legend_data['policy_number'] = _this.fields.individualPolicyList[0].policy_number;

        // if (_this.fields.coverOption === 'additional') {
        url = api_urls.getAdditionalPolicy;
        // }
      }
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          method: 'POST',
          url: url,
          data: legend_data,
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

    // calculateExpiryDate: () => {
    //     if (!_.isEmpty($('#vehicle_effective_date').val()) && $('#vehicle_effective_date').val().length == 10) {
    //         const effectiveDate = $('#vehicle_effective_date').val();
    //         const expiryDate = moment(effectiveDate, 'YYYY-MM-DD').add(1, 'years').format('YYYY-MM-DD');
    //         $('#vehicle_expiry_date').val(expiryDate);
    //     }
    // },

    generateTransactionId: (customer_email) => {
      const uuidv4 = 'xxxxx_xxxx_4xxx_yxxx_xxxx'.replace(/[xy]/g, function(c) {
        var r = (Math.random() * 16) | 0,
          v = c == 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
      });

      return uuidv4;
    },

    addValidationRules: () => {
      $.validator.addMethod(
        'makeYearGreaterThanPurchaseYear',
        function(value, element) {
          const year_of_make = parseInt($('#vehicle_year_make').val());
          const year_of_purchase = parseInt(value);

          return year_of_make < year_of_purchase;
        },
        'Purchase year must be greater than make year'
      );
    },

    validateTab1Form: () => {
      $('#tab1form').validate({
        rules: {
          vehicle_reg_num: {
            required: true
          }
        }
      });
    },

    validateTab2Form: () => {
      $('#tab2form').validate({
        rules: {
          vehicle_make_model: {
            required: true
          },
          vehicle_body: {
            required: true
          },
          vehicle_color: {
            required: true
          },
          vehicle_cubic: {
            required: true
          },
          vehicle_engine_number: {
            required: true
          },
          vehicle_chasis_number: {
            required: true
          },
          vehicle_year_make: {
            required: true
          },
          vehicle_year_purchase: {
            required: true,
            makeYearGreaterThanPurchaseYear: true
          },
          vehicle_number_of_seats: {
            required: true,
            number: true
          },
          vehicle_purchase_price: {
            required: true,
            number: true
          },
          vehicle_purchase_state: {
            required: true
          },
          contact_person: {
            required: true
          },
          bank_verification_number: {
            required: true
          },
          bank_account_number: {
            required: true
          }
          // 'occupation_sector': {
          //     required: true
          // }
        }
      });
    },

    validateTab3Form: () => {
      $('#tab3form').validate({
        rules: {
          insurance_class: {
            required: true
          }
        }
      });
    },

    gotoPage: (vehicle_transaction_details_id) => {
      window.location.href = `/portal/renew-policy/${vehicle_transaction_details_id}`;
    },

    printPage: () => {
      if (_this.fields.activeTab === 'renewPolicy') {
        const details_ids = _this.fields.policyDetails.vehicle_transaction_details_id.split('_');
        _this.getExistingVehicleDetailsByRegistrationNumber(details_ids[1]).then((result) => {
          _this.fields.policyDetails['form_details'] = result.form_details;
          Utility.printPage(_this.fields.policyDetails);
        });
      } else {
        Utility.printPage(_this.fields.policyDetails);
      }

      //   const policyDetails = _this.fields.policyDetails;
      //   const MY_URL = '../../assets/images/Motor-Certificate.jpg';
      //   const title_id = Utility.fields.selectedProfile.title;
      //   const customer_title = _.invert(_this.titles)[title_id];
      //   const customer_firstname = Utility.fields.selectedProfile.firstname;
      //   const customer_lastname = Utility.fields.selectedProfile.lastname;
      //   const policy_holder = `${customer_title} ${customer_firstname} ${customer_lastname}`;

      //   const request = new XMLHttpRequest();
      //   request.open('GET', MY_URL, true);
      //   request.responseType = 'blob';
      //   request.onload = function() {
      //     let reader = new FileReader();
      //     reader.readAsDataURL(request.response);
      //     reader.onload = function(e) {
      //       _this.getExistingVehicleDetailsById(policyDetails.vehicle_transaction_details_id).then((result) => {
      //         const effectiveDate = moment(policyDetails.expiry_date.substring(0, 9), 'DD-MMM-YY')
      //           .subtract(1, 'year')
      //           .format('DD-MMM-YY');
      //         // console.log('DataURL:', e.target.result);
      //         pdfMake.fonts = {
      //           Courier: {
      //             normal: 'Courier',
      //             bold: 'Courier-Bold',
      //             italics: 'Courier-Oblique',
      //             bolditalics: 'Courier-BoldOblique'
      //           },
      //           Roboto: {
      //             normal: 'Courier',
      //             bold: 'Courier-Bold',
      //             italics: 'Courier-Oblique',
      //             bolditalics: 'Courier-BoldOblique'
      //           },
      //           Helvetica: {
      //             normal: 'Helvetica',
      //             bold: 'Helvetica-Bold',
      //             italics: 'Helvetica-Oblique',
      //             bolditalics: 'Helvetica-BoldOblique'
      //           },
      //           Times: {
      //             normal: 'Times-Roman',
      //             bold: 'Times-Bold',
      //             italics: 'Times-Italic',
      //             bolditalics: 'Times-BoldItalic'
      //           },
      //           Symbol: {
      //             normal: 'Symbol'
      //           },
      //           ZapfDingbats: {
      //             normal: 'ZapfDingbats'
      //           }
      //         };

      //         let docDefinition = {
      //           pageSize: 'A5',
      //           background: [
      //             {
      //               image: e.target.result,
      //               width: 400
      //             }
      //           ],
      //           content: [
      //             {
      //               text: policyDetails.policy_number,
      //               absolutePosition: { x: 20, y: 90 }
      //             },
      //             {
      //               text: policy_holder,
      //               absolutePosition: { x: 20, y: 140 }
      //             },
      //             {
      //               text: result.vehicle_make,
      //               absolutePosition: { x: 30, y: 185 }
      //             },
      //             {
      //               text: result.registration_number,
      //               absolutePosition: { x: 20, y: 235 }
      //             },
      //             {
      //               text: effectiveDate,
      //               absolutePosition: { x: 20, y: 285 }
      //             },
      //             {
      //               text: policyDetails.expiry_date,
      //               absolutePosition: { x: 30, y: 340 }
      //             },
      //             {
      //               style: 'inner',
      //               text: [
      //                 { text: `NO: ${policyDetails.certificate_number}\n\n\n`, style: 'certificate' },
      //                 { text: 'Policy Number: ', style: 'boldlabel' },
      //                 { text: `${policyDetails.policy_number}\n` },
      //                 'Policy Holder: ',
      //                 { text: `${policy_holder}\n` },
      //                 'Vehicle Make: ',
      //                 { text: `${result.vehicle_make}\n` },
      //                 'Registration Number: ',
      //                 { text: `${result.registration_number}\n` },
      //                 'Effective Date of Cover: ',
      //                 { text: `${effectiveDate}\n` },
      //                 'Date of Expiry of Insurance: ',
      //                 { text: `${policyDetails.expiry_date}\n\n` },
      //                 { text: '*Persons or Classes of persons entitled to drive*\n', style: 'sectionhead' },
      //                 "1. The Policy Holder:- The Policy Holder may also drive a Motor Car not belonging to him and not hired to him under a hire purchase agreement\n 2. Any other person provided he is in the policy holder's employ and is driving on his order or with his permission\n\n",
      //                 { text: '*Limitation as to use*\n', style: 'sectionhead' },
      //                 "Use only for social domestic and pleasure purposes and for the policy holder's business\n. *The policy does not cover: 1. Use for hire or reward or for racing"
      //               ]
      //             }
      //           ],
      //           styles: {
      //             sectionhead: {
      //               fontSize: 10,
      //               bold: true
      //             },
      //             inner: {
      //               fontSize: 9,
      //               margin: [120, 70, 10, 15]
      //             },
      //             certificate: {
      //               fontSize: 7
      //             },
      //             boldlabel: {
      //               font: 'Roboto',
      //               bold: true
      //             }
      //           },
      //           defaultStyle: {
      //             fontSize: 10,
      //             bold: true
      //           }
      //         };
      //         pdfMake.createPdf(docDefinition).download();
      //       });
      //     };
      //   };
      //   request.send();
    },

    formatNumber: (n) => {
      // format number 1000000 to 1,234,567
      return n.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },

    formatCurrency: (input, blur) => {
      // appends $ to value, validates decimal side
      // and puts cursor back in right position.

      // get input value
      var input_val = input.val();

      // don't validate empty input
      if (input_val === '') {
        return;
      }

      // original length
      var original_len = input_val.length;

      // initial caret position
      var caret_pos = input.prop('selectionStart');

      // check for decimal
      if (input_val.indexOf('.') >= 0) {
        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf('.');

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = Motor.formatNumber(left_side);

        // validate right side
        right_side = Motor.formatNumber(right_side);

        // On blur make sure 2 numbers after decimal
        if (blur === 'blur') {
          right_side += '00';
        }

        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = left_side + '.' + right_side;
      } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = Motor.formatNumber(input_val);

        // final formatting
        if (blur === 'blur') {
          input_val += '.00';
        }
      }

      // send updated string to input
      input.val(input_val);

      // put caret back in the right position
      var updated_len = input_val.length;
      caret_pos = updated_len - original_len + caret_pos;
      input[0].setSelectionRange(caret_pos, caret_pos);
    }
  };
})();
const _this = Motor;

var MotorBroker = (function() {
  return {
    fields: {
      agentClientList: [],
      renewalDetails: {},
      insurancePolicyType: ''
    },
    init: function() {
      Utility.setupProfileAutoComplete();
      $(document).ready(function() {
        _thisBroker.setNewPolicyTab();
      });
      _thisBroker
        .getAgentClientList()
        .then((result) => {
          _thisBroker.fields.agentClientList = result;
        })
        .catch((error) => {});
    },

    setNewPolicyTab: () => {
      // $('#newClient').click();
      $('#newClient').trigger('click');
      $('#newClient').addClass('active');
      _thisBroker.fields.insurancePolicyType = 'new';
    },

    setActive: () => {
      $('#topbar a').each((i, v) => {
        $(v).removeClass('active');
      });
      $(`#${event.target.id}`).addClass('active');

      if (event.target.id === 'newClient') {
        _thisBroker.fields.insurancePolicyType = 'new';
        // $('#newProfileSection').removeClass('hide_elements');
        // if (!$('#newAndAdditionalPolicySection').hasClass('hide_elements')) {
        //     $('#newAndAdditionalPolicySection').addClass('hide_elements')
        // }
        if (!$('#existingPolicyDiv').hasClass('hide_elements')) {
          $('#existingPolicyDiv').addClass('hide_elements');
        }
        if (!$('#existingClientNumberDiv').hasClass('hide_elements')) {
          $('#existingClientNumberDiv').addClass('hide_elements');
        }
      }
      // else {
      //     $('#newProfileSection').addClass('hide_elements');
      // }

      // if(event.target.id === 'newClient' && $('#existingClientSection').hasClass('hide_elements')) {
      //     _thisBroker.populateAgentClientList();
      //     $('#existingClientSection').removeClass('hide_elements');
      //     MotorBroker.showHidePolicySection();
      // } else {
      //     $('#existingClientSection').addClass('hide_elements');
      //     if (!$('#newAndAdditionalPolicySection').hasClass('hide_elements')) {
      //         $('#newAndAdditionalPolicySection').addClass('hide_elements')
      //     }
      // }

      if (event.target.id === 'renewPolicy') {
        // if ($('#newAndAdditionalPolicySection').hasClass('hide_elements')) {
        //     $('#newAndAdditionalPolicySection').removeClass('hide_elements')
        // }
        _thisBroker.fields.insurancePolicyType = 'renew';
        if ($('#existingPolicyDiv').hasClass('hide_elements')) {
          $('#existingPolicyDiv').removeClass('hide_elements');
        }
        if ($('#existingClientNumberDiv').hasClass('hide_elements')) {
          $('#existingClientNumberDiv').removeClass('hide_elements');
        }
      }
    },

    showHidePolicySection: () => {
      if (!_.isEmpty(_thisBroker.fields.insurancePolicyType) && !_.isEmpty($('#client_profile_id').val())) {
        $('#newAndAdditionalPolicySection').removeClass('hide_elements');
      }
    },

    getAgentClientList: () => {
      const url = api_urls.agentClientList;
      const userId = $('#user_id').val();
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${userId}`,
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

    populateAgentClientList: () => {
      if ($('#client_profile_id option').size() <= 1) {
        $profileId = $('#client_profile_id');
        $.each(_thisBroker.fields.agentClientList, (i, v) => {
          const display_name = _.isEmpty(v.firstname) ? v.company_name : `${v.firstname} ${v.lastname}`;
          $profileId.append(`<option value=${v.id}> ${display_name} </option>`);
        });
      }
    },

    setCustomerProfile: () => {
      const userRole = $('#user_role_loggedIn')
        .val()
        .toLowerCase();
      if (userRole === 'client') {
        Utility.fields.selectedProfile = $('#user_profile').val();
      }
      // const customerProfileId = $('#client_profile_id').val();
      // const customerProfile = _thisBroker.fields.agentClientList.filter(c => c.id === parseInt(customerProfileId));
      // console.log(customerProfile);
      // if (!_.isEmpty(customerProfile)) {
      //     $('#profile_id').val(customerProfile[0].id);
      //     $('#title').val(customerProfile[0].title);
      //     $('#customer_firstname').val(customerProfile[0].firstname);
      //     $('#customer_lastname').val(customerProfile[0].lastname);
      //     $('#customer_othernames').val(customerProfile[0].othernames);
      //     $('#company_name').val(customerProfile[0].company_name);
      //     $('#street_address').val(customerProfile[0].street_address);
      //     $('#city').val(customerProfile[0].city);
      //     $('#local_govt_area').val(customerProfile[0].local_govt_area);
      //     $('#state').val(customerProfile[0].state);
      //     $('#date_of_birth').val(customerProfile[0].date_of_birth);
      //     $('#customer_gsm_number').val(customerProfile[0].gsm_number);
      //     $('#customer_office_number').val(customerProfile[0].office_number);
      //     $('#occupation').val(customerProfile[0].occupation);
      //     $('#tin_number').val(customerProfile[0].tin_number);
      //     $('#fax_number').val(customerProfile[0].fax_number);
      //     $('#website').val(customerProfile[0].website);
      //     $('#company_reg_num').val(customerProfile[0].company_reg_num);
      //     $('#contact_person').val(customerProfile[0].contact_person);
      //     $('#bank_account_number').val(customerProfile[0].bank_account_number);
      //     $('#customer_bank').val(customerProfile[0].customer_bank);
      //     if (!_.isEmpty($('#customer_firstname').val()) ) {
      //         $('#user_category').val('Individual');
      //     } else {
      //         $('#user_category').val('Company');
      //     }
      // }
    },

    fillVehicleDetails: (vehicleDetails) => {
      if (vehicleDetails.indexOf('Colour Code') <= -1) {
        $('.alert-message-text').html(vehicleDetails);
        $('.alert-message').addClass('error');
        return false;
      }
      let vehicleData = {};
      const data = vehicleDetails.split(',');
      console.log(data);
      data.forEach((d) => {
        let element = d.split(':');
        vehicleData[element[0].trim()] = element[1].trim();
      });
      console.log(vehicleData);
      _thisBroker.fields.renewalDetails = {
        colorCode: vehicleData['Colour Code'],
        colorName: vehicleData['Colour Name'],
        coverType: vehicleData['Cover Type'],
        cubicCapacity: vehicleData['Cubic Capacity'],
        currency: vehicleData['Currency'],
        currentPremium: vehicleData['Current Premium'],
        effectiveDate: vehicleData['Effective Date'],
        expiryDate: vehicleData['Expiry Date'],
        makeModel: vehicleData['Make/Model'],
        policyNumber: vehicleData['Policy Number'],
        clientNumber: vehicleData['Client Number'],
        purchaseYear: vehicleData['Purchase Year'],
        renewalPremium: vehicleData['Renewal Premium'],
        riskClass: vehicleData['Risk Class'],
        seat: vehicleData['Seat'],
        stateRegion: vehicleData['State/Region'],
        stickerNumber: vehicleData['Sticker Number'],
        sumInsured: vehicleData['Sum Insured'],
        vehicleBodyCode: vehicleData['Vehicle Body Code'],
        vehicleTypeCode: vehicleData['Vehicle Type Code'],
        registrationNumber: $('#vehicle_reg_num').val(),
        chasisNumber: vehicleData['Chassis Number'],
        engineNumber: vehicleData['Engine Number'],
        clientName: vehicleData['Client Name'],
        yearOfMake: vehicleData['Year of Make']
      };
      const premium = parseFloat(_thisBroker.fields.renewalDetails.renewalPremium) / 100;
      $('#coverage_amount').html(Motor.formatNumber(`${premium}`));
      $('#insurance_class').prop('disabled', 'disabled');
      console.log(_thisBroker.fields.renewalDetails);
      $('#rootwizard').bootstrapWizard('show', 2);
    },

    setLegendRenewalData: () => {
      let effective_date = _thisBroker.fields.renewalDetails.expiryDate.substring(0, 9);
      effective_date = moment(effective_date, 'DD-MMM-YY').format('YYYY-MM-DD');
      expiry_date = moment(effective_date, 'YYYY-MM-DD')
        .add(1, 'years')
        .format('YYYY-MM-DD');
      agent_code = $('#agent_code').val();
      const data = {
        policy_number: _thisBroker.fields.renewalDetails.policyNumber,
        client_number: _thisBroker.fields.renewalDetails.clientNumber,
        premium: _thisBroker.fields.renewalDetails.renewalPremium,
        vehicle_plate_number: _thisBroker.fields.renewalDetails.registrationNumber,
        state: _thisBroker.fields.renewalDetails.stateRegion,
        model: _thisBroker.fields.renewalDetails.vehicleTypeCode,
        body: _thisBroker.fields.renewalDetails.vehicleBodyCode,
        color: _thisBroker.fields.renewalDetails.colorCode,
        cubic_capacity: _thisBroker.fields.renewalDetails.cubicCapacity,
        number_of_seat: _thisBroker.fields.renewalDetails.seat,
        engine_number: _thisBroker.fields.renewalDetails.engineNumber,
        chasis_number: _thisBroker.fields.renewalDetails.chasisNumber,
        year_of_make: _thisBroker.fields.renewalDetails.yearOfMake,
        year_of_purchase: _thisBroker.fields.renewalDetails.purchaseYear,
        mode_of_payment: 'CASH',
        agent_name: agent_code,
        cover_type: _thisBroker.fields.renewalDetails.coverType,
        currency: _thisBroker.fields.renewalDetails.currency,
        company_bank: '310-0342',
        effective_date: effective_date,
        expiry_date: expiry_date,
        payment_reference: Motor.transactionDetails.transactionReference
      };
      return data;
    },

    toggleExistingClientNumber: () => {
      if ($('#new_additional_policy').prop('checked')) {
        if ($('#existingClientNumberDiv').hasClass('hide_elements')) {
          $('#existingClientNumberDiv').removeClass('hide_elements');
        }
      } else {
        if (!$('#existingClientNumberDiv').hasClass('hide_elements')) {
          $('#existingClientNumberDiv').addClass('hide_elements');
        }
      }
    }
  };
})();

const _thisBroker = MotorBroker;

var CommonMotor = (function() {
  return {
    getRenewalPolicy: (legend_data) => {
      let url = api_urls.renewpolicy;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          method: 'POST',
          url: url,
          data: legend_data,
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
    }
  };
})();

const _thisCommon = CommonMotor;
