// JavaScript Document
(function(){

    if ($('.btn.add-prize').length > 0)
    {
        $('.btn.add-prize').tooltip();
    }

    onDocReady();

    appendWinnerOpt($('select[name=raffle]').val(), $('select[name=raffle] option:selected').data('winners'), 1);
    /*
    $('#winnerModal').on('shown.bs.modal', function(e){        
        var select_ = $('select[name=raffle]')
            winners = $('select[name=raffle] option:selected').data('winners')+1,
            raffleId = $('select[name=raffle] option:selected').val();
        
        select_.focus();
        appendWinnerOpt(raffleId, winners);
    });
    */

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
        prizeModal: '#prizeModal',
        raffleChange: 'select#raffle',
        addPrize: '.btn.add-prize',
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

                onDocReady();
                // appendWinnerOpt(raffleId, winners);
            })
        },
        prizeModalAction: function() {
            return this.delegate(funcConf.prizeModal, 'shown.bs.modal', function(e){
                var self = $(this),
                    invoker = $(e.relatedTarget),
                    modalForm = self.find('form'),
                    modalFormAction = modalForm.data('action'),
                    imgMsgObj = self.find('.text-danger');
                
                if (invoker.data('type') == 1){
                    modalForm.attr('action', modalFormAction);
                    imgMsgObj.addClass('hide');
                } else {
                    modalForm.attr('action', modalFormAction + '?id=' + invoker.data('id'));
                    imgMsgObj.removeClass('hide');
                }
            })
        },
        addPrize: function(){
            return this.delegate(funcConf.addPrize, 'click', function(e){
                e.preventDefault();
                var self = $(this),
                    status = self.data('status'),
                    prize  = self.parent().parent().parent().parent().data('id'),
                    raffle = $('select[name=raffle] option:selected').val(),
                    winner = $('select[name=winner] option:selected').val();

                    if (status == 0)
                    {
                        self
                        .attr('title', 'Remove').tooltip('fixTitle').tooltip('show')
                        .removeClass('btn-success').addClass('btn-danger')
                        .find('i.fa').removeClass('fa-plus').addClass('fa-minus');
                    } else {
                        self
                        .attr('title', 'Add').tooltip('fixTitle').tooltip('show')
                        .removeClass('btn-danger').addClass('btn-success')
                        .find('i.fa').removeClass('fa-minus').addClass('fa-plus');
                    }
                    
                    self.data('status', status = (status == 0) ? 1 : 0);
                    
                    console.log(prize);
                    console.log(raffle);
                    console.log(winner);
            })
        },
	}
	
	$.extend(config.doc, funcInit);

    config.doc.trashAlert();
    config.doc.raffleActivate();
    config.doc.raffleSearch();
    
    config.doc.prizeModalAction();
    config.doc.raffleChange();
    config.doc.addPrize();

})(jQuery,window,document);


function appendWinnerOpt(id, n)
{
    return true;
    $('#winner').attr('disabled', true).html( '<option>Loading ...</option>' );
    $.get('winner-has-prize', { raffle: id, winners: n })
    .done(function( data )
    {
        $('#winner').attr('disabled', false).empty().html( data );
    })
    .fail(function()
    {
        console.log('Failed to process ...');
    })
    .always(function( data )
    {
        return true;
    });
}

function onDocReady()
{
    if ($('#winner').length === 0) return false;
    
    var winners = $('select[name=raffle] option:selected').data('winners')+1, options_ = '';
    for (var i=1;i<winners;i++)
    {
        options_ += '<option value="'+i+'">'+ordinal_suffix_of(i)+'</option>';
    }
    
    $('#winner').html(options_);
}

function ordinal_suffix_of(i) {
    var j = i % 10,
        k = i % 100;
    if (j == 1 && k != 11) {
        return i + "st";
    }
    if (j == 2 && k != 12) {
        return i + "nd";
    }
    if (j == 3 && k != 13) {
        return i + "rd";
    }
    return i + "th";
}