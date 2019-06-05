/* *
* Author: NetTantra
* @package         cryptocurrency-converter
*/

var data = {};
data.currencies = null;
var app = {
	init: function() {
    if(typeof ccc_option_default_from_value != 'undefined') {
      request_from = ccc_option_default_from_value;
    }
		if (typeof ccc_option_default_to_value != 'undefined') {
      request_to = ccc_option_default_to_value;
    }
		jQuery("#nt_ccc_amount").on('blur', function(event) {
			event.preventDefault();
			app.getCCResult(false, false, false);
		});
		jQuery("#nt_ccc_amount").on('keypress', function(event) {
		 if(event.which == 13) {
        app.getCCResult(false, false, false);
	    }
		});
		jQuery("#nt_ccc_from, #nt_ccc_to").on('change', function(event) {
			event.preventDefault();
			app.getCCResult(false, false, false);
		});
		app.loadCurreny();
	},

	loadCurreny: function(){
		  data.currencies= currencies.rows;
		  app.renderSelectFields();
	},

	renderSelectFields: function(){
		var _selectFrom = jQuery("#nt_ccc_from");
		var _selectTo = jQuery("#nt_ccc_to");
    var amount = jQuery( "#nt_ccc_amount" ).val();

		jQuery.each(data.currencies, function(index, val) {
			 jQuery(_selectFrom).append("<option value='"+val.code+"'>"+val.name+" ("+val.code+")"+"</option>");
			 jQuery(_selectTo).append("<option value='"+val.code+"'>"+val.name+" ("+val.code+")"+"</option>");
		});
		jQuery(_selectFrom).find("option[value='"+request_from+"']").prop('selected', true);
		jQuery(_selectTo).find("option[value='"+request_to+"']").prop('selected', true);

		jQuery(_selectFrom).select2({
			containerCssClass: "ccc-select2-container",
			dropdownCssClass: "ccc-select2-dropdown"
 		});
		jQuery(_selectTo).select2({
			containerCssClass: "ccc-select2-container",
			dropdownCssClass: "ccc-select2-dropdown"
		});

		app.getCCResult(request_from, request_to, amount);
	},

	getCCResult: function(ccc_from, ccc_to, ccc_amount){

		jQuery("#nt_ccc_result").addClass('ccc-loading');
		jQuery("#nt_ccc_result").removeClass('ccc-error');
		jQuery("#nt_ccc_result").val('');
		jQuery("#nt_ccc_result").attr('placeholder', '');

		if(!ccc_from) {
      ccc_from = jQuery( "#nt_ccc_from option:selected" ).val();
    }
    if(!ccc_to) {
      ccc_to = jQuery( "#nt_ccc_to option:selected" ).val();
    }
    if(!ccc_amount) {
      ccc_amount = jQuery( "#nt_ccc_amount" ).val();
    }

		var json_data = {
			action: 'cryptocurrency_converter_ajax',
      from: ccc_from,
      to: ccc_to,
      amount: ccc_amount,
			nonce : cryptocurrency_converter.nonce
		};
    jQuery.ajax({
      type: "POST",
      url: cryptocurrency_converter.ajax_url,
      data: json_data,
      dataType: 'json',
      success: function(result) {
        if(result.error == true) {
					jQuery("#nt_ccc_result").addClass('ccc-error');
          jQuery("#nt_ccc_result").val(result.msg);
        } else {
          jQuery("#nt_ccc_result").val(result.data.format);
					jQuery("#nt_ccc_result").removeClass('ccc-error');
        }
				jQuery("#nt_ccc_result").removeClass('ccc-loading');
      },
    });
	}
}

jQuery(document).ready(function() {
	app.init();
});

jQuery(window).on("popstate", function(e) {
  location.reload();
});
