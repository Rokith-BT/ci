<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('medicines_stock'); ?></h3>
                        <div class="box-tools pull-right">

                            <?php if ($this->rbac->hasPrivilege('import_medicine', 'can_view')) { ?>                
                                <a data-toggle="modal" href="<?php echo base_url(); ?>admin/pharmacy/import" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> <?php echo $this->lang->line('import_medicine'); ?>
                                </a>
                            <?php } ?>

                            <?php if ($this->rbac->hasPrivilege('medicine', 'can_add')) { ?>
                                <a data-toggle="modal" onclick="holdModal('myModal')" class="btn btn-primary btn-sm addmedicine"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_medicine'); ?></a> 
                            <?php } ?>

                            <?php if ($this->rbac->hasPrivilege('medicine_purchase', 'can_view')) { ?>
                                <a href="<?php echo base_url(); ?>admin/pharmacy/purchase" class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i> <?php echo $this->lang->line('purchase'); ?></a>
                            <?php } ?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('medicines_stock'); ?></div>
                       <!--  <form action="<?php echo site_url('admin/pharmacy/bulk_delete') ?>" method="POST" id="deletebulk"> -->
                        
                        <!-- <div class=""> -->
                        <?php if ($this->rbac->hasPrivilege('medicine', 'can_delete')) { ?>
                            <button type="button" class="btn btn-primary pull-right btn-sm mt10 delete_selected" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-trash"></i> <?php echo $this->lang->line('delete_selected'); ?>
                            </button>
                        <?php } ?>
                        <!-- </div>  -->
                      <div class="table-responsive-mobile">   
                        <table class="table table-striped table-bordered table-hover ajaxlist " cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('medicines_stock'); ?>">
                            <thead>
                                <tr>
                                    <th class="noExport"><input type="checkbox" name="checkAll"> #</th>
                                    <th><?php echo $this->lang->line('medicine_name'); ?></th>
                                    <th><?php echo $this->lang->line('medicine_company'); ?></th>
                                    <th><?php echo $this->lang->line('medicine_composition'); ?></th>
                                    <th><?php echo $this->lang->line('medicine_category'); ?></th> 
                                    <th><?php echo $this->lang->line('medicine_group'); ?></th>
                                    <th><?php echo $this->lang->line('unit'); ?></th>
                                    <th><?php echo $this->lang->line('available_qty'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                            
                      </div>
                      <!-- </form>   -->
                    </div>
                </div>                                                    
            </div>                                                                                                                                          
        </div>  
    </section>
</div>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_medicine_details'); ?></h4> 
            </div>
            <form id="formadd" accept-charset="utf-8" method="post" class="ptt10" enctype="multipart/form-data"> 
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_name'); ?></label>
                                            <small class="req"> *</small> 
                                            <input id="medicine_name" name="medicine_name" placeholder="" type="text" class="form-control"/>
                                            <span class="text-danger"><?php echo form_error('medicine_name'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('medicine_category'); ?></label>
                                            <small class="req"> *</small> 
                                            <div>
                                                <select class="form-control select2 medicine_category_id" style="width:100%" name='medicine_category_id' >
                                                    <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                    </option>
                                                    <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                                        ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["medicine_category"] ?>
                                                        </option>   
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('medicine_category_id'); ?></span>
                                        </div>
                                    </div>  
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_company'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" name="medicine_company" value="" class="form-control">
                                            <span class="text-danger"><?php echo form_error('medicine_company'); ?></span>
                                        </div>
                                    </div> 
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_composition'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" name="medicine_composition" value="" class="form-control">
                                            <span class="text-danger"><?php echo form_error('medicine_composition'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_group'); ?><small class="req"> *</small></label>
                                            <input type="text" name="medicine_group" value="" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('unit'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" name="unit" class="form-control">
                                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('min_level'); ?></label>
                                            <input type="text" name="min_level" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('re_order_level'); ?></label>
                                            <input type="text" name="reorder_level" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group"> 
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('tax'); ?></label>
                                            <div class="input-group">                                            
                                                <input type="text" class="form-control right-border-none" name="vat" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                <span class="input-group-addon "> %</span>
                                            </div>
                                        </div>                                        
                                    </div> 
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('unit_packing'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" name="unit_packing" class="form-control" >
                                            <span class="text-danger"><?php echo form_error('unit_packing'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('vat_a_c'); ?></label>
                                            <input type="text" name="vat_ac" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('note'); ?></label>
                                            <textarea name="note" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_photo_jpg_jpeg_png'); ?></label>
                                            <input type="file" name="file" id="file" class="form-control filestyle" />
                                        </div>
                                    </div>
                                </div><!--./row-->   

                            </div><!--./col-md-12-->       
                        </div><!--./row--> 
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form> 
        </div>
    </div>    
</div>


<div class="modal fade" id="myModalImport" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('medicine'); ?></h4> 
            </div>
            <form id="formimp" accept-charset="utf-8" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('medicine') . " " . $this->lang->line('category'); ?></label>
                                        <small class="req"> *</small> 
                                        <div>
                                            <select class="form-control select2 medicine_category_id" style="width:100%" name='medicine_category_id' >
                                                <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                </option>
                                                <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["medicine_category"] ?>
                                                    </option>   
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('medicine_category_id'); ?></span>
                                    </div>
                                </div>  

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('medicine'); ?>CSV File Upload</label>
                                        <input type="file" name="medicine_image" class="form-control filestyle" />
                                    </div>
                                </div>
                            </div><!--./row-->   

                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>
            <div class="modal-footer">
                <button type="submit" id="formimpbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right">Import <?php echo $this->lang->line('medicine'); ?></button>

            </div>
         </form>
        </div>
    </div>    
</div>
<!-- dd -->
<div class="modal fade" id="myModaledit" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_medicine_details'); ?></h4> 
            </div>
            <form id="formedit" accept-charset="utf-8" enctype="multipart/form-data"  method="post" class="ptt10">
                <div class="scroll-area">
                 
                   <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                           
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="<?php echo set_value('id'); ?>">
                                    <input type="hidden" name="oldfile" id="oldfile" value="<?php echo set_value('id'); ?>">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_name'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" id="medicines_name" name="medicine_name1" value="<?php echo set_value('medicine_name'); ?>" class="form-control">
                                            <span class="text-danger"><?php echo form_error('medicine_name'); ?></span>
                                        </div>
                                    </div> 
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('medicine_category'); ?></label>
                                            <small class="req"> *</small> 
                                            <div><select class="form-control select2" style="width:100%" name='medicine_category_id' id="medicines_category_id" >
                                                    <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                                        ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["medicine_category"] ?></option>   
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('medicine_category_id'); ?></span>
                                        </div>
                                    </div>  
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_company'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" id="medicine_company" name="medicine_company1" value="<?php echo set_value('medicine_company'); ?>" class="form-control">
                                            <span class="text-danger"><?php echo form_error('medicine_company'); ?></span>
                                        </div>
                                    </div> 
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_composition'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" id="medicine_composition" name="medicine_composition1" value="<?php echo set_value('medicine_composition'); ?>" class="form-control">
                                            <span class="text-danger"><?php echo form_error('medicine_composition'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_group'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" id="medicine_group" name="medicine_group1" value="<?php echo set_value('medicine_group'); ?>" class="form-control">
                                            <span class="text-danger"><?php echo form_error('medicine_group'); ?></span>
                                        </div>
                                    </div>
                                
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('unit'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" name="unit1" id="unit" value="<?php echo set_value('unit'); ?>" class="form-control">
                                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('min_level'); ?></label>
                                            <input type="text" name="min_level1" id="min_level" value="<?php echo set_value('min_level'); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('re_order_level'); ?></label>
                                            <input type="text" name="reorder_level1" id="reorder_level"  value="<?php echo set_value('reorder_level'); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        
                        <div class="form-group"> 
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('tax'); ?></label>
                            <div class="input-group">

                            
                                <input type="text"  value="<?php echo set_value('vat'); ?>" class="form-control right-border-none"  id="vat" name="vat1" autocomplete="off">
                                <span class="input-group-addon "> %</span>
                            </div>
                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('unit_packing'); ?></label>
                                            <small class="req"> *</small> 
                                            <input type="text" id="unit_packing"  name="unit_packing1" class="form-control" value="<?php echo set_value('unit_packing'); ?>">
                                            <span class="text-danger"><?php echo form_error('unit_packing'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('vat_a_c'); ?></label>
                                            <input type="text" id="vat_ac" name="vat_ac1" value="<?php echo set_value('vat_ac'); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('note'); ?></label>
                                            <textarea type="text" id="edit_note" name="edit_note"  class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('medicine_photo'); ?></label>
                                            <input type="file"  name="medicine_image"  class="form-control filestyle" id="medicine_image2" />
                                            <span class="text-danger"><?php echo form_error('image'); ?>
                                                <input type="hidden"  name="pre_medicine_image" id="pre_medicine_image"  class="form-control" />
                                        </div>
                                    </div>
                                </div><!--./row-->   

                        </div><!--./col-md-12-->       
                    </div><!--./row--> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="formeditbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right" ><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>

            </div>
           </form>   
        </div>
    </div>    
</div>
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" data-toggle="tooltip" data-placement="bottom"  title="Close" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='edit_delete' class="">
                        <a href="#" onclick="holdModal('editModal')" data-toggle="modal" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                        <a href="#" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete') ?>"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('medicine_details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="view" accept-charset="utf-8" method="get" class="ptt10">
                            <div class="col-lg-1 col-md-2 col-sm-4">
                                <img id="medicine_image" src="#" style="width:100px;height: 100px;" />
                            </div>    
                            <div class="col-lg-11 col-md-10 col-sm-8">
                                <div class="table-responsive">
                                    <table class="table mb0 table-striped table-bordered examples">
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <th width="15%"><?php echo $this->lang->line('medicine_name'); ?></th>
                                            <td width="35%"><span id='medicine_names'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('medicine_category'); ?></th>
                                            <td width="35%"><span id="medicine_category_ids"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <th width="15%"><?php echo $this->lang->line('medicine_company'); ?></th>
                                            <td width="35%"><span id='medicine_companys'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('medicine_composition'); ?></th>
                                            <td width="35%"><span id="medicine_compositions"></span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <th width="15%"><?php echo $this->lang->line('medicine_group'); ?></th>
                                            <td width="35%"><span id='medicine_groups'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('unit'); ?></th>
                                            <td width="35%"><span id="units"></span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <th width="15%"><?php echo $this->lang->line('min_level'); ?></th>
                                            <td width="35%"><span id='min_levels'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('re_order_level'); ?></th>
                                            <td width="35%"><span id="reorder_levels"></span>
                                            </td>

                                        </tr>
                                        <tr>                                  <th></th>
                                            <td></td>
                                            <th width="15%"><?php echo $this->lang->line('vat'); ?>(%)</th>
                                            <td width="35%"><span id='vats'></span></td>
                                            <th width="15%"><?php echo $this->lang->line('unit_packing'); ?></th>
                                            <td width="35%"><span id="unit_packings"></span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th></th>
                                            <td></td>

                                            <th width="15%"><?php echo $this->lang->line('vat_a_c'); ?></th>
                                            <td width="35%"><span id="vat_acs"></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>    
                            </div>
                        </form>            
                    </div><!--./col-md-12-->       
                </div><!--./row-->
                <div id="tabledata"></div> 
            </div>
            <div class="modal-footer">
                <div class="pull-right paddA10">
                </div>
            </div>
        </div>
    </div>    
</div>
<div class="modal fade" id="addBulkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('stock') ?></h4> 
            </div>
             <form id="formbatch" accept-charset="utf-8" method="post" class="ptt10">
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <input type="hidden" name="pharmacy_id" id="pharm_id">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('batch') . " " . $this->lang->line('no'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="batch_no" class="form-control">
                                        <span class="text-danger"><?php echo form_error('batch_no'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('expire') . " " . $this->lang->line('date'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" id="expiry" name="expiry_date" class="form-control">
                                        <span class="text-danger"><?php echo form_error('expiry_date'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('inward') . " " . $this->lang->line('date'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" id="inward_date" name="inward_date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('inward_date'); ?></span>
                                    </div>
                                </div> 

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('packing') . " " . $this->lang->line('qty'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="packing_qty" class="form-control">
                                        <span class="text-danger"><?php echo form_error('packing_qty'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('purchase_rate') . " (" . $currency_symbol . ")"; ?></label>

                                        <input type="text" name="purchase_rate_packing" class="form-control">
                                        <span class="text-danger"><?php echo form_error('purchase_rate_packing'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('quantity'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="quantity" class="form-control">
                                        <span class="text-danger"><?php echo form_error('quantity'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('mrp') . " (" . $currency_symbol . ")"; ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="mrp" class="form-control">
                                        <span class="text-danger"><?php echo form_error('mrp'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('sale_price') . " (" . $currency_symbol . ")"; ?></label>
                                        <small class="req"> *</small> 
                                        <input  name="sale_rate" type="text" class="form-control"/>
                                        <span class="text-danger"><?php echo form_error('sale_rate'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('batch') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label>

                                        <input type="text" name="amount" class="form-control">
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div> 
                            </div><!--./row-->   

                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>
               <div class="modal-footer">
                     <button type="submit" id="formbatchbtn" data-loading-text="<?php echo $this->lang->line("processing") ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
           </form>   
        </div>
      
    </div>    
</div>

<div class="modal fade" id="addBadStockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_bad_stock'); ?></h4> 
            </div>
            
             <form id="formstock" accept-charset="utf-8" method="post" class="ptt10">  
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                            <input type="hidden" name="pharmacy_id" id="pharm_id" >
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('batch_no'); ?></label>
                                        <small class="req"> *</small> 
                                        <select name="batch_no" onchange="getExpire(this.value)" id="batch_stock_no" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('batch_no'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('expiry_date'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" id="batch_expire"  name="expiry_date" id="stockexpiry_date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('expiry_date'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('outward_date'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text"  name="inward_date" value="<?php echo date($this->customlib->getHospitalDateFormat()) ?>" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('inward_date'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('qty'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="packing_qty" class="form-control">
                                        <input type="hidden" name="pharmacy_id" id="pharmacy_stock_id" class="form-control">
                                        
                                        <input type="hidden" name="available_quantity" id="batch_available_qty" class="form-control">
                                        <input type="hidden" name="medicine_batch_id" id="medicine_batch_id" class="form-control">
                                        <span class="text-danger"><?php echo form_error('packing_qty'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('note'); ?></label>
                                        <textarea  name="note" class="form-control "></textarea>
                                    </div>
                                </div> 

                            </div><!--./row-->   
                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>
            <div class="modal-footer">
                <button type="submit" id="formstockbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
            </div>
          </form>    
        </div>
    </div>    
</div>
<script>
$(document).ready(function () {
    $("#formadd").on('submit', function (e) {
    e.preventDefault();
    let submit_btn = $("#formaddbtn");
    submit_btn.button('loading');    

    let medicine_name = $("input[name='medicine_name']").val();
    let medicine_category_id = $("select[name='medicine_category_id']").val();
    let medicine_company = $("input[name='medicine_company']").val();
    let medicine_composition = $("input[name='medicine_composition']").val();
    let medicine_group = $("input[name='medicine_group']").val();
    let unit = $("input[name='unit']").val();
    let min_level = $("input[name='min_level']").val();
    let reorder_level = $("input[name='reorder_level']").val();
    let vat = $("input[name='vat']").val();
    let unit_packing = $("input[name='unit_packing']").val();
    let vat_ac = $("input[name='vat_ac']").val();
    let note = $("textarea[name='note']").val();
    let fileInput = document.getElementById('file');
    let file = fileInput.files[0];
    let errorMessages = [];    
    if (!medicine_name) errorMessages.push('Medicine Name is required');
    if (!medicine_category_id) errorMessages.push('Medicine Category is required');
    if (!medicine_company) errorMessages.push('Medicine Company is required');
    if (!medicine_composition) errorMessages.push('Medicine Composition is required');
    if (!medicine_group) errorMessages.push('Medicine Group is required');
    if (!unit) errorMessages.push('Unit is required');
    if (!unit_packing) errorMessages.push('Unit Packing is required');
    let medicineNamePattern = /^[a-zA-Z0-9\s]+$/;
    if (medicine_name && !medicineNamePattern.test(medicine_name)) {
        errorMessages.push('Medicine Name should only contain letters, numbers, and spaces');
    }
    let categoryPattern = /^\d+$/;
    if (medicine_category_id && !categoryPattern.test(medicine_category_id)) {
        errorMessages.push('Medicine Category ID should be a valid number');
    }    
    let numericPattern = /^[0-9]+(\.[0-9]{1,2})?$/;
    if (min_level && !numericPattern.test(min_level)) {
        errorMessages.push('Minimum level should be a valid number');
    }
    if (reorder_level && !numericPattern.test(reorder_level)) {
        errorMessages.push('Reorder level should be a valid number');
    }  
    if (errorMessages.length > 0) {
        errorMsg(errorMessages.join('<br>'));
        submit_btn.button('reset');
        return;
    }
    let formDataObj = {
        "medicine_name": medicine_name,
        "medicine_category_id": parseInt(medicine_category_id),
        "medicine_company": medicine_company,
        "medicine_composition": medicine_composition,
        "medicine_group": medicine_group,
        "unit": unit,
        "min_level": min_level || '',
        "reorder_level": reorder_level  || '',
        "vat": parseFloat(vat) || '0',
        "unit_packing": unit_packing,
        "vat_ac": vat_ac || '',
        "note": note || '',
        "is_active": "yes",
        "Hospital_id": <?=$data['hospital_id']?> 
    };
    if (file) {
        let formData = new FormData();
        formData.append("file", file);
        $.ajax({
            url: 'https://phr-api.plenome.com/file_upload',
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                let fileData = response.data;
                formDataObj["medicine_image"] = fileData;
                submitData(formDataObj);
            },
            error: function () {
                errorMsg('File upload failed');
                submit_btn.button('reset');
            }
        });
    } else {
        formDataObj["medicine_image"] = ' ';
        submitData(formDataObj);  // Proceed without file
    }

    function submitData(data) {
        $.ajax({
            url: '<?=$api_base_url?>pharmacy',
            type: "POST",
            data: JSON.stringify(data),
            dataType: 'json',
            contentType: 'application/json',
            success: function (response) {
                let data = response;
                successMsg(data[0]["data "].messege || 'Successfully');
                location.reload();
                submit_btn.button('reset');
            },
            error: function () {
                errorMsg('Internal server error');
                submit_btn.button('reset');
            }
        });
    }
});



$(document).ready(function () {
    $("#formstock").on('submit', function (e) {
        e.preventDefault();
        $("#formstockbtn").button('loading');

        let formData = new FormData(this);
        let formDataObj = {};
        let errorMessages = [];

        for (let [key, value] of formData.entries()) {
            formDataObj[key] = value;
        }

        if (!formDataObj.medicine_batch_id) {
            errorMessages.push('Medicine Batch ID is required');
        } else if (!/^[A-Za-z0-9]+$/.test(formDataObj.medicine_batch_id)) {
            errorMessages.push('Medicine Batch ID must be alphanumeric');
        }

        if (!formDataObj.pharmacy_id) {
            errorMessages.push('Pharmacy ID is required');
        } else if (!/^[0-9]+$/.test(formDataObj.pharmacy_id)) {
            errorMessages.push('Pharmacy ID must be numeric');
        }

        if (!formDataObj.inward_date) {
            errorMessages.push('Inward Date is required');
        }

        if (!formDataObj.expiry_date) {
            errorMessages.push('Expiry Date is required');
        }

        if (!formDataObj.batch_no) {
            errorMessages.push('Batch Number is required');
        }

        if (!formDataObj.packing_qty) {
            errorMessages.push('Packing Quantity is required');
        } else if (!/^[0-9]+$/.test(formDataObj.packing_qty)) {
            errorMessages.push('Packing Quantity must be a valid number');
        }

        if (errorMessages.length > 0) {
            errorMsg(errorMessages.join('<br>'));
            $("#formstockbtn").button('reset');
            return;
        }

        let inwardDate = new Date(formDataObj.inward_date);
        let outwardDate = new Date(inwardDate);
        outwardDate.setDate(outwardDate.getDate() + 1);

        let expiryParts = formDataObj.expiry_date.split('/');
        let expiryDate = new Date(expiryParts[1], expiryParts[0], 0, 18, 30, 0);

        let editkeydata = {
            "medicine_batch_details_id": formDataObj.medicine_batch_id,
            "pharmacy_id": formDataObj.pharmacy_id,
            "outward_date": new Date(new Date(formDataObj.inward_date).setDate(new Date(formDataObj.inward_date).getDate() + 1)).toISOString().slice(0, 19).replace('T', ' '),
            "expiry_date": new Date(new Date(Date.parse(formDataObj.expiry_date.replace('/', ' 1, '))).getFullYear(), new Date(Date.parse(formDataObj.expiry_date.replace('/', ' 1, '))).getMonth() + 1, 0, 18, 30, 0).toISOString().slice(0, 19).replace('T', ' '),
            "batch_no": formDataObj.batch_no,
            "quantity": formDataObj.packing_qty,
            "note": formDataObj.note,
            "Hospital_id": <?=$data['hospital_id']?> 
        };
        $.ajax({
            type: "GET",
            url: "<?php echo base_url(); ?>admin/pharmacy/stackcount",
            data: {
                medicine_batch_id: formDataObj.medicine_batch_id,
                pharmacy_id: formDataObj.pharmacy_id,
            },
            dataType: "json",
            success: function (response) {
                let total_avaiable_count = response;
                console.log(total_avaiable_count);
                console.log(formDataObj.packing_qty);
                let quantity = parseInt(formDataObj.packing_qty, 10) || 0;
                if (total_avaiable_count < quantity) {
                    errorMsg('Not enough stock available for this batch');
                    $("#formstockbtn").button('reset');
                    console.log("dsfsdf");
                } else {
                    submitForm(editkeydata);
                }
            },
            error: function () {
                errorMsg('Error checking stock availability');
                $("#formstockbtn").button('reset');
            }
        });
    });
    function submitForm(editkeydata) {
        $.ajax({
            url: '<?= $api_base_url ?>pharmacy/badMedicineStock',
            type: "POST",
            data: JSON.stringify(editkeydata),
            dataType: 'json',
            contentType: 'application/json',
            cache: false,
            processData: false,
            success: function (response) {
                let message = (response[0] && response[0]["data"] && response[0]["data"].message) || 'Successfully updated';
                successMsg(message);
                location.reload();
                $("#formstockbtn").button('reset');
            },
            error: function (xhr) {
                if (xhr.status === 500) {
                    errorMsg('Internal server error');
                } else {
                    errorMsg('Error occurred');
                }
                $("#formstockbtn").button('reset');
            }
        });
    }
});

$("#formedit").on('submit', function (e) {
    e.preventDefault();
    let submit_btn = $("#formeditbtn");
    submit_btn.button('loading');

    let fileUploadUrl = 'https://phr-api.plenome.com/file_upload';
    let fileInput = document.getElementById('medicine_image2');
    let oldFileInput = document.getElementById('pre_medicine_image');
    let file = fileInput.files[0];
    
    // Gather input values
    let id = $("input[name='id']").val();
    let medicine_name = $("input[name='medicine_name1']").val();
    let medicine_category_id = $("select[name='medicine_category_id']").val();
    let medicine_company = $("input[name='medicine_company1']").val();
    let medicine_composition = $("input[name='medicine_composition1']").val();
    let medicine_group = $("input[name='medicine_group1']").val();
    let unit = $("input[name='unit1']").val();
    let min_level = $("input[name='min_level1']").val()|| '';
    let reorder_level = $("input[name='reorder_level1']").val() || '';
    let vat = $("input[name='vat1']").val()||0;
    let unit_packing = $("input[name='unit_packing1']").val();
    let vat_ac = $("input[name='vat_ac1']").val() || 0;
    let note = $("textarea[name='edit_note']").val() || '';    

    if (!id) {
        errorMsg('ID is required');
        submit_btn.button('reset');
        return;
    }
    if (!medicine_name) {
        errorMsg('Medicine Name is required');
        submit_btn.button('reset');
        return;
    }
    if (!medicine_category_id) {
        errorMsg('Medicine Category is required');
        submit_btn.button('reset');
        return;
    }
    if (!medicine_company) {
        errorMsg('Medicine Company is required');
        submit_btn.button('reset');
        return;
    }
    if (!medicine_composition) {
        errorMsg('Medicine Composition is required');
        submit_btn.button('reset');
        return;
    }
    if (!medicine_group) {
        errorMsg('Medicine Group is required');
        submit_btn.button('reset');
        return;
    }
    if (!unit) {
        errorMsg('Unit is required');
        submit_btn.button('reset');
        return;
    }   
    
    if (!unit_packing) {
        errorMsg('Unit Packing is required');
        submit_btn.button('reset');
        return;
    }
    if (file) {
        let formData = new FormData();
        formData.append("file", file);

        $.ajax({
            url: fileUploadUrl,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                let fileData = response.data;
                submitForm(fileData);
            },
            error: function () {
                errorMsg('File upload failed');
                submit_btn.button('reset');
            }
        });
    } else {
        let oldFileData = oldFileInput.value;
        submitForm(oldFileData);
    }

    function submitForm(fileData) {
        let formDataObj = {
            "id": id,
            "medicine_name": medicine_name,
            "medicine_category_id": parseInt(medicine_category_id),
            "medicine_image": fileData,
            "medicine_company": medicine_company,
            "medicine_composition": medicine_composition,
            "medicine_group": medicine_group,
            "unit": unit,
            "min_level": min_level,
            "reorder_level": reorder_level,
            "vat": parseFloat(vat),
            "unit_packing": unit_packing,
            "vat_ac": vat_ac,
            "note": note,
            "is_active": "yes",
            "Hospital_id": <?=$data['hospital_id']?>
        };

        $.ajax({
            url: '<?=$api_base_url?>pharmacy/' + id,
            type: "PATCH",
            data: JSON.stringify(formDataObj),
            dataType: 'json',
            contentType: 'application/json',
            success: function (response) {
                let data = response;
                successMsg(data[0]["data "].messege || 'Successfully updated');
                location.reload();
                submit_btn.button('reset');
            },
            error: function () {
                errorMsg('Internal server error');
                submit_btn.button('reset');
            }
        });
    }
});

});

function delete_record(id) {
    if (confirm('<?php echo $this->lang->line('are_you_sure'); ?>')) {
        $.ajax({
            url: '<?=$api_base_url?>pharmacy/deletePharmacy/' + id + '?Hospital_id=<?=$data['hospital_id']?>',
            type: "DELETE",
            dataType: 'json',
            success: function (response) {
                let message = (response[0] && response[0]["data "] && response[0]["data "].messege) || 'Successfully deleted';
                successMsg(message);
                location.reload();
            },
            error: function (xhr) {
                if (xhr.status === 500) {
                    errorMsg('Internal server error');
                } else {
                    errorMsg('Error occurred');
                }
            }
        });
    }
}


function viewDetail(id) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/pharmacy/getDetails',
        type: "POST",
        data: {pharmacy_id: id},
        dataType: 'json',
        success: function (data) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/pharmacy/getMedicineBatch',
                type: "POST",
                data: {pharmacy_id: id},
                success: function (batchData) {
                    $('#tabledata').html(batchData);
                }
            });

            if (data.medicine_image && data.medicine_image !== "") {
                $.ajax({
                    url: 'https://phr-api.plenome.com/file_upload/getDocs',
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ value: data.medicine_image }),
                    success: function (decryptResponse) {
                        let base64Image = "data:image/png;base64," + decryptResponse;
                        if (base64Image.includes("base64") && decryptResponse && typeof decryptResponse === 'string' && decryptResponse.length > 0) {
                            $("#medicine_image").attr('src', base64Image);
                        } else {
                            $("#medicine_image").attr('src', '<?php echo base_url() ?>uploads/patient_images/no_image.png<?php echo img_time(); ?>');
                        }
                    },
                    error: function () {
                        $("#medicine_image").attr('src', '<?php echo base_url() ?>uploads/patient_images/no_image.png<?php echo img_time(); ?>');
                    }
                });
            } else {
                $("#medicine_image").attr('src', '<?php echo base_url() ?>uploads/patient_images/no_image.png<?php echo img_time(); ?>');
            }



            $("#medicine_names").html(data.medicine_name);
            $("#medicine_category_ids").html(data.medicine_category);
            $("#medicine_companys").html(data.medicine_company);
            $("#medicine_compositions").html(data.medicine_composition);
            $("#medicine_groups").html(data.medicine_group);
            $("#units").html(data.unit);
            $("#min_levels").html(data.min_level);
            $("#reorder_levels").html(data.reorder_level);
            $("#vats").html(data.vat);
            $("#unit_packings").html(data.unit_packing);
            $("#suppliers").html(data.supplier);
            $("#vat_acs").html(data.vat_ac);

            $('#edit_delete').html("<?php if ($this->rbac->hasPrivilege('medicine', 'can_edit')) { ?><a href='#'' onclick='getRecord(" + id + ")' data-toggle='tooltip' data-placement='bottom' data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><?php } if ($this->rbac->hasPrivilege('medicine', 'can_delete')) { ?><a onclick='delete_record(" + id + ")' href='#' data-toggle='tooltip' data-placement='bottom' data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php } ?>");

            holdModal('viewModal');
        }
    });
}
</script>
<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    });
    $(function () {
        $('#easySelectable').easySelectable();
    })

    
</script>

<script type="text/javascript">
                    (function ($) {
                        //selectable html elements
                        $.fn.easySelectable = function (options) {
                            var el = $(this);
                            var options = $.extend({
                                'item': 'li',
                                'state': true,
                                onSelecting: function (el) {

                                },
                                onSelected: function (el) {

                                },
                                onUnSelected: function (el) {

                                }
                            }, options);
                            el.on('dragstart', function (event) {
                                event.preventDefault();
                            });
                            el.off('mouseover');
                            el.addClass('easySelectable');
                            if (options.state) {
                                el.find(options.item).addClass('es-selectable');
                                el.on('mousedown', options.item, function (e) {
                                    $(this).trigger('start_select');
                                    var offset = $(this).offset();
                                    var hasClass = $(this).hasClass('es-selected');
                                    var prev_el = false;
                                    el.on('mouseover', options.item, function (e) {
                                        if (prev_el == $(this).index())
                                            return true;
                                        prev_el = $(this).index();
                                        var hasClass2 = $(this).hasClass('es-selected');
                                        if (!hasClass2) {
                                            $(this).addClass('es-selected').trigger('selected');
                                            el.trigger('selected');
                                            options.onSelecting($(this));
                                            options.onSelected($(this));
                                        } else {
                                            $(this).removeClass('es-selected').trigger('unselected');
                                            el.trigger('unselected');
                                            options.onSelecting($(this))
                                            options.onUnSelected($(this));
                                        }
                                    });
                                    if (!hasClass) {
                                        $(this).addClass('es-selected').trigger('selected');
                                        el.trigger('selected');
                                        options.onSelecting($(this));
                                        options.onSelected($(this));
                                    } else {
                                        $(this).removeClass('es-selected').trigger('unselected');
                                        el.trigger('unselected');
                                        options.onSelecting($(this));
                                        options.onUnSelected($(this));
                                    }
                                    var relativeX = (e.pageX - offset.left);
                                    var relativeY = (e.pageY - offset.top);
                                });
                                $(document).on('mouseup', function () {
                                    el.off('mouseover');
                                });
                            } else {
                                el.off('mousedown');
                            }
                        };
                    })(jQuery);
</script>

<script>

    $(document).ready(function (e) {
        $('#batch_expire,#stockexpiry_date').datepicker({
            format: "M/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true,
            startDate: new Date() // Disable past dates
        });
    });

            function getRecord(id) {
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/pharmacy/getDetails',
                    type: "POST",
                    data: {pharmacy_id: id},
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $("#id").val(data.id);
                        $("#medicines_name").val(data.medicine_name);
                        $("#medicines_category_id").val(data.medicine_category_id);
                        $("#medicine_company").val(data.medicine_company);
                        $("#medicine_composition").val(data.medicine_composition);
                        $("#medicine_group").val(data.medicine_group);
                        $("#unit").val(data.unit);
                        $("#min_level").val(data.min_level);
                        $("#reorder_level").val(data.reorder_level);
                        $("#vat").val(data.vat); 
                        $("#unit_packing").val(data.unit_packing); 
                        $("#pre_medicine_image").val(data.medicine_image);
                        $("#vat_ac").val(data.vat_ac);
                        $("#edit_note").val(data.note);
                        $("#updateid").val(id);
                        $("#oldfile").val(data.medicine_image);
                        $("#viewModal").modal('hide');
                        $(".select2").select2().select2('val', data.medicine_category_id);
                        holdModal('myModaledit');
                    },
                });
            }


            function addBulk(id) {
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/pharmacy/getPharmacy',
                    type: "POST",
                    data: {pharmacy_id: id},
                    dataType: 'json',
                    success: function (data) {
                        $("#pharm_id").val(id);
                        holdModal('addBulkModal');
                    },
                })
            }
            $(document).ready(function (e) {
                $("#formbatch").on('submit', (function (e) {
                    e.preventDefault();
                    $("#formbatchbtn").button("loading");
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/pharmacy/medicineBatch',
                        type: "POST",
                        data: new FormData(this),
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.status == "fail") {
                                var message = "";
                                $.each(data.error, function (index, value) {
                                    message += value;
                                });
                                errorMsg(message);
                            } else {
                                successMsg(data.message);
                                window.location.reload(true);
                            }
                            $("#formbatchbtn").button('reset');
                        },
                        error: function () {
                            
                        }
                    });
                }));
            });

            function holdModal(modalId) {
                $('#' + modalId).modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            }

            function addbadstock(id) {
                $("#pharmacy_stock_id").val(id);
                getbatchnolist(id);
                holdModal('addBadStockModal');
            }

 
            function getbatchnolist(id, selectid = '') {
                var div_data = "";
                $("#batch_stock_no").html("<option value=''><?php echo $this->lang->line('select') ?></option>");
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/pharmacy/getBatchNoList",
                    data: {'pharmacy_id': id},
                    dataType: 'json',
                    success: function (res) {
                        console.log(res);
                        $.each(res, function (i, obj)
                        {
                            var sel = "";
                            if (obj.batch_no == selectid) {
                                sel = "selected";
                            }
                            div_data += "<option " + sel + " value='" + obj.batch_no + "'>" + obj.batch_no + "</option>";
                        });
                        $('#batch_stock_no').append(div_data);
                    }
                });
            }

            function getExpire(batch_no) {
               
                if(batch_no==""){
                 $("#batch_expire").val('');
               }else{
                    $.ajax({
                        type: "POST",
                        url: base_url + "admin/pharmacy/getExpireDate",
                        data: {'batch_no': batch_no},
                        dataType: 'json',
                        success: function (data) {
                            if (data != null) {
                                $('#batch_expire').val(data.expiry);
                                $('#batch_available_qty').val(data.available_quantity);
                                $('#medicine_batch_id').val(data.id);
                            }
                        }
                    });
               }
            }

$(document).on('click', '.delete_selected', function () {
var $this = $(this);
let obj = [];
$('input:checkbox.enable_delete').each(function () {
    if (this.checked) {
        obj.push($(this).val());
    }
});
if (confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_delete_this'); ?>')) {
    $.each(obj, function (index, id) {
        $.ajax({
            url:'<?=$api_base_url?>pharmacy/deletePharmacy/'+id+'?Hospital_id=1',          
            type: "Delete",
            dataType: 'json',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res) {
                successMsg(res.message);
                location.reload();
                $this.button('reset');
                if (res.status) {
                    table.ajax.reload(); 
                }
            },
            error: function (xhr) { 
                alert("Error occurred. Please try again.");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });
}
});


    $('.close_btn').click(function(){
        $('#formstock')[0].reset();
    });
</script>


<script type="text/javascript">
// $(".addmedicine").click(function(){
// 	$('#formadd').trigger("reset");	
// });
$('#myModal').on('hidden.bs.modal', function () {
     $(".filestyle").next(".dropify-clear").trigger("click");
    $(".medicine_category_id").select2("val", "");
    $('#formadd').find('input:text, input:password, input:file, textarea').val('');
    $('#formadd').find('select option:selected').removeAttr('selected');
    $('#formadd').find('input:checkbox, input:radio').removeAttr('checked');
});

$("input[name='checkAll']").click(function () {
    $("input[name='pharmacy[]']").not(this).prop('checked', this.checked);
});
</script>
<!-- //========datatable start===== -->
<script type="text/javascript">
( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('ajaxlist','admin/pharmacy/getpharmacyDatatable',[],[],100,[
          { 'bSortable': false, 'aTargets': [ 0,-1 ] }
       ]);
    });
} ( jQuery ) )
</script>
<!-- //========datatable end===== -->