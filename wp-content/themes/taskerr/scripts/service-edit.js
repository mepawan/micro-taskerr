//equalize function
function equalHeight(group) {
	tallest = 0;
	group.each(function() {
		thisHeight = jQuery(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
};

window.onresize = function(){
	equalHeight(jQuery(".pricing .plans .text"));
	equalHeight(jQuery(".pricing .plans .type"));
	equalHeight(jQuery(".features-options"));
};

jQuery(document).ready(function($) {

	$('#edit-service').validate({
		ignore: '.ignore',
		errorClass: 'error',
		errorElement: 'small',
		errorPlacement: function(error, element) {
			if (element.attr('type') === 'checkbox' || element.attr('type') === 'radio') {
				element.closest('ul').before(error);
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function(element, errorClass, validClass) {
			$(element).closest('label').addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).closest('label').removeClass(errorClass).addClass(validClass);
		}
	});

		//call the equalize height function
		equalHeight($(".pricing .plans .text"));
		equalHeight(jQuery(".pricing .plans .type"));
		equalHeight($(".features-options"));
});