(function($) {

    $.formatCurrency.regions['vi-VN'] = {
//        symbol: '',
        positiveFormat: '%n %s',
        negativeFormat: '-%n %s',
        suppressCurrencySymbol: true,
        decimalSymbol: '.',
        digitGroupSymbol: ',',
        groupDigits: true,
        roundToDecimalPlace: 2,
        removeTrailingZerosOnDecimal: false
    };

})(jQuery);
