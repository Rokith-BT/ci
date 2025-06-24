<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('payroll_month_report'); ?></h3>
                    </div>

                    <div class="">
                        <form id="form1">
                            <div class="box-body">
                                <div class="row">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('role'); ?></label>
                                            <select name="role" class="form-control">
                                                <option value="" selected>All</option>
                                                <?php foreach ($role as $rolekey => $rolevalue) { ?>
                                                <option <?php
                                                    if ($rolevalue["type"] == $role_select) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $rolevalue["id"] ?>">
                                                    <?php echo $rolevalue["type"]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('role'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('month'); ?></label>
                                            <select name="month" class="form-control">
                                                <option value="" selected>All</option>
                                                <?php foreach ($monthlist as $monthkey => $monthvalue) { ?>
                                                <option <?php
                                                    if ($month == $monthvalue) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $monthvalue ?>">
                                                    <?php echo $this->lang->line(strtolower($monthvalue)); ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('month'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('year'); ?></label><small class="req">
                                                *</small>
                                            <?php
                                            if (isset($year)) {
                                                $selected_year = $year;
                                            } else {
                                                $selected_year = date('Y');
                                            }
                                            ?>
                                            <select name="year" class="form-control">
                                                <!-- <option value="" selected>All</option> -->
                                                <?php foreach ($yearlist as $yearkey => $yearvalue) { ?>
                                                <option <?php
                                                    if (($yearvalue["year"] == $selected_year)) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $yearvalue["year"]; ?>">
                                                    <?php echo $yearvalue["year"]; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('year'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" name="search" value="search_filter"
                                            class="btn btn-primary btn-sm checkbox-toggle pull-right"><i
                                                class="fa fa-search"></i>
                                            <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="ptbnull"></div>
                        <div class="box border0 clear">
                            <div class="box-body">
                                <div class="tab-content">
                                    <div class="" id="tab_parent">
                                        <div class="download_label">
                                            <?php echo $this->lang->line('payroll_month_report_for'); ?>
                                            <?php echo $month . " " . $year; ?></div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th><?php echo $this->lang->line('role'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('designation'); ?></th>
                                                        <th><?php echo $this->lang->line('month_year'); ?></th>
                                                        <th class="text text-right">
                                                            <?php echo $this->lang->line('earning'); ?>
                                                            <span><?php echo "(" . $currency_symbol . ")"; ?></span>
                                                        </th>
                                                        <th class="text text-right">
                                                            <?php echo $this->lang->line('deduction'); ?>
                                                            <span><?php echo "(" . $currency_symbol . ")"; ?></span>
                                                        </th>
                                                        <th class="text text-right">
                                                            <?php echo $this->lang->line('tax'); ?>
                                                            <span><?php echo "(" . $currency_symbol . ")"; ?></span>
                                                        </th>
                                                        <th class="text text-right">
                                                            <?php echo $this->lang->line('net_salary'); ?>
                                                            <span><?php echo "(" . $currency_symbol . ")"; ?></span>
                                                        </th>
                                                    </tr>
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
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $.extend($.fn.dataTable.defaults, {
        ordering: false,
        paging: false,
        bSort: false,
        info: false
    });
})
$(document).ready(function() {
    $('.table-fixed-header').fixedHeader();
});

(function($) {

    $.fn.fixedHeader = function(options) {
        var config = {
            topOffset: 50

        };
        if (options) {
            $.extend(config, options);
        }

        return this.each(function() {
            var o = $(this);

            var $win = $(window);
            var $head = $('thead.header', o);
            var isFixed = 0;
            var headTop = $head.length && $head.offset().top - config.topOffset;

            function processScroll() {
                if (!o.is(':visible')) {
                    return;
                }
                if ($('thead.header-copy').size()) {
                    $('thead.header-copy').width($('thead.header').width());
                }
                var i;
                var scrollTop = $win.scrollTop();
                var t = $head.length && $head.offset().top - config.topOffset;
                if (!isFixed && headTop !== t) {
                    headTop = t;
                }
                if (scrollTop >= headTop && !isFixed) {
                    isFixed = 1;
                } else if (scrollTop <= headTop && isFixed) {
                    isFixed = 0;
                }
                isFixed ? $('thead.header-copy', o).offset({
                    left: $head.offset().left
                }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
            }
            $win.on('scroll', processScroll);
            $head.on('click', function() {
                if (!isFixed) {
                    setTimeout(function() {
                        $win.scrollTop($win.scrollTop() - 47);
                    }, 10);
                }
            });

            $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
            var header_width = $head.width();
            o.find('thead.header-copy').width(header_width);
            o.find('thead.header > tr:first > th').each(function(i, h) {
                var w = $(h).width();
                o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
            });
            $head.css({
                margin: '0 auto',
                width: o.width(),
                'background-color': config.bgColor
            });
            processScroll();
        });
    };

})(jQuery);
</script>
<script type="text/javascript">
$(document).ready(function() {
    var date_format =
        '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
    $(".date").datepicker({
        format: date_format,
        autoclose: true,
        todayHighlight: true
    });
    $('.detail_popover').popover({
        placement: 'right',
        title: '',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {
            return $(this).closest('td').find('.fee_detail_popover').html();
        }
    });
});
</script>
<script>
$(document).ready(function() {
    let baseurl = '<?= base_url() ?>';
    var dataTable = initSummaryDataTable(`${baseurl}/admin/payroll/getpayrollreport`, '#form1',
        '#ajaxlist');
    $('#form1').on('submit', function(e) {
        e.preventDefault();
        dataTable.ajax.reload();
    });
});
function renderSummaryTable(dataArray) {
    let count = 0;
    return (dataArray || []).map(function(row) {
        let total_allowance = parseFloat(row.total_allowance || 0);
        let total_deduction = parseFloat(row.total_deduction || 0);
        let net_salary = parseFloat(row.net_salary || 0);
        let tax = parseFloat(row.tax || 0);

        let gross_salary = total_allowance - total_deduction;
        let taxamount = ((gross_salary * tax) / 100).toFixed(2);
        return [
            ++count,
            row.user_type || '-',
            row.name || '-',
            row.designation || '-',
            (row.month && row.year) ?
            `${row.month.charAt(0).toUpperCase()}${row.month.slice(1).toLowerCase()} ${row.year}` : '-',
            formatRupees(total_allowance),
            formatRupees(total_deduction),
            formatRupees(taxamount),
            formatRupees(net_salary)
        ];
    });
}

function dateformate(dateStr) {
    const [year, month, day] = dateStr.split('-');
    return `${day}-${month}-${year}`;
}
</script>