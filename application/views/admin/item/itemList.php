<?php $currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('item', 'can_view')) {
            ?>

                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"> <?php echo $this->lang->line('item_list'); ?></h3>
                            <div class="box-tools pull-right">
                                <?php if ($this->rbac->hasPrivilege('item', 'can_add')) { ?>
                                    <a href="" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm additem"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_item'); ?></a>
                                <?php } ?>

                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive mailbox-messages">
                                <div class="download_label"><?php echo $this->lang->line('item_list'); ?></div>
                                <table class="table table-hover table-striped table-bordered ajaxlist" data-export-title="<?php echo $this->lang->line('item_list'); ?>">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('item'); ?></th>
                                            <th><?php echo $this->lang->line('category'); ?>
                                            </th>
                                            <th><?php echo $this->lang->line('unit'); ?>
                                            </th>
                                            <th><?php echo $this->lang->line('available_quantity'); ?>
                                            </th>
                                            <th>
                                                <?php echo $this->lang->line('description'); ?>
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
            <?php
            }
            ?>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_item') ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row ptt10">
                        <form id="form1" id="itemstockform" name="itemstockform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('item'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('item_category'); ?></label><small class="req"> *</small>
                                    <select id="item_category_id" name="item_category_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($itemcatlist as $item_category) {
                                        ?>
                                            <option value="<?php echo $item_category['id'] ?>" <?php
                                                                                                if (set_value('item_category_id') == $item_category['id']) {
                                                                                                    echo "selected = selected";
                                                                                                }
                                                                                                ?>><?php echo $item_category['item_category'] ?></option>

                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('item_category_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('unit'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="unit" name="unit" placeholder="" type="text" class="form-control" value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer clear">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_item'); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row ptt10">
                        <form id="eform1" id="itemstockform" name="itemstockform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('item'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="ename" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('item_category'); ?></label><small class="req"> *</small>
                                    <select id="eitem_category_id" name="item_category_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($itemcatlist as $item_category) {
                                        ?>
                                            <option value="<?php echo $item_category['id'] ?>" <?php
                                                                                                if (set_value('item_category_id') == $item_category['id']) {
                                                                                                    echo "selected = selected";
                                                                                                }
                                                                                                ?>><?php echo $item_category['item_category'] ?></option>

                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('item_category_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('unit'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="eunit" name="unit" placeholder="" type="text" class="form-control" value="<?php echo set_value('unit'); ?>" />
                                    <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="edescription" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"></span>
                                    <input type="hidden" name="id" id="e_id">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer clear">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        $('#date').datepicker({
            //  format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true
        });

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

    $(document).ready(function() {
        $('#form1').on('submit', function(e) {
            e.preventDefault();

            if (!validateForm()) return;

            var formData = new FormData(this);
            var formObject = {};
            formData.forEach(function(value, key) {
                formObject[key] = value;
            });

            let finalData = {
                "item_name": formObject.name,
                "item_category_id": formObject.item_category_id,
                "item_unit": formObject.unit,
                "item_description": formObject.description,
                "hospital_id": <?= $data['hospital_id'] ?>
            };

            $.ajax({
                url: '<?= $api_base_url ?>php-inventory/item',
                type: "POST",
                data: JSON.stringify(finalData),
                dataType: 'json',
                contentType: 'application/json',
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == "fail") {
                        let message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                        location.reload();
                    } else {
                        successMsg(data.message);
                        location.reload();
                    }
                },
                error: function() {
                    errorMsg("An error occurred while processing your request.");
                }
            });
        });

        function validateForm() {
            const requiredFields = [{
                    id: 'name',
                    message: 'Item is required.'
                },
                {
                    id: 'item_category_id',
                    message: 'Item Category is required.'
                },
                {
                    id: 'unit',
                    message: 'Unit is required.'
                }
            ];

            let isValid = true;

            requiredFields.forEach(field => {
                const value = $(`[name="${field.id}"]`).val();
                if (!value || value.trim() === '') {
                    errorMsg(field.message);
                    isValid = false;
                }
            });

            return isValid;
        }
    });



    function get_data(id) {

        $.ajax({
            url: "<?php echo base_url() ?>admin/item/get_data/" + id,
            type: "POST",
            dataType: 'json',
            success: function(res) {
                console.log(res);
                $('#ename').val(res.name);
                $('#eunit').val(res.unit);
                $('#epurchase_price').val(res.purchase_price);
                $('#e_id').val(res.id);
                $('#eitem_category_id').val(res.item_category_id);
                $('#edescription').val(res.description);
                $('#editmyModal').modal('show');
            }
        });
    }

    $(document).ready(function(e) {
        $('#eform1').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var formObject = {};
            formData.forEach(function(value, key) {
                formObject[key] = value;
            });

            let finalData = {
                "item_name": formObject.name,
                "item_category_id": formObject.item_category_id,
                "item_unit": formObject.unit,
                "item_description": formObject.description,
                "hospital_id": <?= $data['hospital_id'] ?>
            };

            let e_id = $('#e_id').val();

            console.log(JSON.stringify(finalData));

            $.ajax({
                url: '<?= $api_base_url ?>php-inventory/item/' + e_id,
                type: "PATCH",
                data: JSON.stringify(finalData),
                dataType: 'json',
                contentType: 'application/json', // Since you're sending JSON
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status === "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                        window.location.reload(true);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                },
                error: function() {
                    // Handle errors if needed
                }
            });
        });
    });


    function delete_record(id) {
        if (confirm('<?php echo $this->lang->line('delete_confirm') ?>')) {
            $.ajax({
                url: '<?= $api_base_url ?>php-inventory/item/' + id + '?hospital_id=<?= $data['hospital_id'] ?>',
                type: "DELETE",
                dataType: 'json',
                success: function(res) {
                    successMsg('<?php echo $this->lang->line('delete_message'); ?>');
                    window.location.reload(true);
                },
                error: function() {
                    alert("Fail")
                }
            });
        }
    }

    $(".additem").click(function() {
        $('#form1').trigger("reset");
    });

    $(document).ready(function(e) {
        $('#myModal,#editmyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<!-- //========datatable start===== -->
<script type="text/javascript">
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('ajaxlist', 'admin/item/getitemdatatable');
        });
    }(jQuery))
</script>
<!-- //========datatable end===== -->