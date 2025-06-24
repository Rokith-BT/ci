<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>     <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('patient_id_card', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_patient_id_card'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form  enctype="multipart/form-data"  id="certificateform" name="certificateform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                     ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('background_image'); ?></label>
                                    <input id="documents" placeholder="" type="file" class="filestyle form-control" data-height="40" name="background_image">
                                     <span class="text-danger"><?php echo form_error('background_image'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('logo'); ?></label>
                                    <input id="logo_img" placeholder="" type="file" class="filestyle form-control" data-height="40" name="logo_img">
                                    <span class="text-danger"><?php echo form_error('logo_img'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('signature'); ?></label>
                                    <input id="sign_image" placeholder="" type="file" class="filestyle form-control" data-height="40" name="sign_image">
                                    <span class="text-danger"><?php echo form_error('sign_image'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line("hospital_name"); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="hospital_name" name="hospital_name" placeholder="" type="text" value="<?php echo set_value('hospital_name'); ?>" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('hospital_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('address_phone_email'); ?></label><small class="req"> *</small>
                                    <textarea class="form-control" id="address" name="address" placeholder="" rows="3" ><?php echo set_value('address'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line("patient_id_card_title"); ?></label><small class="req"> *</small>
                                    <input id="title" name="title" placeholder="" value="<?php echo set_value('title'); ?>" type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_color'); ?></label>
                                    <input id="header_color" name="header_color" value="<?php echo set_value('header_color'); ?>" placeholder="" type="text" class="form-control my-colorpicker1" />
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('patient_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_patient_name" name="is_active_patient_name" type="checkbox" class="chk" value="1">
                                        <label for="enable_patient_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('patient_id'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_patient_unique_id" name="is_active_patient_unique_id" type="checkbox" class="chk" value="1">
                                        <label for="enable_patient_unique_id" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('guardian_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_guardian_name" name="is_active_guardian_name" type="checkbox" class="chk" value="1">
                                        <label for="enable_guardian_name" class="label-success"></label>
                                    </div>
                                </div>
                               
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('patient_address'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_address" name="is_active_address" type="checkbox" class="chk" value="1">
                                        <label for="enable_address" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('phone'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_phone" name="is_active_phone" type="checkbox" class="chk" value="1">
                                        <label for="enable_phone" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('date_of_birth'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_dob" name="is_active_dob" type="checkbox" class="chk" value="1">
                                        <label for="enable_dob" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('blood_group'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_blood_group" name="is_active_blood_group" type="checkbox" class="chk" value="1">
                                        <label for="enable_blood_group" class="label-success"></label>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('patient_id_card', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary" id="hroom">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line("patient_id_card_list"); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line("patient_id_card_list"); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('id_card_title'); ?></th>
                                        <th><?php echo $this->lang->line('background_image'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($idcardlist)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($idcardlist as $idcard) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a style="cursor: pointer;" id="<?php echo $idcard->id ?>" data-toggle="popover" class="detail_popover view_data" ><?php echo $idcard->title; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                <?php if ($idcard->background != '' && !is_null($idcard->background)) { 
                                                    $userdata = $this->session->userdata('hospitaladmin');
                                                    $accessToken = $userdata['accessToken'] ?? '';

                                                        $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                        $client = curl_init($url);
                                                        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($client, CURLOPT_POST, true);
                                                        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $idcard->background]));
                                                        curl_setopt($client, CURLOPT_HTTPHEADER, [
                                                            'Content-Type: application/json',
                                                            'Authorization: ' . $accessToken
                                                        ]);
                                                        $response = curl_exec($client);
                                                        if (curl_errno($client)) {
                                                            error_log('Curl error: ' . curl_error($client));
                                                        }
                                                        curl_close($client);
                                                        if ($response !== false) {
                                                            $base64Image = "data:image/png;base64," . trim($response);
                                                        } else {
                                                            $base64Image = "base_url('uploads/patient_images/no_image.png'.img_time())";
                                                        }
                                                    ?>
                                                        <img src="<?php echo $base64Image; ?>" width="40">
                                                    <?php } else { ?>
                                                        <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                    <?php } ?>


                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <a id="<?php echo $idcard->id ?>" class="btn btn-default btn-xs view_data"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a>
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('patient_id_card', 'can_edit')) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/patientidcard/edit/<?php echo $idcard->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('patient_id_card', 'can_delete')) {
                                                        ?>
                                                        <a class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_record(<?=$idcard->id?>)">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('view_id_card'); ?></h4>
            </div>
            <div class="modal-body" id="certificate_detail">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        Popup(jQuery(elem).html());
    }

    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
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
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }
</script>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $("#header_color").colorpicker();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.view_data').click(function () {
            var certificateid = $(this).attr("id");
            $.ajax({
                url: "<?php echo base_url('admin/patientidcard/view') ?>",
                method: "post",
                data: {certificateid: certificateid},
                success: function (data) {
                    $('#certificate_detail').html(data);
                    $('#myModal').modal("show");
                }
            });
        });
    });
</script>
<script type="text/javascript">
    function valueChanged()
    {
        if ($('#enable_patient_img').is(":checked"))
            $("#enableImageDiv").show();       
        else
            $("#enableImageDiv").hide();       
    }
    
    $(document).ready(function (e) {
        $('#myModal').modal({
        backdrop: 'static',
        keyboard: false,
        show:false
        });
    }); 
</script>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $("#certificateform").submit(async function (e) {
        e.preventDefault();

        var finalData = {
            "title": $("#title").val(),
            "hospital_name": $("#hospital_name").val(),
            "hospital_address": $("#address").val(),
            "header_color": $("#header_color").val(),
            "enable_patient_name": $("#enable_patient_name").is(":checked") ? 1 : 0,
            "enable_guardian_name": $("#enable_guardian_name").is(":checked") ? 1 : 0,
            "enable_patient_unique_id": $("#enable_patient_unique_id").is(":checked") ? 1 : 0,
            "enable_address": $("#enable_address").is(":checked") ? 1 : 0,
            "enable_phone": $("#enable_phone").is(":checked") ? 1 : 0,
            "enable_dob": $("#enable_dob").is(":checked") ? 1 : 0,
            "enable_blood_group": $("#enable_blood_group").is(":checked") ? 1 : 0,
            "status": 1,
            "hospital_id": <?=$data['hospital_id']?>
        };

        let background_image = document.getElementById('documents').files[0];
        let logo_image = document.getElementById('logo_img').files[0];
        let sign_image = document.getElementById('sign_image').files[0];

        if (background_image) finalData.background = await uploadImage(background_image);
        if (logo_image) finalData.logo = await uploadImage(logo_image);
        if (sign_image) finalData.sign_image = await uploadImage(sign_image);

        console.log(JSON.stringify(finalData, null, 2));
        $.ajax({
            url: "<?=$api_base_url?>patient-id-card",
            method: "post",
            data: JSON.stringify(finalData, null, 2),
            contentType: "application/json",
            dataType: "json",         
            success: function (data) {
                if (data.status == "failed") {
                   error(data.message);
                } else {
                    successMsg(data[0]['data '].messege);
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }
        });

        async function uploadImage(file) {
            let fileUploadData = new FormData();
            fileUploadData.append('file', file);
            try {
                let response = await $.ajax({
                    url: 'https://phr-api.plenome.com/file_upload',
                    type: 'POST',
                    data: fileUploadData,
                    contentType: false,
                    processData: false
                });
                return response.data;
            } catch (error) {
                return $("#old_file").val();
            }
        }
    });
</script>
<script>
    function delete_record(id) {
        if (confirm("Are you sure you want to delete this certificate?")) {
            $.ajax({
                url: "<?=$api_base_url?>patient-id-card/" + id + "?hospital_id=<?=$data['hospital_id']?>",
                type: "DELETE",           
                success: function (data) {
                    successMsg("Deleted certificate Successfully!");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            });
        }
    }
</script>

