<?php 
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>

<div class="row">
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('reference_no'); ?> : </strong>
      <?php echo !empty($operation_theater_reference_no.$otdetails->id) ? $operation_theater_reference_no.$otdetails->id : '-'; ?>
    </p>
  </div>
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('operation_name'); ?> : </strong>
      <?php echo !empty($otdetails->operation) ? $otdetails->operation : '-'; ?>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('date'); ?> : </strong>
      <?php echo !empty($otdetails->date) ? date($this->customlib->getHospitalDateFormat(true, true), strtotime($otdetails->date)) : '-'; ?>
    </p>
  </div>
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('operation_category'); ?> :  </strong>
      <?php echo !empty($otdetails->category) ? $otdetails->category : '-'; ?>
    </p>
  </div>
</div> 

<div class="row">
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('consultant_doctor'); ?> : </strong>
      <?php echo (!empty($otdetails->name) && !empty($otdetails->surname) && !empty($otdetails->employee_id)) ? $otdetails->name.' '.$otdetails->surname.' ('.$otdetails->employee_id.')' : '-'; ?>
    </p>
  </div>
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('assistant_consultant').' 1'; ?> : </strong>
      <?php echo !empty($otdetails->ass_consultant_1) ? $otdetails->ass_consultant_1 : '-'; ?>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('assistant_consultant').' 2'; ?> : </strong>
      <?php echo !empty($otdetails->ass_consultant_2) ? $otdetails->ass_consultant_2 : '-'; ?>
    </p>
  </div>
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('anesthetist'); ?> : </strong>
      <?php echo !empty($otdetails->anesthetist) ? $otdetails->anesthetist : '-'; ?>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('anaethesia_type'); ?> : </strong>
      <?php echo !empty($otdetails->anaethesia_type) ? $otdetails->anaethesia_type : '-'; ?>
    </p>
  </div>
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('ot_technician'); ?> : </strong>
      <?php echo !empty($otdetails->ot_technician) ? $otdetails->ot_technician : '-'; ?>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('ot_assistant'); ?> : </strong>
      <?php echo !empty($otdetails->ot_assistant) ? $otdetails->ot_assistant : '-'; ?>
    </p>
  </div>
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('remark'); ?> : </strong>
      <?php echo !empty($otdetails->remark) ? $otdetails->remark : '-'; ?>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <p><strong> <?php echo $this->lang->line('result'); ?> : </strong>
      <?php echo !empty($otdetails->result) ? $otdetails->result : '-'; ?>
    </p>
  </div>
</div>

<?php  
if (!empty($fields)) {
    foreach ($fields as $fields_key => $fields_value) {
        $display_field = !empty($otdetails->{$fields_value->name}) ? $otdetails->{$fields_value->name} : '-';
        if ($fields_value->type == "link" && !empty($otdetails->{$fields_value->name})) {
            $display_field = "<a href='" . $otdetails->{$fields_value->name} . "' target='_blank'>" . $otdetails->{$fields_value->name} . "</a>";
        }
?>
<div class="row">
  <div class="col-md-12">
    <p><strong> <?php echo $fields_value->name; ?> : </strong>
      <?php echo $display_field; ?>
    </p>
  </div>
</div>
<?php } } ?>
