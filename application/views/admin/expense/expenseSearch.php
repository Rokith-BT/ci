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
                        <h3 class="box-title"></i> <?php echo $this->lang->line('expense_report'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <form id="form1" action="" method="post" class="">
                                        <div class="box-body row">
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('search_type'); ?></label>
                                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($searchlist as $key => $search) {
                                                        ?>
                                                            <option value="<?php echo $key ?>" <?php
                                                                                                if ((isset($search_type)) && ($search_type == $key)) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>><?php echo $search ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4" id="fromdate" style="display: none">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('date_from'); ?></label><small class="req"> *</small>
                                                    <input id="date_from" name="date_from" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_from', date($this->customlib->getHospitalDateFormat())); ?>" />
                                                    <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4" id="todate" style="display: none">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('date_to'); ?></label><small class="req"> *</small>
                                                    <input id="date_to" name="date_to" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_to', date($this->customlib->getHospitalDateFormat())); ?>" />
                                                    <span class="text-danger"><?php echo form_error('date_to'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="box border0 clear">
                                        <div class="box-header ptbnull">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th><?php echo $this->lang->line('expense_head'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- /.row -->
    </section><!-- /.content -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        var capital_date_format = date_format.toUpperCase();
        $.fn.dataTable.moment(capital_date_format);
        $(".date").datepicker({
            format: date_format,
            autoclose: true,
            todayHighlight: true
        });
    });
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
<script>
    $(document).ready(function() {
        let baseurl = '<?= base_url() ?>';
        var dataTable = initSummaryDataTable(`${baseurl}/admin/expense/getexpancereport`, '#form1',
            '#ajaxlist');
        $('#form1').on('submit', function(e) {
            e.preventDefault();
            dataTable.ajax.reload();
        });
    });

    function renderSummaryTable(dataArray) {
        let count = 0;
        return (dataArray || []).map(function(row) {
            return [
                ++count,
                row.name || '-',
                row.invoice_no || '-',
                row.exp_category || '-',
                dateformate(row.date) || '-',
                row.amount ? "₹" + Number(row.amount).toFixed(2) : '-'
            ];
        });
    }


    function dateformate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }
</script>