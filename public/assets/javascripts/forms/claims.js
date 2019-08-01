var Claim = (function() {
  return {
    fields: {
      payments: [],
      filesUploaded: [],
      myDropZoneRefernce: {},
      claims: []
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

      const userRole = $('#role_name')
        .val()
        .toLowerCase();
      if (userRole === 'agent') {
        if ($('input.typeahead').length > 0) {
          Utility.setupProfileAutoComplete();
        }
      }

      let $inputClaim = $('.typeahead');
      $inputClaim.change(() => {
        // console.log('Selected Profile ', Utility.fields.selectedProfile);
        Claim.setClaimProfile();
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
      Claim.getClaimsByProfile();
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
      const userRole = $('#role_name')
        .val()
        .toLowerCase();
      if (userRole === 'agent') {
        if (!_.isEmpty(Utility.fields.selectedProfile)) {
          claimsData.profileId = Utility.fields.selectedProfile.id;
        }
      }
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
    getClaimsByProfile: function() {
      const profileId = $('#profile_id').val();
      const url = api_urls.getclaimdetailsbyprofile;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${profileId}`,
          success: function(msg) {
            resolve(msg);
          },
          error: function(err) {
            console.log(err);
            reject(err);
          }
        });
      });
      //return promise;

      promise
        .then((result) => {
          Claim.fields.claims = result;
          Claim.populateClientClaims();
        })
        .catch((err) => {
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('Error - claims could not be retrieved');
          console.log(err);
        });
    },
    populateClientClaims: () => {
      $claims_table = $('#datatable-claims tbody');
      $claims_table.empty();
      $.each(Claim.fields.claims, (i, v) => {
        const markup = `<tr>
                    <td>${v.claim_no}</td>
                    <td class="text-semibold text-dark">${v.policy_no}</td>
                    <td class="text-semibold text-dark">${v.registration_no}</td>
                    <td class="text-center">${v.status}</td>
                    <td class="text-center">${v.created_at}</td>
                    <td class="text-center"><button class="btn btn-primary" onclick="Claim.gotoPage(${
                      v.claim_no
                    })" disabled><i class="fa fa-info-circle"> Details </button></td>
                </tr>`;
        $claims_table.append(markup);
      });
    },

    generateClaimNo: () => {
      const profileId = $('#profile_id').val();
      return `${moment().format('YYYYMMDDHHmmss')}_${profileId}`;
    },

    setClaimProfile: () => {
      if (!_.isEmpty(Utility.fields.selectedProfile)) {
        $('#firstname').val(Utility.fields.selectedProfile.firstname);
        $('#lastname').val(Utility.fields.selectedProfile.lastname);
        $('#othername').val(Utility.fields.selectedProfile.othernames);
      }
    }
  };
})();

var AdminClaim = (function() {
  return {
    fields: {
      claimData: [],
      claimStatus: ['new', 'pending', 'approved']
    },
    init: function() {
      AdminClaim.populateFormScreen();
      $('#update_claim_btn').on('click', () => {
        AdminClaim.updateClaimDetails();
      });
    },

    populateFormScreen: () => {
      console.log(localStorage.getItem('reference'));
      const data = JSON.parse(localStorage.getItem('reference'));
      AdminClaim.fields.claimData = data;
      const requestBody = JSON.parse(data.form_details);
      console.log(requestBody);
      $('#claim_no').html(data.claim_no);
      $('#policy_no').html(data.policy_no);
      $('#registration_no').html(data.registration_no);
      $('#firstname').html(requestBody.clientDetails.firstname);
      $('#lastname').html(requestBody.clientDetails.lastname);
      $('#othernames').html(requestBody.clientDetails.othername);
      $('#address').html(requestBody.clientDetails.address);
      $('#city').html(requestBody.clientDetails.city);
      $('#state').html(requestBody.clientDetails.state);
      $('#email_address').html(requestBody.clientDetails.email_address);
      $('#phone').html(requestBody.clientDetails.phone);
      $('#date_of_birth').html(requestBody.clientDetails.date_of_birth);
      $('#occupation').html(requestBody.clientDetails.occupation);
      $('#vehicle_make_model').html(requestBody.vehicleRegistrationDetails.vehicle_make_model);
      $('#vehicle_body').html(requestBody.vehicleRegistrationDetails.vehicle_body);
      $('#vehicle_color').html(requestBody.vehicleRegistrationDetails.vehicle_color);
      $('#vehicle_engine_number').html(requestBody.vehicleRegistrationDetails.vehicle_engine_number);
      $('#vehicle_chasis_number').html(requestBody.vehicleRegistrationDetails.vehicle_chasis_number);
      $('#driver_fullname').html(requestBody.driverDetails.driver_fullname);
      $('#driver_age').html(requestBody.driverDetails.driver_age);
      $('#driving_license_in_force').html(requestBody.driverDetails.driving_license_in_force);
      $('#driving_license_category').html(requestBody.driverDetails.driving_license_category);
      $('#driving_license_number').html(requestBody.driverDetails.driving_license_number);
      $('#driving_license_endorsed').html(requestBody.driverDetails.driving_license_endorsed);
      $('#date_of_issue').html(requestBody.driverDetails.date_of_issue);
      $('#date_of_expiry').html(requestBody.driverDetails.date_of_expiry);
      $('#place_of_issue').html(requestBody.driverDetails.place_of_issue);
      $('#learners_permit').html(requestBody.driverDetails.learners_permit);
      $('#learners_permit_number').html(requestBody.driverDetails.learners_permit_number);
      $('#learners_permit_period').html(requestBody.driverDetails.learners_permit_period);
      $('#damaged_parts_report').val(requestBody.vehicleDetails.damaged_parts_report);
      $('#present_vehicle_location').html(requestBody.vehicleDetails.present_vehicle_location);
      $('#repairs_estimate').html(requestBody.vehicleDetails.repairs_estimate);
      $('#repairer_name').val(requestBody.vehicleDetails.repairer_name);
      $('#repairer_address').val(requestBody.vehicleDetails.repairer_address);
      $('#damaged_parts_inventory').val(requestBody.vehicleDetails.damaged_parts_inventory);
      $('#discover_loss_fullname').html(requestBody.incidentDetails.discover_loss_fullname);
      $('#discover_loss_date').html(requestBody.incidentDetails.discover_loss_date);
      $('#persons_count_insured_vehicle').html(requestBody.incidentDetails.persons_count_insured_vehicle);
      $('#persons_count_other_vehicle').html(requestBody.incidentDetails.persons_count_other_vehicle);
      $('#police_report_station_address').html(requestBody.incidentDetails.police_report_station_address);
      $('#police_report_statement').val(requestBody.incidentDetails.police_report_statement);
      $('#third_party_fullname').html(requestBody.accidentDetails.third_party_fullname);
      $('#third_party_address').html(requestBody.accidentDetails.third_party_address);
      $('#third_party_injury_type').html(requestBody.accidentDetails.third_party_injury_type);
      $('#third_party_vehicle_make').html(requestBody.accidentDetails.third_party_vehicle_make);
      $('#third_party_reg_num').html(requestBody.accidentDetails.third_party_reg_num);
      $('#third_party_year_of_make').html(requestBody.accidentDetails.third_party_year_of_make);
      $('#third_party_vehicle_location').html(requestBody.accidentDetails.third_party_vehicle_location);
      $('#third_party_owner_insured').html(requestBody.accidentDetails.third_party_owner_insured);
      $('#third_party_policy_number').html(requestBody.accidentDetails.third_party_policy_number);
      $('#third_party_insurer_name').html(requestBody.accidentDetails.third_party_insurer_name);
      $('#third_party_insurer_address').html(requestBody.accidentDetails.third_party_insurer_address);
      $('#claim_status').html(data.status);
      $('#claim_status_notes').html(data.status_notes);

      const accidentImagesString = requestBody.pictureDetails;
      const accidentImages = JSON.parse(accidentImagesString);
      if (accidentImages.length > 0) {
        let imageRow = $('#accident_image_row');
        accidentImages.forEach((element) => {
          // const imageNode = `<img src="../../uploads/${element}" />`;
          const imageNode = `<img src="../../portal/uploads/${element}" />`;
          imageRow.append(imageNode);
        });
      }
      AdminClaim.populateClaimStatuses(data.status);
    },

    populateClaimStatuses: (selectedClaimStatus) => {
      $claim_status_select = $('#claim_status_select');
      $claim_status_select.append(`<option value=''></option>`);
      $.each(AdminClaim.fields.claimStatus, (i, v) => {
        $claim_status_select.append(
          `<option value=${v} ${v === selectedClaimStatus ? 'selected' : ''} > ${v} </option>`
        );
      });
    },

    updateClaimDetails: () => {
      const url = `${api_urls.changeclaimstatus}/${AdminClaim.fields.claimData.id}`;
      const formType = 'POST';
      const data = {
        status: $('#claim_status_select').val(),
        staff_id: $('#user_id').val(),
        status_notes: $('#claim_status_notes').val()
      };
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: formType,
          url: url,
          data: data,
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
          $('.alert-message-text').html('Claim has been successfully updated');
        })
        .catch((err) => {
          console.log(err);
        });
    }
  };
})();
