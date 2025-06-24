<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <?php $this->load->view('admin/printing/sidebar'); ?>
            </div>
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header with-border">
                    <h3 class="box-title titlefix"><?php echo $this->lang->line('pathology_header_footer'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" enctype="multipart/form-data" id="printSettingsForm" method="post">
                            <input type="hidden" name="id" value="<?php echo !empty($printing_list) ? $printing_list[0]['id'] : ''; ?>">
                            <input type="hidden" name="function_name" value="<?php echo $function_name; ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('header_image'); ?>(2230px X 300px)</label>
                                        <?php
                                        $logo_image = base_url() . "uploads/staff_images/no_image.png";
                                        if (!empty($printing_list[0]['print_header'])) {
                                            $userdata = $this->session->userdata('hospitaladmin');
                                            $accessToken = $userdata['accessToken'] ?? '';
                                            $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                            $payload = json_encode(['value' => $printing_list[0]['print_header']]);
                                            $client = curl_init($url);
                                            curl_setopt_array($client, [
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_POST => true,
                                                CURLOPT_POSTFIELDS => $payload,
                                                CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: ' . $accessToken],
                                            ]);
                                            $response = curl_exec($client);
                                            curl_close($client);
                                            if ($response && strpos($response, '"NoSuchKey"') === false) {
                                                $logo_image = "data:image/png;base64," . trim($response);
                                            } elseif (!empty($printing_list[0]['print_header'])) {
                                                $logo_image = base_url() . $printing_list[0]['print_header'];
                                            }
                                        }
                                        ?>
                                        <img src="<?php echo $logo_image ?>" style="height: 30px;">
                                        <input type="hidden" name="old_header_image" value="<?php if(!empty($printing_list)){ echo $printing_list[0]['print_header'];} ?>">
                                       <input data-default-file="<?php echo $logo_image ?>" type="file"
                                            class="filestyle form-control" data-height="180" name="header_image"
                                            id="header_image" accept="image/jpeg, image/png, image/gif, image/webp"
                                            oninput="validateImage(this)">
                                        <input type="hidden" class="form-control" name="print_header">
                                        <span class="text-danger"><?php echo form_error('header_image'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('footer_content'); ?></label>
                                        <textarea id="compose_textarea" name="footer_content" class="form-control" style="height: 250px"><?php echo !empty($printing_list) ? $printing_list[0]['print_footer'] : ''; ?></textarea>
                                        <span class="text-danger"><?php echo form_error('footer_content'); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-primary" id="saveButton"><?php echo $this->lang->line('save'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
 
<script>
    $(function () {
    "use strict";
    $("#compose_textarea").wysihtml5({
        toolbar: {
            "image": false
        }
    });
});

$("#saveButton").on("click", function () {
    let fileInput = $("#header_image")[0].files[0];
    $("#saveButton").button("loading");
    let formData = new FormData();
    let oldImagePath = $("input[name='old_header_image']").val();

    if (fileInput) {
        formData.append("file", fileInput);

        $.ajax({
            url: 'https://phr-api.plenome.com/file_upload',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                let uploadedFilePath = response.data || 'none.img';
                submitForm(uploadedFilePath);
            },
            error: function () {
                let fallbackPath = oldImagePath || 'none.img';
                submitForm(fallbackPath);
            }
        });
    } else {
        let fallbackPath = oldImagePath || 'none.img';
        submitForm(fallbackPath);
    }
});

function submitForm(headerImagePath) {
    let jsonData = {
        id: $("input[name='id']").val(),
        print_header: headerImagePath,
        print_footer: $("#compose_textarea").val(),
        setting_for: "pathology",
        is_active: "yes",
        created_at: new Date().toISOString().slice(0, 19).replace('T', ' ')
    };

    $.ajax({
        url: '<?= $api_base_url ?>print-settings',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(jsonData),
        success: function (response) {
            if (response.status == "failed") {
                errorMsg(response.message);
                setInterval(() => {
                    $("#saveButton").button("reset");
                }, 3000);
                
            } else {
                successMsg(response.message);
                setInterval(() => {
                    $("#saveButton").button("reset");
                }, 3000);
            }
        },
        error: function () {
            errorMsg("Error saving data. Please try again.");
            setInterval(() => {
                    $("#saveButton").button("reset");
                }, 3000);
        }
    });
}

</script>
