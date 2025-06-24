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
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('radiology_single_billing'); ?></h3>
                         <div class="box-tools pull-right">
                           <button type="button" class="btn btn-primary btn-sm assigntest" id="load1" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_patient'); ?></button>
                            
                           
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('radiology_test_reports'); ?></div>
                <div class="">        
                    <table class="table table-striped table-bordered table-hover ajaxlist" id="testreport" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('radiology_test_reports'); ?>">
                            <thead class="white-space-nowrap">
                            <tr>
                                <th><?php echo $this->lang->line('bill_no'); ?></th>
                                <th><?php echo $this->lang->line('case_id'); ?></th>
                                <th><?php echo $this->lang->line('reporting_date'); ?></th> 
                                <th><?php echo $this->lang->line('patient_name'); ?></th>
                                <th><?php echo $this->lang->line('reference_doctor'); ?></th>
                                <th><?php echo $this->lang->line('note'); ?></th>
                                <?php 
                                if (!empty($fields)) {
                                        foreach ($fields as $fields_key => $fields_value) {
                                            ?>
                                            <th><?php echo $fields_value->name; ?></th>
                                             <?php
                                        } 
                                    }
                                ?> 
                                <th class="" ><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?></th>
                                <th class="" ><?php echo $this->lang->line('paid_amount'). ' (' . $currency_symbol . ')'; ?></th>
                                <th class="text-right" ><?php echo $this->lang->line('balance_amount'). ' (' . $currency_symbol . ')'; ?></th>
                            </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                         </table>
                       </div>  
                    </div>
                </div>                                                    
            </div>
        </div>  
    </section>
</div>
<div class="modal fade" id="assigntestModal" aria-hidden="true" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
        <form id="bill" accept-charset="utf-8" method="post">
            <div class="modal-header modal-media-header">
                 <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-9">
                        <div class="p-2 select2-full-width">
                            <select name="patientid" class="form-control patient_list_ajax" id="addpatient_id" name=''>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-1">
                        <div class="p-2">        
                        <?php if ($this->rbac->hasPrivilege('patient', 'can_add')) {?>
                            <a data-toggle="modal" id="add" onclick="holdModal('myModalpa')" class="modalbtnpatient"><i class="fa fa-plus"></i>  <span><?php echo $this->lang->line('new_patient'); ?></span></a>
                        <?php }?>
                       </div>
                    </div>   
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="p-2">
                            <div class="input-group">
                                <input type="text" class="form-control border-0" id="prescription_no" placeholder="<?php echo $this->lang->line('prescription_no'); ?>" name="prescription_no">
                                <div class="input-group-btn">
                                    <button class="btn btn-default btn-group-custom" type="button" id="search_prescription"><i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>    
             
                </div><!--./d-flex-->
            </div><!--./modal-header-->
                <div class="pup-scroll-area">
                    <div class="tabinsetbottom pt5">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-4">
                                    <label><?php echo $this->lang->line('bill_no'); ?><input readonly name="bill_no" id="billno" type="text" class="transparentbg-border"/>
                                    <span class="text-danger"><?php echo form_error('bill_no'); ?></span></label>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <label><?php echo $this->lang->line('case_id'); ?>
                                        <input readonly name="case_reference_id" id="case_reference_id" type="text" class="transparentbg-border"/>
                                        <span class="text-danger"><?php echo form_error('case_reference_id'); ?></span>
                                    </label>
                                </div>
                                <div class="col-lg-7 col-md-5 col-sm-4 text-right text-md-left">
                                     <label><?php echo $this->lang->line('date'); ?>
                         <input name="date" type="text"  id="txtDate10"  class="transparentbg-border"/>
                                     </label>
                                </div>
                            </div>
                        </div>
                    </div>            
                    <div class="modal-body pb0">
                        
                    </div><!--./row-->
                </div>
                <div class="modal-footer sticky-footer">
                    <div class="pull-right">                        
                        <p id="demo"></p>
                     <button type="button" onclick="addTotal()" class="btn btn-info" autocomplete="off"><i class="fa fa-calculator"></i> <?php echo $this->lang->line('calculate'); ?></button>&nbsp;

                        <button type="submit" name="save" data-loading-text="<?php echo $this->lang->line('processing'); ?>" style="display: none" id="billsave" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                        <button type="submit" name="save_print" style="display: none; margin-right:2px;" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right printsavebtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?>
                        </button>
                    </div>

                </div>
            </form>

        </div><!--./modal-body-->

    </div>
</div>

<div class="modal fade" id="viewDetailReportModal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='action_detail_report_modal'>

                   </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('bill_details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div id="reportbilldata"></div>
            </div>
        </div>
    </div>    
</div>

<div class="modal fade" id="collectionModal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-media-content">
        <form action="" method="POST" id="form-sample-collected">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='collection_modal_header'>

                   </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('sample_collection'); ?></h4> 
            </div>
        <div class="modal-body">
          <input type="hidden" name="radiology_report_id" value="0">  
          <input type="hidden" name="radiology_bill_id" value="0">  
          <div class="form-group">
                <label><?php echo $this->lang->line('sample_collected_person_name'); ?></label><small class="req"> *</small>
                <select class="form-control" name="collected_by" id="collected_by">
                     <option value=""><?php echo $this->lang->line('select') ?></option>
                            <?php foreach ($radiologist as $dkey => $dvalue) {
                                                            ?>
                            <option value="<?php echo $dvalue["id"]; ?>" <?php
                                    if ((isset($radiologist_select)) && ($radiologist_select == $dvalue["id"])) {
                                        echo "selected";
                                     }
                                ?>><?php echo $dvalue["name"] . " " . $dvalue["surname"]." (".$dvalue["employee_id"].")" ?>
                            </option>   
                        <?php } ?>
                </select>          
            </div>
              <div class="form-group">
                <label><?php echo $this->lang->line('collected_date'); ?></label><small class="req"> *</small>
                <input type="text" class="form-control" name="collected_date" id="collected_date">
              </div>
               <div class="form-group">
                <label><?php echo $this->lang->line('radiology_center'); ?></label><small class="req"> *</small>
                <input type="text" class="form-control" name="radiology_center" id="radiology_center">
              </div>
              </div>
            <div class="modal-footer">
              <button type="submit"  data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right" ><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
      </div>
      </form>
        </div>
    </div>    
</div>
    
<div class="modal fade" id="addReportModal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
              <form action="" method="POST" id="form-report_param">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='collection_modal_header'>
                   </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('add_edit_report'); ?></h4> 
            </div>
            <div class="scroll-area">
                <div class="modal-body">         
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit"  data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right" ><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
      </div>
      </form>
        </div>
    </div>    
</div>

<div class="modal fade" id="addPaymentModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('payments'); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pb0 min-h-3">

                </div>
            </div>
        </div>
    </div>
</div>

<script id="testradio-template" type="text/template">
   <?php
foreach ($testlist as $dkey => $testlist_value) {
    ?>
    <option value='<?php echo $testlist_value["id"]; ?>'>
        <?php echo $testlist_value["test_name"]." (".$testlist_value["short_name"].")"; ?>
    </option>
    <?php
     }
   ?>
</script>

<script type="text/javascript">

    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
    });
    var  total_rows = 1;
    var date_format_new = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var datetime_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(true, true), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY', 'H' => 'hh', 'i' => 'mm']) ?>';

     $(document).ready(function(){
 
           $('input[name="collected_date"]').datepicker({
          format: date_format_new,
          autoclose: true,
          todayHighlight: true
          });
    });

    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }

     $(document).on('click','.assigntest',function(){
            var createModal=$('#assigntestModal');
            var $this = $(this);
             $this.button('loading');
            $.ajax({
                url: '<?php echo base_url(); ?>admin/radio/assigntestradio',
                type: "POST",
                dataType: 'json',
                 beforeSend: function() {
                    $this.button('loading');
                      createModal.addClass('modal_loading');
                },
                success: function(res) {     
                    $('#assigntestModal #billno').val(res.bill_no);
                    $('#assigntestModal .modal-body').html(res.page);
                  
                     $('#assigntestModal .filestyle').dropify();
                     $(".test_name").select2();
                     updateDate();
                    $('#assigntestModal').modal('show');
                       createModal.removeClass('modal_loading');
                },
                   error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                   $this.button('reset');
                      createModal.removeClass('modal_loading');
            },
            complete: function() {
                  $this.button('reset');
                     createModal.removeClass('modal_loading');
            }
            });
        });

        $(document).on('click','.edit_radiology',function(){
            var createModal=$('#assigntestModal');
            var $this = $(this);
            var recordId=$this.data('recordId');
             $this.button('loading');
            $.ajax({
                url: base_url+'admin/bill/editradiology',
                type: "POST",
                data: {'id':recordId},
                dataType: 'json',
                 beforeSend: function() {
                    $this.button('loading');
                      createModal.addClass('modal_loading');
                },
                success: function(res) {   
                    console.log(JSON.stringify(res,null,2));
                    total_rows =res.total_rows; 
                    $('#assigntestModal #billno').val(res.bill_no);
                    $('#case_reference_id').val(res.case_reference_id);
                    // $("#addpatient_id").select2("val", res.patient_id);
                      var option = new Option(res.patient_name, res.patient_id, true, true);
                    $("#bill .patient_list_ajax").append(option).trigger('change');

                    $('#assigntestModal .modal-body').html(res.page);
                    $('#assigntestModal .filestyle').dropify();
                    $('#txtDate10').data("DateTimePicker").date(new Date(res.radiology_date));
                    updateDate();
                    $('#viewDetailReportModal').modal('hide');
                    $('#assigntestModal').modal('show');
                     // $('.select2').select2();
                    createModal.removeClass('modal_loading');
                },
                   error: function(xhr) {
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again') ?>");
                   $this.button('reset');
                    createModal.removeClass('modal_loading');
            },
            complete: function() {
                  $this.button('reset');
                     createModal.removeClass('modal_loading');
            }
            });
        });

       $(document).on('click','.delete_radiology',function(){ 
       if (confirm('<?php echo $this->lang->line("delete_confirm") ?>')) {             
            var $this = $(this);
            var recordId=$this.data('recordId');
            $this.button('loading');
            $.ajax({
                url: '<?=$api_base_url?>radiology-generate-bill/'+recordId+'?hospital_id=<?=$data['hospital_id']?>',
                type: "DELETE",
                data: {'id':recordId},
                dataType: 'json',
                 beforeSend: function() {
                    $this.button('loading');
                    
                },
                success: function(res) {   
                    if (res.status == "fail") {
                        
                        errorMsg(res.message);
                    } else {
                        successMsg(res.message);
                        $('#viewDetailReportModal').modal('hide');
                         table.ajax.reload();
                    }

                  $this.button('reset');
                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                   $this.button('reset');
                    createModal.removeClass('modal_loading');
            },
            complete: function() {
                  $this.button('reset');
            
            }
            });
        }
        });
        function gettestradiodetails(testid,id) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/radio/gettestradiodetails",
                data: {'id': testid },
                dataType: 'json',
                success: function (res) {
                    if (res != null) {
                        console.log(res);
                        $('#reportday_' + id).val(res.report_days);
                        $('#amount_' + id).val(res.standard_charge);
                        $('#taxpercent_' + id).val(res.percentage);
                        var stnd_amt =  $('#amount_' + id).val();
                        var tax_per = $('#taxpercent_' + id).val();
                        var tax_amount = stnd_amt*tax_per/100;
                        $('#taxamount_' + id).val(tax_amount);
                        var day = $('#reportday_' + id).val()
                        getdate(day,id)
                        addTotal()
                    }
                }
            });
        }

       
       
        function getdate(day,id) {
            var report_day =  parseInt(day, 10);
            var selected_date=$("#txtDate10").data('DateTimePicker').date().toDate(); 
            var newdate = new Date(selected_date);
            newdate.setDate(newdate.getDate() + report_day);
            $("#reportdate_"+id).datepicker("update", newdate);           
        }

      
        $(document).on('click','.add-record',function(){
        var table = document.getElementById("tableID");

       // var id = total_rows+1;
        total_rows=total_rows+1;
        var template=$("#testradio-template").html();
        var div = "<td><input type='hidden' id='total_rows' name='total_rows[]' value='" + total_rows + "'><input type='hidden' name='inserted_id_" + total_rows + "' value='0'><select class='form-control test_name select2' style='width:100%'  onchange='gettestradiodetails(this.value," + total_rows + ")' name='test_name_" + total_rows + "' ><option value='<?php echo set_value('test_name_id'); ?>'><?php echo $this->lang->line('select') ?></option>"+template+"</select></td><td><input type='text' name='reportday_" + total_rows + "' id='reportday_" + total_rows + "'  class='form-control text-right days' readonly></td><td><input type='text' name='reportdate_" + total_rows + "' id='reportdate_" + total_rows + "'  class='form-control text-right report_date'></td><td><div class='input-group'><input type='text' name='taxpercent_" + total_rows + "' id='taxpercent_" + total_rows + "'  class='form-control text-right right-border-none taxpercent' autocomplete='off' readonly><span class='input-group-addon'> %</span></div></td><td><input type='text' name='amount_" + total_rows + "' id='amount_" + total_rows + "'  class='form-control text-right amount' readonly><input type='hidden' name='taxamount_" + total_rows + "' id='taxamount_" + total_rows + "'  class='form-control text-right taxamount' readonly></td>";

        var row =  "<tr id='row" + total_rows + "'>" + div + "<td><button type='button' data-row-id='"+total_rows+"' class='closebtn delete_row'><i class='fa fa-remove'></i></button></td></tr>";
        $('#tableID').append(row);
        updateDate();
        $('.test_name').select2();
        total_rows++;
    });

      $(document).on('click','.delete_row',function(e){
            var modal_=$(e.target).closest('div.modal');
            var del_row_id=$(this).data('rowId');
            $("#row" + del_row_id).remove();
            addTotal()
      });


      function addTotal() {
        var total = 0;
        var total_taxamt = 0;
        var medicineTable=$("#assigntestModal .modal-body").find('table.tblProducts');

        medicineTable.find("tbody tr").each(function() {
         total += parseFloat($(this).find("td input.amount").val());
         total_taxamt += parseFloat($(this).find("td input.taxamount").val());
        });
        if(total>0){

        
        var discount_percent = $("#discount_percent").val();
        if (discount_percent != '') {
            var discount = (total * discount_percent) / 100;
            $("#discount").val(discount.toFixed(2));
        } else {
            var discount = $("#discount").val();
          
        }

       

        $("#total").val(total.toFixed(2));
        var net_amount = parseFloat(total) + parseFloat(total_taxamt) - parseFloat(discount);
      
        var cnet_amount = net_amount.toFixed(2)
        $("#net_amount").val(cnet_amount);
        $("#tax").val(total_taxamt.toFixed(2));
        $("#payamount").val(cnet_amount);
        $("#amount").val(cnet_amount);
        $("#billsave").show();
        $(".printsavebtn").show();
    }
       
    }   

        function dateChanged(ev) {      

            var $tblrows = $('.tblProducts').find("tbody tr");

            $tblrows.each(function (index) {
            var $tblrow = $(this); 
            var _row_day = $tblrow.find(".days").val();
            if(_row_day !=""){
            //==============
            var report_day =  parseInt(_row_day, 10);
            var selected_date=$("#txtDate10").data('DateTimePicker').date().toDate() ;
            var newdate = new Date(selected_date);
            newdate.setDate(newdate.getDate() + report_day);
            $tblrow.find(".report_date").datepicker("update", newdate); 
            //================            
                
            }        
            });
        }



$(document).ready(function (e) {
$('#txtDate10').datetimepicker({
format: datetime_format,
}).on('dp.change',dateChanged);
$('#txtDate10').data("DateTimePicker").date(new Date());
    });

    function get_Docname(id) {
       
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/doctName',
            type: "POST",
            data: {doctor: id},
            dataType: 'json',
            success: function (res) {
                //console.log(res.name)
                if (res) {
                    $('#doctname').val(res.name + " " + res.surname + " (" + res.employee_id + ")");
                } else {

                }
            }
        });
    }

    function viewDetailReport(id,radiology_id) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/radio/getReportDetails/'  + id +'/'+radiology_id,
            type: "GET",
            data: {id: id},
            success: function (data) {
                $('#reportdatareport').html(data);
                $('#edit_deletereport').html("<?php if ($this->rbac->hasPrivilege('add_radio_patient_test_reprt', 'can_view')) { ?><a href='#' data-toggle='tooltip' onclick='printData(" + id + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a><?php } ?>");
                holdModal('viewModalReport');
            },
        });
    }

    function printData(id) {
             $.ajax({
            url: base_url+'admin/radio/getBillDetails',
            type: "POST",
            data: {'id': id},
            dataType: 'json',
               beforeSend: function() {
            
               },
            success: function (data) {     
                popup(data.page);
            },

             error: function(xhr) { // if error occured
            },
           complete: function() {
           
        
          }
        });
    }

      $(document).on('click','.print_bill',function(){
        var id=$(this).data('recordId');
      
        var $this = $(this);
   
        $.ajax({
            url: base_url+'admin/radio/getBillDetails',
            type: "POST",
            data: {'id': id},
            dataType: 'json',
               beforeSend: function() {
              $this.button('loading');
               },
            success: function (data) {    
           popup(data.page);

            },

             error: function(xhr) { // if error occured
          alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
             $this.button('reset');
               
      },
      complete: function() {
            $this.button('reset');
     
      }
        });


    });

    $('#viewModalbill').on('hidden.bs.modal', function () {
     $('#reportdatabill,#edit_deletebill').html("");
    });

    function viewDetailbill(id) {
        var view_modal=$('#viewModalbill');
        $.ajax({
            url: '<?php echo base_url() ?>admin/radio/getradiobilldetails',
            type: "POST",
            data: {'id': id},
            dataType:"JSON",
            beforeSend: function(){
            $('#reportdatabill,#edit_deletebill').html("");
            $('#viewModalbill').modal('show');
           view_modal.addClass('modal_loading');
           },
           complete: function(){
             view_modal.removeClass('modal_loading');
           },
            success: function (data) {
                $('#reportdatabill').html(data.page);
                $('#edit_deletebill').html(data.actions);
                view_modal.removeClass('modal_loading');
            },
        });
    }

    function deleterecord(id) {
        var url = '<?php echo base_url() ?>admin/radio/deletetestbill/' + id;
        var msg = "<?php echo $this->lang->line('delete_message') ?>";
        delete_recordById(url, msg)
    }

    function editTestReport(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/getRadiologyReport',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                $("#report_id").val(data.id);
                $("#charge_category_html").val(data.charge_category);
                $("#code_html").val(data.code);
                $("#charge_html").val(data.standard_charge);
                if (data.apply_charge == "") {
                    $("#apply_charge").val(data.standard_charge);
                } else {
                    $("#apply_charge").val(data.apply_charge);
                }

                $("#customer_types").val(data.customer_type);
                $("#opdipd").val(data.opd_ipd_no);
                $("#edit_patient_name").val(data.patient_name);
                $("#edit_report_date").val(data.reporting_date);
                $('select[id="edit_consultant_doctor"] option[value="' + data.consultant_doctor + '"]').attr("selected", "selected");
                $("#edit_description").val(data.description);
                $(".select2").select2().select2('val', data.patient_id);
                $("#viewModal").modal('hide');
                holdModal('editTestReportModal');
            },
        })
    }

    $(document).ready(function (e) {
        $("#updatetest").on('submit', (function (e) {
            $("#updatetestbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/radio/updateTestReport',
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
                    $("#updatetestbtn").button('reset');
                },
                error: function () {
                }
            });
        }));
    });

    $(document).on('submit', '#form-sample-collected', function(e) {
    e.preventDefault();
    
    var clicked_btn = $("button[type=submit]", $(this));
    var form = $(this);

    var jsonData = {
        "collection_specialist": form.find('input[name="collected_by"]').val() || 98,
        "collection_date": formatDate(form.find('input[name="collected_date"]').val()),
        "radiology_center": form.find('input[name="radiology_center"]').val() || "Inhos Radiology Center",
        "hospital_id": parseInt('<?=$data['hospital_id']?>')
    };

    console.log(JSON.stringify(jsonData, null, 2));

    $.ajax({
        url: '<?=$api_base_url?>radiology-generate-bill/updateCollectedPerson/' + form.find('input[name="radiology_report_id"]').val(),
        type: "PATCH",
        data: JSON.stringify(jsonData),
        contentType: "application/json",
        dataType: 'json',
        beforeSend: function() {
            clicked_btn.button('loading');
        },
        success: function(data) {
            if (data[0]['data '].status === "fail") {
                var message = "";
                $.each(data.error, function(index, value) {
                    message += value;
                });
                errorMsg(message);
            } else {
                successMsg(data[0]["data "].messege);
                clicked_btn.button('reset');
                $('#collectionModal').modal('hide');
                PatientRadiologyDetails(jsonData.radiology_bill_id, clicked_btn);
            }
        },
        error: function(xhr) {
            alert("Error occurred. Please try again.");
            clicked_btn.button('reset');
        },
        complete: function() {
            clicked_btn.button('reset');
        }
    });
});

function formatDate(dateStr) {
    let dateParts = dateStr.split('/');
    return `${dateParts[2]}-${dateParts[0]}-${dateParts[1]}`;
}



$(document).on('submit', '#form-report_param', function(e) {
    e.preventDefault();

    var clicked_btn = $("button[type=submit]", $(this));
    var form = $(this);
    var formData = new FormData(this);
    
    var currentDate = new Date();
    var formattedDate = currentDate.getFullYear() + '-' +
        ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' +
        ('0' + currentDate.getDate()).slice(-2) + ' ' +
        ('0' + currentDate.getHours()).slice(-2) + ':' +
        ('0' + currentDate.getMinutes()).slice(-2) + ':' +
        ('0' + currentDate.getSeconds()).slice(-2);

    var jsonData = {
        "parameter_update": formattedDate,
        "approved_by":formData.get('approved_by'),
        "radiology_result": "Done",
        "hospital_id": <?=$data['hospital_id']?>,
        "radiology_parameterdetail_id": parseInt(formData.get('radiology_parameterdetails[]')),
        "radiology_report_value": formData.get('test_result')
    };
    let editid = formData.get('radiology_bill_id');
    let fileInput = document.getElementById('attachment_report').files[0];
    var documentUrl = $('#filedataalread').val();

    if (fileInput) {
        var fileUploadData = new FormData();
        fileUploadData.append('file', fileInput);

        $.ajax({
            url: 'https://phr-api.plenome.com/file_upload',
            type: 'POST',
            data: fileUploadData,
            contentType: false,
            processData: false,
            success: function(response) {
                jsonData.report_name = jsonData.attachment_report;
                jsonData.radiology_report = response.data;
                // console.log(JSON.stringify(jsonData, null, 4));
                submitForm(jsonData);
            },
            error: function() {
                jsonData.documents = documentUrl;
                submitForm(jsonData);
            }
        });
    } else {
        jsonData.documents = $('#oldfile').val();
        submitForm(jsonData);
    }

    function submitForm(jsonData) {
        console.log(JSON.stringify(jsonData, null, 2));
        $.ajax({
            url: '<?=$api_base_url?>radiology-generate-bill/updateApprovalByPerson/' + editid,
            type: "PATCH",
            data: JSON.stringify(jsonData),
            contentType: "application/json",
            dataType: 'json',
            beforeSend: function() {
                clicked_btn.button('loading');
            },
            success: function(data) {
                if (data[0]['data '].status === "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data[0]["data "].messege);
                    $('#addReportModal').modal('hide');
                    var radiology_bill_id = form.find('input[name="radiology_bill_id"]').val();
                    PatientRadiologyDetails(radiology_bill_id, clicked_btn);
                }
                clicked_btn.button('reset');
            },
            error: function(xhr) {
                alert("Error occurred. Please try again.");
                clicked_btn.button('reset');
            },
            complete: function() {
                clicked_btn.button('reset');
            }
        });
    }    
});



function updateDate(){
     $('#tableID').find('.report_date').datepicker({
      format: date_format_new,
      autoclose: true,
      todayHighlight: true
      });
}
</script>

<script type="text/javascript">
    $(document).on('click','#search_prescription',function(){

     getPrescriptionData();
});


function getPrescriptionData(){
     var createModal=$('#assigntestModal');
     
  $.ajax({
        url: '<?php echo base_url(); ?>admin/radio/prescriptionBill',
        type: "POST",
        data:{'prescription_no':$("input[name=prescription_no]").val(),'date':$('#txtDate10').val()},
        dataType: 'json',
         beforeSend: function() {
             createModal.addClass('modal_loading');
        },
        success: function(res) {     
       $('#assigntestModal .modal-body').html(res.page);
       $('#assigntestModal .filestyle').dropify();
       $('#case_reference_id').val(res.case_reference_id);
        $('.test_name').select2();
       var option = new Option(res.patient_name+" ("+res.patient_id+")", res.patient_id, true, true);
    
        $("#bill .patient_list_ajax").append(option).trigger('change');
   
        updateDate();
        addTotal();
          total_rows=(res.total_rows <= 0) ? 1:res.total_rows;
     
        if(res.total_rows <= 0){
                  errorMsg("<?php echo $this->lang->line('no_prescription_found'); ?>");
            }else{
        var newOption = new Option(res.patient_name, res.patient_id, false, false);
        $('#addpatient_id').append(newOption).trigger('change');
            }
       
        },
           error: function(xhr) { // if error occured
        alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
              createModal.removeClass('modal_loading'); 
    },
    complete: function() {
            createModal.removeClass('modal_loading');
    }
    });
}

</script>
    <script type="text/javascript">

   $(document).on('click','.add_payment',function(){  
            var record_id=$(this).data('recordId'); 
            var $add_btn= $(this);  
            var payment_modal=$('#addPaymentModal');
            payment_modal.addClass('modal_loading'); 
            payment_modal.modal('show'); 
            getPayments(record_id);
    });

   function getPayments(record_id){
         var payment_modal=$('#addPaymentModal');
        $.ajax({
            url: '<?php echo base_url() ?>admin/bill/getRadiologyTransactions',
            type: "POST",
            data: {'id': record_id},
            dataType:"JSON",
            beforeSend: function(){
            },          
            success: function (data) {
         
           $('.modal-body',payment_modal).html(data.page);
            payment_modal.removeClass('modal_loading');  
            },
             error: function () {
             payment_modal.removeClass('modal_loading'); 
            },  complete: function(){
             payment_modal.removeClass('modal_loading'); 
            }
        });

    }

    $(document).on('submit','#add_partial_payment', function(e){
            e.preventDefault();
            var clicked_btn = $("button[type=submit]");
            var radiology_billing_id=$("input[name='radiology_billing_id']",'#add_partial_payment').val();

            var form = $(this);    
            var btn = clicked_btn;
            btn.button('loading');
            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
               processData:false,               
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        getPayments(radiology_billing_id);
                        table.ajax.reload();
                        }
                     btn.button('reset');
                },
                error: function () {

                },
                complete: function(){
                 btn.button('reset');
   }
            }); 

        });

          $(document).on('click','.delete_trans', function(e){
            e.preventDefault();
        var record_id=$(this).data('recordId');         
        var radiology_billing_id=$("input[name='radiology_billing_id']",'#add_partial_payment').val();

            var btn = $(this);       
            btn.button('loading');
             var conf = confirm("<?php echo $this->lang->line('delete_confirm')?>");
    if(conf == true){
        
    
            $.ajax({
                url: base_url+'admin/transaction/deleteByID',
                type: "POST",
                data: {'id':record_id,'radiology_billing_id':radiology_billing_id},
                dataType: 'JSON',               
                success: function (data) {
                    successMsg(data.message);
                    getPayments(radiology_billing_id);
                    btn.button('reset');
                       table.ajax.reload();
                },
                error: function () {
                    btn.button('reset');
                },
                complete: function(){
                 btn.button('reset');
   }
            }); 
}
        });
     

         $(document).on('click','.print_receipt',function(){
      var $this = $(this);
         var record_id=$this.data('recordId')
       $this.button('loading');
      $.ajax({
          url: '<?php echo base_url(); ?>admin/radio/printTransaction',
          type: "POST",
          data:{'id':record_id},
          dataType: 'json',
           beforeSend: function() {
                 $this.button('loading');
      
          },
          success: function(res) {
           popup(res.page);
          },
             error: function(xhr) { // if error occured
          alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                  $this.button('reset');
              
         },
              complete: function() {
                   $this.button('reset');
                 
             }
      });
  });

    $(document).on('click','.view_detail',function(){
         var id=$(this).data('recordId');
         PatientRadiologyDetails(id,$(this));
    });

    function PatientRadiologyDetails(id,btn_obj){
         var modal_view=$('#viewDetailReportModal');
         var $this = btn_obj;   
        $.ajax({
            url: base_url+'admin/bill/getPatientRadiologyDetails',
            type: "POST",
            data: {'id': id},
            dataType: 'json',
            beforeSend: function() {
                $this.button('loading');
                modal_view.addClass('modal_loading');
               },
            success: function (data) {                      
                 $('#viewDetailReportModal .modal-body').html(data.page);  
                 $('#viewDetailReportModal #action_detail_report_modal').html(data.actions);  
                 $('#viewDetailReportModal').modal('show');
                  modal_view.removeClass('modal_loading');
            },
            error: function(xhr) { // if error occured
             alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
             $this.button('reset');
                modal_view.removeClass('modal_loading');
           },
            complete: function() {
            $this.button('reset');
                modal_view.removeClass('modal_loading');
          
           }
        });  
    }

    $(document).on('click','.add_report',function(){
        //alert('Hello');
           var id=$(this).data('recordId');
           var modal_view=$('#addReportModal');
           var $this = $(this);   
           $.ajax({
                url: base_url+'admin/radio/getRadiologyReportDetail',
                type: "POST",
                data: {'id': id},
                dataType: 'json',
                   beforeSend: function() {
                  $this.button('loading');
                   },
                success: function (data) {       
                $('#addReportModal .modal-body').html(data.page);
                $('#addReportModal .filestyle').dropify();
                $('#addReportModal').modal('show');
                },

                 error: function(xhr) { // if error occured
                 alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                 $this.button('reset');
                   
          },
          complete: function() {
                $this.button('reset');
         
          }
            });

    });

    $(document).on('click','.print_report',function(){
       var id=$(this).data('recordId');
  
       var $this = $(this);   
       $.ajax({
            url: base_url+'admin/radio/printPatientReportDetail',
            type: "POST",
            data: {'id': id},
            dataType: 'json',
               beforeSend: function() {
              $this.button('loading');
               },
            success: function (data) {       
          popup(data.page);
            },

             error: function(xhr) { // if error occured
             alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
             $this.button('reset');
               
      },
      complete: function() {
            $this.button('reset');
     
      }
        });

    });
 
    $(document).on('click','.add_collection',function(){
        $('#collected_by').val('');
       var id=$(this).data('recordId');
       var modal_view=$('#collectionModal');
       var $this = $(this);   
       $.ajax({
            url: base_url+'admin/radio/getReportCollectionDetail',
            type: "POST",
            data: {'id': id},
            dataType: 'json',
               beforeSend: function() {
              $this.button('loading');
               },

            success: function (data) {       
            
          $('#collected_by').val(data.report.collection_specialist);
            $("#collectionModal .modal-body").find('input[name="radiology_report_id"]').val(data.report.id);
            $("#collectionModal .modal-body").find('input[name="radiology_bill_id"]').val(data.report.radiology_bill_id);
            $("#collectionModal .modal-body").find('input[name="radiology_center"]').val(data.report.radiology_center);
            $("#collectionModal .modal-body").find('input[name="collected_date"]').datepicker("update", new Date(data.report.collection_date));
            $('#collectionModal').modal('show');
            },

             error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
             $this.button('reset');
               
      },
        complete: function() {
            $this.button('reset');
          }
        });
    });

    $(document).on('change','.payment_mode',function(){
      var mode=$(this).val();
      if(mode == "Cheque"){
        $('.filestyle','#addPaymentModal').dropify();
        $('.cheque_div').css("display", "block");
      }else{
        $('.cheque_div').css("display", "none");
      }
    });
       function popup(data)
    {
        var base_url = '<?php echo base_url() ?>';
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
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body >');
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

    $('#assigntestModal').on('hidden.bs.modal', function () {
        var assigntestModal= $('#assigntestModal');
    $('#addpatient_id').select2("val", "");
    $('#billno,#prescription_no,#case_reference_id',assigntestModal).val("");
      $("#assigntestModal").find('input[name="date"]').data("DateTimePicker").date(new Date());;
   });
</script>


<!-- //========datatable start===== -->
<script type="text/javascript">
( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('ajaxlist','admin/radio/getradiologybillDatatable',[],[],100,
            [
        {  "sWidth": "105px", "aTargets": [ -2,-3 ] ,'sClass': 'dt-body-right'},
        {  "sWidth": "105px", "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'},
               {  "sWidth": "30px", "aTargets": [ 2 ] ,'sClass': 'dt-body-left'},
               {  "sWidth": "100px", "aTargets": [ 0 ] ,'sClass': 'dt-body-left'},
            ]);
    });
} ( jQuery ) )
</script>
<!-- //========datatable end===== -->
<?php $this->load->view('admin/patient/patientaddmodal') ?>
<script type="text/javascript">
    
     $(document).ready(function(){
            $('#assigntestModal,#collectionModal,#addReportModal').modal({
              backdrop: 'static',
              keyboard: true, 
              show: false
            });
            $('.datetime').datetimepicker({
                 format: datetime_format,
            });
           
       });
    
    $(document).ready(function (e) {
        $('#viewDetailReportModal,#addPaymentModal').modal({
        backdrop: 'static',
        keyboard: false,
        show:false
        });
    });
</script>
<script>
$(document).ready(function () {
    $("form#bill button[type=submit]").click(function() {
        $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
    });

    $(document).on('submit', '#bill', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let case_reference_id = formData.get('case_reference_id');
        let ipd_prescription_basic_id = $('#prescription_no').val().replace(/\D/g, '') || $('#ipd_prescription_basic_id').val();
        let patient_id = formData.get('patientid');
        let doctor_id = formData.get('consultant_doctor');
        let doctor_name = formData.get('doctor_name');
        let total = formData.get('total');
        let discount_percentage = formData.get('discount_percent');
        let discount = formData.get('discount');
        let tax_percentage = formData.get('tax_percent') || $('#tax_percentage').val();
        let tax = formData.get('tax');
        let net_amount = formData.get('net_amount');
        let note = formData.get('note') || "none";
        let payment_mode = formData.get('payment_mode');
        let amount = formData.get('amount');
        let cheque_no = formData.get('cheque_no');
        let cheque_date = formData.get('cheque_date');
        let attachment = formData.get('attachment');
        let hospital_id = '<?=$data['hospital_id']?>';

        let data1 = {
            "case_reference_id": case_reference_id,
            "ipd_prescription_basic_id": ipd_prescription_basic_id,
            "patient_id": patient_id,
            "doctor_id": doctor_id,
            "doctor_name": doctor_name,
            "total": total,
            "discount_percentage": discount_percentage,
            "discount": discount,
            "tax_percentage": tax_percentage,
            "tax": tax,
            "net_amount": net_amount,
            "note": note,
            "generated_by": <?=$data['id']?>,
            "payment_mode": payment_mode,
            "amount": amount,
            "hospital_id": hospital_id
        };

        if (payment_mode === 'Cheque') {
            data1.cheque_no = cheque_no;
            data1.cheque_date = cheque_date;
            if (attachment) {
                data1.attachment = attachment;
            }
        }

        let jsonData = {};
        formData.forEach(function(value, key) {
            jsonData[key] = value;
        });

        let radiologyTests = [];
        let totalRows = parseInt(jsonData['total_rows[]']) || 0;
        let edit = $('#radiology_billing_id').val();
        let api_url = edit ? '<?=$api_base_url?>radiology-generate-bill/' + edit : '<?=$api_base_url?>radiology-generate-bill';
        let method = edit ? 'PATCH' : 'POST';
        let api_url2 = edit ? '<?=$api_base_url?>radiology-generate-bill/radioReport/reportUpdate/editradiologyReports': '<?=$api_base_url?>radiology-generate-bill/radiologyReport';

        console.log(JSON.stringify(radiologyTests));

        $.ajax({
            url: api_url,
            type: method,
            data: JSON.stringify(data1),
            contentType: "application/json",
            dataType: 'json',
            success: function (response) { 
                let radiology_bill_id = 
                    response?.[0]?.["data "]?.Radiology_bill_Values?.[0]?.id || 
                    response?.[0]?.["data "]?.updated_values?.[0]?.id;

                for (let i = 1; i <= totalRows; i++) {
                    let testName = jsonData[`test_name_${i}`];
                    let reportDate = jsonData[`reportdate_${i}`];
                    let taxPercent = jsonData[`taxpercent_${i}`];
                    let amount = jsonData[`amount_${i}`];
                    let dateParts = reportDate.split('/');
                    let formattedDate = `${dateParts[2]}-${dateParts[0]}-${dateParts[1]}`;

                    if (testName && reportDate && taxPercent && amount) {
                        let radiologyTest = {
                            "radiology_bill_id": radiology_bill_id,
                            "radiology_id": testName,
                            "patient_id": patient_id,
                            "reporting_date": `${formattedDate} 11:11:11`,
                            "tax_percentage": taxPercent,
                            "apply_charge": amount,
                            "hospital_id": hospital_id,
                            "consultant_doctor": doctor_name,
                            "generated_by": doctor_id
                        };

                        if (edit) {
                            let inserted_id = $("input[name='inserted_id_" + i + "']").val();
                            if (inserted_id) {
                                radiologyTest.id = inserted_id; 
                            }
                        }

                        radiologyTests.push(radiologyTest);
                    }
                }
                $.ajax({
                    url: api_url2,
                    type: method,
                    data: JSON.stringify(radiologyTests),
                    contentType: "application/json",
                    dataType: 'json',
                    success: function (data) {
                        successMsg(data.message);
                        location.reload();
                    },
                    error: function (xhr) {
                        alert("Error occurred. Please try again.");
                    }
                });                    
            },
            error: function (xhr) {
                alert("Error occurred. Please try again.");
            }
        });
    });
});
</script>



