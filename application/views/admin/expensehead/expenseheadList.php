<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php if ($this->module_lib->hasActive('income')) { ?>
                            <?php if ($this->rbac->hasPrivilege('income_head', 'can_view')) { ?>
                                <li><a href="<?php echo base_url(); ?>admin/incomehead"><?php echo $this->lang->line('income_head'); ?></a></li>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($this->module_lib->hasActive('expense')) { ?>
                            <?php if ($this->rbac->hasPrivilege('expense_head', 'can_view')) { ?>
                                <li><a href="<?php echo base_url(); ?>admin/expensehead" class="active"><?php echo $this->lang->line('expense_head'); ?></a></li>
                            <?php } ?>
                        <?php } ?>

                    </ul>
                </div>
            </div>
            <?php if ($this->module_lib->hasActive('expense')) { ?>
                <div class="col-md-10">
                    <!-- general form elements -->
                    <div class="box box-primary" id="exphead">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('expense_head_list'); ?></h3>
                            <div class="box-tools pull-right">
                                <?php if ($this->rbac->hasPrivilege('expense_head', 'can_add')) { ?>
                                    <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm expense_head"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_expense_head'); ?></a>
                                <?php } ?>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive mailbox-messages">
                                <div class="download_label"><?php echo $this->lang->line('expense_head_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover ajaxlist" id="ajaxlist">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th><?php echo $this->lang->line('expense_head'); ?></th>
                                            <th class="text-right no-print noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table><!-- /.table -->
                            </div><!-- /.mail-box-messages -->
                        </div><!-- /.box-body -->
                    </div>
                </div>
            <?php } ?>
            <!-- right column -->

        </div> <!-- /.row -->
    </section><!-- /.content -->
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_expense_head'); ?></h4>
            </div>
            <form id="formadd" action="<?php echo site_url('admin/expensehead/add') ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="expensehead" name="expensehead" placeholder="" type="text" class="form-control" value="<?php echo set_value('expensehead'); ?>" />
                            <span class="text-danger"><?php echo form_error('expensehead'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?></textarea>
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>
                    </div>
                </div><!--./modal-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_expense_head'); ?></h4>
            </div>
            <form id="editformadd" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="expensehead1" name="expensehead" placeholder="" type="text" class="form-control" value="<?php echo set_value('expensehead'); ?>" />
                            <span class="text-danger"><?php echo form_error('expensehead'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description1" name="description" placeholder="" rows="3"></textarea>
                            <input type="hidden" id="exphead_id" name="exphead_id">
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>
                    </div>
                </div><!--./modal-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="editformaddbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';


    function Popup(data) {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
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
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
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
    $("#print_div").click(function() {
        Popup($('#exphead').html());
    });
</script>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function() {
        $('#formadd').on('submit', function(e) {
            e.preventDefault();
            try {
                var formData = {
                    'exp_category': $('#expensehead').val(),
                    'description': $('#description').val(),
                    "is_active": "yes",
                    "is_deleted": "no",
                    "Hospital_id": <?= $data['hospital_id'] ?>
                };
                sendAjaxRequest('<?= $api_base_url ?>setup-finance-expense-head', 'POST', formData, function(response) {
                    handleResponse(response);
                });
            } catch (error) {
                console.error('AJAX Error:', error);
                const errMsg = error.responseJSON?.message || error.responseText ||
                    'An unexpected error occurred.';
                errorMsg(errMsg);
            }
        });
    });
</script>
<script>
    //editformadd 
    $(document).ready(function() {
        $('#editformadd').on('submit', function(e) {
            e.preventDefault();
            try {
                var formData = {
                    'exp_category': $('#expensehead1').val(),
                    'description': $('#description1').val(),
                    "is_active": "yes",
                    "Hospital_id": <?= $data['hospital_id'] ?>,
                    "is_deleted": "no",
                };
                console.log(JSON.stringify(formData));
                let id = $('#exphead_id').val();
                sendAjaxRequest('<?= $api_base_url ?>setup-finance-expense-head/' + id, 'PATCH', formData, function(response) {
                    handleResponse(response);
                });
            } catch (error) {
                console.error('AJAX Error:', error);
                const errMsg = error.responseJSON?.message || error.responseText ||
                    'An unexpected error occurred.';
                errorMsg(errMsg);
            }
        });
    });
</script>
<script>
    function get(id) {
        $('#editmyModal').modal();
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/expensehead/get_data/' + id,
            success: function(result) {
                $('#exphead_id').val(result.id);
                $('#expensehead1').val(result.exp_category);
                $('#description1').text(result.description);
            }
        });
    }

    $(".expense_head").click(function() {
        $('#formadd').trigger("reset");
    });

    $(document).ready(function(e) {
        $('#myModal,#editmyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>

<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function() {

        let actionTemplate = `
        <a data-target="#editmyModal" onclick="get(key:id)" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordByIdReload('<?php echo $api_base_url; ?>setup-finance-expense-head/key:id?Hospital_id=<?php echo $data['hospital_id']; ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
    `;
        initializeTable(initialData, initialDataTotal, `${base_url}admin/expensehead/getexpenselist`, '#ajaxlist', [
                'sno', 'exp_category', 'action'
            ],
            actionTemplate,
            'id'
        );
    });
</script>