<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/sidebar'); ?>
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">
                            <?php echo $this->lang->line('prefix_setting'); ?>
                        </h3>
                    </div>
                    <div class="">
                        <form class="form-horizontal" id="form_prefix" method="POST" action="<?php echo site_url('admin/prefix/update'); ?>">
                            <div class="box-body">
                                <?php foreach ($prefix_result as $prefix_key => $prefix_value) { ?>
                                    <div class="form-group"> 
                                        <label for="<?php echo $prefix_value->type; ?>" class="control-label col-md-3 col-sm-3 col-xs-12">
                                            <?php 
                                            $prefix_name = $this->customlib->getPrefixnameByType($prefix_value->type);
                                            echo !empty($prefix_name) ? $prefix_name : $prefix_value->type;
                                            ?>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="hidden" name="perfix_id_<?php echo $prefix_value->id; ?>" value="<?php echo $prefix_value->id; ?>">
                                            <input type="hidden" name="tyle_name<?php echo $prefix_value->id; ?>" value="<?php echo $prefix_value->type; ?>">
                                            <input type="text" class="form-control" id="<?php echo $prefix_value->type; ?>" 
                                                name="<?php echo $prefix_value->type; ?>" 
                                                value="<?php echo set_value($prefix_value->type, $prefix_value->prefix); ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="box-footer">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <?php if ($this->rbac->hasPrivilege('prefix_setting', 'can_edit')) { ?>
                                        <button type="submit" class="btn btn-info pull-left" id="load1" 
                                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait">
                                            <i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?>
                                        </button>
                                    <?php } ?>
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

<script type="text/javascript"> 
    $(document).on('submit', '#form_prefix', function(e) {
        e.preventDefault();
        var btn = $("button[type=submit]");
        var form = $(this);
        btn.button('loading');

        var formData = [];
        form.find('input[type="text"]').each(function() {
            var prefix_id = $(this).prevAll('input[name^="perfix_id_"]').val();
            var type_name = $(this).prevAll('input[name^="tyle_name"]').val();
            var prefix = $(this).val();
            formData.push({ "id": prefix_id, "type": type_name, "prefix": prefix });
        });

        var totalRequests = formData.length;
        var completedRequests = 0;

        formData.forEach(function(dataObj) {
            $.ajax({
                url: '<?=$api_base_url?>setting-prefix-setting/' + dataObj.id, 
                type: "PATCH",
                data: JSON.stringify(dataObj),
                contentType: 'application/json',
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === "fail") {
                        var message = Object.values(response.error).join(" ");
                        errorMsg(message);
                    }
                },
                error: function() {
                    errorMsg('An error occurred while updating. Please try again.');
                },
                complete: function() {
                    completedRequests++;
                    if (completedRequests === totalRequests) {
                        btn.button('reset');
                        successMsg('All prefixes updated successfully!');
                        location.reload();
                    }
                }
            });
        });
    });
</script>
