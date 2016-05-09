<?php
	if (!function_exists('clean_echo'))
	{
		function clean_echo ($string)
		{
			echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		}
	}
?>
<?php echo $header; ?>

<script type="text/javascript">

jQuery( document ).ready(function() {
	checkTerminales();
});

function checkTerminales(){
	if (jQuery("#paytpv_terminales").val()==2){
		jQuery(".paytpv_tdmin,.paytpv_tdfirst").show();
	}else{
		jQuery(".paytpv_tdmin,.paytpv_tdfirst").hide();
	}
}


</script>

<div id="content">
  <div class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb): ?>
		<a href="<?php echo $breadcrumb['href'] ?>"><?php echo $breadcrumb['text'] ?></a>
	<?php endforeach ?>
  </div>
  <div class="box">
	  <div class="heading">
		<h1><img src="view/image/payment.png" alt="" /> <?php clean_echo($heading_title) ?></h1>
		<div class="buttons"><a onclick="(window.jQuery || window.$)('#form').submit()" class="button"><span><?php clean_echo($button_save) ?></span></a><a href="<?php echo $cancel ?>" class="button"><span><?php clean_echo($button_cancel) ?></span></a></div>
	  </div>
	  <div class="container-fluid">
	    <?php if ($error_warning) { ?>
	    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	      <button type="button" class="close" data-dismiss="alert">&times;</button>
	    </div>
	    <?php } ?>
	    <div class="panel panel-default">
	      <div class="panel-heading">
	        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
	      </div>
	      <div class="panel-body">
	        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
	        	<div class="form-group">
		            <label class="col-sm-2 control-label" for="paytpv_status"><?php echo $entry_status; ?></label>
		            <div class="col-sm-10">
		              <select name="paytpv_status" id="paytpv_status" class="form-control">
		                <?php if ($paytpv_status) { ?>
		                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
		                <option value="0"><?php echo $text_disabled; ?></option>
		                <?php } else { ?>
		                <option value="1"><?php echo $text_enabled; ?></option>
		                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
		                <?php } ?>
		              </select>
		            </div>
	          	</div>

		        <div class="form-group required">
		            <label class="col-sm-2 control-label" for="paytpv_client"><span data-toggle="tooltip" title="<?php echo $help_client; ?>"><?php echo $entry_client; ?></span></label>
		            <div class="col-sm-10">
		              <input type="text" name="paytpv_client" id="paytpv_client" value="<?php echo $paytpv_client;?>" placeholder="<?php echo $entry_client; ?>" class="form-control"/>
		              <?php if ($error_client) { ?>
		              <div class="text-danger"><?php echo $error_client; ?></div>
		              <?php } ?>
		            </div>
	          	</div>
	          	<div class="form-group required">
		            <label class="col-sm-2 control-label" for="paytpv_terminal"><span data-toggle="tooltip" title="<?php echo $help_terminal; ?>"><?php echo $entry_terminal; ?></span></label>
		            <div class="col-sm-10">
		              <input type="text" name="paytpv_terminal" id="paytpv_terminal" value="<?php echo $paytpv_terminal; ?>" placeholder="<?php echo $entry_terminal; ?>" class="form-control"/>
		              <?php if ($error_terminal) { ?>
		              <div class="text-danger"><?php echo $error_terminal; ?></div>
		              <?php } ?>
		            </div>
	          	</div>
	          	<div class="form-group required">
		            <label class="col-sm-2 control-label" for="paytpv_password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
		            <div class="col-sm-10">
		              <input type="text" name="paytpv_password" id="paytpv_password" value="<?php echo $paytpv_password; ?>"  placeholder="<?php echo $entry_password; ?>" class="form-control"/>
		              <?php if ($error_password) { ?>
		              <div class="text-danger"><?php echo $error_password; ?></div>
		              <?php } ?>
		            </div>
	          	</div>
	          	<div class="form-group">
		            <label class="col-sm-2 control-label" for="paytpv_terminales"><?php echo $entry_terminales; ?></label>
		            <div class="col-sm-10">
		              <select name="paytpv_terminales" id="paytpv_terminales" class="form-control" onChange="checkTerminales();">
		                <option value="0" selected="selected" <?php if ($paytpv_terminales==0) print "selected='selected'";?>><?php echo $text_secure; ?></option>
		                <option value="1" <?php if ($paytpv_terminales==1) print "selected='selected'";?>><?php echo $text_nosecure; ?></option>
		                <option value="2" <?php if ($paytpv_terminales==2) print "selected='selected'";?>><?php echo $text_both; ?></option>
		              </select>
		            </div>
	          	</div>
	          	<div class="form-group paytpv_tdfirst">
		            <label class="col-sm-2 control-label" for="paytpv_tdfirst"><span data-toggle="tooltip" title="<?php echo $help_tdfirst; ?>"><?php echo $entry_tdfirst; ?></label>
		            <div class="col-sm-10">
		              <select name="paytpv_tdfirst" id="paytpv_tdfirst" class="form-control">
		                <option value="0" <?php if ($paytpv_tdfirst==0) print "selected";?>><?php echo $text_no; ?></option>
		                <option value="1" <?php if ($paytpv_tdfirst==1) print "selected";?>><?php echo $text_yes; ?></option>
		              </select>
		            </div>
	          	</div>
	          	<div class="form-group paytpv_tdmin">
		            <label class="col-sm-2 control-label" for="paytpv_tdmin"><span data-toggle="tooltip" title="<?php echo $help_tdmin; ?>"><?php echo $entry_tdmin; ?></label>
		            <div class="col-sm-10">
		              <input type="text" name="paytpv_tdmin" id="paytpv_tdmin" value="<?php echo $paytpv_tdmin; ?>"  placeholder="<?php echo $entry_tdmin; ?>"  class="form-control"/>
		              <?php if ($error_tdmin) { ?>
		              <div class="text-danger"><?php echo $error_tdmin; ?></div>
		              <?php } ?>
		            </div>
	          	</div>
	          	<div class="form-group paytpv_commerce_password">
		            <label class="col-sm-2 control-label" for="paytpv_commerce_password"><span data-toggle="tooltip" title="<?php echo $help_commerce_password; ?>"><?php echo $entry_commerce_password; ?></label>
		            <div class="col-sm-10">
		              <select name="paytpv_commerce_password" id="paytpv_commerce_password" class="form-control">
		                <option value="0" <?php if ($paytpv_commerce_password==0) print "selected";?>><?php echo $text_no; ?></option>
		                <option value="1" <?php if ($paytpv_commerce_password==1) print "selected";?>><?php echo $text_yes; ?></option>
		              </select>
		            </div>
	          	</div>
	          	
	          	<div class="form-group">
		            <label class="col-sm-2 control-label" for="paytpv_order_status_id"><?php echo $entry_order_status; ?></label>
		            <div class="col-sm-10">
		              <select name="paytpv_order_status_id" id="paytpv_order_status_id" class="form-control">
		                <?php foreach ($order_statuses as $order_status) { ?>
		                <?php if ($order_status['order_status_id'] == $paytpv_order_status_id) { ?>
		                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
		                <?php } else { ?>
		                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
		                <?php } ?>
		                <?php } ?>
		              </select>
		            </div>
	          	</div>
	          	<div class="form-group">
		            <label class="col-sm-2 control-label" for="paytpv_sort_order"><?php echo $entry_sort_order; ?></label>
		            <div class="col-sm-10">
		              <input type="text" name="paytpv_sort_order" id="paytpv_sort_order" value="<?php echo $paytpv_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
		            </div>
		        </div>
	         
	        </form>
	      </div>
	    </div>
	  </div>
	 </div>
</div>
<?php echo $footer; ?>