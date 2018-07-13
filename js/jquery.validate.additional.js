// JavaScript Document
$.validator.addMethod("phone_number", function(value, element) {
	return this.optional(element) || value === "NA" ||
		value.match(/^[0-9]+$/);
}, "Please enter a valid phone number");

// For Address 
$.validator.addMethod("letterswithbasicpunc", function(value, element) {
	return this.optional(element) || /^[a-z0-9-_.,#()&':;\"\s]+$/i.test(value);
}, "Letters, numbers and basic punctuation are allowed");

$.validator.addMethod("alphanumeric", function(value, element) {
	return this.optional(element) || /^\w+\s$/i.test(value);
}, "Letters, numbers, spaces or underscores are allowed");

// For first name and last names
$.validator.addMethod("lettersonly", function(value, element) {
	//return this.optional(element) || /^([ \u00c0-\u01ffa-zA-Z0-9-_.'\-])+\s*$/i.test(value);
	return this.optional(element) || /^([a-zA-Z0-9-_']+\s?)*$/i.test(value);
}, "Letters, numbers, spaces and underscores are allowed");

$.validator.addMethod("lettersonlyschedule", function(value, element) {
	//return this.optional(element) || /^([ \u00c0-\u01ffa-zA-Z0-9-_.'\-])+\s*$/i.test(value);
	return this.optional(element) || /^([a-zA-Z0-9-_']+\s?)*$/i.test(value);
}, "Symbols are not allowed");
 
 
// For Username Validation
$.validator.addMethod("chkusername", function(value, element) {
	return this.optional(element) || /^[A-Za-z0-9_]{3,100}$/i.test(value);
}, "Alphabetic characters, numbers and underscores are allowed");

$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param) 
});

$.validator.addMethod("placeholder", function(value, element) {
	return value!=$(element).attr("placeholder");
}, $.validator.messages.required);

$.validator.addMethod("quantity", function(value, element) {	
	return !this.optional(element);
});

jQuery.validator.addMethod("greaterThan", function(value, element, params) {
	if (!/Invalid|NaN/.test(new Date(value))) {
		return new Date(value) >= new Date($(params).val());
	}
	return isNaN(value) && isNaN($(params).val()) || (Number(value) >= Number($(params).val()));
},'Must be greater than {0}.');

jQuery.fn.ForceNumericOnly = function(){
	return this.each(function(){
		$(this).keydown(function(e){
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
			return ( key == 8 || key == 9 || key == 46 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
		});
	});
};
			
function ampreplace(str){
	return str.replace(/&/g,"%26");
}

function trim(stringToTrim){
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}