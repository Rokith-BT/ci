<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');

?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('complain_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <div class="box-tools pull-right">
                                <?php if ($this->rbac->hasPrivilege('complain', 'can_add')) {?>
                                    <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm complain"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_complain'); ?> </a>
                                <?php }?>

                            </div>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered ajaxlist" data-export-title="<?php echo $this->lang->line('complain_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('complain'); ?> #
                                        </th>
                                        <th>
                                            <?php echo $this->lang->line('complain_type'); ?>
                                        </th>
                                        <th>
                                            <?php echo $this->lang->line('source'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('phone'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
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
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->
<div id="complaintdetails" class="modal fade" role="dialog">
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

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_complain'); ?></h4>
            </div>
            <form id="formadd" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('complain_type'); ?></label>
                                        <select name="complaint" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($complaint_type as $key => $value) {?>
                                                <option value="<?php print_r($value['id']);?>" <?php if (set_value('complaint') == $value['complaint_type']) {?>selected=""<?php }?>><?php print_r($value['complaint_type']);?></option>
                                            <?php }?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('complaint'); ?></span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="pwd"><?php echo $this->lang->line('source'); ?></label>
                                        <select name="source" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($complaintsource as $key => $value) {?>
                                                <option value="<?php echo $value['source']; ?>" <?php if (set_value('source') == $value['source']) {?>selected=""<?php }?>><?php echo $value['source']; ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('source'); ?></span>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('complain_by'); ?></label> <small class="req"> *</small>
                                        <input type="text" class="form-control" value="<?php echo set_value('name'); ?>"  name="name">
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('phone'); ?></label>
                                        <input type="text" class="form-control" value="<?php echo set_value('contact'); ?>"  name="contact">
                                        <span class="text-danger"><?php echo form_error('contact'); ?></span>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                            <input type="text" class="form-control" value="<?php echo set_value('date', date($this->customlib->getHospitalDateFormat())); ?>"  name="date" id="date" readonly>
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="description" name="description"rows="3"><?php echo set_value('description'); ?></textarea>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('action_taken'); ?></label>
                                        <input type="text" class="form-control" value="<?php echo set_value('action_taken'); ?>"  name="action_taken">
                                        <span class="text-danger"><?php echo form_error('action_taken'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('assigned'); ?></label>
                                        <input type="text" class="form-control" value="<?php echo set_value('assigned'); ?>"  name="assigned">
                                        <span class="text-danger"><?php echo form_error('assigned'); ?></span>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                        <span class="text-danger"><?php echo form_error('note'); ?></span>
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div>
                                            <input class="filestyle form-control" type='file' name='file' id="attachment_report" />
                                        </div>
                                        <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                </div>

                            </div><!-- /.box-body -->
                        </div>
                    </div><!--./modal-body-->
                </div>
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>" id="formaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editmyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('complain'); ?></h4>
            </div>
         <form id="editformadd" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="ptt10">    
            <div class="scroll-area">
                <div class="modal-body pb0 pt0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('complain_type'); ?></label>

                                        <select name="complaint" id="ecomplaint" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($complaint_type as $key => $value) {?>
                                                <option value="<?php print_r($value['id']);?>" <?php if (set_value('complaint') == $value['id']) {?>selected=""<?php }?>><?php print_r($value['complaint_type']);?></option>
                                            <?php }?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('complaint'); ?></span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <label for="pwd"><?php echo $this->lang->line('source'); ?></label>
                                        <select name="source" id="esource" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($complaintsource as $key => $value) {?>
                                                <option value="<?php echo $value['source']; ?>" <?php if (set_value('source') == $value['source']) {?>selected=""<?php }?>><?php echo $value['source']; ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('source'); ?></span>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('complain_by'); ?></label> <small class="req"> *</small>
                                        <input type="hidden" name="id" id="id">
                                        <input type="text" id="ename" class="form-control" value="<?php echo set_value('name'); ?>"  name="name">
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('phone'); ?></label>
                                        <input type="text" id="econtact" class="form-control" value="<?php echo set_value('contact'); ?>"  name="contact">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                            <input type="text"  class="form-control" value="<?php echo set_value('date', date($this->customlib->getHospitalDateFormat())); ?>"  name="date" id="edate" readonly>
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="edescription" name="description"rows="3"><?php echo set_value('description'); ?></textarea>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('action_taken'); ?></label>
                                        <input type="text" id="eaction_taken" class="form-control" value="<?php echo set_value('action_taken'); ?>"  name="action_taken">
                                        <span class="text-danger"><?php echo form_error('action_taken'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('assigned'); ?></label>
                                        <input type="text" id="eassigned" class="form-control" value="<?php echo set_value('assigned'); ?>"  name="assigned">
                                        <span class="text-danger"><?php echo form_error('assigned'); ?></span>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea class="form-control" id="enote" name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                        <span class="text-danger"><?php echo form_error('note'); ?></span>
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div>
                                            <input class="filestyle form-control" type='file' name='file' id="attachment_report1" />
                                            <input type="hidden" name="attachment_report" id="old_file"/>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                </div>
                            </div>
                </div><!-- /.box-body -->
            </div><!-- scroll-area> -->
            <div class="modal-footer">
                <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>" id="editformaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
            </div>
        </form>
      </div>
    </div>
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        $('#date').datepicker({
            //  format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true
        });

        $('#edate').datepicker({
            //  format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true
        });


    });

    function getRecord(id) {
        //alert(id);

        $('#complaintdetails').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/complaint/details/' + id,
            success: function (result) {
                //alert(result);
                $('#getdetails').html(result);
            }


        });
    }

    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/complaint/get_complaint/' + id,
            success: function (result) {
                $('#ecomplaint').val(result.complaint_type_id);
                $('#esource').val(result.source);
                $('#ename').val(result.name);
                $('#econtact').val(result.contact);
                $('#eedate').val(result.datedd);
                $('#edescription').val(result.description);
                $('#enote').val(result.note);
                $('#id').val(result.id);
                $('#eaction_taken').val(result.action_taken);
                $('#eassigned').val(result.assigned);
                $('#old_file').val(result.image);
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
            jsonData[key] = value;
        });

        var formattedData = {
            complaint_type_id:jsonData.complaint,
            source: jsonData.source,
            name: jsonData.name,
            contact: jsonData.contact,
            email: "",
            date: formatDate(jsonData.date),
            description: jsonData.description,
            action_taken: jsonData.action_taken,
            assigned: jsonData.assigned,
            note: jsonData.note,
            image: '',
            hospital_id: <?=$data['hospital_id']?>
        };

        // console.log(JSON.stringify(formattedData, null, 2));

        let fileInput = document.getElementById('attachment_report').files[0];
        var documentUrl = $('#filedataalread').val();

        if (fileInput) {
            var fileUploadData = new FormData();
            fileUploadData.append('file', fileInput);

            $.ajax({
                url: 'https://phr-api.plenome.com/file_upload',
                type: 'POST',
                data: fileUploadData,
                contentType: false,
                processData: false,
                success: function(response) {
                    formattedData.image = response.data;
                    submitForm(formattedData);
                },
                error: function() {
                    formattedData.image = documentUrl;
                    submitForm(formattedData);
                }
            });
        } else {
            formattedData.image = 'none.pdf';
            submitForm(formattedData);
        }
    });

    function formatDate(dateStr) {
    var parts = dateStr.split('/');
    var date = new Date(parts[2], parts[0] - 1, parts[1]);
    date.setDate(date.getDate() + 1);  // Add one day
    return date.toISOString().slice(0, 19).replace('T', ' ');
    }

    function submitForm(data) {
        console.log(JSON.stringify(data,null,2));
        $.ajax({
            url: '<?=$api_base_url?>frontoffice-complain',
            type: "POST",
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                successMsg(response[0]['data '].messege);
                location.reload();
                $("#formaddbtn").button('reset');
            },
            error: function () {
                console.error("Form submission failed.");
            }
        });
    }
});


$(document).ready(function (e) {
    $("#editformadd").on('submit', function (e) {
        $("#editformaddbtn").button('loading');
        e.preventDefault();

        var formData = new FormData(this);
        var jsonData = {};

        formData.forEach(function(value, key) {
            jsonData[key] = value;
        });

        var formattedData = {
            complaint_type_id: jsonData.complaint,
            source: jsonData.source,
            name: jsonData.name,
            contact: jsonData.contact,
            email: " ",
            date: formatDate(jsonData.date),
            description: jsonData.description,
            action_taken: jsonData.action_taken,
            assigned: jsonData.assigned,
            note: jsonData.note,
            image: jsonData.image || '',
            hospital_id: <?=$data['hospital_id']?>,
        };      

        let fileInput = document.getElementById('attachment_report1').files[0];
        var documentUrl = $('#filedataalread').val();

        if (fileInput) {
            var fileUploadData = new FormData();
            fileUploadData.append('file', fileInput);
            $.ajax({
                url: 'https://phr-api.plenome.com/file_upload',
                type: 'POST',
                data: fileUploadData,
                contentType: false,
                processData: false,
                success: function(response) {
                    formattedData.image = response.data;
                    submitForm1(formattedData);
                },
                error: function() {
                    formattedData.image = documentUrl;
                    submitForm1(formattedData);
                }
            });
        } else {
            formattedData.image = $("#old_file").val();
            submitForm1(formattedData);
        }
    });
});

function formatDate(dateStr) {
    var parts = dateStr.split('/');
    var date = new Date(parts[2], parts[0] - 1, parts[1]);
    date.setDate(date.getDate() + 1);  // Add one day
    return date.toISOString().slice(0, 19).replace('T', ' ');
}

function submitForm1(data) {
    let editid = $("#id").val();
    // console.log(JSON.stringify(data, null, 2));
    $.ajax({
        url: '<?=$api_base_url?>frontoffice-complain/' + editid,
        type: "PATCH",
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function (response) {
            successMsg((response[0]['data '].messege));
            location.reload();
            $("#editformaddbtn").button('reset');
        },
        error: function () {}
    });
}

 $("#myModal").on('hidden.bs.modal', function (e) {
     $(".filestyle").next(".dropify-clear").trigger("click");
    
     $('form#formadd').find('input:text, input:password, input:file, textarea').val('');
     $('form#formadd').find('select option:selected').removeAttr('selected');
     $('form#formadd').find('input:checkbox, input:radio').removeAttr('checked');
 });


function delete_record(id) {
    if (confirm('<?php echo $this->lang->line('delete_confirm') ?>')) {
        $.ajax({
            url: '<?=$api_base_url?>frontoffice-complain/removeFrontofficeComplaint/' + id + '?hospital_id=<?=$data['hospital_id']?>',
            type: 'DELETE',
            success: function (res) {
                successMsg('<?php echo $this->lang->line('delete_message'); ?>');
                window.location.reload(true);
            },
            error: function () {
                alert("Fail")
            }
        });
    }
}  

    $(document).ready(function (e) {
        $('#myModal,#complaintdetails,#editmyModal').modal({
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
        initDatatable('ajaxlist','admin/complaint/getcomplaintdatatable');
    });
} ( jQuery ) )
</script>
<!-- //========datatable end===== -->