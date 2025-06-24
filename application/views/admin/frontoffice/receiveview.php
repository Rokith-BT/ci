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
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('postal_receive'); ?> <?php echo $this->lang->line('list'); ?></h3>
                            <div class="box-tools pull-right">
                                    <?php if ($this->rbac->hasPrivilege('postal_receive', 'can_add')) {?>
                                        <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm receive"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_receive'); ?> </a>
                                    <?php }?>
                            </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('postal_receive_list'); ?></div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered ajaxlist" data-export-title="<?php echo $this->lang->line('postal_receive_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('from_title'); ?></th>
                                        <th><?php echo $this->lang->line('reference_no'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('to_title'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('address'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('note'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('date'); ?>
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

<div id="receviedetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_receive'); ?></h4>
            </div>
            <form id="formadd" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('from_title'); ?></label>   <small class="req"> *</small>
                                        <input type="text" class="form-control" value="<?php echo set_value('from_title'); ?>" name="from_title">
                                        <span class="text-danger"><?php echo form_error('from_title'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('reference_no'); ?></label>

                                        <input type="text" class="form-control" value="<?php echo set_value('ref_no'); ?>" name="ref_no">
                                        <span class="text-danger"><?php echo form_error('ref_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('address'); ?></label>
                                        <textarea class="form-control" id="description"  name="address" rows="3"><?php echo set_value('address'); ?></textarea>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('to_title'); ?></label>
                                        <input type="text" class="form-control" value="<?php echo set_value('to_title'); ?>"  name="to_title">
                                        <span class="text-danger"><?php echo form_error('to_title'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                        <input id="date" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date', date($this->customlib->getHospitalDateFormat())); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div><input class="filestyle form-control" type='file' name='file' id="attachment_report"  />
                                        </div>
                                        <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                </div>

                            </div>
                        </div>
                    </div><!-- /.modal-body -->
                </div>
                <div class="box-footer">
                    <button type="submit" id="formaddbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->

<div id="editmyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_receive'); ?></h4>
            </div>
            <form id="editformadd" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="ptt10">
                   <div class="scroll-area">
                        <div class="modal-body pb0 pt0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('from_title'); ?></label><small class="req"> *</small>
                                        <input type="text" id="from_title" class="form-control" value="<?php echo set_value('from_title'); ?>" name="from_title">
                                        <input type="hidden" name="id" id="id">
                                        <span class="text-danger"><?php echo form_error('from_title'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('reference_no'); ?></label>
                                        <input type="text" id="ref_no" class="form-control" value="<?php echo set_value('ref_no'); ?>" name="ref_no">
                                        <span class="text-danger"><?php echo form_error('ref_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('address'); ?></label>
                                        <textarea class="form-control" id="eaddress"  name="address" rows="3"><?php echo set_value('address'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea class="form-control" id="enote"  name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('to_title'); ?></label>
                                        <input type="text" id="to_title" class="form-control" value="<?php echo set_value('to_title'); ?>"  name="to_title">
                                        <span class="text-danger"><?php echo form_error('to_title'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                        <input id="edate" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date', date($this->customlib->getHospitalDateFormat())); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div>
                                            <input class="filestyle form-control" type='file' name='file'  id="attachment_report1"/>
                                            <input class="" type='hidden' name='old_file'  id="old_file"/>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                </div>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>" id="editformaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
      </div>
    </div>
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        $('#date').datepicker({

            format: date_format,
            autoclose: true
        });

        $('#edate').datepicker({

            format: date_format,
            autoclose: true
        });
    });

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        $('#date_of_call').datepicker({

            format: date_format,
            autoclose: true
        });
    });

    function getRecord(id) {

        $('#receviedetails').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/dispatch/details/' + id + '/receive',
            success: function (result) {

                $('#getdetails').html(result);
            }
        });
    }

    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/receive/get_receive/' + id,
            success: function (result) {                
                $('#from_title').val(result.from_title);
                $('#ref_no').val(result.reference_no);
                $('#ename').val(result.address);
                $('#to_title').val(result.to_title);
                $('#eedate').val(result.datedd);
                $('#eaddress').val(result.address);
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
            reference_no: jsonData.ref_no,
            to_title: jsonData.to_title,
            address: jsonData.address,
            note: jsonData.note,
            from_title: jsonData.from_title,
            date: formatDate(jsonData.date),
            image: '',
            hospital_id: <?=$data['hospital_id']?>
        };

        console.log(JSON.stringify(formattedData, null, 2));
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
                    submitForm1(formattedData);
                },
                error: function() {
                    formattedData.image = documentUrl;
                    submitForm1(formattedData);
                }
            });
        } else {
            formattedData.image = 'none.img';
            submitForm1(formattedData);
        }
    });
});

function formatDate(dateStr) {
    var parts = dateStr.split('/');
    var date = new Date(parts[2], parts[0] - 1, parts[1]);
    date.setDate(date.getDate() + 1);
    return date.toISOString().slice(0, 19).replace('T', ' ');
}


function submitForm1(data) {
    $.ajax({
        url: '<?=$api_base_url?>postal-receive-dispatch/postalReceive',
        type: "POST",
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function (response) {
            successMsg(response[0]['data '].messege);
            location.reload();
            $("#formaddbtn").button('reset');
        },
        error: function () {}
    });
}

// $(".receive").click(function(){
//     $('#formadd').trigger("reset");
// });


 $("#myModal").on('hidden.bs.modal', function (e) {
     $(".filestyle").next(".dropify-clear").trigger("click");
    
     $('form#formadd').find('input:text, input:password, input:file, textarea').val('');
     $('form#formadd').find('select option:selected').removeAttr('selected');
     $('form#formadd').find('input:checkbox, input:radio').removeAttr('checked');
 });



    $(document).ready(function (e) {
        $('#myModal,#receviedetails,#editmyModal').modal({
        backdrop: 'static',
        keyboard: false,
        show:false
        });
    });

    function delete_ById(id){
        if (confirm('<?php echo $this->lang->line('delete_confirm')?>')) {
         $.ajax({
            dataType: 'json',
            url: '<?=$api_base_url?>postal-receive-dispatch/removeFrontofficePostalReceive/' + id + '?hospital_id=<?=$data['hospital_id']?>',
            type: 'DELETE',
            success: function (result) {
                successMsg(result.message);
                table.ajax.reload();
            }
        });
     }
     } 
</script>
<script>
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
            reference_no: jsonData.ref_no,
            to_title: jsonData.to_title,
            address: jsonData.address,
            note: jsonData.note,
            from_title: jsonData.from_title,
            date: formatDate(jsonData.date),
            image: '',
            hospital_id: <?=$data['hospital_id']?>
        };

        console.log(JSON.stringify(formattedData, null, 2));
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
                    submitForm(formattedData);
                },
                error: function() {
                    formattedData.image = documentUrl;
                    submitForm(formattedData);
                }
            });
        } else {
            formattedData.image = $('#old_file').val();
            submitForm(formattedData);
        }
    });
});

function formatDate(dateStr) {
    var parts = dateStr.split('/');
    var date = new Date(parts[2], parts[0] - 1, parts[1]);
    date.setDate(date.getDate() + 1); // Add one day
    return date.toISOString().slice(0, 19).replace('T', ' ');
}


function submitForm(data) {
    let editid = $('#id').val();
    $.ajax({
        url: '<?=$api_base_url?>postal-receive-dispatch/updateReceive/' + editid,
        type: "PATCH",
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function (response) {
            successMsg(response[0]['data '].messege);
            location.reload();
            $("#formaddbtn").button('reset');
        },
        error: function () {}
    });
}
   
</script>
<!-- //========datatable start===== -->
 <script type="text/javascript">
( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('ajaxlist','admin/receive/getreceivedatatable');
    });
} ( jQuery ) )
</script> 
<!-- //========datatable end===== -->
