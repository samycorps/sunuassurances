<section class="card">
    <header class="card-header">
        <div class="card-actions">
            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
        </div>
    </header>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10 form-label">
                ** To renew a policy previously generated outside this platform, Kindly provide your registration number below
            </div>
            <div class="col-md-2 pull-left">
                <button class="btn btn-primary" onclick="Motor.startRenewalLegendOnlyCustomers()"><i class="fa fa-repeat"> Renew </i></button>
            </div>
        </div>
        <p></p>
        <table class="table table-bordered table-striped mb-none" id="datatable-payment" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
            <thead>
                <tr>
                    <th>Reg. Number</th>
                    <th>Amount (Naira)</th>
                    <th>Policy #</th>
                    <th>Cover Type</th>
                    <th>Exp. Date</th>
                    <th>Renew</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</section>