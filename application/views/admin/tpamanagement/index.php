<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
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
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('tpa_management'); ?></h3>
                        <div class="box-tools pull-right box-tools-md">
                            <?php if ($this->rbac->hasPrivilege('organisation', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm organisation"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_tpa'); ?></a> 
                            <?php } ?>
                        </div>    
                    </div><!-- /.box-header -->
                   
                        <div class="box-body">
                            <div class="download_label"><?php echo $title; ?></div>
                            <table class="table table-striped table-bordered table-hover ajaxlist" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('tpa_management'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('address'); ?></th>
                                        <th><?php echo $this->lang->line('contact_person_name'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('contact_person_phone'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                 
                                </tbody>
                            </table>
                        </div>
                </div>  
            </div>
        </div> 
    </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_tpa'); ?></h4> 
            </div>
            <form id="formadd" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
                   <div class="scroll-area">
                        <div class="modal-body pt0 pb0">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small> 
                                            <input id="name" name="name" placeholder="" type="text" class="form-control"   autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('code'); ?></label><small class="req"> *</small> 
                                            <input id="code" name="code" placeholder="" type="text" class="form-control"   autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('contact_no'); ?></label><small class="req"> *</small> 
                                            <input id="name" name="contact_number" placeholder="" type="text" class="form-control"   autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="email"><?php echo $this->lang->line('address'); ?></label> 
                                            <textarea name="address" class="form-control" autocomplete="off"></textarea>
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('contact_person_name'); ?> </label>
                                            <input id="name" name="contact_person_name" placeholder="" type="text" class="form-control"   autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('contact_person_phone'); ?></label>
                                            <input id="name" name="contact_person_phone" placeholder="" type="text" class="form-control"   autocomplete="off" />
                                        </div>
                                    </div>
                                </div><!--./row-->   
                        </div>     
                   </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>     
        </div>
    </div> 
</div> 

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_tpa'); ?></h4> 
            </div>
            <form id="formedit" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
                <div class="scroll-area"> 
                    <div class="modal-body pt0 pb0">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small> 
                                        <input id="ename" name="ename" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('code'); ?></label><small class="req"> *</small> 
                                        <input id="ecode" name="ecode" placeholder="" type="text" class="form-control"  value="<?php echo set_value('code'); ?>" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('contact_no'); ?></label><small class="req"> *</small> 
                                        <input id="econtact_number" name="econtact_number" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('address'); ?></label> 
                                        <textarea name="eaddress" id="eaddress" class="form-control" autocomplete="off"></textarea>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('contact_person_name'); ?> </label>
                                        <input type="hidden" id="org_id" name="org_id" >
                                        <input id="econtact_persion_name" name="econtact_persion_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('contact_person_phone'); ?> </label>
                                        <input id="econtact_persion_phone" name="econtact_persion_phone" placeholder="" type="text" class="form-control"   autocomplete="off" />
                                    </div>
                                </div>
                            </div><!--./row-->  
                    </div><!--./col-md-12-->       
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formeditbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>     
        </div>
    </div> 
</div>

<script type="text/javascript">
    function get_orgdata(id) {
        $('#editModal').modal('show')
        $.ajax({
            url: '<?php echo base_url(); ?>admin/tpamanagement/get_data/' + id,
            dataType: 'json',
            success: function (res) {

                $('#org_id').val(res.id);	
                $('#ename').val(res.ename);
                $('#ecode').val(res.ecode);
                $('#econtact_number').val(res.econtact_number);
                $('#eaddress').val(res.eaddress);
                $('#econtact_persion_name').val(res.econtact_persion_name);
                $('#econtact_persion_phone').val(res.econtact_persion_phone);
            }
        });
    }
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
<script type="text/javascript">
$(document).ready(function (e) {
    $("#formadd").on('submit', function (e) {
        $("#formaddbtn").button('loading');
        e.preventDefault();
        
        var formData = new FormData(this);
        var formObject = {};
        formData.forEach(function (value, key) {
            formObject[key] = value;
        });

        // Validation checks
        let errorMsg1 = "";
        if (!formObject.name) {
            errorMsg1 += "Name is required.\n";
        }
        if (!formObject.code) {
            errorMsg1 += "Code is required.\n";
        }
        if (!formObject.contact_number) {
            errorMsg1 += "Contact No is required.\n";
        }

        if (errorMsg1) {
            errorMsg(errorMsg1.trim());
            $("#formaddbtn").button('reset');
            return;
        }

        let finalData = {
            "organisation_name": formObject.name,
            "code": formObject.code,
            "contact_no": formObject.contact_number,
            "address": formObject.address,
            "contact_person_name": formObject.contact_person_name,
            "contact_person_phone": formObject.contact_person_phone,
            "Hospital_id": <?=$data['hospital_id']?>
        };

        console.log(JSON.stringify(finalData, null, 2));

        $.ajax({
            url: '<?=$api_base_url?>tpa-management',
            type: "POST",
            data: JSON.stringify(finalData),
            dataType: 'json',
            contentType: 'application/json',
            cache: false,
            success: function (data) {
                let message = data[0]['data '].messege;
                successMsg(message);
                location.reload();                
                $("#formaddbtn").button('reset');
            },
            error: function () {
                console.error("Error occurred during the AJAX request.");
                $("#formaddbtn").button('reset');
            }
        });
    });
});
            

$(document).ready(function (e) {
    $("#formedit").on('submit', function (e) {
        $("#formeditbtn").button('loading');
        e.preventDefault();
        
        var formData = new FormData(this);
        var formObject = {};
        formData.forEach(function (value, key) {
            formObject[key] = value;
        });

        let finalData = {
            "id": formObject.org_id,
            "organisation_name": formObject.ename,
            "code": formObject.ecode,
            "contact_no": formObject.econtact_number,
            "address": formObject.eaddress,
            "contact_person_name": formObject.econtact_persion_name,
            "contact_person_phone": formObject.econtact_persion_phone,
            "Hospital_id": <?= $data['hospital_id'] ?>
        };

        console.log(JSON.stringify(finalData));

        $.ajax({
            url: '<?=$api_base_url?>tpa-management/' + formObject.org_id,
            type: "PATCH",
            data: JSON.stringify(finalData),
            dataType: 'json',
            contentType: 'application/json',
            cache: false,
            success: function (data) {
                let message = data[0]['data'].message;
                successMsg(message);
                location.reload();    
                $("#formeditbtn").button('reset');
            },
            error: function () {
                console.error("Error occurred during the AJAX request.");
                $("#formeditbtn").button('reset');
            }
        });
    });
});

function delete_tpa(id)
{
    var r = confirm("Are you sure you want to delete this TPA?");
    if (r == true) {
        $.ajax({
            url: '<?=$api_base_url?>tpa-management/' + id +'?hos_id=<?=$data['hospital_id']?>',
            type: "DELETE",
            cache: false,
            success: function (data) {
                let message = data[0].message;
                successMsg(message);
                location.reload();
            },
            error: function () {
                console.error("Error occurred during the AJAX request.");
            }
        });
    }
}
			
$(".organisation").click(function(){
	$('#formadd').trigger("reset");
});

    $(document).ready(function (e) {
        $('#myModal,#editModal').modal({
        backdrop: 'static',
        keyboard: false,
        show:false
        });
    });
</script>
<!-- //========datatable start===== -->
<script type="text/javascript">
( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('ajaxlist','admin/tpamanagement/gettpadatatable',[],[],100);
    });
} ( jQuery ) )
</script>
<!-- //========datatable end===== -->