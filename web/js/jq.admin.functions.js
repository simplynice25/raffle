// JavaScript Document
(function(){


  $("#winners, #consolations").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        return false;
    }
   });

    $('#raffleModal').on('shown.bs.modal', function(){
        $('input[name=title]').focus();
    })

	var funcConf = {
	}
	
	var funcInit = {
	}
	
	$.extend(config.doc, funcInit);

})(jQuery,window,document)