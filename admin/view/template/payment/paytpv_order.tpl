<fieldset>
  <legend><?php echo $text_transaction; ?></legend>
  <div id="paytpv-transaction"></div>
</fieldset>

<fieldset>
  <legend><?php echo $text_payment; ?></legend>
  <table class="table table-bordered">
    <tr>
      <td><?php echo $text_capture_status; ?></td>
      <td id="capture-status"><?php echo $capture_status; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_captured; ?></td>
      <td id="paytpv-captured"><?php echo $captured; ?></td>
    </tr>
    <tr>
      <td><?php echo $text_amount_refunded; ?></td>
      <td id="paytpv-refund"><?php echo $refunded; ?></td>
    </tr>
  </table>
</fieldset>

<script type="text/javascript"><!--
	$('#paytpv-transaction').load('index.php?route=payment/paytpv/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

	function refund(totalRefund=0) {
	  var amount = $('input[name="amount"]').val();

	  $.ajax({
	    type: 'POST',
	    dataType: 'json',
	    data: {'order_id': '<?php echo $order_id; ?>', 'amount': amount, 'totalRefund': totalRefund},
	    url: 'index.php?route=payment/paytpv/dorefund&token=<?php echo $token; ?>',

	    beforeSend: function () {
	      button = (totalRefund==0)?"button-refund":"button-total-refund";
	      
	      $('#'+button).after('<span class="btn btn-primary loading"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>');
	      $('#'+button).hide();
	    },

	    success: function (data) {
	      if (!data.error) {
	        alert(data.success);
	        $('input[name="amount"]').val('0.00');
	        $('#paytpv-transaction').load('index.php?route=payment/paytpv/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
	        $('#paytpv-refund').text(data.total_refunded);
	      }

	      if (data.error) {
	        alert(data.error);
	      }
	      $('#'+button).show();
	      $('.loading').remove();
	    }
	  });
	}

	$('#paytpv-transaction').delegate('button', 'click', function() {
		
		var element = this;

		$.ajax({
			url: $(element).attr('href'),
			dataType: 'json',
			beforeSend: function() {
				$(element).button('loading');
			},
			complete: function() {
				$(element).button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#tab-paytpv').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#paytpv-transaction').load('index.php?route=payment/paytpv/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				}
			}
		});
	});
//--></script>