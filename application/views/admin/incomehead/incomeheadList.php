<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php if ($this->module_lib->hasActive('income')) { ?>
                            <?php if ($this->rbac->hasPrivilege('income_head', 'can_view')) { ?>
                                <li><a class="active" href="<?php echo base_url(); ?>admin/incomehead"><?php echo $this->lang->line('income_head'); ?></a></li>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($this->module_lib->hasActive('expense')) { ?>
                            <?php if ($this->rbac->hasPrivilege('income_head', 'can_view')) { ?>
                                <li><a href="<?php echo base_url(); ?>admin/expensehead"><?php echo $this->lang->line('expense_head'); ?></a></li>
                            <?php } ?>
                        <?php } ?>

                    </ul>
                </div>
            </div>
            <?php if ($this->module_lib->hasActive('income')) { ?>
                <div class="col-md-10">
                    <!-- general form elements -->
                    <div class="box box-primary" id="exphead">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('income_head_list'); ?></h3>
                            <div class="box-tools pull-right">
                                <?php if ($this->rbac->hasPrivilege('income_head', 'can_add')) { ?>
                                    <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm income_head"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_income_head'); ?></a>
                                <?php } ?>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="download_label"><?php echo $this->lang->line('income_head_list'); ?></div>
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-striped table-bordered table-hover ajaxlist" id="ajaxlist">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th><?php echo $this->lang->line('income_head'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_income_head'); ?></h4>
            </div>
            <form id="incomehead" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="incomehead" name="incomehead" placeholder="" type="text" class="form-control" value="<?php echo set_value('incomehead'); ?>" />
                            <span class="text-danger"><?php echo form_error('incomehead'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?></textarea>
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>

                    </div>
                </div><!--./mpdal-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#incomehead').on('submit', function(e) {
            e.preventDefault();
            try {
                var formData = {
                    'income_category': $('input[name=incomehead]').val(),
                    'description': $('textarea[name=description]').val(),
                    "is_active": "yes",
                    "is_deleted": "no",
                    'hospital_id': '<?= $data['hospital_id'] ?>'
                };
                const accessToken = '<?= $data['accessToken'] ?? '' ?>';
                if (!accessToken) {
                    errorMsg("Access token missing. Please login again.");
                    return;
                }
                sendAjaxRequest('<?= $api_base_url ?>setup-finance-income-head', 'POST', formData, function(response) {
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
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_income_head'); ?></h4>
            </div>
            <form id="editformadd" action="<?php echo site_url('admin/incomehead/edit') ?>" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?></label><small class="req"> *</small>
                            <input id="incomehead1" name="incomehead" placeholder="" type="text" class="form-control" value="<?php echo set_value('incomehead'); ?>" />
                            <span class="text-danger"><?php echo form_error('incomehead'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description1" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?></textarea>
                            <input type="hidden" id="income_id" name="income_id">
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>

                    </div>
                </div><!--./modal-->
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<script>
    //editformadd 
    $(document).ready(function() {
        $('#editformadd').on('submit', function(e) {
            e.preventDefault();
            try {
                var formData = {
                    'income_category': $('#incomehead1').val(),
                    'description': $('#description1').val(),
                };
                var income_id = $('#income_id').val();
                sendAjaxRequest('<?= $api_base_url ?>setup-finance-income-head/' + income_id, 'PATCH', formData, function(response) {
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

<script>
    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/incomehead/get_data/' + id,
            success: function(result) {
                $('#income_id').val(result.id);
                $('#incomehead1').val(result.income_category);
                $('#description1').val(result.description);
            }

        });

    }
    $(".income_head").click(function() {
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
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordByIdReload('<?php echo $api_base_url; ?>setup-finance-income-head/key:id?Hospital_id=<?php echo $data['hospital_id']; ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
            <i class="fa fa-trash"></i>
        </a>
    `;
        initializeTable(initialData, initialDataTotal, `${base_url}admin/incomehead/getincomelist`, '#ajaxlist', [
                'sno', 'income_category', 'action'
            ],
            actionTemplate,
            'id'
        );
    });
</script>