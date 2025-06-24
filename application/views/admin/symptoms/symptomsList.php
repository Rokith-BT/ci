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
                        if ($this->rbac->hasPrivilege('symptoms_head', 'can_view')) { ?>
                        <li><a class="<?php echo set_sidebar_Submenu('setup/symptoms/symptoms_head'); ?>"
                                href="<?php echo base_url(); ?>admin/symptoms"><?php echo $this->lang->line('symptoms_head'); ?></a>
                        </li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('symptoms_type', 'can_view')) { ?>
                        <li><a class="<?php echo set_sidebar_Submenu('setup/symptoms/symptoms_type'); ?>"
                                href="<?php echo base_url(); ?>admin/symptoms/symptomstype"><?php echo $this->lang->line('symptoms_type'); ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('symptoms_head_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('symptoms_head', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal"
                                class="btn btn-primary btn-sm symptoms_head"><i class="fa fa-plus"></i>
                                <?php echo $this->lang->line('add_symptoms_head'); ?></a>
                            <?php } ?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('symptoms_head_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('symptoms_head'); ?></th>
                                        <th><?php echo $this->lang->line('symptoms_type'); ?></th>
                                        <th><?php echo $this->lang->line('symptoms_description'); ?></th>
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_symptoms_head'); ?></h4>
            </div>
            <form id="symptomsheadadd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label
                                for="exampleInputEmail1"><?php echo $this->lang->line('symptoms_head'); ?></label><small
                                class="req"> *</small>
                            <input autofocus="" id="symptoms_title" name="symptoms_title" placeholder="" type="text"
                                class="form-control" value="<?php echo set_value('symptoms'); ?>" />
                            <span class="text-danger"><?php echo form_error('symptoms'); ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('symptoms_type'); ?></label>
                            <small class="req"> *</small>
                            <select name="type" onchange="" class="form-control" id="type">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($symptomsresulttype as $value) {
                                                ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['symptoms_type']; ?>
                                </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('type'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description" name="description" placeholder=""
                                rows="3"><?php echo set_value('description'); ?></textarea>
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
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





<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_symptoms_head'); ?></h4>
            </div>
            <form id="editformadd" action="<?php echo site_url('admin/symptoms/edit') ?>" name="employeeform"
                method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label
                                for="exampleInputEmail1"><?php echo $this->lang->line('symptoms_head'); ?></label><small
                                class="req"> *</small>
                            <input id="symptomstitle" name="symptoms_title" placeholder="" type="text"
                                class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('symptoms'); ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('symptoms_type'); ?></label>
                            <small class="req"> *</small>
                            <select name="type" id="symtype" onchange="" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($symptomsresulttype as $value) {
                                                ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['symptoms_type']; ?>
                                </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description_edit" name="description" placeholder=""
                                rows="3"><?php echo set_value('description'); ?></textarea>
                            <input type="hidden" id="symptoms_id" name="symptoms_id">
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
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
            var formData = [];
            var errors = [];
            var title = $('#symptomstitle').val();
            var type = $('#symtype').val();
            var description = $('#description_edit').val();
            var titlePattern = /^(?![\s]+$)(?![^a-zA-Z0-9\s\-_,\.:]+$).+$/;
            if (!title || !titlePattern.test(title)) {
                errors.push('Please enter a valid title. Allowed: letters, numbers, spaces, and -_,.:');
            }
            if (!type) {
                errors.push('Type is required.');
            }
            if (errors.length > 0) {
                errors.forEach(function(error) {
                    errorMsg(error);
                });
                return;
            }
            formData.push({
                "symptoms_title": title,
                "type": type,
                "description": description,
                "Hospital_id": '<?=$data["hospital_id"]?>',
            });
            var symptoms_id = $('#symptoms_id').val();
            sendAjaxRequest('<?=$api_base_url?>setup-symptoms-symptoms-head/' + symptoms_id, 'PATCH', formData[0], function(
            response) {
                handleResponse(response);
            });
           
        } catch (err) {
            errorMsg('Unexpected error: ' + err.message);
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $('#symptomsheadadd').on('submit', function(e) {
        e.preventDefault();
        try {
            let formData = {};
            let missingFields = [];
            let requiredFields = [{
                    key: "symptoms_title",
                    label: "Symptoms Title",
                    value: $('#symptoms_title').val().trim()
                },
                {
                    key: "type",
                    label: "Type",
                    value: $('#type').val().trim()
                }
            ];

            for (let field of requiredFields) {
                if (!field.value) {
                    missingFields.push(field.label);
                } else {
                    formData[field.key] = field.value;
                }
            }

            if (missingFields.length > 0) {
                errorMsg(`The following fields are required: ${missingFields.join(', ')}`);
                return;
            }
            formData["description"] = $('#description').val().trim();
            formData["Hospital_id"] = '<?=$data["hospital_id"]?>';
            sendAjaxRequest('<?=$api_base_url?>setup-symptoms-symptoms-head', 'POST', formData, function(response) {
                handleResponse(response);
            });
        } catch (err) {
            errorMsg('Unexpected error: ' + err.message);
        }
    });
});
</script>
<script>
const initialData = <?= json_encode($initialData) ?>;
const initialDataTotal = initialData.recordsTotal || initialData.length || 0;

$(document).ready(function() {
    let actionTemplate = `
        <a data-target="#editmyModal" onclick="get(key:id)" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit">
            <i class="fa fa-pencil"></i>
        </a>
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete" onclick="delete_recordByIdReload('<?=$api_base_url?>setup-symptoms-symptoms-head/key:id?Hospital_id=<?=$data["hospital_id"]?>', 'Delete Confirm?')">
            <i class="fa fa-trash"></i>
        </a>
    `;

    initializeTable(
        initialData,
        initialDataTotal,
        `${base_url}admin/symptoms/getSymptomshead`,
        '#ajaxlist',
        ['sno', 'symptoms_head', 'symptoms_type', 'description', 'action'],
        actionTemplate,
        'id'
    );
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
        url: '<?php echo base_url(); ?>admin/symptoms/get_data/' + id,
        success: function(result) {
            console.log(result.symptoms_type)
            $('#symptoms_id').val(result.id);
            $('#symptomstitle').val(result.symptoms_title);
            $('#symtype').val(result.type);
            $('#description_edit').val(result.description);
        }
    });
}



$(".symptoms_head").click(function() {
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