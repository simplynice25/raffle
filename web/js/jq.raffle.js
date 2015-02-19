$(function(){

    var fakeNames = ['John Doe', 'Bill Gates', 'Mark Zuckerberg', 'Albert Einstein', 'Johnny Depp', 'Al Pacino', 'Robert De Niro', 'Denzel Washington', 'Russell Crowe', 'Brad Pitt', 'Angelina Jolie', 'Leonardo DiCaprio', 'Tom Cruise', 'John Travolta', 'Arnold Schwarzenegger', 'Sylvester Stallone', 'Morgan Freeman', 'Nicolas Cage'];

    var names = (realNames.length > 0) ? realNames : fakeNames;
    //if (names.length < 10)
        //names = names.concat(fakeNames);

    if (noShuffle == 'false') shuffleNames(names);
    //console.log(noShuffle);

    var raffle_ = $('#raffle'),
        winners_ = $("#winner"),
        consos_ = $("#conso"),
        wc_ = $('.w-c'),
        theater_ = $('.load-wc-info');

    raffle_.change(function(){
        var id = 0,
            self_ = $(this),
            raffleId_ = self_.val();

        if (raffleId_ === "0")
            return false;

        $.get('r/get-winners-consos', {id: raffleId_})
        .done(function( data )
        {
            data = $.parseJSON(data);
            winners_.empty().removeAttr('disabled').focus();
            consos_.empty().removeAttr('disabled');
            for (var i=0;i<2;i++)
            {
                var options = '',
                    dataLength = data[i].length,
                    select_ = (i==0) ? winners_ : consos_,
                    txt = (i==0) ? 'winner(s)' : 'conso(s)';

                    //console.log(data[i]);

                if (dataLength > 0 && data[i][0] !== '')
                {
                    options = '<option value="0">Select '+ txt +' to view</option>';
                    select_.append(options);


                    dataLength = dataLength+1;
                    for (var j=1;j<dataLength;j++)
                    {
                        options = '<option value="'+ data[i][j-1] +'" data-place="' + j + '">'+ ordinal_suffix_of(j) +'</option>';
                        select_.append(options);
                    }
                } else {
                    options = '<option value="0">No '+ txt +'</option>';
                    select_.append(options);
                }
            }
        })
        .fail(function()
        {
            console.log('Failed to process ...');
        })
    })

    wc_.change(function(){

        theater_.empty();

        var self = $(this),
            id = self.val(),
            raffle = raffle_.val(),
            type = self.data('type'),
            text = (type == 1) ? 'winner' : 'conso',
            heading = (type == 1) ? 'Winner' : 'Conso',
            place = self.find(':selected').data('place');

            //console.log( place );

        if (id === "0") return false;

        (type == 1) ? consos_.prop('selectedIndex',0) : winners_.prop('selectedIndex',0) ;

        theater_.html('<h3><i class="fa fa-fw fa-spin fa-circle-o-notch"></i> Loading ' + text + ' informations...</h3>');

        var data = {id: id, type: type, place: place, raffle: raffle};

        $.get('r/get-person', data)
        .done(function( data )
        {
            theater_.html( data );
            theater_.prepend( '<h1>' + ordinal_suffix_of(place) + ' place (' + heading + ')</h1><hr>' );
            $('html,body').animate({scrollTop: theater_.offset().top}, 'slow');
        })
        .fail(function()
        {
            console.log('Failed to process ...');
        })
    })

});

function shuffleNames(names)
{
    var container = $(".shuffleTheater span");
    container.text(names[Math.floor(Math.random() * names.length)]);

    setInterval(function(){
        container.shuffleLetters({
            "text": names[Math.floor(Math.random() * names.length)]
        });
        
    },100); 
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