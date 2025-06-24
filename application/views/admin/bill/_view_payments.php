<?php 
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$print = ($module == 'radiology_billing_id') ? 'print_receipt' : 'print_trans';
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover example">
                <thead class="thead-dark">
                    <tr>
                        <th><?php echo $this->lang->line('date'); ?></th>      
                        <!-- <th><?php echo $this->lang->line('section'); ?></th>                  -->
                        <th>Transation ID</th>
                        <th><?php echo $this->lang->line('payment_mode'); ?></th>
                        <th class="text-right"><?php echo $this->lang->line('paid_amount') . " (" . $currency_symbol . ")"; ?></th>
                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total = 0;
                        if (!empty($payment_details)) {
                            foreach ($payment_details as $payment) {
                                if (!empty($payment['amount'])) {
                                    $total += $payment['amount'];
                                }
                    ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($payment['payment_date'])); ?></td>
                            <!-- <td><?php echo $payment["section"]; ?></td> -->
                            <td><?php echo $this->customlib->getSessionPrefixByType('transaction_id'). $payment["id"]; ?></td>
                            <td>
                                <?php echo $this->lang->line(strtolower($payment["payment_mode"])); ?><br>
                                <?php
                                    if ($payment['payment_mode'] == "Cheque") {
                                        if ($payment['cheque_no'] != '') {
                                            echo $this->lang->line("cheque_no") . ": " . $payment['cheque_no'] . "<br>";
                                        }
                                        if ($payment['cheque_date'] != '' && $payment['cheque_date'] != '0000-00-00') {
                                            echo $this->lang->line("cheque_date") . ": " . $this->customlib->YYYYMMDDTodateFormat($payment['cheque_date']);
                                        }
                                    }
                                ?>
                            </td>
                            <td class="text-right"><?php echo amountFormat($payment["amount"]); ?></td>
                            <td class="text-right">
                                <?php if ($payment['payment_mode'] == "Cheque" && $payment['attachment'] != "") { ?>
                                    <a href='<?php echo site_url('admin/transaction/download/' . $payment['id']); ?>' class='btn btn-default btn-xs' title='<?php echo $this->lang->line('download'); ?>'>
                                        <i class='fa fa-download'></i>
                                    </a>
                                <?php } ?>
                                <a href="javascript:void(0);" class="btn btn-default btn-xs <?php echo $print; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>" data-record-id="<?php echo $payment['id']; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spi'></i>">
                                    <i class="fa fa-print"></i>
                                </a> 
                            </td>
                        </tr>
                    <?php } } ?>
                    <tr class="total-row bg-light">
                        <td colspan="3" class="text-right font-weight-bold"><strong><?php echo $this->lang->line('total'); ?></strong></td>
                        <td class="text-right font-weight-bold"><strong><?php echo $currency_symbol . amountFormat($total); ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>                        
        </div>
    </div>
</div>  
