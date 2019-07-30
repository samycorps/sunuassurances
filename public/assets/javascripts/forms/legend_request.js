var LegendRequest = (function() {
  return {
    init: function() {
      $('#legendform :input').attr('readonly', 'readonly');
      LegendRequest.populateFormScreen();
    },

    populateFormScreen: () => {
      console.log(localStorage.getItem('reference'));
      const data = JSON.parse(localStorage.getItem('reference'));
      const requestBody = JSON.parse(data.request_body);
      console.log(requestBody);
      $('#vehicleTransactionDetailsId').val(requestBody.vehicleTransactionDetailsId);
      $('#payment_reference').val(requestBody.payment_reference);
      $('#title_id').val(requestBody.title_id);
      $('#firstname').val(requestBody.firstname);
      $('#lastname').val(requestBody.lastname);
      $('#othernames').val(requestBody.othernames);
      $('#address').val(requestBody.address);
      $('#city').val(requestBody.city);
      $('#state').val(requestBody.state);
      $('#email_address').val(requestBody.email_address);
      $('#gsm_number').val(requestBody.gsm_number);
      $('#office_number').val(requestBody.office_number);
      $('#website').val(requestBody.website);
      $('#contact_person').val(requestBody.contact_person);
      $('#date_of_birth').val(requestBody.date_of_birth);
      $('#company_reg_num').val(requestBody.company_reg_num);
      $('#tin_number').val(requestBody.tin_number);
      $('#occupation').val(requestBody.occupation);
      $('#sector').val(requestBody.sector);
      $('#client_class').val(requestBody.client_class);
      $('#risk_class').val(requestBody.risk_class);
      $('#policy_class').val(requestBody.policy_class);
      $('#cover_type').val(requestBody.cover_type);
      $('#policy_type').val(requestBody.policy_type);
      $('#vehicle_plate_number').val(requestBody.vehicle_plate_number);
      $('#engine_number').val(requestBody.engine_number);
      $('#chasis_number').val(requestBody.chasis_number);
      $('#model').val(requestBody.model);
      $('#body').val(requestBody.body);
      $('#color').val(requestBody.color);
      $('#cubic_capacity').val(requestBody.cubic_capacity);
      $('#number_of_seat').val(requestBody.number_of_seat);
      $('#year_of_make').val(requestBody.year_of_make);
      $('#year_of_purchase').val(requestBody.year_of_purchase);
      $('#mode_of_payment').val(requestBody.mode_of_payment);
      $('#currency').val(requestBody.currency);
      $('#bank_id').val(requestBody.bank_id);
      $('#account_number').val(requestBody.account_number);
      $('#bvn').val(requestBody.bvn);
      $('#company_bank').val(requestBody.company_bank);
      $('#effective_date').val(requestBody.effective_date);
      $('#expiry_date').val(requestBody.expiry_date);
      $('#transaction_status').html(data.legend_status);
    }
  };
})();
