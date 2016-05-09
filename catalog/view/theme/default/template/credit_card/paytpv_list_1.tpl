<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div class="container">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="content">
        <table width="100%" class="table table-bordered table-hover">
          <thead>
            <tr>
              <td width="20%" class="text-left"><?php echo $column_type; ?></td>
              <td width="20%" class="text-left"><?php echo $column_digits; ?></td>
              <td width="60%" class="text-left"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($cards) { ?>
            <?php foreach ($cards  as $card) { ?>
            <tr>
              <td width="20%" class="text-left"><?php echo $card['paytpv_brand']; ?></td>
              <td width="20%" class="text-left"><?php echo $card['paytpv_cc']; ?></td>
              
			        <td width="60%" class="text-right"><a href="<?php echo $delete . $card['paytpv_iduser']; ?>" class="btn btn-danger"><?php echo $button_delete; ?></a></td>

            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="5"><?php echo $text_empty; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    

   
	  <div class="buttons">
        <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
        <div class="right"><a href="<?php echo $add; ?>" class="button"><?php echo $button_new_card; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
