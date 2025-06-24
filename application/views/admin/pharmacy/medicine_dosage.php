<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php $this->load->view('admin/pharmacy/pharmacyMasters') ?>
            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('medicine_dosage_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('medicine_dosage', 'can_add')) { ?>
                            <a onclick="addModal()" class="btn btn-primary btn-sm medicine"><i class="fa fa-plus"></i>
                                <?php echo $this->lang->line('add_medicine_dosage'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('medicine_dosage_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('category_name'); ?></th>
                                        <th><?php echo $this->lang->line('dosage'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    if (!empty($medicineDosage)) {
                                        foreach ($medicineDosage as $dosage) {

                                           $subcount = 1;
                                            foreach ($dosage as $key => $value) {
                                              
                                            
                                            ?>
                                    <tr>
                                        <td><?php if($subcount==1){ echo $value['medicine_category']; } ?></td>
                                        <td><?php echo $value['dosage']." ".$value['unit']; ?></td>


                                        <td class="text-right">
                                            <?php  if ($this->rbac->hasPrivilege('medicine_dosage', 'can_edit')) { ?>
                                            <a data-target="#editmyModal" onclick="get(<?php echo $value['id'] ?>)"
                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('medicine_dosage', 'can_delete')) {
                                                        ?>
                                            <a class="btn btn-default btn-xs" data-toggle="tooltip"
                                                title="<?php echo $this->lang->line('delete'); ?>"
                                                onclick="delete_medicine_dosage('<?php echo $value['id'] ?>')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <?php } } ?>
                                        </td>
                                    </tr>
                                    <?php
                                            $subcount++;
                                            
                                        }
                                        $count++;
                                    
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_medicine_dosage'); ?></h4>
            </div>
            <form id="formadd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="form-group">
                                <label
                                    for="medicine_category"><?php echo $this->lang->line('medicine_category'); ?></label>
                                <small class="req"> *</small>
                                <select name="medicine_category" class="form-control" required>
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($medicineCategory as $key => $catvalue) { ?>
                                    <option value="<?php echo $catvalue["id"] ?>">
                                        <?php echo $catvalue["medicine_category"] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>
                            </div>

                            <div id="dose_fields">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="dosage"><?php echo $this->lang->line('dose'); ?></label>
                                            <input type="hidden" name="Medicine" id="Medicine" value="1">
                                            <small class="req"> *</small>
                                            <input name="dosage[]" placeholder="" type="text" class="form-control"
                                                required />
                                            <span class="text-danger"><?php echo form_error('dosage'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="unit"><?php echo $this->lang->line('unit'); ?></label>
                                            <small class="req"> *</small>
                                            <select name="unit[]" class="form-control" required>
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($unit_list as $key => $value) { ?>
                                                <option value="<?php echo $value->id; ?>"><?php echo $value->unit; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>
                                            <a class="btn addplus-xs btn-primary add-record" data-added="0">
                                                <i class="fa fa-plus"></i>&nbsp;<?php echo $this->lang->line('add'); ?>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?>
                    </button>
                </div>
            </form>

        </div>
        <!--./row-->
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#formadd').on('submit', function(e) {
        e.preventDefault();

        var formData = [];
        var medicineCategory = $('select[name="medicine_category"]').val();
        var hospitalId = <?=$data['hospital_id']?>;

        $('input[name="Medicine"]').each(function(index) {
            formData.push({
                medicine_category_id: medicineCategory,
                dosage: $(this).val(),
                charge_units_id: $('select[name="unit[]"]').eq(index).val(),
                Hospital_id: hospitalId
            });
        });

        if (!formData.length) return;

        var completedRequests = 0;

        function sendData(data, index) {
            if (index >= data.length) {
                if (completedRequests === data.length) {
                    successMsg('All data inserted successfully');
                    $('#formaddbtn').button('reset');
                    location.reload();
                }
                return;
            }

            $.ajax({
                url: '<?=$api_base_url?>setup-pharmacy-medicine-dosage',
                type: 'POST',
                data: JSON.stringify(data[index]),
                contentType: 'application/json',
                dataType: 'json',
                beforeSend: function() {
                    $('#formaddbtn').button('loading');
                },
                success: function() {
                    completedRequests++;
                },
                error: function(xhr, status, error) {
                    console.log('Error in form submission:', error);
                    $('#formaddbtn').button('reset');
                },
                complete: function() {
                    sendData(data, index + 1);
                }
            });
        }

        sendData(formData, 0);
    });

    $('.add-record').click(function() {
        let currectdatacount = parseInt($('#Medicine').val()) || 0;
        $('#Medicine').val(currectdatacount + 1);

        var newDoseField = `
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <input name="dosage[]" placeholder="" type="text" class="form-control" required/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <select name="unit[]" class="form-control" required>
                            <option value="">Select</option>
                            <?php foreach ($unit_list as $key => $value) { ?>
                                <option value="<?php echo $value->id; ?>"><?php echo $value->unit; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        `;
        $('#dose_fields').append(newDoseField);
    });
});
</script>




<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_medicine_dosage'); ?></h4>
            </div>



            <form id="editformadd" action="<?php echo site_url('admin/medicinedosage/add') ?>" name="employeeform"
                method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">

                        <div class="form-group">
                            <label
                                for="exampleInputEmail1"><?php echo $this->lang->line('category_name'); ?></label><small
                                class="req"> *</small>
                            <select name="medicine_category" id="medicine_category1" placeholder="" type="text"
                                class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($medicineCategory as $key => $catvalue) {
                                    ?>
                                <option value="<?php echo $catvalue["id"] ?>">
                                    <?php echo $catvalue["medicine_category"] ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>
                            <input type="hidden" id="id" name="medicinecategoryid">
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('dosage'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="dosageid" id="dosageid" placeholder="" type="hidden"
                                        class="form-control" />
                                    <input autofocus="" name="dosage[]" id="dosage" placeholder="" type="text"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('unit'); ?></label>
                                    <small class="req"> *</small>
                                    <select name="unit[]" id="unit" class="form-control">
                                        <option value=""> <?php echo $this->lang->line('select');?></option>
                                        <?php 
                                    foreach ($unit_list as $key => $value) {
                                       
                                    ?>
                                        <option value="<?php echo $value->id;?>"><?php echo $value->unit;?></option>
                                        <?php
                                }
                                    ?>
                                    </select>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!--./modal-body-->
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
    $("#editformadd").on('submit', function(e) {
        e.preventDefault();

        var errors = [];

        var medicineCategory = $('#medicine_category1').val();
        var dosage = $('#dosage').val();
        var unit = $('#unit').val();
        var hospitalId = '<?=$data['hospital_id']?>';

        if (!medicineCategory) {
            errors.push('Medicine category is required.');
        }

        if (!dosage) {
            errors.push('Dosage is required.');
        }

        if (!unit) {
            errors.push('Unit is required.');
        }

        if (errors.length > 0) {
            errorMsg(errors); // Using the push method to collect and display errors
            return;
        }

        var formData = {
            medicine_category_id: medicineCategory,
            dosage: dosage,
            charge_units_id: unit,
            Hospital_id: hospitalId
        };

        let medicineCategoryId = $('#dosageid').val();
        let data = JSON.stringify(formData);
        console.log(data);

        $.ajax({
            url: '<?=$api_base_url?>setup-pharmacy-medicine-dosage/' + medicineCategoryId,
            type: 'PATCH',
            data: data,
            contentType: 'application/json',
            dataType: 'json',
            beforeSend: function() {
                $("#editformaddbtn").button('loading');
            },
            success: function(response) {
                let message = response?.message || 'Saved successfully';
                successMsg(message);
                location.reload();
                $("#editformaddbtn").button('reset');
            },
            error: function(xhr, status, error) {
                console.log('Error in form submission:', error);
                $("#editformaddbtn").button('reset');
            }
        });
    });
});
</script>


<script>
function makeid(length) {
    var result = '';
    var characters = '0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

$(document).ready(function(e) {

    $(".select2").select2();
});

function get(id) {
    $('#editmyModal').modal('show');
    $.ajax({

        dataType: 'json',

        url: '<?php echo base_url(); ?>admin/medicinedosage/get_data/' + id,

        success: function(result) {
            $('#dosageid').val(result.id);
            $('#dosage').val(result.dosage);
            $('#unit').val(result.charge_units_id);
            $('#medicine_category1').val(result.medicine_category_id);
        }

    });

}




$(".medicine").click(function() {
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
function addModal() {
    $("#myModal").modal("show");
    $("div").remove(".dosage_row");
}

function delete_medicine_dosage(id) {
    var result = confirm("Are you sure you want to delete this record?");
    if (result) {
        $.ajax({
            url: '<?=$api_base_url?>setup-pharmacy-medicine-dosage/' + id +
                "?Hospital_id=<?=$data['hospital_id']?>",
            type: 'DELETE',
            success: function(response) {
                successMsg(response.message);
                location.reload();
            }
        });
    }
}
</script>