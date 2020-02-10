<?php
class ModelShippingZasilkovnaPL extends Model {
  function getQuote($address) {
    $this->load->language('shipping/ZasilkovnaPL');
    $weight = $this->cart->getWeight();
    $max_weight = $this->config->get('ZasilkovnaPL_weight_max');
    $valid_weight = (!$max_weight && $max_weight !== 0) || ($max_weight > 0 && $weight <= $max_weight); // weight condition check, yay logic
    if ($this->config->get('ZasilkovnaPL_status') && $valid_weight) {
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ZasilkovnaPL_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
    
          if (!$this->config->get('ZasilkovnaPL_geo_zone_id')) {
            $status = TRUE;
          } elseif ($query->num_rows) {
            $status = TRUE;
          } else {
            $status = FALSE;
          }
    } else {      
      $status = FALSE;      
    }    
    $method_data = array();

    if ($status) {      
      $weight = $this->cart->getWeight();
  
      $quote_data = array();
  
      $text = $this->language->get('text_description') . ' : ';
      $api_key = $this->config->get('ZasilkovnaPL_api_key') ;




$HELPER_JS = '<script> (function(d){ var el, id = "packetery-jsapi", head = d.getElementsByTagName("head")[0]; if(d.getElementById(id)) { return; } el = d.createElement("script"); el.id = id; el.async = true; el.src = "//www.zasilkovna.cz/api/'.$api_key.'/branch.js?callback=addHooks"; head.insertBefore(el, head.firstChild); }(document)); </script>
<script language="javascript" type="text/javascript">   ;
if(typeof window.packetery != "undefined"){
  setTimeout(function(){initBoxes()},1000)
}else{
  setTimeout(function(){setRequiredOpt()},500)
}
function initBoxes(){
   var api = window.packetery;
   divs = $(\'#ZasilkovnaPL_box\');
   $(\'.packetery-branch-list\').each(function() {

       api.initialize(api.jQuery(this));
       this.packetery.option("selected-id",0);
    });
   addHooks();  
   setRequiredOpt();
}
var SubmitButtonDisabled = true;
function setRequiredOpt(){
        var setOnce = false;
        var disableButton=false;
        var ZasilkovnaPL_selected = false;
        var opts={
            connectField: \'textarea[name=comment]\'
          }        
        $("div.packetery-branch-list").each(            
            function() {               
              var tr = $(this).closest(\'tr\');              
              var radioButt = $(tr).find(\'input[name="shipping_method"]:radio\');     
              var select_branch_message = $(tr).find(\'#select_branch_message\');

              if($(radioButt).is(\':checked\')){
                ZasilkovnaPL_selected = true;                
              }else{//deselect branch (so when user click the radio again, he must select a branch). Made coz couldnt update connect-field if only clicked on radio with already selected branch
                if(this.packetery.option("selected-id")>0){
                  this.packetery.option("selected-id",0);
                }
                //$(this).find(\'option:selected\', \'select\').removeAttr(\'selected\');
                //$($(this).find(\'option\', \'select\')[0]).attr(\'selected\', \'selected\');
              }

              if($(radioButt).is(\':checked\')&&!this.packetery.option("selected-id")){                            
                select_branch_message.show();
                disableButton=true;                       
              }else{
                select_branch_message.hide();  

              }
            }
          );        
        
        $(\'#button-shipping-method\').attr(\'disabled\', disableButton);     
        SubmitButtonDisabled = disableButton;
    
        if(!ZasilkovnaPL_selected){          
          updateConnectedField(opts,0);
        }
}

function submitForm(){

  if(!SubmitButtonDisabled){
    $(\'#shipping\').submit();
  }
}

function updateConnectedField(opts, id) {
          var branches;
          if(typeof(opts) == "undefined"){
              $(".packetery-branch-list").each(function() {
                  if(this.packetery.option("selected-id")){
                      opts = {
                          connectField: "textarea[name=comment]",
                          selectedId: this.packetery.option("selected-id")
                      };
                      branches = this.packetery.option("branches");
                  }
              });
          }
          if (opts.connectField) {
              if (typeof(id) == "undefined") {
                  id = opts.selectedId
              }
              var f = $(opts.connectField);
              var v = f.val() || "",
              re = /\[Z\u00e1silkovna\s*;\s*[0-9]+\s*;\s*[^\]]*\]/,
              newV;
              if (id > 0) {
                  var branch = branches[id];
                  newV = "[Z\u00e1silkovna; " + branch.id + "; " + branch.name + "]"
              } else {
                  newV = ""
              }
              if (v.search(re) != -1) {
                  v = v.replace(re, newV)
                  } else {
                  if (v) {
                      v += "\n" + newV
                  } else {
                      v = newV
                  }
              }
              function trim(s) {
                  return s.replace(/^\s*|\s*$/, "")
                  }
              f.val(trim(v))
              }
}

    function addHooks(){
      //called when no ZasilkovnaPL method is selected. Dunno how to call this from the branch.js        
      
      
      //set each radio button to call setRequiredOpt if clicked
      $(\'input[name="shipping_method"]:radio\').each(
        function(){
          $(this).click(setRequiredOpt);         
         }
      );      
      button = $(\'[onclick="$(\\\'#shipping\\\').submit();"]\');
      button.removeAttr("onclick");
      button.click(submitForm);

      $("div.packetery-branch-list").each(            
          function() {             
            var fn = function(){       
              var selected_id = this.packetery.option("selected-id");             
              var tr = $(this).closest(\'tr\');              
              var radioButt = $(tr).find(\'input[name="shipping_method"]:radio\');                   
              if(selected_id)$(radioButt).attr("checked",\'checked\');
              setTimeout(setRequiredOpt, 1);
            };
            this.packetery.on("branch-change", fn);
            fn.call(this);
          }
      );            
    }
$("#content").delegate("textarea[name=comment]", "change", function () {
  updateConnectedField();
});
    </script>';

      $addedHelperJS = false;
      for($i = 0; $i <= 10 ;$i++){
        $enabled = $this->config->get('ZasilkovnaPL_enabled_'.$i);
        if (empty($enabled) || $enabled == 0) continue;

    $cost = 0;
    if($this->config->get('ZasilkovnaPL_freeover_'.$i) == 0 || $this->cart->getTotal() < $this->config->get('ZasilkovnaPL_freeover_'.$i)) // shipment is not free
      $cost = $this->config->get('ZasilkovnaPL_price_'.$i);
    
        $title = $this->config->get('ZasilkovnaPL_title_'.$i);                
        $country = $this->config->get('ZasilkovnaPL_destination_'.$i);                

        $JS = "";
        if($addedHelperJS==false)     {
          $JS.=$HELPER_JS;
          $addedHelperJS=true;
        }

        if($this->config->get('ZasilkovnaPL_branches_enabled_'.$i)){
          $JS .= '<script>                
            var radio = $(\'input:radio[name="shipping_method"][value="ZasilkovnaPL.'.$title.$i.'"]\');
            var td = radio.parents("td").next(); 
            if(td.find(\'#ZasilkovnaPL_box\').length == 0){
              $(td).append(\'<div id="ZasilkovnaPL_box" class="packetery-branch-list list-type=8 connect-field=textarea[name=comment] country='.$country.'" style="border: 1px dotted black;">Načítání: seznam poboček osobního odběru</div> \');                                      
              $(td).append(\'<p id="select_branch_message" style="color:red; font-weight:bold; display:none">Vyberte pobočku</p>\');
            }
          </script>';
        }
        $quote_data[$title.$i] = array(
          'id'            => 'ZasilkovnaPL.'.$title.$i,
          'code'            => 'ZasilkovnaPL.'.$title.$i,
          'title'           => $this->language->get('text_description'),
          'cost'            => $cost,
          'tax_class_id'    => $this->config->get('ZasilkovnaPL_tax_class_id'),
		  'cod_fee'      => 21,
          'text'            => $JS.$this->currency->format($this->tax->calculate($cost, $this->config->get('ZasilkovnaPL_tax_class_id'), $this->config->get('config_tax')))
        );        
      }      

                  
      $method_data = array(
        'code'         => 'ZasilkovnaPL',
        'title'      => $this->language->get('text_title'),
        'quote'      => $quote_data,
        'sort_order' => $this->config->get('ZasilkovnaPL_sort_order'),
		'cod_fee'      => 21,
        'error'      => FALSE
      );
    }    
    return $method_data;
  }
}
?>
