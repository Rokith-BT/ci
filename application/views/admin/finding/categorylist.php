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
                        <?php
                        if ($this->rbac->hasPrivilege('finding', 'can_view')) { ?>
                        <li><a class="<?php echo set_sidebar_Submenu('setup/finding'); ?>"
                                href="<?php echo base_url(); ?>admin/finding"><?php echo $this->lang->line('finding'); ?></a>
                        </li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('finding_category', 'can_view')) { ?>
                        <li><a class="<?php echo set_sidebar_Submenu('setup/finding/category'); ?>"
                                href="<?php echo base_url(); ?>admin/finding/category"><?php echo $this->lang->line('category'); ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('finding_category_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('finding_category', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm finding_head"><i
                                    class="fa fa-plus"></i> <?php echo $this->lang->line('add_finding_category'); ?></a>
                            <?php } ?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('finding_category_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover ajaxlist" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
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
            <!-- right column -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_finding_category'); ?></h4>
            </div>
            <form id="findingcategorylistadd" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label
                                for="exampleInputEmail1"><?php echo $this->lang->line('finding_category'); ?></label><small
                                class="req"> *</small>
                            <input autofocus="" id="finding_category" name="finding_category" placeholder="" type="text"
                                class="form-control" value="<?php echo set_value('finding_type'); ?>" />
                            <span class="text-danger"><?php echo form_error('finding_category'); ?></span>
                        </div>
                    </div>
                </div>
                <!--./mpdal-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        id="formaddbtn"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
        <!--./row-->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#findingcategorylistadd').on('submit', function(e) {
        e.preventDefault();
        try {
            var category = $('input[name="finding_category"]').val();
            if (!category) {
                errorMs('Finding Category is required');
                return;
            }
            var formData = {
                category: category,
                Hospital_id: '<?=$data['hospital_id']?>'
            };
            sendAjaxRequest('<?= $api_base_url ?>findings_category', 'POST', formData, function(
                response) {
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
                <h4 class="modal-title"><?php echo $this->lang->line('edit_finding_category'); ?></h4>
            </div>
            <form id="editformadd" name="employeeform" method="post" accept-charset="utf-8"
                enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label
                                for="exampleInputEmail1"><?php echo $this->lang->line('finding_category'); ?></label><small
                                class="req"> *</small>
                            <input id="findingcategory" name="finding_category" placeholder="" type="text"
                                class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('finding_category'); ?></span>
                            <input type="hidden" id="finding_id" name="finding_id">
                        </div>
                    </div>
                </div>
                <!--./modal-->
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
        <!--./row-->
    </div>
</div>
<script>
$(document).ready(function() {
    $('#editformadd').on('submit', function(e) {
        e.preventDefault();
        try {
            var category = $('#findingcategory').val();
            if (!category) {
                alert('Finding Category is required');
                return;
            }
            var formData = {
                category: category,
                Hospital_id: <?=$data['hospital_id']?>
            };
            let id = $('#finding_id').val();
            const accessToken = '<?= $data['accessToken'] ?? '' ?>';
            if (!accessToken) {
                errorMsg("Access token missing. Please login again.");
                return;
            }
            sendAjaxRequest('<?=$api_base_url?>findings_category/' + id, 'PATCH', formData, function(
                response) {
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

$("#print_div").click(function() {
    Popup($('#exphead').html());
});
</script>
<script>
function get(id) {
    $('#editmyModal').modal('show');
    $.ajax({
        dataType: 'Json',
        url: '<?php echo base_url(); ?>admin/finding/getfindingcategory/' + id,
        success: function(result) {
            $('#finding_id').val(result.id);
            $('#findingcategory').val(result.category);
        }
    });
}
$(".finding_head").click(function() {
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
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordByIdReload('<?=$api_base_url?>findings_category/key:id?Hospital_id=<?php echo $data['hospital_id']; ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
    `;
    initializeTable(initialData, initialDataTotal, `${base_url}admin/finding/getfindingcategorylist`,
        '#ajaxlist', [
            'sno', 'category', 'action'
        ],
        actionTemplate,
        'id'
    );
});
</script>