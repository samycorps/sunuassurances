var Utility = function() {
    return {
        fields: {
            selectedProfile: {}
        },
        clientClasses: {
            'Individual': 'I',
            'Company': 'C',
            'Corporate': 'C',
            'Government': 'G'
        },
        titles: {
            'Mr': '001',
            'Mrs': '002',
            'Miss': '003',
            'Master': '004',
            'Ms': '005',
            'Chief': '006',
            'Clergy': '007',
            'Dr': '008',
            'Mr & Mrs': '009'
        },
        init: function() {
            $("input[data-type='currency']").on({
                keyup: function() {
                    Utility.formatCurrency($(this));
                },
                blur: function() { 
                    Utility.formatCurrency($(this), "blur");
                }
            });
        },

        setupProfileAutoComplete: () => {
            let $input = $(".typeahead");
            $input.typeahead({
            autoSelect: true,
            minLength: 3,
            delay: 400,
            source: function (query, process) {
                const url = api_urls.profileList;
                return $.ajax({
                    url: `${url}/${query}`,
                    dataType: 'json'
                })
                .done(function(response) {
                    console.log(response);
                    return process(response);
                });
            }
            });
            $input.change(function() {
            let current = $input.typeahead("getActive");
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
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        
        formatCurrency: (input, blur) => {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.
            
            // get input value
            var input_val = input.val();
            
            // don't validate empty input
            if (input_val === "") { return; }
            
            // original length
            var original_len = input_val.length;
          
            // initial caret position 
            var caret_pos = input.prop("selectionStart");
              
            // check for decimal
            if (input_val.indexOf(".") >= 0) {
          
              // get position of first decimal
              // this prevents multiple decimals from
              // being entered
              var decimal_pos = input_val.indexOf(".");
          
              // split number by decimal point
              var left_side = input_val.substring(0, decimal_pos);
              var right_side = input_val.substring(decimal_pos);
          
              // add commas to left side of number
              left_side = Utility.formatNumber(left_side);
          
              // validate right side
              right_side = Utility.formatNumber(right_side);
              
              // On blur make sure 2 numbers after decimal
              if (blur === "blur") {
                right_side += "00";
              }
              
              // Limit decimal to only 2 digits
              right_side = right_side.substring(0, 2);
          
              // join number by .
              input_val = left_side + "." + right_side;
          
            } else {
              // no decimal entered
              // add commas to number
              // remove all non-digits
              input_val = Utility.formatNumber(input_val);
              
              // final formatting
              if (blur === "blur") {
                input_val += ".00";
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
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });

            return uuidv4;
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

        getLocationsList: () => {
            const url = api_urls.locations;
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

        saveVehicleDetails: (operation, vehicleTransactionDetails) => {
            const url = operation === 'update' ? `${api_urls.vehicletransactiondetails}/${vehicleTransactionDetails.vehicleDetailsId}` : api_urls.vehicletransactiondetails;
            const formType = operation === 'update' ? "PUT" : "POST";
                const promise = new Promise(function(resolve, reject){
                    $.ajax({
                            type: formType,
                            url: url,
                            // contentType: "application/json; charset=utf-8",
                            data: vehicleTransactionDetails,
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

        saveVehiclePaymentDetails: (vehiclePaymetDetails) => {
            const url = api_urls.vehicletransactionpayment;
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                        type: "POST",
                        url: url,
                        // contentType: "application/json; charset=utf-8",
                        data: vehiclePaymetDetails,
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
                const promise = new Promise(function(resolve, reject){
                    $.ajax({
                            type: "POST",
                            url: url,
                            // contentType: "application/json; charset=utf-8",
                            data: policyDetails,
                            success: function(msg){
                                resolve(msg);
                            },
                            error: function(err) {
                                console.log(err);
                                reject(err);
                            }
                    });
                })
                return promise;
            }
        }
    }
}();