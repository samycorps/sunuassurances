var Payment = (function() {
  return {
    fields: {
      transactionDetails: []
    },
    init: function() {
      /*
       *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
       */
      // Set Selected Profile
      Utility.fields.selectedProfile = JSON.parse($('#profile_details').val());

      //   _this.getExistingVehicleDetailsByProfile().then(
      //     (values) => {
      //       console.log(values);
      //       if (values.length > 0) {
      //         values.map((v) => {
      //           const policy_number = v.policy.length > 0 ? v.policy[0].policy_number : '';
      //           const cover_type = v.policy.length > 0 ? v.policy[0].cover_type : '';
      //           const expiry_date = v.policy.length > 0 ? v.policy[0].expiry_date : '';
      //           v.payment.map((p) => {
      //             _this.fields.transactionDetails.push({
      //               ...p,
      //               registration_number: v.registration_number,
      //               policy_number: policy_number,
      //               cover_type: cover_type,
      //               expiry_date: expiry_date
      //             });
      //           });
      //         });
      //       }
      //       console.log(_this.fields.transactionDetails);
      //       _this.displayPaymentDetails();
      //     },
      //     (error) => {
      //       console.log(error);
      //     }
      //   );

      _this.getExistingVehicleDetailsByUser().then(
        (values) => {
          console.log('User Policies', values);
          if (values.length > 0) {
            _this.fields.transactionDetails = values.map((v) => {
              return {
                firstname: v.firstname,
                lastname: v.lastname,
                title: v.title,
                registration_number: v.registration_number,
                transaction_reference: v.transaction_reference,
                transaction_amount: v.transaction_amount,
                response_message: v.response_message,
                policy_number: v.policy_number,
                cover_type: v.cover_type,
                expiry_date: v.expiry_date,
                certificate_number: v.certificate_number,
                form_details: v.form_details
              };
            });
          }
          console.log(_this.fields.transactionDetails);
          _this.displayPaymentDetails();
        },
        (error) => {
          console.log(error);
        }
      );
    },

    getExistingVehicleDetailsByProfile: function() {
      const url = api_urls.gettransactiondetailsbyprofile;
      const profile_id = $('#profile_id').val();
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${profile_id}`,
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

    getExistingVehicleDetailsByUser: function() {
      const url = api_urls.policylistByUser;
      const user_id = Utility.fields.selectedProfile.user_id;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${user_id}`,
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

    displayPaymentDetails: function() {
      $payment_table = $('#datatable-payment tbody');
      const KOBO_NAIRA_DIVISION = 100;
      $.each(_this.fields.transactionDetails, (i, v) => {
        const transaction_amount = parseFloat(v.transaction_amount) / KOBO_NAIRA_DIVISION;
        const markup = `<tr>
                    <td>${v.registration_number}</td>
                    <td>${v.transaction_reference}</td>
                    <td>${transaction_amount.toFixed(2)}</td>
                    <td>${v.response_message}</td>
                    <td>${v.policy_number}</td>
                    <td>${v.cover_type}</td>
                    <td>${v.expiry_date}</td>
                    <td><button class="btn btn-primary" onclick="Payment.printPage(${i})"><i class="fa fa-print"></button></td>
                </tr>`;
        $payment_table.append(markup);
      });
    },

    printPage: (index) => {
      console.log(index);
      Utility.printPage(_this.fields.transactionDetails[index]);
    },

    validateUser: function() {
      const user_data = {
        username: $('#username').val(),
        password: $('#userpassword').val()
      };
      console.log(user_data);
      const url = api_urls.login;
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
    }
  };
})();
const _this = Payment;
