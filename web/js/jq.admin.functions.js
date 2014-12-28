// JavaScript Document
(function(){
    
    appendWinnerOpt($('select[name=raffle_]').val(), $('select[name=raffle_] option:selected').data('winners'), 1);
    
    $('#winnerModal').on('shown.bs.modal', function(e){        
        var select_ = $('select[name=raffle]')
            winners = $('select[name=raffle] option:selected').data('winners')+1,
            raffleId = $('select[name=raffle] option:selected').val();
        
        select_.focus();
        appendWinnerOpt(raffleId, winners);
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
        raffleSearch: 'input[name=q]',
        searchTime: 0,
        raffleChange: 'select#raffle',
        raffleChange_: 'select#raffle_',
        winnerChange: 'select#winners'
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
        raffleSearch: function() {
            return this.delegate(funcConf.raffleSearch, 'keypress', function(){
                var self = $(this);
                
                if (self.next('span').length < 1)
                {
                    self.after(' <span><br/><i class="fa fa-fw fa-refresh fa-spin"></i> Loading ...</span>');
                }
                
                clearTimeout(funcConf.searchTime);
                
                funcConf.searchTime = setTimeout(function(){
                    $.get('raffle-search', { keyword: self.val(), tab: self.data('tab') })
                    .done(function( data )
                    {
                        self.next('span').remove();
                    })
                    .fail(function()
                    {
                        console.log('Failed to process ...');
                    })
                    .always(function( data )
                    {
                        self.parent().next('table').remove();
                        self.parent().next('.alert').remove()
                        self.parent().after( data );
                    })
                }, 2000);
            })
        },
        raffleChange: function() {
            return this.delegate(funcConf.raffleChange, 'change', function(){
                var selected = $('select[name=raffle] option:selected'),
                    winners = selected.data('winners')+1
                    raffleId = selected.val();

                appendWinnerOpt(raffleId, winners);
            })
        },
        raffleChange_: function() {
            return this.delegate(funcConf.raffleChange_, 'change', function(){
                var selected = $('select[name=raffle_] option:selected'),
                    winners = selected.data('winners')+1
                    raffleId = selected.val();

                appendWinnerOpt(raffleId, winners, 1);
            })
        },
        winnerChange: function() {
            return this.delegate(funcConf.winnerChange, 'change', function(){
                return true;
                var selected = $('select[name=winner] option:selected');
                
                if (selected.data('hasprize') == 0 || selected.data('hasprize') == '0')
                {
                    alert('Already has a prize.');
                    $('#winnerModal button[type=submit]').attr('disabled', true);
                } else {
                    $('#winnerModal button[type=submit]').attr('disabled', false);
                }
                
            })
        },
	}
	
	$.extend(config.doc, funcInit);

    config.doc.trashAlert();
    config.doc.raffleActivate();
    config.doc.raffleSearch();
    config.doc.raffleChange();
    config.doc.raffleChange_();
    config.doc.winnerChange();

})(jQuery,window,document);

function appendWinnerOpt(id, n, z)
{
    if (!z)
    {
        $('#winners').attr('disabled', true).html( '<option>Loading ...</option>' );
    } else {
        $('#winners_').attr('disabled', true).html( '<option>Loading ...</option>' );
    }
        
    $.get('winner-has-prize', { raffle: id, winners: n })
    .done(function( data )
    {
        if (!z)
        {
            $('#winners').attr('disabled', false).empty().html( data );
        } else {
            $('#winners_').attr('disabled', false).empty().html( data );
        }
    })
    .fail(function()
    {
        console.log('Failed to process ...');
    })
    .always(function( data )
    {
        return true;
        var selected = $('select[name=winner] option:selected');
        
        if (selected.data('hasprize') == 0 || selected.data('hasprize') == '0')
        {
            $('#winnerModal button[type=submit]').attr('disabled', true);
        }
    });
}