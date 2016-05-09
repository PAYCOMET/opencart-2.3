<?php echo $header; ?>
<?php
/**
   * @return bool
   */
  function isOpencart2 ()
  {
    return version_compare(VERSION, 2, ">=");
  }
?>

<script>

var msg_accept = "<?php echo $txt_msg_accept; ?>";

$(document).ready(function() {

  
    $(document).delegate('.open_conditions', 'click', function(e) {
      e.preventDefault();

      <?php 
      if (isOpencart2()){
        print "$('#conditions').modal('show');";
      }else{
        print "$('.open_conditions').colorbox({
          inline:true,
          width: 640,
          height: 480,
          href:'#conditions2',
          open: true
          });";
      }
      ?>
    })

  $("body").on("click",".exec_directpay",function(event) {
      event.preventDefault();
      $("#clockwait").show();
      $("#form_paytpv").submit();
  });

});


function vincularTarjeta(){
    if ($("#savecard").is(':checked')){
        $('#savecard').attr("disabled", true);
        $('#close_vincular').show();
        $('#nueva_tarjeta').show();
    }else{
        alert(msg_accept);
    }

}

function close_vincularTarjeta(){
    $('#savecard').attr("disabled", false);
    $('#nueva_tarjeta').hide();
    $('#close_vincular').hide();
}


</script>


<div class="container">
  <?php if (isOpencart2()){?>
  <ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
  </ul>
  <?php }else{?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php }?>
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
      <div id="storingStep" class="box">
        <p><?php echo $text_streamline; ?></p>
        <p>
            <span class="checked"><input type="checkbox" name="savecard" id="savecard"></span>
            <label for="savecard"><?php echo $txt_click_terms; ?> <a class="open_conditions" href="#conditions"><strong><?php echo $txt_terms; ?></strong></a></label>
        </p>
        <p>
            <a href="javascript:void(0);" onclick="vincularTarjeta();" title="<?php echo $txt_link_card; ?>" class="button button-small btn btn-default">
                <span><?php echo $txt_link_card; ?><i class="icon-chevron-right right"></i></span>
            </a>
            <a href="javascript:void(0);" onclick="close_vincularTarjeta();" title="<?php echo $txt_cancel; ?>" class="button button-small btn btn-default" id="close_vincular" style="display:none">
                <span><?php echo $txt_cancel; ?><i class="icon-chevron-right right"></i></span>
            </a>
        </p>

        <p class="payment_module paytpv_iframe" id="nueva_tarjeta" style="display:none">
            <iframe src="<?php echo $txt_url_paytpv; ?>" name="paytpv" style="width: 670px; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; border-style: initial; border-color: initial; border-image: initial; height: 322px; " marginheight="0" marginwidth="0" scrolling="no"></iframe>
        </p>
      </div>

	  <?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
</div>

<div id="conditions" class="modal" style="overflow:auto;display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div id="conditions2"  class="modal-body">

        <h2 class="estilo-tit1"><?php print $text_conditions4;?></h2>
        <p>
        <?php print $text_conditions5;?>
        </p>
        <h2 class="estilo-tit1" id="politica_seguridad"><?php print $text_conditions6;?></h2>
        <p>
        <?php print $text_conditions7;?>
        </p>
        <h2 class="estilo-tit1" id="politica_seguridad"><?php print $text_conditions8;?></h2>
        <p>
        <?php print $text_conditions9;?>
        </p>
        <h2 class="estilo-tit1" id="politica_seguridad"><?php print $text_conditions10;?></h2>
        <p>
        <?php print $text_conditions11;?>
        </p>
        <p>
        <?php print $text_conditions12;?>
        </p>
        <h2 class="estilo-tit1" id="politica_seguridad"><?php print $text_conditions13;?></h2>
        <p>
        <?php print $text_conditions14;?>
        </p>
        <h2 class="estilo-tit1" id="politica_seguridad"><?php print $text_conditions15;?></h2>
        <p>
        <?php print $text_conditions16;?>
        </p>
        <h2 class="estilo-tit1" id="politica_seguridad"><?php print $text_conditions17;?></h2>
        <p>
        <?php print $text_conditions18;?>
        </p>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
</div>

<?php echo $footer; ?>
