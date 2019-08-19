<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_transaction; ?></td>
      <td class="text-left"><?php echo $column_amount; ?></td>
      <td class="text-left"><?php echo $column_type; ?></td>
      <td class="text-left"><?php echo $column_action; ?></td>
      <td class="text-left"><?php echo $column_date_added; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($transactions) { ?>
      <?php foreach($transactions as $transaction) { ?>
      <tr>
        <td class="text-left"><?php echo $transaction['transaction_id']; ?></td>
        <td class="text-left"><?php echo $transaction['amount']; ?></td>
        <td class="text-left"><?php echo $transaction['type_text']; ?></td>
        <td class="text-left"><?php echo $transaction['date']; ?></td>
        
        <td class="text-left">
          <?php if ($transaction['transaction_id'] && $transaction['type']!="refund" && $transaction['amount']>$transaction['total_refunded']) { ?>
            <input type="text" value="0.00" name="amount" />
              <a class="btn btn-primary" onclick="refund(0)" id="button-refund" data-toggle="tooltip" title="<?php echo $button_partial_refund; ?>"><?php echo $button_partial_refund ?></a>
              
              <a onclick="refund(1)" data-toggle="tooltip" id="button-total-refund" title="<?php echo $button_total_refund; ?>" class="btn btn-danger"><?php echo $button_total_refund ?></i></a>&nbsp;
            <?php } ?>


        </td>
      </tr>
      <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
