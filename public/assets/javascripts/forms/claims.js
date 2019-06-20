var Claim = (function() {
  return {
    fields: {
      payments: []
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

      $('#rootwizard').bootstrapWizard({
        onTabClick: function(tab, navigation, index) {
          return false;
        },
        onNext: function(tab, navigation, index) {
          console.log(index);

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
          let spanDropZone = $('div.dz-default').children()[0];
          $(spanDropZone).html('Drop files or click here to upload');
          console.log($('.dz-default dz-message').val());
          this.on('addedfile', function(file) {
            alert('Added file.');
            var fd = new FormData(file);
            // const url = api_urls.saveImages;
            // const promise = new Promise(function(resolve, reject) {
            //   $.ajax({
            //     type: 'POST',
            //     method: 'POST',
            //     url: url,
            //     data: fd,
            //     success: function(msg) {
            //       resolve(msg);
            //     },
            //     error: function(err) {
            //       console.log(err);
            //       reject(err);
            //     }
            //   });
            // });
            // promise
            //   .then((result) => {
            //     console.log(result);
            //   })
            //   .catch((error) => {
            //     console.log(error);
            //   });
          });
          this.on('uploadprogress', function(file, progress, bytesSent) {
            console.log('Bytes Sent ', bytesSent);
          });
        }
      };
    }
  };
})();
