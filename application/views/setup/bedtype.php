<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <?php
                $this->load->view('setup/bedsidebar');
                ?>
            </div>
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('bed_type_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('bed_type', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addbed"><i
                                    class="fa fa-plus"></i> <?php echo $this->lang->line('add_bed_type'); ?></a>
                            <?php } ?>
                        </div><!-- /.box-tools -->
                    </div>

                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('bed_type_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('purpose'); ?></th>
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
            <!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_bed_type'); ?></h4>
            </div>


            <form id="addbedtype" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small
                                    class="req"> *</small>
                                <input id="invoice_no" name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />

                            </div>
                        </div>

                    </div>
                </div>
                <!--./box-body-->
                <div class="modal-footer">
                    <div class="pull-right ">
                        <button type="submit" id="addbedtypebtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_bed_type'); ?></h4>
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" id="edit_bedtypedata">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


</div>
</div>

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

function edit(id) {
    $('#editmyModal').modal('show');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/setup/bedtype/getdata/' + id,
        success: function(data) {
            $('#edit_bedtypedata').html(data);
        },

    });
}

$(".addbed").click(function() {
    $('#addbedtype').trigger("reset");
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
function delete_bedtype(id) {
    var confirmDelete = confirm("Are you sure you want to delete this bed type?");
    if (confirmDelete) {
        sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed-type/' + id + '?Hospital_id=<?= $data['hospital_id'] ?>',
            'DELETE',
            {},
            function(response) {
               handleResponse(response);
            },
        );
    }
}
</script>
<script>
    $(document).ready(function() {
        $('#addbedtype').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                'name': $('#invoice_no').val(),
                "Hospital_id": <?= $data['hospital_id'] ?>
            };
            if (!formData.name) {
                errorMsg('Please enter Name');
                return false;
            }
            sendAjaxRequest(
                '<?= $api_base_url ?>setup-bed-bed-type',
                'POST',
                formData,
                function(response) {
                    handleResponse(response);
                }
            );
        });
    });
</script>
<script>
const initialData = <?= json_encode($initialData) ?>;
const initialDataTotal = initialData.recordsTotal || initialData.length || 0;

$(document).ready(function() {
    let actionTemplate = `
            <a data-target="#editmyModal" onclick="edit(key:id)" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit">
                <i class="fa fa-pencil"></i>
            </a>
            <a class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_bedtype(key:id)" data-original-title="Delete">
                <i class="fa fa-trash"></i>
            </a>
        `;

    initializeTable(
        initialData,
        initialDataTotal,
        `${base_url}admin/setup/bedtype/getbedtype`,
        '#ajaxlist',
        ['sno', 'name', 'action'],
        actionTemplate,
        'id'
    );
});
</script>