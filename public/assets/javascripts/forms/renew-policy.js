var RenewPolicy = function() {
    return {
        fields: {
            legend_vehicle_data: {},
            legend_vehicle_data_payment: [],
            legend_request_body: {},
            transactionDetails: {},
            legendData: {},
            legendResponse: {}
        },
        init: function() {
        },
        processPolicy: () => {
            const data = $('#vehicle_details').val();
            if(!_.isEmpty(data)) {
                const legend_data = JSON.parse(data);
                RenewPolicy.fields.legend_vehicle_data = legend_data;
                RenewPolicy.fields.legend_vehicle_data_payment = legend_data.payment;
                const lastRequestLog = legend_data.request_log.slice(-1)[0];
                const request_body = JSON.parse(lastRequestLog.request_body);
                console.log(request_body);
                RenewPolicy.fields.legend_request_body = request_body;
                const lastPolicy = legend_data.policy.slice(-1)[0];
                const legendData = { policy_number: lastPolicy.policy_number, ...request_body };
                let effective_date = lastPolicy.expiry_date.substring(0,9);
                effective_date = moment(effective_date,'DD-MMM-YY').format('YYYY-MM-DD'); expiry_date = moment(effective_date,'DD-MMM-YY').add(1, 'years').format('YYYY-MM-DD');
                legendData.effective_date = effective_date;
                legendData.expiry_date = expiry_date;
                RenewPolicy.fields.legendData = legendData;
                const lastPayment = legend_data.payment.slice(-1)[0];
                console.log(`${legendData.effective_date} ------   ${lastPayment.transaction_reference} ----- ${lastRequestLog.payment_reference} ----- ${request_body.legend_response}`);
                
                if (lastPayment.transaction_reference === lastRequestLog.payment_reference && lastRequestLog.legend_response.indexOf("Policy Number") < 0) {
                    let messageText = `Transaction with successful payment reference ${lastPayment.transaction_reference} already exist <br/>`;
                    messageText = `${messageText} Policy could not be renewed, error message ${lastRequestLog.legend_response}`;
                    $('.alert-message').addClass('error');
                    $('.alert-message-text').html(messageText);
                } else {
                    RenewPolicy.processPayment();
                }
            }
        },

        processPayment: () => {
            const legend_vehicle_data = RenewPolicy.fields.legend_vehicle_data;
            const legend_vehicle_data_payment = legend_vehicle_data.payment;
            const request_body = RenewPolicy.fields.legend_request_body;
            const transactionReference = RenewPolicy.generateTransactionId
            (legend_vehicle_data_payment[0].customer_email);
            RenewPolicy.fields.transactionDetails = {
                userId: $('#user_id').val(),
                profileId: $('#profile_id').val(),
                transactionReference: transactionReference,
                customerEmail: legend_vehicle_data_payment[0].customer_email,
                transactionAmount: legend_vehicle_data_payment[0].transaction_amount,
                transactionDate: moment().format('YYYY-MM-DD HH:mm:ss'),
                paymentGateway: 'PayStack',
                responseStatus: 'initiate',
                responseReference: '',
                responseMessage: '',
                vehicleTransactionDetailsId: legend_vehicle_data.id
            };
                const handler = PaystackPop.setup({
                    key: paystack.key,
                    email: legend_vehicle_data_payment[0].customer_email,
                    amount: legend_vehicle_data_payment[0].transaction_amount,
                    ref: transactionReference,
                    metadata: {
                       custom_fields: [
                          {
                              display_name: "Mobile Number",
                              variable_name: "mobile_number",
                              value: request_body.gsm_number
                          }
                       ]
                    },
                    callback: function(response){
                        console.log(response);
                        console.log('success. transaction ref is ' + response.reference);
                        RenewPolicy.fields.transactionDetails.responseReference = response.transaction;
                        RenewPolicy.fields.transactionDetails.responseStatus = response.status;
                        RenewPolicy.fields.transactionDetails.responseMessage = response.message;
                        console.log(RenewPolicy.fields.transactionDetails);
                        RenewPolicy.saveVehiclePaymentDetails(RenewPolicy.fields.transactionDetails)
                        .then(result => {
                            console.log(result);
                            $('#renew_policy_btn').prop('disabled', 'disabled'); 
                            RenewPolicy.renewExistingPolicy()
                            .then(result => {
                                console.log(result);
                                responseString = result.message;
                                RenewPolicy.displayPolicyResult(responseString);
                            })
                            .catch( err => {
                                console.log(err);
                            }); 
                        })
                        .catch( err => {
                            console.log(err);
                        }); 
                    },
                    onClose: function(){
                        console.log('window closed');
                    }
                  });
                handler.openIframe();
        },

        renewExistingPolicy: () => {
            const url = api_urls.renewPolicy;
            RenewPolicy.fields.legendData['payment_reference'] = RenewPolicy.fields.transactionDetails.transactionReference
            const promise = new Promise(function(resolve, reject){
                $.ajax({
                        type: "POST",
                        url: url,
                        data: RenewPolicy.fields.legendData,
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

        displayPolicyResult: (responseString) => {
            if( responseString.indexOf("Policy Number") > -1 ){
                const responseArray = responseString.split(", ");
                $.each(responseArray, (i,v) => {
                    const eachData = v.split(":");
                    RenewPolicy.fields.legendResponse[`${eachData[0]}`.replace(/\s/g, '')] = eachData[1].trim();
                });

                // Save Policy Details
                RenewPolicy.savePolicyDetails();

                // Set the response html elements
                $('#client_number').html(RenewPolicy.fields.legendResponse.ClientNumber);
                $('#policy_number').html(RenewPolicy.fields.legendResponse.PolicyNumber);
                $('#certificate_number').html(RenewPolicy.fields.legendResponse.CertificateNumber);
                $('#debit_note_number').html(RenewPolicy.fields.legendResponse.DebitNoteNumber);
                $('#receipt_number').html(RenewPolicy.fields.legendResponse.ReceiptNumber);
                $('#expiry_date').html(RenewPolicy.fields.legendResponse.ExpiryDate);
            }
            else {
                $('#legendResponseMessage').html(responseString);
            }

            $('#policy_renewal_details').removeClass('hide_elements');
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

        savePolicyDetails: () => {
            const url = api_urls.vehicletransactionpolicy;
            if (!_.isEmpty(RenewPolicy.fields.legendResponse)) {
                const policyDetails = {
                    profileId: $('#profile_id').val(), 
                    userId: $('#user_id').val(),
                    policyType: 'motor',
                    coverType: RenewPolicy.fields.legend_vehicle_data.policy.cover_type,
                    coverOption: 'renewal', 
                    vehicleTransactionDetailsId: RenewPolicy.fields.legend_vehicle_data.id, ...RenewPolicy.fields.legendResponse};
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
                });
                promise.then(result => {
                    console.log(result);
                })
                .catch( err => {
                    console.log(err);
                });
            }
        },

        generateTransactionId: (customer_email) => {
            const uuidv4 = 'xxxxx_xxxx_4xxx_yxxx_xxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });

            return uuidv4;
        },

        legendRenewPolicy: () => {

        }
    }
}();