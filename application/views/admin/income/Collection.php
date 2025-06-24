<style type="text/css">
    .modal-backdrop {
        opacity: 0.5 !important;
        position: relative !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>

<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Collection Report</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body pb0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <form id="form1">
                                        <div class="box-body row">
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('search_type'); ?></label><small
                                                        class="req"> *</small>
                                                    <select class="form-control" name="search_type"
                                                        id="search_type_select" onchange="showdate(this.value)">
                                                        <option value="" disabled>
                                                            <?php echo $this->lang->line('select') ?></option>
                                                        <?php
                                                        foreach ($searchlist as $key => $search) { ?>
                                                            <option value="<?php echo $key ?>" <?php
                                                                                                if ((isset($search_type)) && ($search_type == $key)) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>><?php echo $search ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"
                                                        id="error_search_type"><?php echo form_error('search_type'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('collected_by'); ?></label>
                                                    <select class="form-control select2" name="collect_staff"
                                                        style="width: 100%" id="collect_staff_select">
                                                        <option value="" selected>All</option>
                                                        <?php foreach ($collected_by_list as $item) {
                                                            $name = $item['received_by_name'];
                                                            if (!empty($name)) { ?>
                                                                <option value="<?php echo htmlspecialchars($name); ?>" <?php
                                                                                                                        if (isset($staffsearch_select) && $staffsearch_select == $name) {
                                                                                                                            echo "selected";
                                                                                                                        } ?>>
                                                                    <?php echo htmlspecialchars($name); ?>
                                                                </option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                    <span class="text-danger"
                                                        id="error_collect_staff"><?php echo form_error('collect_staff'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3" id="fromdate" style="display: none">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('date_from'); ?></label><small
                                                        class="req"> *</small>
                                                    <input id="date_from" name="date_from" placeholder="" type="text"
                                                        class="form-control date"
                                                        value="<?php echo set_value('date_from', date($this->customlib->getHospitalDateFormat())); ?>" />
                                                    <span
                                                        class="text-danger"><?php echo form_error('date_from'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3" id="todate" style="display: none">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('date_to'); ?></label><small
                                                        class="req"> *</small>
                                                    <input id="date_to" name="date_to" placeholder="" type="text"
                                                        class="form-control date"
                                                        value="<?php echo set_value('date_to', date($this->customlib->getHospitalDateFormat())); ?>" />
                                                    <span
                                                        class="text-danger"><?php echo form_error('date_to'); ?></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="submit" name="search" value="search_filter"
                                                        class="btn btn-primary btn-sm pull-right"><i
                                                            class="fa fa-search"></i>
                                                        <?php echo $this->lang->line('search'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tabsborderbg"></div>
                    <div class="nav-tabs-custom border0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="all">
                                <div class="download_label"><?php echo $this->lang->line('collection_report'); ?></div>
                                <div id="payment-summary-tags"></div>
                                <div class="box-body table-responsive">
                                    <table class="table table-striped table-bordered ipdcharged" id="collection-table">
                                        <thead>
                                            <th>S.No</th>
                                            <th>Collected By</th>
                                            <th>Cash</th>
                                            <th>Online</th>
                                            <th>UPI</th>
                                            <th>Card</th>
                                            <th>Neft</th>
                                            <th class="text-right">Total</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
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
    $(document).ready(function() {
        let baseurl = '<?= base_url() ?>';
        var dataTable = initSummaryDataTable(`${baseurl}/admin/income/getallcollectionreport`, '#form1',
            '#collection-table');
        $('#form1').on('submit', function(e) {
            e.preventDefault();
            dataTable.ajax.reload();
        });
    });
    // function renderSummaryTable(dataArray, searchType) {
    //     var formData = $('#form1').serialize();
    //     return (dataArray || []).map(function(row, index) {
    //         function makeLink(amount, type) {
    //             if (amount <= 0) return formatCurrency(amount);
    //             var params = {
    //                 Payment_type: type,
    //                 collected_by: row.collected_by,
    //                 formdate: formData
    //             };
    //             var jsonString = JSON.stringify(params);
    //             var encoded = btoa(unescape(encodeURIComponent(jsonString)));
    //             return `<a href="<?= base_url() ?>admin/income/Collection_generate_bill?data=${encoded}" class="view-opd-bill">${amount}</a>`;
    //         }
    //         return [
    //             index + 1,
    //             row.collected_by || '',
    //             makeLink(Number(row.cash || 0), 'cash'),
    //             makeLink(Number(row.online || 0), 'online'),
    //             makeLink(Number(row.upi || 0), 'upi'),
    //             makeLink(Number(row.card || 0), 'card'),
    //             makeLink(Number(row.neft || 0), 'neft'),
    //             makeLink(Number(row.totalAmount || 0), 'total')
    //         ];
    //     });
    // }

    function renderSummaryTable(dataArray, searchType) {
    return (dataArray || []).map(function(row, index) {
        function format(amount) {
            return formatCurrency(Number(amount || 0));
        }
        return [
            index + 1,
            row.collected_by || '',
            format(row.cash),
            format(row.online),
            format(row.upi),            
            format(row.card),
            format(row.neft),
            format(row.totalAmount)
        ];
    });
}


    function formatCurrency(amount) {
        return amount ? "₹" + Number(amount).toFixed(2) : '₹0.00';
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
</script>