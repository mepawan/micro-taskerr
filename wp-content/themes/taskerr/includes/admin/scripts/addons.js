jQuery(document).ready(function($) {
	$('#_blank').prop('checked', true).parent().hide();
	datepickerL10n.dateFormat = trLabels.dateFormat;

	var enableAddon = function(enableBox, blankDateBox, actualDateBox, expireDateBox, durationBox) {
		if (enableBox.attr("checked") === "checked") {

			if (durationBox.val() == "") {
				durationBox.val(0);
			}
			durationBox.prop('disabled', false);
			var dateToSet = actualDateBox.val() ? $.datepicker.parseDate( "yy-mm-dd", actualDateBox.val()) : new Date();
			blankDateBox.prop('disabled', enableBox.attr('id') === '_blank').datepicker("setDate", dateToSet);
			expireHandler(blankDateBox.val(), durationBox, expireDateBox);
		} else {
			blankDateBox.val('').prop('disabled', true);
			durationBox.val('').prop('disabled', true);
			expireDateBox.val('');
		}
	};

	var expireHandler = function(selectedDate, durationBox, expireBox){
		var duration = parseInt(durationBox.val());
		if (isNaN(duration))
			duration = 0;
		if (duration === 0){
			expireBox.val(trLabels.Never);
			return false;
		}
		var selectedDateObj = $.datepicker.parseDate(datepickerL10n.dateFormat, selectedDate, datepickerL10n);
		selectedDateObj.setDate(selectedDateObj.getDate() + duration);
		expireBox.datepicker('setDate', selectedDateObj);
	};

	$('.enable-addon').each(function() {
		var enabled;       // State of Addon
		var flag;          // Addont type
		var trAddon;       // Current Addon info
		var blankDateBox;  // Date field in localized format
		var actualDateBox; // Date field in mySQL format (for database)
		var expireDateBox; // Calculated date in localized format
		var durationBox;   // Addon duration in days

		enabled = $(this).attr("checked") === "checked";
		flag =  $(this).attr("name");

		if (typeof trAddons !== 'undefined' && flag in trAddons) {
			trAddon = trAddons[flag];
		} else {
			return true;
		}

		blankDateBox = $("#_blank_" + trAddon.start_date_key);
		actualDateBox = $("#" + trAddon.start_date_key);
		expireDateBox = $("#_blank_expire_" + flag);
		durationBox = $("#" + trAddon.duration_key);

		expireDateBox.datepicker({
			dateFormat: datepickerL10n.dateFormat
		}).prop('disabled', true);

		blankDateBox.datepicker({
			dateFormat: datepickerL10n.dateFormat,
			altField: actualDateBox,
			altFormat: "yy-mm-dd",
			onClose: function(selectedDate) {
				expireHandler(selectedDate, durationBox, expireDateBox);
			}
		});

		durationBox.attr( 'type', 'number' ).change(function() {
			expireHandler(blankDateBox.val(), durationBox, expireDateBox);
		});

		$(this).change(function() {
			enableAddon($(this), blankDateBox, actualDateBox, expireDateBox, durationBox);
		}).change();
	});
});