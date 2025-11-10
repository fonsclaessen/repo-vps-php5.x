
var data_taartdiagram = {};
var data_staafdiagram = {}; 
var toolTipCustomFormatFn = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
    var dataItem = data_taartdiagram[itemIndex];
    //return '<DIV style="text-align:left"><b>Team: ' + categoryValue +
    //'</b><br />Start day: ' + value.from +
    //'<br />End day: ' + value.to;
	
    return '<DIV style="padding-top:15px; zzztext-align:left">' + dataItem.Uren + ':  ' + value + '%</DIV>';
};

var toolTipCustomFormatFnBanketstaaf = function (value, itemIndex, serie, group, categoryValue, categoryAxis) {
    var dataItem = data_staafdiagram[itemIndex];
    //return '<DIV style="text-align:left"><b>Team: ' + categoryValue +
    //'</b><br />Start day: ' + value.from +
    //'<br />End day: ' + value.to;

    //return '<DIV style="padding-top:5px; zzztext-align:left;">' + serie.displayText + ':  ' + value + '</DIV>';

   return '<DIV style="padding-top:15px; zzztext-align:left;">' + 'Jaar ' + categoryValue + '</DIV>' +
      '<DIV style="padding-top:15px; zzztext-align:left;">' + serie.displayText + ':  ' + value + '</DIV>';

    //neeeja
    //return '<div style="padding-top:15px; zzztext-align:left;">' + 'Jaar ' + categoryValue + '</div>' +
      //    '<div style="padding-top:15px; zzztext-align:left;">' + serie.displayText + ':  ' + value + '</div>';

};

