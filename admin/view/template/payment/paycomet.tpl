<?php echo $header; ?><?php echo $column_left; ?>

<script type="text/javascript">

jQuery( document ).ready(function() {
	checkTerminales();
});

function checkTerminales(){
	if (jQuery("#paycomet_terminales").val()==2){
		jQuery(".paycomet_tdmin,.paycomet_tdfirst").show();
	}else{
		jQuery(".paycomet_tdmin,.paycomet_tdfirst").hide();
	}
}


</script>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-pp-payflow-iframe" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paycomet" class="form-horizontal">
        	<div class="form-group">
	            <label class="col-sm-2 control-label" for="paycomet_status"><?php echo $entry_status; ?></label>
	            <div class="col-sm-10">
	              <select name="paycomet_status" id="paycomet_status" class="form-control">
	                <?php if ($paycomet_status) { ?>
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
	            <label class="col-sm-2 control-label" for="paycomet_client"><span data-toggle="tooltip" title="<?php echo $help_client; ?>"><?php echo $entry_client; ?></span></label>
	            <div class="col-sm-10">
	              <input type="text" name="paycomet_client" id="paycomet_client" value="<?php echo $paycomet_client;?>" placeholder="<?php echo $entry_client; ?>" class="form-control"/>
	              <?php if ($error_client) { ?>
	              <div class="text-danger"><?php echo $error_client; ?></div>
	              <?php } ?>
	            </div>
          	</div>
          	<div class="form-group required">
	            <label class="col-sm-2 control-label" for="paycomet_terminal"><span data-toggle="tooltip" title="<?php echo $help_terminal; ?>"><?php echo $entry_terminal; ?></span></label>
	            <div class="col-sm-10">
	              <input type="text" name="paycomet_terminal" id="paycomet_terminal" value="<?php echo $paycomet_terminal; ?>" placeholder="<?php echo $entry_terminal; ?>" class="form-control"/>
	              <?php if ($error_terminal) { ?>
	              <div class="text-danger"><?php echo $error_terminal; ?></div>
	              <?php } ?>
	            </div>
          	</div>
          	<div class="form-group required">
	            <label class="col-sm-2 control-label" for="paycomet_password"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
	            <div class="col-sm-10">
	              <input type="text" name="paycomet_password" id="paycomet_password" value="<?php echo $paycomet_password; ?>"  placeholder="<?php echo $entry_password; ?>" class="form-control"/>
	              <?php if ($error_password) { ?>
	              <div class="text-danger"><?php echo $error_password; ?></div>
	              <?php } ?>
	            </div>
          	</div>
          	<div class="form-group">
	            <label class="col-sm-2 control-label" for="paycomet_terminales"><?php echo $entry_terminales; ?></label>
	            <div class="col-sm-10">
	              <select name="paycomet_terminales" id="paycomet_terminales" class="form-control" onChange="checkTerminales();">
	                <option value="0" selected="selected" <?php if ($paycomet_terminales==0) print "selected='selected'";?>><?php echo $text_secure; ?></option>
	                <option value="1" <?php if ($paycomet_terminales==1) print "selected='selected'";?>><?php echo $text_nosecure; ?></option>
	                <option value="2" <?php if ($paycomet_terminales==2) print "selected='selected'";?>><?php echo $text_both; ?></option>
	              </select>
	            </div>
          	</div>
          	<div class="form-group paycomet_tdfirst">
	            <label class="col-sm-2 control-label" for="paycomet_tdfirst"><span data-toggle="tooltip" title="<?php echo $help_tdfirst; ?>"><?php echo $entry_tdfirst; ?></label>
	            <div class="col-sm-10">
	              <select name="paycomet_tdfirst" id="paycomet_tdfirst" class="form-control">
	                <option value="0" <?php if ($paycomet_tdfirst==0) print "selected";?>><?php echo $text_no; ?></option>
	                <option value="1" <?php if ($paycomet_tdfirst==1) print "selected";?>><?php echo $text_yes; ?></option>
	              </select>
	            </div>
          	</div>
          	<div class="form-group paycomet_tdmin">
	            <label class="col-sm-2 control-label" for="paycomet_tdmin"><span data-toggle="tooltip" title="<?php echo $help_tdmin; ?>"><?php echo $entry_tdmin; ?></label>
	            <div class="col-sm-10">
	              <input type="text" name="paycomet_tdmin" id="paycomet_tdmin" value="<?php echo $paycomet_tdmin; ?>"  placeholder="<?php echo $entry_tdmin; ?>"  class="form-control"/>
	              <?php if ($error_tdmin) { ?>
	              <div class="text-danger"><?php echo $error_tdmin; ?></div>
	              <?php } ?>
	            </div>
          	</div>
          	<div class="form-group paycomet_commerce_password">
	            <label class="col-sm-2 control-label" for="paycomet_commerce_password"><span data-toggle="tooltip" title="<?php echo $help_commerce_password; ?>"><?php echo $entry_commerce_password; ?></label>
	            <div class="col-sm-10">
	              <select name="paycomet_commerce_password" id="paycomet_commerce_password" class="form-control">
	                <option value="0" <?php if ($paycomet_commerce_password==0) print "selected";?>><?php echo $text_no; ?></option>
	                <option value="1" <?php if ($paycomet_commerce_password==1) print "selected";?>><?php echo $text_yes; ?></option>
	              </select>
	            </div>
          	</div>
          	
          	<div class="form-group">
	            <label class="col-sm-2 control-label" for="paycomet_order_status_id"><?php echo $entry_order_status; ?></label>
	            <div class="col-sm-10">
	              <select name="paycomet_order_status_id" id="paycomet_order_status_id" class="form-control">
	                <?php foreach ($order_statuses as $order_status) { ?>
	                <?php if ($order_status['order_status_id'] == $paycomet_order_status_id) { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
	                <?php } ?>
	                <?php } ?>
	              </select>
	            </div>
          	</div>
          	<div class="form-group">
	            <label class="col-sm-2 control-label" for="paycomet_sort_order"><?php echo $entry_sort_order; ?></label>
	            <div class="col-sm-10">
	              <input type="text" name="paycomet_sort_order" id="paycomet_sort_order" value="<?php echo $paycomet_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
	            </div>
	        </div>
         
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>