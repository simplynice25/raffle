;(function(){
	
	var status = { 0: "Cancelled", 1: "Pending", 2: "On Delivery", 3: "Delivered" }
	
	var cartConf = {
		add:     ".add-cart",
		order:   ".order-btn",
		remove:  ".remove-cart",
		archive: ".remove-flower",
		cancel:  ".cancel-flower",
		append:  ".append-orders",
		details: ".view-details",
		suggest: ".btn-suggestion",
		categ:   ".flower_category",
		bydate: ".by_date",
		status: ".order-status",
		billing: ".view-billing"
	}
	
	var cartFunc = {
		billing: function() {
			return this.delegate(cartConf.billing, "click", function(){
				var me = $(this), flower_id = me.data("flower-id"), order_id = me.data("order-id");
				console.log(order_id);
				console.log(flower_id);
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/orders/billing/",
					data: { 'flower_id' : flower_id, 'order_id' : order_id },
					cache: false,
					success: function (response) {
						$("#billing .modal-body").html(response);
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			})
		},
		category: function() {
			return this.delegate(cartConf.categ, "change", function() {
				$(cartConf.append).val("empty");
			});
		},
		suggestion: function() {
			return this.delegate(cartConf.suggest, "click", function(){
				var me = $(this), flower_id = me.data("entry-id");
				var suggest = $(".suggest-textarea").val();
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/admin/suggest/",
					data: { 'flower_id' : flower_id, 'suggest' : suggest },
					cache: false,
					success: function (response) {
						alert("Suggestions added.");
						$(".suggest-textarea").val("");
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		},
		view: function() {
			return this.delegate(cartConf.details, "click", function() {
				var me = $(this), order_id = me.data("order-id"), flower_id = me.data("flower-id");
					jQuery.ajax({
						type: "POST",
						url: config.base_url+"/orders/details/",
						data: { 'order_id' : order_id, 'flower_id' : flower_id },
						cache: false,
						success: function (response) {
							$("#myModal .modal-body").html(response);
						}, error: function () {
							console.log('Something went wrong..');
						}
					});
			});
		},
		status: function() {
			return this.delegate(cartConf.status, "change", function() {
				var me = $(this), stats = me.val(), id = me.data("status"), order_id = me.data("entry-id");
				if(stats == id) return false;
				if(!confirm("Are you sure you want to change this order status into "+status[stats]+"?")) return false;
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/admin/change_status/",
					data: { 'order_id' : order_id, 'stats' : stats },
					cache: false,
					success: function (response) {
						me.closest("tr").slideToggle();
						me.data("status", stats);
						alert("Success!");
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		},
		bydate: function() {
			return this.delegate(cartConf.bydate, "change", function() {
				var me = $(this), date = me.val();

				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/admin/bydate/",
					data: { 'date' : date },
					cache: false,
					success: function (response) {
						$(".append_orders").html(response);
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		},
		append: function() {
			return this.delegate(cartConf.append, "change", function() {
				var me = $(this), order_status = me.val(), action = me.data("action");
				if(action == 1) {
					var f_categ = $(".flower_category").val();
				} else {
					var f_categ = 0;
				}
				
				if(order_status == "") return false;
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/orders/append/",
					data: { 'order_status' : order_status, 'action': action, 'f_categ' : f_categ },
					cache: false,
					success: function (response) {
						$(".append_orders").html(response);
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		},
		cancel: function() {
			return this.delegate(cartConf.cancel, "click", function() {
				var me = $(this), order_id = me.data("entry-id");
				if(!confirm("Are you sure want to cancel this order?")) return false;
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/orders/cancel_order/",
					data: { 'order_id' : order_id },
					cache: false,
					success: function (response) {
						me.closest("tr").slideToggle();
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			})
		},
		order: function(){
			return this.delegate(cartConf.order, "click", function(){
				var me = $(this),
				data = me.closest("#order_form").serialize();
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/orders/add_order/",
					data: data,
					cache: false,
					success: function (response) {
						alert("Success!");
						if(response == 0) {
							location.href=config.base_url+"/cart";
						} else {
							location.href=config.base_url+"/orders";
						}
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		},
		archive: function(){
			return this.delegate(cartConf.archive, "click", function(){
				var me = $(this), 
				id = me.data("entry-id");
				if(!confirm("Are you sure you want to remove this to cart?")) return false;
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/cart/remove_cart/",
					data: { 'id' : id },
					cache: false,
					success: function (response) {
						me.closest("tr").slideToggle();
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		},
		add: function() {
			return this.delegate(cartConf.add, "click", function(){
				var me = $(this),
				id = me.data("entry-id");

				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/cart/add_cart/",
					data: { 'id' : id },
					cache: false,
					success: function (response) {
						if(response == "off-session"){
							alert("Please login to perform this action.");
							return false;
						}

						me
						.removeClass("add-cart btn-primary")
						.addClass("remove-cart btn-danger")
						.html('<i class="glyphicon glyphicon-remove"></i> Remove to cart')
						.data("cart-id", response);
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			})
		},
		remove: function(){
			return this.delegate(cartConf.remove, "click", function(){
				var me = $(this),
				id = me.data("cart-id");
				if(!confirm("Are you sure you want to remove this from the cart?")) return false;

				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/cart/remove_cart/",
					data: { 'id' : id },
					cache: false,
					success: function (response) {
						if(response == "off-session"){
							alert("Please login to perform this action.");
							return false;
						}

						me
						.removeClass("remove-cart btn-danger")
						.addClass("add-cart btn-primary")
						.html('<i class="glyphicon glyphicon-shopping-cart"></i> Add to cart')
						.data("cart-id", response);
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		}
	}

	$.extend(config.doc, cartFunc);
	config.doc.add();
	config.doc.view();
	config.doc.billing();
	config.doc.status();
	config.doc.bydate();
	config.doc.category();
	config.doc.order();
	config.doc.remove();
	config.doc.append();
	config.doc.cancel();
	config.doc.archive();
	config.doc.suggestion();

	var deleteConf = {
		archive: "a.delete",
		specific: "i.specific-delete"
	}
	
	var deleteFunc = {
		archive: function() {
			return this.delegate(deleteConf.archive,"click", function(){
				var me = $(this),
				id = me.data("entry-id");
				if(!confirm("Are you sure you want to delete this?")) return false;

				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/view/delete/",
					data: { 'id' : id },
					cache: false,
					success: function (response) {
						alert("Successfuly deleted!");
						location.href="";
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			})
		},
		specific: function() {
			return this.delegate(deleteConf.specific,"click", function(){
				var me = $(this),
				id = me.data("entry-id");
				if(!confirm("Are you sure you want to delete this?")) return false;
				var img = $("img.img-"+id).attr("src");
				
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/view/img_delete/",
					data: { 'id' : id, 'img' : img },
					cache: false,
					success: function (response) {
						alert("Successfuly deleted!");
						me.hide();
						$("img.img-"+id).slideToggle();
					}, error: function () {
						console.log('Something went wrong..');
					}
				});
			});
		}
	}
	
	$.extend(config.doc, deleteFunc);
	config.doc.archive();
	config.doc.specific();
	
	var salesConf = {
		gen: '.generator',
		date: '.sales-date',
		face: '.generated-report-interface',
		print: '.print'
	}

	var salesFunc = {
		report: function() {
			return this.delegate(salesConf.gen, "click", function(){
				var me = $(this), action = me.data("action");
				var date = $(salesConf.date).val();
				
				$(".generated-report-interface").html("Loading sales...");
				jQuery.ajax({
					type: "POST",
					url: config.base_url+"/sales/date/",
					data: { 'date' : date, 'action': action },
					cache: false,
					success: function (response) {
						if(response == "none") {
							$(".generated-report-interface").html("No sales..");
						} else {
							$(".generated-report-interface").html(response);
						}
					}, error: function () {
						console.log('Something went wrong..');
					}
				})
			})
		},
		print: function() {
			return this.delegate(salesConf.print, "click", function(){
				window.print();
			})
		}
	}

	$.extend(config.doc, salesFunc);
	config.doc.report();
	config.doc.print();

}(jQuery, window, document));
