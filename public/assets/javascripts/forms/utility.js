var Utility = (function() {
  return {
    fields: {
      selectedProfile: {},
      vehicleBodies: [],
      vehicleModels: [],
      states: [],
      occupations: [],
      cities: [],
      colors: []
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
      $("input[data-type='currency']").on({
        keyup: function() {
          Utility.formatCurrency($(this));
        },
        blur: function() {
          Utility.formatCurrency($(this), 'blur');
        }
      });

      if (_.isEmpty(Utility.fields.vehicleBodies)) {
        Utility.loadPresetDataSet();
      }
    },

    loadPresetDataSet: () => {
      Promise.all([Utility.getVehicleBodyList(), Utility.getVehicleModelList()]).then(
        (values) => {
          [Utility.fields.vehicleBodies, Utility.fields.vehicleModels] = values;
        },
        (error) => {
          console.log(error);
        }
      );
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
            console.log(response);
            return process(response);
          });
        }
      });
      $input.change(function() {
        let current = $input.typeahead('getActive');
        console.log(current);
        Utility.fields.selectedProfile = current.profile;
        if (current) {
          // Some item from your model is active!
          if (current.name == $input.val()) {
            // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
          } else {
            // This means it is only a partial match, you can either add a new item
            // or take the active if you don't want new items
          }
        } else {
          // Nothing is active so it is a new value (or maybe empty value)
        }
      });
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
        left_side = Utility.formatNumber(left_side);

        // validate right side
        right_side = Utility.formatNumber(right_side);

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
        input_val = Utility.formatNumber(input_val);

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
    },

    generateTransactionId: (customer_email) => {
      const uuidv4 = 'xxxxx_xxxx_4xxx_yxxx_xxxx'.replace(/[xy]/g, function(c) {
        var r = (Math.random() * 16) | 0,
          v = c == 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
      });

      return uuidv4;
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

    getLocationsList: () => {
      const url = api_urls.locations;
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

    saveVehicleDetails: (operation, vehicleTransactionDetails) => {
      const url =
        operation === 'update'
          ? `${api_urls.vehicletransactiondetails}/${vehicleTransactionDetails.vehicleDetailsId}`
          : api_urls.vehicletransactiondetails;
      const formType = operation === 'update' ? 'PUT' : 'POST';
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: formType,
          url: url,
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

      return promise;
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
      return promise;
    },

    savePolicyDetails: (policyDetails) => {
      const url = api_urls.vehicletransactionpolicy;
      if (!_.isEmpty(_this.fields.legendResponse)) {
        // const policyDetails = {
        //     profileId: $('#profile_id').val(),
        //     userId: $('#user_id').val(),
        //     policyType: 'motor',
        //     coverType: $('#insurance_class').val(),
        //     coverOption: _this.fields.coverOption,
        //     coverAddOns: JSON.stringify(_this.fields.coverAddOns),
        //     vehicleTransactionDetailsId: _this.fields.vehicleTransactionDetails.vehicleTransactionDetailsId, ..._this.fields.legendResponse};
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
        return promise;
      }
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

    printPage: (policyDetails) => {
      console.log(policyDetails);
      const MY_URL = '../../portal/assets/images/Motor-Certificate.jpg';
      //   const MY_URL = '../../assets/images/Motor-Certificate.jpg';
      const title_id = policyDetails.title !== undefined ? policyDetails.title : Utility.fields.selectedProfile.title;
      const customer_title = _.invert(Utility.titles)[title_id];
      const customer_firstname =
        policyDetails.firstname !== undefined ? policyDetails.firstname : Utility.fields.selectedProfile.firstname;
      const customer_lastname =
        policyDetails.lastname !== undefined ? policyDetails.lastname : Utility.fields.selectedProfile.lastname;
      const customer_company =
        policyDetails.company_name !== undefined
          ? policyDetails.company_name
          : Utility.fields.selectedProfile.company_name;
      let policy_holder = `${customer_title} ${customer_firstname} ${customer_lastname}`;
      if (policyDetails.user_category !== 'Individual') {
        policy_holder = customer_company;
      }

      const request = new XMLHttpRequest();
      request.open('GET', MY_URL, true);
      request.responseType = 'blob';
      request.onload = function() {
        let reader = new FileReader();
        reader.readAsDataURL(request.response);
        reader.onload = function(e) {
          Utility.getExistingVehicleDetailsById(policyDetails.vehicle_transaction_details_id).then((result) => {
            const effectiveDate = moment(policyDetails.expiry_date.substring(0, 9), 'DD-MMM-YY')
              .subtract(1, 'year')
              .format('DD-MMM-YY');
            console.log(result);
            const form_details =
              policyDetails.form_details !== undefined
                ? JSON.parse(policyDetails.form_details)
                : JSON.parse(result.form_details);
            const registration_number =
              result.registration_number !== undefined ? result.registration_number : policyDetails.registration_number;
            let vehicleModel = _.isEmpty(Utility.fields.vehicleModels)
              ? form_details.vehicle_make_model
              : Utility.fields.vehicleModels.find((v) => {
                  return v.value === form_details.vehicle_make_model;
                });
            if (form_details.cargo_type !== undefined) {
              vehicleModel = {
                name: form_details.vessel_name
              };
            }
            pdfMake.fonts = {
              Courier: {
                normal: 'Courier',
                bold: 'Courier-Bold',
                italics: 'Courier-Oblique',
                bolditalics: 'Courier-BoldOblique'
              },
              Roboto: {
                normal: 'Roboto-Regular.ttf',
                bold: 'Roboto-Medium.ttf',
                italics: 'Roboto-Italic.ttf',
                bolditalics: 'Roboto-MediumItalic.ttf'
              },
              Helvetica: {
                normal: 'Helvetica.ttf',
                bold: 'Helvetica-Bold',
                italics: 'Helvetica-Oblique',
                bolditalics: 'Helvetica-BoldOblique'
              },
              Times: {
                normal: 'Times-Roman',
                // bold: 'Times-Bold',
                italics: 'Times-Italic',
                bolditalics: 'Times-BoldItalic'
              },
              Symbol: {
                normal: 'Symbol'
              },
              ZapfDingbats: {
                normal: 'ZapfDingbats'
              }
            };

            let docDefinition = {
              pageSize: 'A5',
              background: [
                {
                  image: e.target.result,
                  width: 400
                }
              ],
              content: [
                {
                  text: policyDetails.policy_number,
                  absolutePosition: { x: 20, y: 90 }
                },
                {
                  text: policy_holder.substring(0, 25),
                  absolutePosition: { x: 20, y: 140 }
                },
                {
                  text: vehicleModel.name,
                  absolutePosition: { x: 30, y: 185 }
                },
                {
                  text: registration_number,
                  absolutePosition: { x: 20, y: 235 }
                },
                {
                  text: effectiveDate,
                  absolutePosition: { x: 20, y: 285 }
                },
                {
                  text: policyDetails.expiry_date,
                  absolutePosition: { x: 30, y: 340 }
                },
                {
                  style: 'inner',
                  text: [
                    { text: `NO: ${policyDetails.certificate_number}\n\n\n`, style: 'certificate' },
                    { text: 'Policy Number: ', style: 'boldlabel' },
                    { text: `${policyDetails.policy_number}\n` },
                    'Policy Holder: ',
                    { text: `${policy_holder}\n` },
                    'Vehicle Make: ',
                    { text: `${vehicleModel.name}\n` },
                    'Registration Number: ',
                    { text: `${registration_number}\n` },
                    'Effective Date of Cover: ',
                    { text: `${effectiveDate}\n` },
                    'Date of Expiry of Insurance: ',
                    { text: `${policyDetails.expiry_date}\n\n` },
                    { text: '*Persons or Classes of persons entitled to drive*\n', style: 'sectionhead' },
                    "1. The Policy Holder:- The Policy Holder may also drive a Motor Car not belonging to him and not hired to him under a hire purchase agreement\n 2. Any other person provided he is in the policy holder's employ and is driving on his order or with his permission\n\n",
                    { text: '*Limitation as to use*\n', style: 'sectionhead' },
                    "Use only for social domestic and pleasure purposes and for the policy holder's business\n. *The policy does not cover: 1. Use for hire or reward or for racing"
                  ]
                }
              ],
              styles: {
                sectionhead: {
                  fontSize: 10,
                  bold: true
                },
                inner: {
                  fontSize: 9,
                  margin: [120, 70, 10, 15]
                },
                certificate: {
                  fontSize: 7
                },
                boldlabel: {
                  font: 'Roboto',
                  bold: true
                }
              },
              defaultStyle: {
                fontSize: 10,
                bold: true
              }
            };
            pdfMake.createPdf(docDefinition).download(`${registration_number}.pdf`);
          });
        };
      };
      request.send();
    },

    sendPolicyEmail(policyData) {
      const url = api_urls.sendemail;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          method: 'POST',
          url: url,
          data: policyData,
          success: function(msg) {
            resolve(msg);
          },
          error: function(err) {
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
    }
  };
})();
