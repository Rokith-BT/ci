<style>
.box {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
}

.box-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.box-title {
    margin: 0;
    font-size: 18px;
    color: #2c3e50;
    font-weight: 500;
}

.box-body {
    padding: 20px;
}

.billingbox {
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
    background: #ffffff !important;
    height: 100%;
}

.form-control1 {
    height: 38px;
    border-radius: 4px;
    border: 1px solid #dde2e6;
    padding: 8px 12px;
    width: 100%;
    transition: all 0.3s ease;
}

.form-control1:focus {
    border-color: #3f51b5;
    box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.1);
    outline: none;
}

.btn-primary {
    background: #3f51b5;
    border: none;
    padding: 8px 16px;
    color: white;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #32408f;
    transform: translateY(-1px);
}

.btn-success {
    background: #28a745;
    border: none;
    padding: 8px 16px;
    color: white;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: #218838;
    transform: translateY(-1px);
}

#qr-reader {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #dde2e6;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.col-md-4, .col-md-5, .col-md-3 {
    flex: 1;
    min-width: 200px;
}

.alert-danger {
    background: #fff3f3;
    color: #dc3545;
    padding: 12px;
    border-radius: 4px;
    border: 1px solid #ffcdd2;
    margin-top: 15px;
}
</style>

<!-- Keep the original HTML structure but add some minor enhancements -->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <!-- Single Module Billing -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('single_module_billing'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <?php if ($this->rbac->hasPrivilege('appointment_billing', 'can_view')) { ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
                                    <div class="billingbox text-center p-3">
                                        <a href="<?php echo base_url('admin/bill/appointment');?>" class="text-decoration-none">
                                            <div class="billingbox-icon">
                                                <i class="fa fa-calendar-check-o fa-2x"></i>
                                            </div>
                                            <p class="font-weight-bold mt-2"><?php echo $this->lang->line('appointment'); ?></p>
                                        </a>
                                    </div>
                                </div>
                            <?php } if ($this->rbac->hasPrivilege('opd_billing', 'can_view')) { ?>
                                <div class="col-lg-4 col-md-6 col-sm-6 mb-3">
                                    <div class="billingbox text-center p-3">
                                        <a href="<?php echo base_url('admin/bill/opd');?>" class="text-decoration-none">
                                            <div class="billingbox-icon">
                                                <i class="fas fa-stethoscope fa-2x"></i>
                                            </div>
                                            <p class="font-weight-bold mt-2"><?php echo $this->lang->line('opd'); ?></p>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OPD/IPD Billing through Case ID -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h3 class="box-title titlefix"><?php echo "Scan With Patient QR"; ?></h3>
                        <button id="scan-qr-btn" class="btn btn-success">
                            <i class="fa fa-qrcode"></i> <?php echo $this->lang->line('scan_qr'); ?>
                        </button>
                    </div>
                    <div class="box-body">
                    <form action="<?php echo base_url()?>admin/bill/dashboard" method="post" id="search-form">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="form-row">
                            <div class="col-md-4">
                            <select name="search_type" id="search_type" class="form-control1" required>
                                <option value="" disabled <?php echo empty($search_type) ? "selected" : ""; ?>>Select</option>
                                <option value="opd_id" <?php echo ($search_type == "opd_id") ? "selected" : ""; ?>>OPD ID</option>
                                <option value="ipd_id" <?php echo ($search_type == "ipd_id") ? "selected" : ""; ?>>IPD ID</option>
                                <option value="case_id" <?php echo ($search_type == "case_id") ? "selected" : ""; ?>>Case ID</option>
                            </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="search_value" id="search_value" value="<?=$search_value??""?>" class="form-control1" placeholder="Enter ID" autocomplete="off" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                </button>
                            </div>
                        </div>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                    </form>
                        <div id="qr-reader" class="mt-4" style="display: none;">
                                <div class="scan-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
  $(document).ready(function() {
    const searchInput = $("#search_value");
    const searchType = $("#search_type");  

    searchInput.autocomplete({
        source: function(request, response) {
            $.ajax({
                type: "GET",
                url: base_url + 'admin/bill/search',
                data: { 
                    term: request.term,
                    type: searchType.val()
                },
                dataType: "json",
                success: function(data) {
                    response($.map(data, function(item) {
                        return { label: item.id, value: item.id };
                    }));
                }
            });
        },
        minLength: 2
    });

    $("#scan-qr-btn").click(function() {
        const qrReader = $("#qr-reader");
        qrReader.toggle();

        if (qrReader.is(":visible")) {
            const html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (qrCodeMessage) => {
                    try {
                        const qrData = JSON.parse(qrCodeMessage);
                        if (qrData?.profile_details?.aayush_unique_id) {
                            $.ajax({
                                type: "GET",
                                url: base_url + "admin/bill/get_patientid_by_aayush_unique_id",
                                data: { 
                                    aayush_unique_id:qrData?.profile_details?.aayush_unique_id
                                },
                                dataType: "json",
                                success: function(response) {
                                    if(response.Patient_id){
                                        let patient_id = response.Patient_id;
                                        searchInput.val(patient_id);
                                        searchType.val('patient_id');
                                        $("#search-form").submit();
                                    }else{
                                        errorMsg("Patient not found with provided Aayush Unique ID.");
                                    }
                                }
                            });
                        }
                    } catch (error) {}

                    html5QrCode.stop();
                    qrReader.hide();
                },
                (error) => {}
            );
        }
    });

    searchType.change(function() {
        searchInput.attr("placeholder", `Enter ${$(this).val().toUpperCase()}`);
    });
});
  
</script>
<script>
$(document).ready(function(){
    $("#search_value").on("input", function() {
        let value = $(this).val();
        let cleanedValue = value.replace(/\D/g, '');
        $(this).val(cleanedValue);
    });
});
</script>

