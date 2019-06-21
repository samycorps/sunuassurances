var Claim = (function() {
  return {
    fields: {
      payments: [],
      filesUploaded: [],
      myDropZoneRefernce: {}
    },
    init: function() {
      /* Configure Date picker */
      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(1920, 01, 01),
        endDate: new Date()
      });

      $('.datepicker2').datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(1920, 01, 01)
      });

      $('#submit_claim_btn').on('click', () => {
        Claim.submitClaim();
      });

      $('#rootwizard').bootstrapWizard({
        onTabClick: function(tab, navigation, index) {
          return false;
        },
        onPrevious: function(tab, navigation, index) {
          if (index < 6) {
            if (!$('#submit_claim_section').hasClass('hide_elements')) {
              $('#submit_claim_section').addClass('hide_elements');
            }
          }
        },
        onNext: function(tab, navigation, index) {
          if (!$('#submit_claim_section').hasClass('hide_elements')) {
            $('#submit_claim_section').addClass('hide_elements');
          }
          switch (index) {
            case 1: {
              if ($('#tab1form').valid()) {
                // Claim.wizardStepOne();
                return true;
              }
              return false;
            }
            case 2: {
              if ($('#tab2form').valid()) {
                return true;
              }
              return false;
            }
            case 3: {
              if ($('#tab3form').valid()) {
                return true;
              }
              return false;
            }
            case 4: {
              if ($('#tab4form').valid()) {
                return true;
              }
              return false;
            }
            case 5: {
              if ($('#tab5form').valid()) {
                return true;
              }
              return false;
            }
            case 6: {
              if ($('#tab6form').valid()) {
                if ($('#submit_claim_section').hasClass('hide_elements')) {
                  $('#submit_claim_section').removeClass('hide_elements');
                }
                return true;
              }
              return false;
            }
            default: {
              break;
            }
          }

          return false;
        }
      });
      Claim.loadDropdownLists();
      Claim.initDropZone();
    },

    loadDropdownLists: () => {
      Promise.all([
        Utility.getOccupationList(),
        Utility.getCitiesList(),
        Utility.getStatesList(),
        Utility.getColoursList(),
        Utility.getVehicleModelList(),
        Utility.getVehicleBodyList(),
        Payment.getExistingVehicleDetailsByProfile()
      ])
        .then((values) => {
          [
            Utility.fields.occupations,
            Utility.fields.cities,
            Utility.fields.states,
            Utility.fields.colors,
            Utility.fields.vehicleModels,
            Utility.fields.vehicleBodies,
            Claim.fields.payments
          ] = values;
        })
        .then(() => {
          Claim.populateOccupations();
          Claim.populateCities();
          Claim.populateStates();
          Claim.populateColorList();
          Claim.populateVehicleModels();
          Claim.populateVehicleBodies();
          Claim.populateRegistrationNumbersList();
          Claim.populateYearsList();
        });
    },

    wizardStepOne: () => {
      $('#rootwizard').bootstrapWizard('next');
    },

    wizardStepTwo: () => {
      $('#rootwizard').bootstrapWizard('next');
      //   return true;
    },

    wizardStepThree: () => {
      $('#rootwizard').bootstrapWizard('next');
    },

    wizardStepFour: () => {
      $('#rootwizard').bootstrapWizard('next');
    },

    populateStates: () => {
      $state = $('#state');
      $.each(Utility.fields.states, (i, v) => {
        $state.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateOccupations: () => {
      $occupation = $('#occupation');
      $.each(Utility.fields.occupations, (i, v) => {
        $occupation.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateCities: () => {
      $city = $('#city');
      $.each(Utility.fields.cities, (i, v) => {
        $city.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateVehicleBodies: () => {
      $vehicleBody = $('#vehicle_body');
      $.each(Utility.fields.vehicleBodies, (i, v) => {
        $vehicleBody.append(`<option value=${v.value}> ${v.name} </option>`);
      });
    },

    populateVehicleModels: () => {
      $vehicleModels = $('#vehicle_make_model');
      $third_party_vehicle_make = $('#third_party_vehicle_make');
      $.each(Utility.fields.vehicleModels, (i, v) => {
        $vehicleModels.append(`<option value=${v.value}> ${v.name} </option>`);
        $third_party_vehicle_make.append(`<option value=${v.value}> ${v.name} </option>`);
      });
      // $('#vehicle_make_model option')
      //   .filter(function() {
      //     return $.trim($(this).text()) == Motor.fields.vehicleDetails.licenseInfo.model.toUpperCase();
      //   })
      //   .attr('selected', 'selected');
    },

    populateColorList: () => {
      $vehicleColor = $('#vehicle_color');
      $.each(Utility.fields.colors, (i, v) => {
        $vehicleColor.append(`<option value=${v.value}> ${v.name} </option>`);
      });
      // $('#vehicle_color option')
      //   .filter(function() {
      //     return $.trim($(this).text()) == Motor.fields.vehicleDetails.licenseInfo.color.toUpperCase();
      //   })
      //   .attr('selected', 'selected');
    },

    populateRegistrationNumbersList: () => {
      $vehicleRegNum = $('#vehicle_reg_num');
      const regNumbers = Claim.fields.payments.map((p) => {
        return p.registration_number;
      });
      $.each(regNumbers, (i, v) => {
        $vehicleRegNum.append(`<option value=${v}> ${v} </option>`);
      });
      // $('#vehicle_color option')
      //   .filter(function() {
      //     return $.trim($(this).text()) == Motor.fields.vehicleDetails.licenseInfo.color.toUpperCase();
      //   })
      //   .attr('selected', 'selected');
    },

    populateYearsList: () => {
      $selectMakeYear = $('#third_party_year_of_make');
      const currentYear = parseInt(moment().format('YYYY'));
      for (i = currentYear; i >= currentYear - 30; --i) {
        $selectMakeYear.append(`<option value=${i}> ${i} </option>`);
      }
    },

    initDropZone: () => {
      Dropzone.options.myAwesomeDropzone = {
        uploadMultiple: true,
        init: function() {
          myDropZoneRefernce = this;
          let spanDropZone = $('div.dz-default').children()[0];
          $(spanDropZone).html('Drop files or click here to upload');
          this.on('addedfile', function(file) {
            var fd = new FormData(file);
          });
          this.on('uploadprogress', function(file, progress, bytesSent) {
            console.log('Bytes Sent ', bytesSent);
          });
          this.on('complete', function(file) {
            // console.log(file);
            // Claim.filesUploaded = this.getAcceptedFiles();
          });
          this.on('success', function(file, response) {
            console.log(response); // console should show the ID you pointed to
            if (response.filename) {
              Claim.fields.filesUploaded.push(response.filename);
            }
          });
        }
      };
    },

    submitClaim: () => {
      const userDetails = JSON.parse($('#user_details').val());
      const tab1Form = $('#tab1form').serializeJSON();
      let tab2Form = $('#tab2form').serializeJSON();
      const claimsData = {
        profileId: $('#profile_id').val(),
        userId: userDetails.id,
        claimNumber: Claim.generateClaimNo(),
        policyNumber: tab1Form.policy_number,
        registrationNumber: tab2Form.vehicle_reg_num,
        formDetails: {
          clientDetails: $('#tab1form').serializeJSON(),
          vehicleRegistrationDetails: $('#tab2form').serializeJSON(),
          driverDetails: $('#tab3form').serializeJSON(),
          incidentDetails: $('#tab4form').serializeJSON(),
          vehicleDetails: $('#tab5form').serializeJSON(),
          accidentDetails: $('#tab6form').serializeJSON(),
          pictureDetails: JSON.stringify(Claim.fields.filesUploaded)
        }
      };
      console.log(claimsData);
      Claim.saveClaimDetails(claimsData)
        .then((result) => {
          $('.pager').empty();
          $('.alert-message').addClass('success');
          $('.alert-message-text').html('Claims has been successfully submitted');
          $('#submit_claim_btn').prop('disabled', 'disabled');
        })
        .catch((err) => {
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('Error - claims was not submitted');
          console.log(err);
        });
    },

    saveClaimDetails: (claimsData) => {
      const url = api_urls.claimdetails;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'POST',
          url: url,
          // contentType: "application/json; charset=utf-8",
          data: claimsData,
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

    generateClaimNo: () => {
      const profileId = $('#profile_id').val();
      return `${moment().format('YYYYMMDDHHmmss')}_${profileId}`;
    }
  };
})();
