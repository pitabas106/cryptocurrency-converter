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
		app.loadCurreny();
	},

	loadCurreny: function(){
		  data.currencies= currencies.rows;
		  app.renderSelectFields();
	},

	renderSelectFields: function(){
		var _selectFrom = jQuery("#nt_ccc_from");
		var _selectTo = jQuery("#nt_ccc_to");

		jQuery.each(data.currencies, function(index, val) {
			 jQuery(_selectFrom).append("<option value='"+val.code+"'>"+val.name+" ("+val.code+")"+"</option>");
			 jQuery(_selectTo).append("<option value='"+val.code+"'>"+val.name+" ("+val.code+")"+"</option>");
		});
		console.log(ccc_option_default_from_value);
		jQuery(_selectFrom).find("option[value='"+request_from+"']").prop('selected', true);
		jQuery(_selectTo).find("option[value='"+request_to+"']").prop('selected', true);
		jQuery(_selectFrom).select2();
		jQuery(_selectTo).select2();
	}
}

jQuery(document).ready(function() {
	app.init();
	if( jQuery('.ccc-option-custom-color').length > 0 ) {
		jQuery('.ccc-option-custom-color').wpColorPicker();
	}
});

