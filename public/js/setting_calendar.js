function checktheDate(dateStr) {
    var hideRow = $('.hideRow').attr('id');
    if(hideRow != undefined && hideRow != dateStr) {
        let NoSelected = dateSelected.indexOf(hideRow);
        removeHightlight(NoSelected, hideRow);
        settingCalendar();
    }
}

function addInput(dateStr, statusCalendar = ""){      
    if(dateStr != "") {
        $('.hideRow').remove();

        var clone =  '<div id ="'+dateStr + '" class="celendar display-none hideRow">';
        clone +=  $("#clone_calendar_label").clone().html() + '</div>';
        clone = clone.replace("date", dateStr);
        clone = clone.replace("_statusCalendar", '_StatusCalendar['+dateStr+']');
        clone = clone.replace("date-info", dateStr);

        if(dateSelected.includes(dateStr)==false) {
            dateSelected.push(dateStr);
            mydate.push(new Date(dateStr));
            multiDatesPicker();
        }else{
            multiDatesPicker();
        }

        if($('#'+dateStr).length==0){
            $( "#price_input" ).append( clone ); 
            if(statusCalendar != "") { $('#'+dateStr + " select").val(1) }
        }
    }
}

function showInput(dateStr){
    setTimeout(() => {
        settingCalendar();
        $( '.celendar' ).addClass('display-none');
        $( '#'+dateStr ).removeClass('display-none');
        $( 'select' ).removeClass('dateShow');
        $( 'select[data-info="'+ dateStr +'"]' ).addClass('dateShow');
    }, 10);
}

function addInputPrices(date, price, price_child) {
    let priceAdult = $('#price_adult').val();
    let priceChild = $('#price_child').val();
    if(price == "") {price = priceAdult}
    price = formatPrice(price);
    if(price_child == "") {price_child = priceChild}
    price_child = formatPrice(price_child);
    
    var clone =  $("#clone_calendar_prices").clone().html() + '</tr>';
    clone = clone.replace("_Price_of_date", "_price_of_date["+date+"]");
    clone = clone.replace("_Price_child_of_date", "_price_child_of_date["+date+"]");

    $( '#'+date ).append( clone );
    $('input[name="_price_of_date['+date+']"]').val(price);
    $('input[name="_price_child_of_date['+date+']"]').val(price_child);
}

function DeleteInputPrices(date) {
    $('.hideRow').remove();
    let NoSelected = dateSelected.indexOf(date);
    removeHightlight(NoSelected, date);
    settingCalendar();
}

function settingCalendar() {
    $('.ui-state-highlight a').css({'color': 'white'});
}

function statusCalendar() {
    let status = $('.dateShow').val();
    let date = $('.dateShow').attr('data-info');
    if(status == 1) {
        $('.'+date).removeClass('display-none');
        $('#'+date).removeClass('hideRow');
        addInputPrices(date, price = "", price_child = "")
    } else{
        $('.'+date).remove();
        $('#'+date).addClass('hideRow');
        DeleteInputPrices(date)
    }
}

function removeHightlight(NoSelected, dateStr) {
    dateSelected.splice(NoSelected,1);
    mydate.splice(NoSelected,1);
    $('#mdp-demo').multiDatesPicker('removeDates', new Date(dateStr));
}

var mydate = [new Date('1970-01-01')];
var dateSelected = ['1970-01-01'];
function multiDatesPicker() {
    $('#mdp-demo').multiDatesPicker({
    altField: '#multiDate',
    dateFormat: "yy-mm-dd",
    addDates: mydate,      
    onSelect: function(dateStr) {
        checktheDate(dateStr);
            addInput(dateStr);
            showInput(dateStr);
        },
    });
}