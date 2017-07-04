function Bliskapaczka()
{
}

Bliskapaczka.showMap = function (prices, disabledOperators) {
    aboutPoint = document.getElementById('bpWidget_aboutPoint');
    aboutPoint.style.display = 'none';

    bpWidget = document.getElementById('bpWidget');
    bpWidget.style.display = 'block';

    Bliskapaczka.updateSelectedCarrier();

    BPWidget.init(
        bpWidget,
        {
            callback: function (posCode, posOperator) {
                console.log('BPWidget callback:', posCode, posOperator)

                posCodeForm = document.getElementById('bliskapaczka_posCode')
                posOperatorForm = document.getElementById('bliskapaczka_posOperator')

                posCodeForm.value = posCode;
                posOperatorForm.value = posOperator;

                Bliskapaczka.pointSelected(posCode, posOperator, prices);
            },
            prices: prices,
            disabledOperators: disabledOperators,
            posType: 'DELIVERY'
        }
    );
}

Bliskapaczka.pointSelected = function (posCode, posOperator, prices) {
    Bliskapaczka.updatePrice(posOperator, prices);

    bpWidget = document.getElementById('bpWidget');
    bpWidget.style.display = 'none';

    aboutPoint = document.getElementById('bpWidget_aboutPoint');
    aboutPoint.style.display = 'block';

    posCodeBlock = document.getElementById('bpWidget_aboutPoint_posCode');
    posOperatorBlock = document.getElementById('bpWidget_aboutPoint_posOperator');

    posCodeBlock.innerHTML = posCode
    posOperatorBlock.innerHTML = posOperator
}

Bliskapaczka.updatePrice = function (posOperator, prices) {
    item = Bliskapaczka.getTableRow();
    if (item) {
        priceDiv = item.find('.delivery_option_price').first();
        price = prices[posOperator];
        priceDiv.html(priceDiv.text().replace(/([\d\.,]{2,})/, price));
    }
}

Bliskapaczka.updateSelectedCarrier = function () {
    item = Bliskapaczka.getTableRow();
    
    if (item) {
        input = item.find('input.delivery_option_radio').first();
        // Magic because in themes/default-bootstrap/js/order-carrier.js is defined event onchanged input
        input.click();
    
        items = jQuery('td.delivery_option_radio span')
        items.each(function (index, element) {
            jQuery(this).removeClass('checked');
        });
        item.find('td.delivery_option_radio span').first().addClass('checked');
    }
}

Bliskapaczka.getTableRow = function () {
    item = null;
    itemList = jQuery('.order_carrier_content').find('.delivery_option:contains("bliskapaczka")');
    
    if (itemList.length > 0) {
        item = jQuery(itemList[0]);
    }

    return item;
}
