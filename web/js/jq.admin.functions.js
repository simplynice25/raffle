// JavaScript Document
(function(){


  $("#winners, #consolations").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        return false;
    }
   });

    $('#raffleModal').on('shown.bs.modal', function(e){
        $('input[name=title]').focus();
        
        var self = $(this),
            invoker = $(e.relatedTarget),
            parent_inv = invoker.closest('tr'),
            data_ = parent_inv.data('raffle'),
            actionAttr = self.find('form').data('action');

       self.find('form').find("textarea, input[type=text], input[type=date], input[type=number]").val("");

       if (data_)
       {
           var action_ = actionAttr + '&id=' + data_.id;
           self.find('form').attr('action', action_);
           self.find('form input[name=title]').val(data_.title);
           self.find('form textarea[name=desc]').val(data_.desc);
           self.find('form input[name=start]').val(data_.start);
           self.find('form input[name=end]').val(data_.end);
           self.find('form input[name=winners]').val(data_.winners);
           self.find('form input[name=consolations]').val(data_.consos);
           
           return true;
       }
       
       self.find('form').attr('action', actionAttr);
    })

	var funcConf = {
        trashAlert: '.btn-delete',
        raffleActivate: '.btn-activate',
	}
	
	var funcInit = {
        trashAlert: function() {
            return this.delegate(funcConf.trashAlert, 'click', function(){
                if ( ! confirm('Are you sure you want to delete this?')) return false;
            })
        },
        raffleActivate: function() {
            return this.delegate(funcConf.raffleActivate, 'click', function(){
                if ( ! confirm('You can\'t undo this if you decide to active a raffle. Are you sure you want to continue?')) return false;
            })
        },
	}
	
	$.extend(config.doc, funcInit);
    
    config.doc.trashAlert();
    config.doc.raffleActivate();

})(jQuery,window,document)