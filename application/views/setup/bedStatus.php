<div class="content-wrapper" style="min-height: 348px;">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <?php
                $this->load->view('setup/bedsidebar');
                ?>
            </div>
            <div class="col-md-10">
                <!-- general form elements -->
                <?php if ($this->rbac->hasPrivilege('bed_status', 'can_view')) { ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('bed_status'); ?></h3>

                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('bed_status'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('bed_type'); ?></th>
                                        <th><?php echo $this->lang->line('bed_group'); ?></th>
                                        <th><?php echo $this->lang->line('floor'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
                <?php } ?>
            </div>
            <!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->
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
</script>
<script>
const initialData = <?= json_encode($initialData) ?>;
const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
$(document).ready(function() {
    let condition = {
        key: 'status',
        condition_fn: function(value) {
            return value === 'no' ? 'Allotted' : 'Unused';
        },
    };
    initializeTable(
        initialData,
        initialDataTotal,
        `${base_url}admin/setup/bed/bedstatus`,
        '#ajaxlist',
        ['sno', 'name', 'bed_type', 'bed_group', 'floor', 'status'],
        actionTemplate = null,
        'id',
        condition
    );
});
</script>