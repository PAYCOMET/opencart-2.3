<?php
/**
   * @return bool
   */
  function isOpencart2 ()
  {
    return version_compare(VERSION, 2, ">=");
  }
?>
<div id="saved_cards" style="">
    <form class="form" name="form_paycomet" id="form_paycomet">
        <div class="form-group">
            <label for="card"><?php print $text_credit_cards;?>:</label>
            <select name="card" id="card" onChange="checkCard()" class="form-control">
                <?php 
                print "<option value='0'>".strtoupper($text_new_card)."</option>";
                foreach ($saved_cards as $key=>$saved_card){
                  $selected = ($key==sizeof($saved_cards)-1)?"selected":"";
                  $desc = $saved_card["paycomet_cc"];
                  if ($saved_card["card_desc"]!="")
                    $desc .= "- " . $saved_card["card_desc"];
                  print "<option value='".$saved_card["paycomet_iduser"]."' $selected>".$desc."</option>";
                }
                ?>
            </select>

            <?php

            $display_iframe = (sizeof($saved_cards)>0)?"display:none":"";

            ?>
        </div>

        <?php

            if (sizeof($saved_cards)>0){
              print '<div class="direct_pay_button">';
                if ($paycomet_commerce_password){
                  print '<div class="form-group">';
                    print "<label for='commerce_password'>".$text_commerce_password."</label>";
                    // Pago directo
                    print  '<input type="password" autocomplete="false" class="form-control" name="commerce_password" id="commerce_password">';
                  print '</div>';
                  
                }
                ?>
                <div class="buttons">
                  <div class="pull-right">
                    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
                  </div>
                </div>
                <?php
            }

            ?>
    </form>
</div>



<div id="paycomet_iframe" style="<?php print $display_iframe;?>">

  <h6><?php print $text_streamline;?></h4>
  <div class="checkbox"><label for="savecard" class="checkbox"><input type="checkbox" name="savecard" id="savecard" onChange="saveOrderInfoJQ(<?php print $order_id;?>)" checked><?php print $txt_remember;?><a id="open_conditions" class="open_conditions" href="#conditions"><?php print $txt_terms;?></a>.</label></div>

  <iframe id="paycomet_iframe2" src="<?php echo $paycomet_iframe; ?>" name="paycomet" style="width: 670px; border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px; border-style: initial; border-color: initial; border-image: initial; height: 322px;" marginheight="0" marginwidth="0" scrolling="no"></iframe>

</div>


<div id="conditions" class="modal" style="overflow:auto;display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div id="conditions2" class="modal-body">
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




<script type="text/javascript"><!--

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

  });
  

  $('#button-confirm').bind('click', function() {
      event.preventDefault();
      
      $.ajax({
        url: 'index.php?route=payment/paycomet/directpay',
        type: 'post',
        data: $("#form_paycomet").serialize(),
        dataType: 'json',
        cache: false,
      beforeSend: function() {
        $('#button-confirm').button('loading');
      },
      complete: function() {
        $('#button-confirm').button('reset');
      },

      success: function(json) {
          if (json['ACSURL']) {
            $('#3dauth').remove();

            html = '<form action="' + json['ACSURL'] + '" method="post" id="3dauth">';
            html += '</form>';

            $('#paycomet_iframe').after(html);

            $('#3dauth').submit();
          }

          if (json['error']) {
            alert(json['error']);
          }

          if (json['redirect']) {
            location = json['redirect'];
          }
        }
      });
    });

});


function checkCard(){
  if ($("#card").val()>0){
      $("#paycomet_iframe").hide();
      $(".direct_pay_button").show();
  }else{
    $("#paycomet_iframe").show();
    $(".direct_pay_button").hide();
  }
}

function saveOrderInfoJQ(order_id){
    
  paycomet_agree = $("#savecard").is(':checked')?1:0;

  $.ajax({
      url: 'index.php?route=payment/paycomet/saveOrderInfo',
      type: "POST",
      data: {
          'paycomet_agree': paycomet_agree,
          'order_id' : order_id,
          'ajax': true
      },
      dataType:"json"
  })
}

//--></script>