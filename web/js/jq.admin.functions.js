// JavaScript Document
(function(){

    if ($('.btn.add-prize').length > 0)
    {
        $('.btn.add-prize').tooltip();
    }

    onDocReady();

    $('#raffleModal').on('shown.bs.modal', function(e){
        $('input[name=title]').focus();
        
        var self = $(this),
            invoker = $(e.relatedTarget),
            parent_inv = invoker.closest('tr'),
            data_ = parent_inv.data('raffle'),
            actionAttr = self.find('form').data('action');

        self.find('form').find("textarea, input[type=text], input[type=date], input[type=number]").val("");
        for (instance in CKEDITOR.instances){
           //CKEDITOR.instances[instance].setData(" ");
        }

       if (data_)
       {
           var action_ = actionAttr + '&id=' + data_.id;
           self.find('form').attr('action', action_);
           self.find('form input[name=title]').val(data_.title);
           //self.find('form textarea[name=desc]').val(unescape(data_.desc));
           self.find('form input[name=start]').val(data_.start);
           self.find('form input[name=end]').val(data_.end);
           self.find('form input[name=winners]').val(data_.winners);
           self.find('form input[name=consolations]').val(data_.consos);
           
           var desc_ = unescape(data_.desc);
           CKEDITOR.instances['desc'].setData(desc_);
           
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
        winnerChange: 'select#winner',
        addPrize: '.btn.add-prize',
        banUser: '.btn.btn-ban',
        userSearch: 'input#search',
        userRole: 'select[name=user-role]',
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
            return this.delegate(funcConf.raffleChange+','+funcConf.winnerChange, 'change', function(){
                var obj = $(this),
                    raffle = $('select[name=raffle] option:selected').val()
                    dataType = obj.data('type');

                if (obj.attr('id') == 'raffle') onDocReady();
                winnerPrizes(raffle, dataType);
            })
        },
        prizeModalAction: function() {
            return this.delegate(funcConf.prizeModal, 'shown.bs.modal', function(e){
                var self = $(this),
                    invoker = $(e.relatedTarget),
                    modalForm = self.find('form'),
                    modalFormAction = modalForm.data('action'),
                    imgMsgObj = self.find('.text-danger'),
                    title = invoker.data('title'),
                    desc = invoker.data('desc');
                
                if (invoker.data('type') == 1){
                    modalForm.attr('action', modalFormAction);
                    imgMsgObj.addClass('hide');
                    $(funcConf.prizeModal + ' input[name=title]').val('');
                    $(funcConf.prizeModal + ' textarea[name=desc]').val('');
                    $(funcConf.prizeModal + ' input[name=image]').attr('required', true);
                } else {
                    modalForm.attr('action', modalFormAction + '?id=' + invoker.data('id'));
                    imgMsgObj.removeClass('hide');
                    $(funcConf.prizeModal + ' input[name=title]').val(title);
                    $(funcConf.prizeModal + ' textarea[name=desc]').val(desc);
                    $(funcConf.prizeModal + ' input[name=image]').attr('required', false);
                }
            })
        },
        addPrize: function(){
            return this.delegate(funcConf.addPrize, 'click', function(e){
                e.preventDefault();
                var self = $(this),
                    status = self.data('status'),
                    type = self.data('type'),
                    prize  = self.parent().parent().parent().parent().data('id'),
                    raffle = $('select[name=raffle] option:selected').val(),
                    winner = $('select[name=winner] option:selected').val();
                    
                    if (prize == 0 || raffle == 0 || winner == 0) return false;
                    
                    self.attr('disable', true).find('i.fa').removeClass('fa-plus fa-minus').addClass('fa-refresh fa-spin');
                    
                    $.get((type==='conso') ? 'add-conso-prize' : 'add-prize', {prize: prize, raffle: raffle, winner: winner, status: (status == 0) ? 5 : 1})
                    .done(function(data)
                    {
                        console.log('Done processing ..');
                    })
                    .fail(function(data)
                    {
                        console.log('Something went wrong ..');
                    })
                    .always(function(data)
                    {
                        if (status == 0)
                        {
                            self
                            .attr('title', 'Remove').tooltip('fixTitle').tooltip('show')
                            .removeClass('btn-success').addClass('btn-danger')
                            .find('i.fa').removeClass('fa-refresh fa-spin').addClass('fa-minus');
                        } else {
                            self
                            .attr('title', 'Add').tooltip('fixTitle').tooltip('show')
                            .removeClass('btn-danger').addClass('btn-success')
                            .find('i.fa').removeClass('fa-refresh fa-spin').addClass('fa-plus');
                        }
                        
                        self.data('status', status = (status == 0) ? 1 : 0).attr('disable', false);
                    })
            })
        },
        banUser: function(){
            return this.delegate(funcConf.banUser, 'click', function(){
                if (!confirm('Are you sure you want to ban this user?')) return false;

                var self = $(this),
                    btnTxt = '',
                    userId = self.parent().parent().parent().data('user-id'),
                    status_ = self.data('status');

                    self.children('i.fa').addClass('fa-refresh fa-spin').removeClass('fa-ban');

                $.get('user-action', { user: userId, status: status_ })
                .done(function( data )
                {
                    data = $.parseJSON(data);

                    if (data.message == 'error')
                    {
                        btnTxt = (status_ == 5) ? 'Unban' : 'Ban';
                        return false;
                    }
                    else if (status_ == 5)
                    {
                        btnTxt = 'Ban';
                        self.removeClass('btn-danger').addClass('btn-success').data('status', 1);
                    }
                    else
                    {
                        btnTxt = 'Unban';
                        self.removeClass('btn-success').addClass('btn-danger').data('status', 5);
                    }

                    console.log(status_);

                    self.html('<i class="fa fa-fw fa-ban"></i> ' + btnTxt);

                })
                .fail(function()
                {
                    console.log('Failed to process ...');
                })
                .always(function( data )
                {
                    return true;
                });
            })
        },
        userSearch: function(){
            return this.delegate(funcConf.userSearch, 'keypress', function(){
                var self = $(this);
                
                if (self.next('span').length < 1)
                {
                    self.after(' <span><br/><i class="fa fa-fw fa-refresh fa-spin"></i> Loading ...</span>');
                }
                
                clearTimeout(funcConf.searchTime);
                
                funcConf.searchTime = setTimeout(function(){
                    $.get('users-search', { keyword: self.val()})
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
                        $('table.table').find('tbody').html( data );
                    })
                }, 2000);
            })
        },
        userRole: function () {
            return this.delegate(funcConf.userRole, 'change', function(){
                if ( ! confirm('Are you sure you want to change this user role?')) return false;

                var self = $(this),
                    userId = self.parent().parent().data('user-id');

                self.attr('disabled', true);

                $.get('user-role', { role: self.val(), userId: userId})
                .done(function( data )
                {
                    data = $.parseJSON(data);
                    if (data.message == 'error')
                    {
                        alert('Something went wrong, try again..');
                    } else {
                        alert('User role changed!');
                    }
                })
                .fail(function()
                {
                    console.log('Failed to process ...');
                })
                .always(function( data )
                {
                    self.attr('disabled', false);
                })
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

    config.doc.banUser();
    config.doc.userSearch();
    config.doc.userRole();

})(jQuery,window,document);


function winnerPrizes(raffle, type)
{
    var winner = $('select[name=winner] option:selected').val(),
        link_ = (type==='conso') ? 'conso-prizes' : 'winner-prizes',
        h1Txt = (type==='conso') ? 'conso' : 'winners';

    if (winner == '' || winner == 0) {
        $('.prizes-theater').empty().html('<div class="col-lg-12"><h1>No '+ h1Txt +' assigned for this raffle.</h1></div>');
        return false;
    }
    
    $('.prizes-theater').empty().html('<div class="col-lg-12"><i class="fa fa-refresh fa-spin"></i> Loading prizes ...</div>');
    $.get(link_, { raffle: raffle, winner: winner, type: type })
    .done(function( data )
    {
        $('.prizes-theater').html( data );
    })
    .fail(function()
    {
        console.log('Failed to process ...');
    })
    .always(function( data )
    {
        console.log(link_);
        return true;
    });
}

function onDocReady()
{
    if ($('#winner').length === 0) return false;
    
    var type = $('select[name=raffle]').data('type'),
        optTxt = (type==='conso') ? 'conso' : 'winners',
        winners = $('select[name=raffle] option:selected').data('winners')+1, options_ = '';
    for (var i=1;i<winners;i++)
    {
        options_ += '<option value="'+i+'">'+ordinal_suffix_of(i)+'</option>';
    }
    
    $('#winner').html(options_);
    
    if ($('select[name=winner] option').length===0)
    {
        $('#winner').html( '<option value="0">No '+ optTxt +' assigned.</option>' );
        $('.prizes-theater').html('<div class="col-lg-12"><h1>No '+ optTxt +' assigned for this raffle.</h1></div>');
    }
}

function ordinal_suffix_of(i)
{
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