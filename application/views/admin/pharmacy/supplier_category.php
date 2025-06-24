<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <?php $this->load->view('admin/pharmacy/pharmacyMasters') ?>

            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('supplier_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('medicine_supplier', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm supplier"><i
                                    class="fa fa-plus"></i> <?php echo $this->lang->line('add_supplier'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('supplier_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('supplier_name'); ?></th>
                                        <th><?php echo $this->lang->line('supplier_contact'); ?></th>
                                        <th><?php echo $this->lang->line('contact_person_name'); ?></th>
                                        <th><?php echo $this->lang->line('contact_person_phone'); ?></th>
                                        <th><?php echo $this->lang->line("drug_license_number"); ?></th>
                                        <th><?php echo $this->lang->line('address'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($supplierCategory as $supplier) {
                                        ?>
                                    <tr>
                                        <td><?php echo $supplier['supplier']; ?></td>
                                        <td><?php echo $supplier['contact']; ?></td>
                                        <td><?php echo $supplier['supplier_person']; ?></td>
                                        <td><?php echo $supplier['supplier_person_contact']; ?></td>
                                        <td><?php echo $supplier['supplier_drug_licence']; ?></td>
                                        <td><?php echo $supplier['address']; ?></td>
                                        <td class="text-right">
                                            <?php if ($this->rbac->hasPrivilege('medicine_supplier', 'can_edit')) { ?>
                                            <a data-target="#editmyModal" onclick="get(<?php echo $supplier['id'] ?>)"
                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php
                                                }
                                                if ($this->rbac->hasPrivilege('medicine_supplier', 'can_delete')) {
                                                    ?>
                                            <a class="btn btn-default btn-xs" data-toggle="tooltip"
                                                title="<?php echo $this->lang->line('delete'); ?>"
                                                onclick="delete_recordByIdReload('<?=$api_base_url?>setup-pharmacy-supplier/<?php echo $supplier['id']; ?>?Hospital_id=<?php echo $data['hospital_id']; ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_supplier'); ?></h4>
            </div>

            <form id="formadd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('supplier_name'); ?></label>
                                    <small class="req"> *</small>
                                    <input name="supplier_category" type="text" class="form-control"
                                        value="<?php if (isset($result)) echo $result['supplier_category']; ?>"
                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('supplier_contact'); ?></label>
                                    <input name="contact" type="text" class="form-control"
                                        value="<?php if (isset($result)) echo $result['contact']; ?>"
                                        oninput="this.value=this.value.replace(/[^0-9]/g, '').slice(0,10)"
                                        maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('contact_person_name'); ?></label>
                                    <input name="supplier_person" type="text" class="form-control"
                                        value="<?php if (isset($result)) echo $result['supplier_person']; ?>"
                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('contact_person_phone'); ?></label>
                                    <input name="supplier_person_contact" type="text" class="form-control"
                                        value="<?php if (isset($result)) echo $result['supplier_person_contact']; ?>"
                                        onkeyup="this.value=this.value.replace(/[^0-9]/g, '')" maxlength="10">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line("drug_license_number"); ?></label>
                                    <input name="supplier_drug_licence" type="text" class="form-control"
                                        value="<?php if (isset($result)) echo $result['supplier_drug_licence']; ?>"
                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9]/g, '')">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('address'); ?></label>
                                    <input name="address" type="text" class="form-control"
                                        value="<?php if (isset($result)) echo $result['address']; ?>"
                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9\s,.-]/g, '')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="formaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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

        var supplierCategory = $('input[name="supplier_category"]').val();
        if (!supplierCategory) {
            errorMsg('Supplier Name is required.');
            return;
        }

        var pattern = /^(?=.*[a-zA-Z0-9])[a-zA-Z0-9 ]+$/;
        if (!pattern.test(supplierCategory)) {
            errorMsg(
                'Supplier Name can only contain letters, numbers, and spaces, and must include at least one alphanumeric character.'
            );
            return;
        }

        var formData = {
            supplier: supplierCategory,
            contact: $('input[name="contact"]').val() || '',
            supplier_person: $('input[name="supplier_person"]').val() || '',
            supplier_person_contact: $('input[name="supplier_person_contact"]').val() || '',
            supplier_drug_licence: $('input[name="supplier_drug_licence"]').val() || '',
            address: $('input[name="address"]').val() || '',
            Hospital_id: <?=$data['hospital_id']?>
        };

        $.ajax({
            url: '<?=$api_base_url?>setup-pharmacy-supplier',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#formaddbtn').button('loading');
            },
            success: function(response) {
                $('#formaddbtn').button('reset');
                successMsg('Supplier added successfully!');
                location.reload();
            },
            error: function(xhr, status, error) {
                $('#formaddbtn').button('reset');
                console.log('Error in form submission:', error);
            }
        });
    });
});
</script>



<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_supplier'); ?></h4>
            </div>
            <form id="editformadd" action="<?php echo site_url('admin/medicinecategory/addsupplier') ?>"
                name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <input type="hidden" id="id" name="suppliercategoryid">
                    <div class="row ptt10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label
                                    for="exampleInputEmail1"><?php echo $this->lang->line('supplier_name'); ?></label><small
                                    class="req"> *</small>
                                <input autofocus="" id="supplier_category" name="supplier_category" placeholder=""
                                    type="text" class="form-control" value="<?php
                                if (isset($result)) {
                                    echo $result["supplier_category"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('supplier_category'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label
                                    for="exampleInputEmail1"><?php echo $this->lang->line('supplier_contact'); ?></label>
                                <input autofocus="" id="contact" name="contact" placeholder="" type="text"
                                    class="form-control" value="<?php
                                if (isset($result)) {
                                    echo $result["contact"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('contact'); ?></span>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label
                                    for="exampleInputEmail1"><?php echo $this->lang->line('contact_person_name'); ?></label>
                                <input autofocus="" id="supplier_person" name="supplier_person" placeholder=""
                                    type="text" class="form-control" value="<?php
                                if (isset($result)) {
                                    echo $result["supplier_person"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('supplier_person'); ?></span>


                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label
                                    for="exampleInputEmail1"><?php echo $this->lang->line('contact_person_phone'); ?></label>
                                <input autofocus="" id="supplier_person_contact" name="supplier_person_contact"
                                    placeholder="" type="text" class="form-control" value="<?php
                                if (isset($result)) {
                                    echo $result["supplier_person_contact"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('supplier_person_contact'); ?></span>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    for="exampleInputEmail1"><?php echo $this->lang->line("drug_license_number"); ?></label>
                                <input autofocus="" id="supplier_drug_licence" name="supplier_drug_licence"
                                    placeholder="" type="text" class="form-control" value="<?php
                                if (isset($result)) {
                                    echo $result["supplier_drug_licence"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('supplier_drug_licence'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-8">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                <input autofocus="" id="address" name="address" placeholder="" type="text"
                                    class="form-control" value="<?php
                                if (isset($result)) {
                                    echo $result["address"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('address'); ?></span>

                            </div>

                        </div>

                    </div>
                </div>
                <!--./modalbody-->

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

        var supplierCategory = $('#supplier_category').val();
        var contact = $('#contact').val() || '';
        var supplierPerson = $('#supplier_person').val() || '';
        var supplierPersonContact = $('#supplier_person_contact').val() || '';
        var supplierDrugLicence = $('#supplier_drug_licence').val() || '';
        var address = $('#address').val() || '';

        if (!supplierCategory) {
            errorMsg('SupplierCategory fields are required.');
            return;
        }

        var pattern = /^(?=.*[a-zA-Z0-9])[a-zA-Z0-9 ]+$/;
        if (!pattern.test(supplierCategory)) {
            errorMsg(
                'Supplier Name can only contain letters, numbers, and spaces, and must include at least one alphanumeric character.'
            );
            return;
        }

        var formData = {
            supplier: supplierCategory,
            contact: contact,
            supplier_person: supplierPerson,
            supplier_person_contact: supplierPersonContact,
            supplier_drug_licence: supplierDrugLicence,
            address: address,
        };

        let suppliercategoryid = $('#id').val();

        $.ajax({
            url: '<?=$api_base_url?>setup-pharmacy-supplier/' + suppliercategoryid,
            type: 'PATCH',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $("#editformaddbtn").button('loading');
            },
            success: function(response) {
                let message = response[0]['data '].message || 'Saved successfully';
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
function get(id) {
    $('#editmyModal').modal('show');
    $.ajax({

        dataType: 'json',

        url: '<?php echo base_url(); ?>admin/medicinecategory/get_datasupplier/' + id,

        success: function(result) {

            $('#id').val(result.id);
            $('#supplier_category').val(result.supplier);
            $('#supplier_person').val(result.supplier_person);
            $('#supplier_person_contact').val(result.supplier_person_contact);
            $('#supplier_drug_licence').val(result.supplier_drug_licence);
            $('#contact').val(result.contact);
            $('#address').val(result.address);

        }

    });

}




$(".supplier").click(function() {
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