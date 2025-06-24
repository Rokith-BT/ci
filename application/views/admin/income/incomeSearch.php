<style type="text/css">
@media print {

    .no-print,
    .no-print * {
        display: none !important;
    }
}
</style>
<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('income_report'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body pb0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">

                                    <form id="form1" action="" method="post" class="">
                                        <div class="box-body row">
                                            <?php echo $this->customlib->getCSRF(); ?>

                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label>Filter By</label>
                                                    <select class="form-control" name="filter_type" id="filter_type">
                                                        <option value="total">Total</option>
                                                        <option value="month">Month</option>
                                                        <option value="consultant">Consultant</option>
                                                        <option value="patient">Patient</option>
                                                        <option value="paymenttype">Payment type</option>
                                                    </select>
                                                    <span class="text-danger"
                                                        id="error_filter_type"><?php echo form_error('filter_type'); ?></span>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="button" name="search" value="search_filter"
                                                        onclick="filterdata()"
                                                        class="btn btn-primary btn-sm checkbox-toggle pull-right"><i
                                                            class="fa fa-search"></i>
                                                        <?php echo $this->lang->line('search'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>

                        <div id="payment-summary-tags"></div>
                        <div class="table-responsive" id="totaldiv">
                            <table class="table table-striped table-bordered table-hover ipdcharged"
                                id="ajaxlist_total">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Appointment</th>
                                        <th>O/P</th>
                                        <th>I/P</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                        <div class="table-responsive" style="display: none;" id="monthdiv">
                            <table class="table table-striped table-bordered table-hover ipdcharged"
                                id="ajaxlist_month">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Month</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                        <div class="table-responsive" style="display: none;" id="consultantdiv">
                            <table class="table table-striped table-bordered table-hover ipdcharged"
                                id="ajaxlist_consultant">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Consultant</th>
                                        <th>Appointment</th>
                                        <th>O/P</th>
                                        <th>I/P</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                        <div class="table-responsive" style="display: none;" id="patientdiv">
                            <table class="table table-striped table-bordered table-hover ipdcharged"
                                id="ajaxlist_patient">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>Age</th>
                                        <th>Address</th>
                                        <th>Appointment Income</th>
                                        <th>OPIncome</th>
                                        <th>IPIncome</th>
                                        <th>Total Income</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                        <div class="table-responsive" style="display: none;" id="userwisediv">
                            <table class="table table-striped table-bordered table-hover ipdcharged"
                                id="ajaxlist_userwise">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th>User</th>
                                        <th>Cash</th>
                                        <th>Card</th>
                                        <th>Cheque</th>
                                        <th>DD</th>
                                        <th>Neft</th>
                                        <th>Credit</th>
                                        <th>UPI</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                        <div class="table-responsive" style="display: none;" id="paymenttypediv">
                            <table class="table table-striped table-bordered table-hover ipdcharged"
                                id="ajaxlist_paymenttype">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <?php
                                        foreach ($payment_mode as $key => $value) {
                                            if ($value != "Paylater")
                                                echo '<th>' . $value . '</th>';
                                        }
                                        ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination justify-content-center" id="pagination"></ul>
                        </nav>

                    </div>



                </div> <!-- /.row -->

    </section><!-- /.content -->
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#filter_type').val('total');
    filterdata();
});

function filterdata() {
    const value = $('#filter_type').val();
    if (!value) return;
    const allDivs = ['#totaldiv', '#monthdiv', '#consultantdiv', '#patientdiv', '#userwisediv', '#paymenttypediv'];
    allDivs.forEach(div => $(div).hide());
    const tableMap = {
        total: {
            tableId: '#ajaxlist_total',
            renderFunc: renderTotalTable,
            functionname: 'renderTotalTable',
            showSummary: updatePaymentSummary
        },
        month: {
            tableId: '#ajaxlist_month',
            renderFunc: renderMonthTable,
            functionname: 'renderMonthTable',
            showSummary: updatePaymentSummary
        },
        consultant: {
            tableId: '#ajaxlist_consultant',
            renderFunc: renderConsultantTable,
            functionname: 'renderConsultantTable',
            showSummary: updatePaymentSummary
        },
        patient: {
            tableId: '#ajaxlist_patient',
            renderFunc: renderPatientTable,
            functionname: 'renderPatientTable',
            showSummary: updatePaymentSummary
        },
        paymenttype: {
            tableId: '#ajaxlist_paymenttype',
            renderFunc: renderPaymenttypeTable,
            functionname: 'renderPaymenttypeTable',
            showSummary: ''
        }
    };
    const config = tableMap[value];
    if (!config) return;
    const $divToShow = `#${value}div`;
    $(config.tableId).DataTable().destroy();
    initSummaryDataTable(`${baseurl}/admin/income/incomeReport`, '#form1', config.tableId, config.showSummary, config.functionname, value);
    $($divToShow).show();
}

function renderTotalTable(dataArray) {
    let count = 0;
    return (dataArray || []).map(item => {
        const date = item.paymentDate || '-';
        const appointmentincome = item.appointmentAmount || '0.00';
        const opincome = item.opdAmount || '0.00';
        const ipincome = item.ipdAmount || '0.00';
        const total = item.totalAmount || '0.00';
        return [
            ++count,
            date,
            "₹" + parseFloat(appointmentincome).toFixed(2),
            "₹" + parseFloat(opincome).toFixed(2),
            "₹" + parseFloat(ipincome).toFixed(2),
            "₹" + parseFloat(total).toFixed(2)
        ];
    });
}

function renderMonthTable(dataArray) {
    let count = 0;
    return (dataArray || []).map(item => {
        const month = item.paymentMonth || '-';
        const amount = item.totalAmount || '0.00';
        return [
            ++count,
            month,
            "₹" + parseFloat(amount).toFixed(2)
        ];
    });
}

function renderConsultantTable(dataArray) {
    let count = 0;
    return (dataArray || []).map(item => {
        const fname = item.staff_first_name || '';
        const lname = item.staff_last_name || '';
        const consultant = (fname + ' ' + lname).trim() || '-';
        const appointmentincome = item.appointmentAmount || '0.00';
        const opincome = item.opdAmount || '0.00';
        const ipincome = item.ipdAmount || '0.00';
        const appcount = item.appointmentCount || '-';
        const opcount = item.opdCount || '-';
        const ipcount = item.ipdCount || '-';
        const total = item.totalAmount || '0.00';
        return [
            ++count,
            consultant,
            "₹" + parseFloat(appointmentincome).toFixed(2),
            "₹" + parseFloat(opincome).toFixed(2),
            "₹" + parseFloat(ipincome).toFixed(2),
            "₹" + parseFloat(total).toFixed(2)
        ];
    });
}

function renderPatientTable(dataArray) {
    let count = 0;
    return (dataArray || []).map(item => {
        const patientid = item.patient_id || '-';
        const name = item.patient_name || '-';
        const gender = item.gender || '-';
        const dob = (item.dob && item.dob.trim()) ? moment(item.dob).format("D/M/YYYY") : '-';
        const age = item.age || '-';
        var parts = [];
        if (item.address) parts.push(item.address);
        if (item.district_name) parts.push(item.district_name);
        if (item.state_name) parts.push(item.state_name);
        const address = parts.length > 0 ? parts.join(', ') : '-';
        const appointmentincome = item.appointmentAmount || '0.00';
        const opincome = item.opdAmount || '0.00';
        const ipincome = item.ipdAmount || '0.00';
        const income = item.totalAmount || '0.00';
        return [
            ++count,
            patientid,
            name,
            gender,
            dob,
            age,
            address,
            "₹" + parseFloat(appointmentincome).toFixed(2),
            "₹" + parseFloat(opincome).toFixed(2),
            "₹" + parseFloat(ipincome).toFixed(2),
            "₹" + parseFloat(income).toFixed(2)
        ];
    });
}

function renderPaymenttypeTable(dataArray, formattedSummary) {
    let count = 0;
    const cash = parseFloat(formattedSummary.cash || 0);
    const online = parseFloat(formattedSummary.online || 0);
    const upi = parseFloat(formattedSummary.upi || 0);
    const card = parseFloat(formattedSummary.card || 0);
    const neft = parseFloat(formattedSummary.neft || 0);
    const totalamount = cash + online + upi + card + neft;
    return [
        [
            ++count,
            "₹" + cash.toFixed(2),
            "₹" + online.toFixed(2),
            "₹" + upi.toFixed(2),
            "₹" + card.toFixed(2),
            "₹" + neft.toFixed(2),
            "₹" + totalamount.toFixed(2)
        ]
    ];
}
</script>
<script type="text/javascript">
var base_url = '<?php echo base_url() ?>';

function printDiv(elem) {
    Popup(jQuery(elem).html());
}

function Popup(data) {

    var frame1 = $('<iframe />');
    frame1[0].name = "frame1";
    frame1.css({
        "position": "absolute",
        "top": "-1000000px"
    });
    $("body").append(frame1);
    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0]
        .contentDocument.document : frame1[0].contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write('<html>');
    frameDoc.document.write('<head>');
    frameDoc.document.write('<title></title>');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/datepicker/datepicker3.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
    frameDoc.document.write('</head>');
    frameDoc.document.write('<body>');
    frameDoc.document.write(data);
    frameDoc.document.write('</body>');
    frameDoc.document.write('</html>');
    frameDoc.document.close();
    setTimeout(function() {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();
    }, 500);


    return true;
}
</script>


<script type="text/javascript">
function showdate(value) {

    if (value == 'period') {
        $('#fromdate').show();
        $('#todate').show();
    } else {
        $('#fromdate').hide();
        $('#todate').hide();
    }
}
</script>
