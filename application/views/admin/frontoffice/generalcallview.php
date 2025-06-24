<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');

?>
<div class="content-wrapper">
    <?php $call_type = $this->customlib->getCalltype();?> 
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12"> 
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('phone_call_log_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('phone_call_log', 'can_add')) {?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm call_log"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_call_log'); ?> </a>
                            <?php }?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('phone_call_log'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered ajaxlist" data-export-title="<?php echo $this->lang->line('phone_call_log'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('phone'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('date'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('next_follow_up_date'); ?></th>
                                        <th><?php echo $this->lang->line('call_type'); ?>
                                        </th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div> 
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->
<div id="calldetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>
            </div>
            <div class="modal-body" id="getdetails">


            </div>
        </div>
    </div>
</div>
</div><!-- /.content-wrapper -->
<div id="editmyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_call_log'); ?> </h4>
            </div>
            <form id="editformadd" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('name'); ?></label>  <small class="req"> *</small>
                                        <input type="text" id="ename" class="form-control" value="<?php echo set_value('name'); ?>" name="name">
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                                        <input type="text" class="form-control" id="econtact" value="<?php echo set_value('contact'); ?>" name="contact">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                        <input id="edate" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date', date($this->customlib->getHospitalDateFormat())); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="edescription" name="description"  rows="3"><?php echo set_value('description'); ?></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('next_follow_up_date'); ?></label><input id="efollow_up_date" name="follow_up_date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('follow_up_date'); ?>" readonly="readonly" />
                                            <span class="text-danger"><?php echo form_error('follow_up_date'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('call_duration'); ?></label>
                                        <input id="ecall_dureation" type="text" class="form-control" value="<?php echo set_value('call_dureation'); ?>" name="call_dureation">
                                        <span class="text-danger"><?php echo form_error('call_dureation'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea class="form-control" id="enote" name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                        <input type="hidden" name="id" id="generalcall_id" >
                                        <span class="text-danger"><?php echo form_error('note'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('call_type'); ?></label>

                                        <small class="req"> *</small>
                                        <?php
                                        $n = 1;
                                        foreach ($call_type as $key => $value) {
                                            ?>
                                            <label class="radio-inline"><input type="radio" id="<?php echo $n++; ?>" name="call_type" value="<?php echo $key; ?>" <?php if (set_value('call_type') == $key) {?> checked=""<?php }?>> <?php echo $value; ?></label>

                                        <?php }?>

                                        <span class="text-danger"><?php echo form_error('call_type'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div>    
                </div>
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_call_log'); ?></h4>
            </div>
            <form id="formadd" method="post" accept-charset="utf-8" enctype="multipart/form-data" >
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">

                        <div class="ptt10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('name'); ?></label>  <small class="req"> *</small>
                                        <input type="text" class="form-control" value="<?php echo set_value('name'); ?>" name="name">
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                                        <input type="text" class="form-control" value="<?php echo set_value('contact'); ?>" name="contact">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                        <input id="date" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date', date($this->customlib->getHospitalDateFormat())); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="description" name="description"  rows="3"><?php echo set_value('description'); ?></textarea>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('next_follow_up_date'); ?></label>     <input id="follow_up_date" name="follow_up_date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('follow_up_date'); ?>" readonly="readonly" />
                                            <span class="text-danger"><?php echo form_error('follow_up_date'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('call_duration'); ?></label>
                                        <input type="text" class="form-control" value="<?php echo set_value('call_dureation'); ?>" name="call_dureation">
                                        <span class="text-danger"><?php echo form_error('call_dureation'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                        <span class="text-danger"><?php echo form_error('note'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('call_type'); ?></label>

                                        <small class="req"> *</small>

                                        <?php foreach ($call_type as $key => $value) {?>
                                            <label class="radio-inline"><input type="radio" name="call_type" value="<?php echo $key; ?>" <?php if (set_value('call_type') == $key) {?> checked=""<?php }?>> <?php echo $value; ?></label>

                                        <?php }?>

                                        <span class="text-danger"><?php echo form_error('call_type'); ?></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- /.box-body -->
                </div>
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>" id="formaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        $('#date').datepicker({

            format: date_format,
            autoclose: true
        });
    });

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        $('#follow_up_date').datepicker({

            format: date_format,
            autoclose: true
        });

        $('#efollow_up_date').datepicker({
            format: date_format,
            autoclose: true
        });

    });

    function getRecord(id) {
        $('#calldetails').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/generalcall/details/' + id,
            success: function (result) {

                $('#getdetails').html(result);
            }
        });
    }
    function delete_ById(id){
        if (confirm('<?php echo $this->lang->line('delete_confirm')?>')) {
         $.ajax({
            dataType: 'json',
            url: '<?=$api_base_url?>phone-call-log/removeFrontofficeCallLogs/' + id+'?hospital_id=<?=$data['hospital_id']?>',
            type: 'DELETE',
            success: function (result) {
                successMsg(result.message);
                table.ajax.reload();
            }
        });
     }
     } 
    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/generalcall/get_calls/' + id,
            success: function (result) {

                if (result.call_type == "Incoming") {
                    $('#1').prop("checked", true);
                } else {
                    $('#2').prop("checked", true);
                }

                $('#efollow_up_date').val(result.follow_up_date);
                $('#epurpose').val(result.purpose);
                $('#ename').val(result.name);
                $('#econtact').val(result.contact);
                $('#eedate').val(result.date);
                $('#edescription').val(result.description);
                $('#enote').val(result.note);
                $('#generalcall_id').val(result.id);
                $('#ecall_dureation').val(result.call_duration);

            }

        });
    }


    $(document).ready(function (e) {
    $("#formadd").on('submit', function (e) {
        $("#formaddbtn").button('loading');
        e.preventDefault();
        
        var formData = new FormData(this);
        var jsonData = {};

        formData.forEach(function(value, key) {
            switch (key) {
                case 'date':
                case 'follow_up_date':
                    jsonData[key] = formatDate(value);
                    break;
                case 'call_dureation':
                    jsonData['call_duration'] = value + ' mins';
                    break;
                default:
                    jsonData[key] = value;
                    break;
            }
        });

        jsonData['hospital_id'] = <?=$data['hospital_id']?>;
        // console.log(JSON.stringify(jsonData, null, 2));

        $.ajax({
            url: '<?=$api_base_url?>phone-call-log',
            type: "POST",
            data: JSON.stringify(jsonData),
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            success: function (response) {
                successMsg(response[0]['data '].messege);
                location.reload();
                $("#formaddbtn").button('reset');
            },
            error: function () {}
        });
    });

    function formatDate(dateStr) {
        var parts = dateStr.split('/');
        var date = new Date(parts[2], parts[0] - 1, parts[1]);
        date.setDate(date.getDate() + 1);
        return date.toISOString().slice(0, 19).replace('T', ' ');
    }

});


$(document).ready(function () {
    $("#editformadd").on('submit', function (e) {
        $("#editformaddbtn").button('loading');
        e.preventDefault();

        var formData = new FormData(this);
        var jsonData = {};

        formData.forEach(function(value, key) {
            jsonData[key] = value;
        });

        var formattedData = {
            name: jsonData.name,
            contact: jsonData.contact,
            date: formatDate(jsonData.date),
            description: jsonData.description,
            follow_up_date: formatDate(jsonData.follow_up_date),
            call_duration: jsonData.call_dureation,
            note: jsonData.note,
            call_type: jsonData.call_type,
            hospital_id: <?=$data['hospital_id']?>
        };

        // console.log(JSON.stringify(formattedData, null, 2));
        let editId = $('#generalcall_id').val();
        
        $.ajax({
            url: '<?=$api_base_url?>phone-call-log/' + editId,
            type: "PATCH",
            data: JSON.stringify(formattedData),
            dataType: 'json',
            contentType: 'application/json',
            success: function (response) {
                successMsg(response[0]['data '].messege);
                location.reload();
                $("#editformaddbtn").button('reset');
            },
            error: function (xhr, status, error) {
                errorMsg("Error: " + xhr.responseText);
                $("#editformaddbtn").button('reset');
            }
        });
    });

    function formatDate(dateStr) {
        var parts = dateStr.split('/');
        var date = new Date(parts[2], parts[0] - 1, parts[1]);
        date.setDate(date.getDate() + 1);
        return date.toISOString().slice(0, 19).replace('T', ' ');
    }

});




$(".call_log").click(function(){
    $('#formadd').trigger("reset");
});

    $(document).ready(function (e) {
        $('#myModal,#editmyModal,#calldetails').modal({
            backdrop: 'static',
            keyboard: false,
            show:false
        });
    });
</script>

<!-- //========datatable start===== -->
<script type="text/javascript">
( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('ajaxlist','admin/generalcall/getgeneralcalldatatable');
    });
} ( jQuery ) )
</script>