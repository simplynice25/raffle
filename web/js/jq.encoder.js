// JavaScript Document
(function(){

	var funcConf = {
		orOpt: 'input[name=or_options]',
	};

	var funcInit = {
		switchOrOpt: function() {
			return this.delegate(funcConf.orOpt, 'change', function() {
				var self = $(this),
					exist = $('.existing-user-wrapper'),
					non_existing = $('.non-existing-wrapper'),
					fullname_ = $('input[name=fullname]');

				console.log(fullname_);

				if (self.val() == 1) {
					exist.removeClass('hide');
					non_existing.addClass('hide');
					fullname_.removeAttr('required');
				} else {
					exist.addClass('hide');
					non_existing.removeClass('hide');
					fullname_.attr('required', 'required');
				}

				return true;
			})
		}
	};

	$.extend(config.doc, funcInit);

	config.doc.switchOrOpt();

})(jQuery,window,document);