<div class="row">
    <div class="col-md-3">
        <label for="start_date">Start Date</label>
        <input type="text" id="start_date" name="start_date" class="form-control datepicker"
        required="required" readonly="readonly" />
    </div>
    <div class="col-md-3">
        <label for="end_date">End Date</label>
        <input type="text" id="end_date" name="end_date" class="form-control datepicker"
        required="required" readonly="readonly" />
    </div>
    <div class="col-md-2">
        <label for="">Rows/Page</label>
        <select id="rows_per_page" name="rows_per_page" class="form-control" required="required">
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
    <div class="col-md-2">
        <label for="">Filter</label>
        <select id="filter_by" name="filter_by" class="form-control" required="required">
            <option value="all">All</option>
            <option value="success">Success</option>
            <option value="failure">Fail</option>
        </select>
    </div>
    <div class="col-md-2">
        <label for=""></label>
        <button class="form-control btn btn-primary" onclick="Admin.searchRequestLogs()"><i class="fa fa-search"></i>&nbsp; Search</button>
    </div>
</div>
<div class="row col-md-12">.</div>
<table class="table table-bordered table-striped mb-none" id="datatable-request-logs" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Payment Reference</th>
            <th>Payment Response</th>
            <th>Transaction Date</th>
            <th>Created</th>
            <th>Status</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8">
                <div id="tablePagination"></div>
            </td>
        </tr>
    </tfoot>
</table>