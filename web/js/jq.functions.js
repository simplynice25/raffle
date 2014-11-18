// JavaScript Document

$(document).ready(function(){
	$(".actions-btn .btn").tooltip();
});

(function(){

	var funcConf = {
		actionsBtn: ".actions-btn .btn",
		Loader: "fa fa-spin fa-spinner",
		actions: {
			1: {
				0: config._actions,
				1: "btn-danger",
				2: "Remove from watchlist"
			},
			2: {
				0: config._actions,
				1: "btn-danger",
				2: "Remove from portfolio"
			},
			3: {
				0: config._actions, // config._delete
				1: "btn-danger",
				2: "Unhide"
			},
			4: {
				0: config._delete,
			},
			_default: 0
		},
		new_link: ".new-link-btn",
		new_link_remove: ".new-link-remove",
		pastData: "#pastData",
	}
	
	var funcInit = {
		actionsInit: function(){
			return this.delegate(funcConf.actionsBtn, "click", function(){
				
				if ( ! confirm("Are you sure you want to do this action?"))
				{
					return false;
				}
				
				$(funcConf.actionsBtn).attr("disabled", true);
				var _self = $(this), _class, _action = _self.data('action');

				_class = _self.children("i.fa").attr("class");
				_self.children("i.fa").removeClass(_class).addClass(funcConf.Loader);
/*
				setTimeout(function(){
					
					var _objData = {"id": _action['id'], "method": _action['method'], "type": 0};

					_self
						.attr('title', funcConf.actions[_action['method']][2])
						.tooltip('fixTitle')
						.removeClass("btn-default")
						.addClass(funcConf.actions[_action['method']][1])
						.data("action", _objData);
					
					
					$(".actions-btn .btn").tooltip();

					_self.children("i.fa").removeClass(funcConf.Loader).addClass(_class);
					$(funcConf.actionsBtn).attr("disabled", false);
				}, 2000);
				
				return false;
*/

				$.get(funcConf.actions[_action['method']][0], { id: _action['id'], method: _action['method'], type: _action['type'] })
				.done(function( data )
				{
					if (_action['method'] == 4)
					{
						_self.parent().parent().parent().remove();
						return false;
					}

					location.href = config._currUrl;
				})
				.fail(function()
				{
					console.log('Failed to process ...');
				})
				.always(function()
				{
					if (_action['method'] == 4)
					{
						_self.children("i.fa").removeClass(funcConf.Loader).addClass(_class);
						$(funcConf.actionsBtn).attr("disabled", false);
					}
				})
			});
		},
		newLink: function(){
			return this.delegate(funcConf.new_link, "click", function(){
				var clonedInput = $(".form-group:last").clone();
				$("#newLink .modal-body .form-group:first").after( clonedInput );
				$(".modal-body .form-group:first").next().find("input").focus().val("");
			})
		},
		newLinkRemove: function(){
			return this.delegate(funcConf.new_link_remove, "click", function(){
				if ($(".modal-body .form-group").length == 2) {
					alert("Cannot be deleted.");
					return false;
				}

				$(this).parent().parent().remove();
				$(".modal-body .form-group:first").next().find("input").focus();
			})
		},
		pastDataShow: function(){
			return this.delegate(funcConf.pastData, "shown.bs.modal", function(e){
				var _self = $(this), _triggerTxt = $(e.relatedTarget).text();
				
				var _link = _self.find("a.show-all").attr("href");
				
				_self.find(".modal-body").html("Loading ...");
				_self.find("#myModalLabel").html(_triggerTxt + " DATA");
				_self.find("a.show-all").attr("href", _link + "?abbr=" + _triggerTxt)

				$.get(config._pastData, { abbr: _triggerTxt })
				.done(function( data ) {
					_self.find(".modal-body").html( data );
					$('#scrapedTable2').tablesorter();
				})
				.fail(function(){
					console.log("Something went wrong!");
				});
			})
		}
	}
	
	$.extend(config.doc, funcInit);
	config.doc.actionsInit();
	config.doc.newLink();
	config.doc.newLinkRemove();
	
	config.doc.pastDataShow();

})(jQuery,window,document)