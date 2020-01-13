var Report = (function() {
  return {
    fields: {
      payments: [],
      requestLogs: [],
      paginationReference: {},
      paginationInitialized: false,
      reportCategory: ''
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

      Report.fields.paginationReference = $('#tablePagination');
      $('#rows_per_page').on('change', () => {
        Report.fields.paginationInitialized = false;
        Report.fields.paginationReference.pagination('selectPage', 1);
      });
      $('#filter_by').on('change', () => {
        Report.fields.paginationInitialized = false;
        Report.fields.paginationReference.pagination('selectPage', 1);
      });
      Report.fields.paginationReference.pagination();
      Report.setDefaultSearchDate();

      Report.generateDays();
    },

    setDefaultSearchDate: () => {
      const startDate = moment().format('YYYY-MM-DD');
      const endDate = moment()
        .add(2, 'weeks')
        .format('YYYY-MM-DD');
      $('#start_date').val(startDate);
      $('#end_date').val(endDate);
    },

    onCategoryChange: () => {
      const categoryId = event.target.id;
      console.log($('#' + categoryId).val());
      Report.fields.reportCategory = $('#' + categoryId).val();
    },

    getSalesReport: () => {
      console.log('Page Number', Report.fields.paginationReference.pagination('getCurrentPage'));
      const pageNumber = Report.fields.paginationReference.pagination('getCurrentPage');
      const rowsPerPage = $('#rows_per_page').val();
      const searchStartDate = $('#start_date').val();
      const searchEndDate = $('#end_date').val();
      const filter_by = $('#filter_by').val();
      const search_by = $('#search_by').val();
      let search_value = _.isEmpty($('#search_value').val()) ? 'none' : $('#search_value').val();
      const regex = /\//gi;
      search_value = search_value.replace(regex, '\\');

      let url = '';
      switch (Report.fields.reportCategory) {
        case 'salesbydate': {
          url = api_urls.salesbydate;
          break;
        }
        case 'salesbyproducts': {
          url = api_urls.salesbyproducts;
          break;
        }
        case 'salesbyagents': {
          url = api_urls.salesbyagents;
          break;
        }
      }
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
          Report.fields.requestLogs = result;
          switch (Report.fields.reportCategory) {
            case 'salesbydate': {
              Report.populateSalesLogs();
              break;
            }
            case 'salesbyproducts': {
              Report.populateSalesProductsLogs();
              break;
            }
            case 'salesbyagents': {
              Report.populateSalesAgentsLogs();
              break;
            }
          }
        })
        .catch((err) => {
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('Error - claims could not be retrieved');
          console.log(err);
        });
    },

    getPolicyReport: () => {
      let url = '';
      switch (Report.fields.reportCategory) {
        case 'activepolicies': {
          url = api_urls.activepolicies;
          break;
        }
        case 'expiringpolicies': {
          const expiry_days = $('#expiry_days').val();
          url = `${api_urls.expiringpolicies}/${expiry_days}`;
          break;
        }
        case 'expiredpolicies': {
          url = api_urls.expiredpolicies;
          break;
        }
      }
      const promise = new Promise(function(resolve, reject) {
        $.ajax({
          type: 'GET',
          method: 'GET',
          url: `${url}`,
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
          Report.fields.requestLogs = result;
          //   switch (Report.fields.reportCategory) {
          //     case 'activepolicies': {
          //       Report.populatePolicies();
          //       break;
          //     }
          //     case 'expiringpolicies': {
          //       Report.populatePolicies();
          //       break;
          //     }
          //     case 'expiredpolicies': {
          //       Report.populateExpiredPolicies();
          //       break;
          //     }
          //   }
          Report.populatePolicies();
        })
        .catch((err) => {
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('Error - claims could not be retrieved');
          console.log(err);
        });
    },

    getClaimsLogs: () => {
      console.log('Page Number', Report.fields.paginationReference.pagination('getCurrentPage'));
      const pageNumber = Report.fields.paginationReference.pagination('getCurrentPage');
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
          Report.fields.requestLogs = result;
          Report.populateClaimsLogs();
        })
        .catch((err) => {
          $('.alert-message').addClass('error');
          $('.alert-message-text').html('Error - claims could not be retrieved');
          console.log(err);
        });
    },

    populateSalesLogs: () => {
      // Hide other tables
      $('#datatable-sales-products-logs').addClass('hide_elements');
      $('#datatable-sales-agents-logs').addClass('hide_elements');
      $('#datatable-sales-logs').removeClass('hide_elements');
      $request_log_table = $('#datatable-sales-logs tbody');
      $request_log_table.empty();
      let records_count = Report.fields.requestLogs.length;
      let last_record = records_count > 0 ? Report.fields.requestLogs[records_count - 1] : [];
      let total_records = _.isEmpty(last_record) ? 0 : last_record.registration_number;
      let data = Report.fields.requestLogs;
      data.pop();
      $.each(data, (i, v) => {
        const fullname = _.isEmpty(v.company_name) ? `${v.firstname} ${v.lastname}` : `${v.company_name}`;
        const markup = `<tr>
                        <td>${v.client_number}</td>
                        <td class="text-semibold text-dark">${v.policy_number}</td>
                        <td class="text-semibold text-dark">${fullname}</td>
                        <td class="text-center">${v.registration_number}</td></td>
                        <td class="text-center">${v.created_at}</td>
                        <td class="text-center">${(v.transaction_amount / 100).toFixed(2)}</td>
                        <td class="text-center"><button class="btn btn-primary" onclick="Report.gotoPage('${i}')" ><i class="fa fa-info-circle"> Details </button></td>
                    </tr>`;
        $request_log_table.append(markup);
      });
      const pagingRow = `<tr>
                              <td colspan="6"><strong>(Total Records - ${total_records} )</strong></td>
                              <td colspan="2"></td>
                              </tr>`;
      $request_log_table.append(pagingRow);
      if (!Report.fields.paginationInitialized) {
        Report.fields.paginationReference.pagination({
          items: total_records,
          itemsOnPage: $('#rows_per_page').val(),
          cssStyle: 'light-theme',
          onPageClick: (pageNumber, event) => {
            console.log(`Page clincked ${pageNumber}`);
            Report.getLegendRequestLogs();
          }
        });
        Report.fields.paginationInitialized = true;
      }
    },

    populateSalesProductsLogs: () => {
      // Hide other tables
      $('#datatable-sales-products-logs').removeClass('hide_elements');
      $('#datatable-sales-agents-logs').addClass('hide_elements');
      $('#datatable-sales-logs').addClass('hide_elements');
      $request_log_table = $('#datatable-sales-products-logs tbody');
      $request_log_table.empty();
      let data = Report.fields.requestLogs;
      $.each(data, (i, v) => {
        const markup = `<tr>
                          <td>${v.cover_type}</td>
                          <td class="text-semibold text-dark">${parseFloat(v.total_amount).toFixed(2)}</td>
                          <td class="text-semibold text-dark">${v.total_sales}</td>
                      </tr>`;
        $request_log_table.append(markup);
      });
    },

    populateSalesAgentsLogs: () => {
      // Hide other tables
      $('#datatable-sales-products-logs').addClass('hide_elements');
      $('#datatable-sales-agents-logs').removeClass('hide_elements');
      $('#datatable-sales-logs').addClass('hide_elements');
      $request_log_table = $('#datatable-sales-agents-logs tbody');
      $request_log_table.empty();
      let data = Report.fields.requestLogs;
      $.each(data, (i, v) => {
        const fullname = _.isEmpty(v.company_name) ? `${v.firstname} ${v.lastname}` : `${v.company_name}`;
        const markup = `<tr>
                            <td>${fullname}</td>
                            <td class="text-semibold text-dark">${parseFloat(v.total_amount).toFixed(2)}</td>
                            <td class="text-semibold text-dark">${v.num_of_policies}</td>
                        </tr>`;
        $request_log_table.append(markup);
      });
    },

    populatePolicies: () => {
      // Hide other tables
      //   $('#datatable-sales-products-logs').addClass('hide_elements');
      $('#datatable-customers-active-logs').removeClass('hide_elements');
      $('#datatable-sales-logs').addClass('hide_elements');
      $request_log_table = $('#datatable-customers-active-logs tbody');
      $request_log_table.empty();
      let data = Report.fields.requestLogs;
      $.each(data, (i, v) => {
        const fullname = _.isEmpty(v.company_name) ? `${v.firstname} ${v.lastname}` : `${v.company_name}`;
        const markup = `<tr>
                            <td>${v.client_number}</td>
                            <td>${v.policy_number}</td>
                            <td>${fullname}</td>
                            <td>${v.registration_number}</td>
                            <td>${v.formatted_expiry_date}</td>
                            <td class="text-semibold text-dark">${v.cover_type}</td>
                          </tr>`;
        $request_log_table.append(markup);
      });
    },

    populateClaimsLogs: () => {
      $request_log_table = $('#datatable-claims-logs tbody');
      $request_log_table.empty();
      let records_count = Report.fields.requestLogs.length;
      let last_record = records_count > 0 ? Report.fields.requestLogs[records_count - 1] : [];
      let total_records = _.isEmpty(last_record) ? 0 : last_record.id;
      let data = Report.fields.requestLogs;
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
                        <td class="text-center"><button class="btn btn-primary" onclick="Report.gotoClaimPage('${i}')" ><i class="fa fa-info-circle"> Details </button></td>
                    </tr>`;
        $request_log_table.append(markup);
      });
      const pagingRow = `<tr>
                              <td colspan="6"><strong>(Total Records - ${total_records} )</strong></td>
                              <td colspan="2"></td>
                              </tr>`;
      $request_log_table.append(pagingRow);
      if (!Report.fields.paginationInitialized) {
        Report.fields.paginationReference.pagination({
          items: total_records,
          itemsOnPage: $('#rows_per_page').val(),
          cssStyle: 'light-theme',
          onPageClick: (pageNumber, event) => {
            console.log(`Page clincked ${pageNumber}`);
            Report.getLegendRequestLogs();
          }
        });
        Report.fields.paginationInitialized = true;
      }
    },

    searchRequestLogs: () => {
      Report.getLegendRequestLogs();
    },

    gotoPage: (pos) => {
      localStorage.setItem('reference', JSON.stringify(Report.fields.requestLogs[pos]));
      window.location.href = `/portal/administrator/request/${Report.fields.requestLogs[pos].transaction_reference}`;
    },

    gotoClaimPage: (pos) => {
      localStorage.setItem('reference', JSON.stringify(Report.fields.requestLogs[pos]));
      window.location.href = `/portal/administrator/claim`;
    },

    generateDays: () => {
      let expiry_days = $('#expiry_days');
      for (let i = 1; i <= 100; i++) {
        expiry_days.append(
          $('<option></option>')
            .attr('value', i)
            .text(`${i} days`)
        );
      }
    }
  };
})();
