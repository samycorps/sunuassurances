var Admin = (function() {
  return {
    fields: {
      payments: [],
      requestLogs: [],
      paginationReference: {},
      paginationInitialized: false
    },
    init: function() {
      $('.datepicker')
        .datepicker({
          format: 'yyyy-mm-dd',
          startDate: new Date(1920, 01, 01),
          endDate: new Date()
        })
        .on('changeDate', (e) => {
          // `e` here contains the extra attributes
          console.log(e);
        });

      Admin.fields.paginationReference = $('#tablePagination');
      $('#rows_per_page').on('change', () => {
        Admin.fields.paginationInitialized = false;
        Admin.fields.paginationReference.pagination('selectPage', 1);
      });
      $('#filter_by').on('change', () => {
        Admin.fields.paginationInitialized = false;
        Admin.fields.paginationReference.pagination('selectPage', 1);
      });
      Admin.fields.paginationReference.pagination();
      Admin.setDefaultSearchDate();
    },

    setDefaultSearchDate: () => {
      const startDate = moment().format('YYYY-MM-DD');
      const endDate = moment()
        .add(2, 'weeks')
        .format('YYYY-MM-DD');
      $('#start_date').val(startDate);
      $('#end_date').val(endDate);
    },

    getLegendRequestLogs: () => {
      console.log('Page Number', Admin.fields.paginationReference.pagination('getCurrentPage'));
      const pageNumber = Admin.fields.paginationReference.pagination('getCurrentPage');
      const rowsPerPage = $('#rows_per_page').val();
      const searchStartDate = $('#start_date').val();
      const searchEndDate = $('#end_date').val();
      const filter_by = $('#filter_by').val();
      const search_by = $('#search_by').val();
      let search_value = _.isEmpty($('#search_value').val()) ? 'none' : $('#search_value').val();
      const regex = /\//gi;
      search_value = search_value.replace(regex, '\\');
      const url = api_urls.getpoliciesrequestlog;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${pageNumber}/${rowsPerPage}/${searchStartDate}/${searchEndDate}/${filter_by}/${search_by}/${encodeURIComponent(
            search_value
          )}`,
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
          Admin.fields.requestLogs = result;
          Admin.populateLegendRequestLogs();
        })
        .catch((err) => {
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('Error - claims could not be retrieved');
          console.log(err);
        });
    },

    getClaimsLogs: () => {
      console.log('Page Number', Admin.fields.paginationReference.pagination('getCurrentPage'));
      const pageNumber = Admin.fields.paginationReference.pagination('getCurrentPage');
      const rowsPerPage = $('#rows_per_page').val();
      const searchStartDate = $('#start_date').val();
      const searchEndDate = $('#end_date').val();
      const filter_by = $('#filter_by').val();
      const search_by = $('#search_by').val();
      let search_value = _.isEmpty($('#search_value').val()) ? 'none' : $('#search_value').val();
      const regex = /\//gi;
      search_value = search_value.replace(regex, '\\');
      const url = api_urls.getclaimslog;
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}/${pageNumber}/${rowsPerPage}/${searchStartDate}/${searchEndDate}/${filter_by}/${search_by}/${encodeURIComponent(
            search_value
          )}`,
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
          Admin.fields.requestLogs = result;
          Admin.populateClaimsLogs();
        })
        .catch((err) => {
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('Error - claims could not be retrieved');
          console.log(err);
        });
    },

    populateLegendRequestLogs: () => {
      $request_log_table = $('#datatable-request-logs tbody');
      $request_log_table.empty();
      let records_count = Admin.fields.requestLogs.length;
      let last_record = records_count > 0 ? Admin.fields.requestLogs[records_count - 1] : [];
      let total_records = _.isEmpty(last_record) ? 0 : last_record.request_body;
      let data = Admin.fields.requestLogs;
      data.pop();
      $.each(data, (i, v) => {
        const markup = `<tr>
                      <td>${v.transaction_reference}</td>
                      <td class="text-semibold text-dark">${v.customer_email}</td>
                      <td class="text-semibold text-dark">${v.response_reference}</td>
                      <td class="text-center">${v.response_message}</td></td>
                      <td class="text-center">${v.transaction_date}</td></td>
                      <td class="text-center">${v.created_at}</td>
                      <td class="text-center">${v.legend_status}</td>
                      <td class="text-center"><button class="btn btn-primary" onclick="Admin.gotoPage('${i}')" ><i class="fa fa-info-circle"> Details </button></td>
                  </tr>`;
        $request_log_table.append(markup);
      });
      const pagingRow = `<tr>
                            <td colspan="6"><strong>(Total Records - ${total_records} )</strong></td>
                            <td colspan="2"></td>
                            </tr>`;
      $request_log_table.append(pagingRow);
      if (!Admin.fields.paginationInitialized) {
        Admin.fields.paginationReference.pagination({
          items: total_records,
          itemsOnPage: $('#rows_per_page').val(),
          cssStyle: 'light-theme',
          onPageClick: (pageNumber, event) => {
            console.log(`Page clincked ${pageNumber}`);
            Admin.getLegendRequestLogs();
          }
        });
        Admin.fields.paginationInitialized = true;
      }
    },

    populateClaimsLogs: () => {
      $request_log_table = $('#datatable-claims-logs tbody');
      $request_log_table.empty();
      let records_count = Admin.fields.requestLogs.length;
      let last_record = records_count > 0 ? Admin.fields.requestLogs[records_count - 1] : [];
      let total_records = _.isEmpty(last_record) ? 0 : last_record.id;
      let data = Admin.fields.requestLogs;
      data.pop();
      $.each(data, (i, v) => {
        const form_details = JSON.parse(v.form_details);
        const markup = `<tr>
                      <td>${v.claim_no}</td>
                      <td class="text-semibold text-dark">${form_details.clientDetails.email_address}</td>
                      <td class="text-semibold text-dark">${v.policy_no}</td>
                      <td class="text-center">${v.registration_no}</td></td>
                      <td class="text-center">${form_details.driverDetails.driver_fullname}</td></td>
                      <td class="text-center">${v.created_at}</td>
                      <td class="text-center">${v.status}</td>
                      <td class="text-center"><button class="btn btn-primary" onclick="Admin.gotoClaimPage('${i}')" ><i class="fa fa-info-circle"> Details </button></td>
                  </tr>`;
        $request_log_table.append(markup);
      });
      const pagingRow = `<tr>
                            <td colspan="6"><strong>(Total Records - ${total_records} )</strong></td>
                            <td colspan="2"></td>
                            </tr>`;
      $request_log_table.append(pagingRow);
      if (!Admin.fields.paginationInitialized) {
        Admin.fields.paginationReference.pagination({
          items: total_records,
          itemsOnPage: $('#rows_per_page').val(),
          cssStyle: 'light-theme',
          onPageClick: (pageNumber, event) => {
            console.log(`Page clincked ${pageNumber}`);
            Admin.getLegendRequestLogs();
          }
        });
        Admin.fields.paginationInitialized = true;
      }
    },

    searchRequestLogs: () => {
      Admin.getLegendRequestLogs();
    },

    searchClaimsLogs: () => {
      Admin.getClaimsLogs();
    },

    gotoPage: (pos) => {
      localStorage.setItem('reference', JSON.stringify(Admin.fields.requestLogs[pos]));
      window.location.href = `/portal/administrator/request/${Admin.fields.requestLogs[pos].transaction_reference}`;
    },

    gotoClaimPage: (pos) => {
      localStorage.setItem('reference', JSON.stringify(Admin.fields.requestLogs[pos]));
      window.location.href = `/portal/administrator/claim`;
    }
  };
})();
