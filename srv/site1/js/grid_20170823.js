


$(document).ready(function () {
  
	//var httpconfigbase = "https://zzp.coopinfo.nl/_periode";
	 var httpconfigbase = "https://zzp.coopinfo.nl";
	//var httpconfigbase = "";
	//alert(httpconfigbase);
    var zoekenmet = jQuery("#searchfor").val();

    $("#jqGridAdmin").jqGrid({
        url: httpconfigbase + '/index.php/pdf/getadministratorjson',
        postData: { FilterValue: jQuery("#searchfor").val() },
        //postdata: {
          //  FilterValue: function () { return jQuery("#searchfor").val(); },
        //},
//        postData: { FilterValue: "qweq" },
        datatype: "json",
        colNames: ['GebruikerID', 'Gebruiker', 'Naam', 'Email', 'Bedrijfsnaam'],
        colModel: [
            {
                label: 'GebruikerID', name: 'GebruikerID', width: 10,
                formatter: function (cellvalue, options, rowObject) {
                    var id = rowObject[0];
                    return '<a href="/index.php/users/adminselect/' + id + '">' + id + '</a>';
                }
            },
            {
                label: 'Gebruiker', name: 'username', width: 30
            },
            {
                label: 'Naam', name: 'last_name', width: 30
            },
            {
                label: 'Email', name: 'email', width: 30
            },
            {
                label: 'company_name', name: 'company_name', width: 30
            },
        ],
        //
        sortable: true,  //lkj
        //dezedan         loadonce: true,  //lkj
        //
        viewrecords: true,
        /////footerrow: true,
        /////userDataOnFooter: true,
        //altRows : true,
        width: 1200,
        height: 620,
        //height:'auto',
        rowNum: 20,
        pager: "#jqGridAdminPager"
    });

    /**/
        $("#jqGridAdmin").jqGrid('setLabel', 'GebruikerID', '', 'textalignleft');
        $("#jqGridAdmin").jqGrid('setLabel', 'username', '', 'textalignleft');
        $("#jqGridAdmin").jqGrid('setLabel', 'last_name', '', 'textalignleft');
        $("#jqGridAdmin").jqGrid('setLabel', 'email', '', 'textalignleft');
        $("#jqGridAdmin").jqGrid('setLabel', 'company_name', '', 'textalignleft');
    /**/

    //    jQuery("#jqGridAdmin").jqGrid('setGridParam', { zoeken: "zoekdan" });

        //$(".selectRequestType").change(function () {
     //!   $('#jqGridAdmin').setGridParam({ postData: { FilterValue: "kok" } }).trigger('reloadGrid', [{ page: 1 }]);
        //});

});



$(document).ready(function () {

/*
    $("#jqGrid_periode").hover(
      function() {
          $( this ).append( $( "<span> Periode W = week  4 = 4 week periode  M = maand</span>" ) );
      }, function() {
          $( this ).find( "span:last" ).remove();
      }
    );
 
    $("#jqGrid_periode.fade").hover(function () {
        $( this ).fadeOut( 100 );
        $( this ).fadeIn( 500 );
    });
*/

		//url: 'https://zzp.coopinfo.nl/index.php/pdf/getfacturenjson',
//    var setTooltipsOnColumnHeader = function (grid, iColumn, text) {
  //      var thd = $("thead:first", grid[0].grid.hDiv)[0];
    //    $("tr.ui-jqgrid-labels th:eq(" + iColumn + ")", thd).attr("title", text);
    //};

    //setTooltipsOnColumnHeader($("#jqGrid"), 1, "bla bla");



    $("#jqGrid").jqGrid({
        url: '/index.php/pdf/getfacturenjson',
		//url: 'https://zzp.coopinfo.nl/index.php/pdf/getfacturenjson',
        datatype: "json",
        headertitles: true,
        hiddengrid: true,
        colNames: ['P', 'Periode', 'Opdrachtgever', 'Uren', 'Factuur', '', 'Datum', 'Vervalt', 'Betaald', 'Factuur', 'Voorschot', 'Co&ouml;peratie', 'Dienstverlening', '', 'Resteert', 'Btw', 'Factuur ADZ', ''],
        //colNames: ['Week', 'Opdrachtgever', 'Uren', 'Factuur', '', 'Datum', 'Vervalt', 'Betaald', 'Factuur', 'Voorschot', 'Co&ouml;peratie', 'Dienstverlening', '', 'Resteert', 'Btw', 'Factuur ADZ', ''],
        colModel: [
            { label: 'periode', name: 'periode', width: 10 },
            { label: 'Week', name: 'week', width: 30, sorttype: 'int' },
            { label: 'Opdrachtgever', name: 'opdrachtgever', width: 150 },
            { label: 'Uren', name: 'uren', align: 'right', width: 30, sorttype: 'int' },
            { label: 'FactuurNr', name: 'Factuurnr', align: 'right', width: 65 },
            {
                label: 'Pdf', name: 'Factuurnr', width: 20,
                formatter: function (cellvalue, options, rowObject) {
                    var factuurnummer = rowObject[4];
                    factuurnummer = (factuurnummer == undefined ? rowObject.Factuurnr : factuurnummer);
                    //??var factuurnummer = cellvalue;
                    //alert("nummer: " + factuurnummer);
                    //return '<span class="div-icon-pdf2"  title="klik om het bestand te downloaden" onclick="ajaxPdf(' + options.rowId + ');"></span>';
                    return '<span class="div-icon-pdf2"  title="klik om het bestand te downloaden" onclick="ajaxPdf(' + '\'' + factuurnummer + '\'' + ');"></span>';
                }
            },
            { label: 'Datum', name: 'FactuurDatumDATEONLY', align: 'right', width: 40 },
            { label: 'Vervalt', name: 'VervalDatumDATEONLY', align: 'right', width: 40 },
            { label: 'Betaald', name: 'BetaalddatumDATEONLY', align: 'right', width: 40 },
            { label: 'Factuur', name: 'FactuurTotaal', align: 'right', width: 45 },
            { label: 'Voorschot', name: 'Voorschot', align: 'right', width: 45 },

//            { label: 'Btw', name: 'Btw', align: 'right', width: 45 },

            { label: 'Co&ouml;peratie', name: 'BorgZZP', align: 'right', width: 45 },
            { label: 'Dienstverlening', name: 'kosten', align: 'right', width: 58 },
            {
                label: 'FactuurOverzicht', name: 'Factuurnummer', width: 20,
                formatter: function (cellvalue, options, rowObject) {
                    var factuurnummer = rowObject[4];
                    factuurnummer = (factuurnummer == undefined ? rowObject.Factuurnr : factuurnummer);
                    var factuurnummer1 = rowObject.Factuurnr;
                    var factuurnummer2 = cellvalue;
                    //       alert("factuurnummer: " + factuurnummer2);
                    return '<a class="various fancybox.iframe ZZZa_pdfpopup" href="/index.php/pdf/pdfpopup/' + factuurnummer + '"><span class="div-icon-informatie2"  title="klik voor factuur specificatie"></span></a>';
                }
            },
            { label: 'Resteert', name: 'Resteert', align: 'right', width: 45 },
            { label: 'Btw', name: 'Btw', align: 'right', width: 45 },
            { label: 'FactuurADZ', name: 'FactuurADZ', align: 'right', width: 50 },
            {
                label: 'PDFADZ', name: 'FactuurADZpdf', width: 20,
                formatter: function (cellvalue, options, rowObject) {
                    var veldwaarde_als_string = "'" + cellvalue + "'"; 	  //hoeft niet zo: var veldwaarde_als_string ="'" + rowObject[14] + "'";
                    //alert("nummer: " + veldwaarde_als_string);
                    return '<span class="div-icon-pdf2" title="klik om het bestand te downloaden" onclick="ajaxPdfAdz(' + veldwaarde_als_string + ');"></span>';
                }
            }
        ],
        //
        sortable: false,  //lkj, staat nu uit, je kunt kolommen niet meer verslepen.  Dit is dus niet het sorteren verticaal van data!
        loadonce: true,  //lkj
        //
        viewrecords: true,
        footerrow: true,
        userDataOnFooter: true,
        //altRows : true,
        width: 1200,
        height: 620,
        //height:'auto',
        rowNum: 20,
        pager: "#jqGridPager"
    });


    //$("#jqGrid").jqGrid('setLabel', 'periode', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'week', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'opdrachtgever', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'Factuurnr', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'Pdf', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'FactuurDatumDATEONLY', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'VervalDatumDATEONLY', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'BetaalddatumDATEONLY', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'FactuurADZ', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'FactuurADZpdf', '', 'textalignleft');

    $("#jqGrid").jqGrid('setLabel', 'uren', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'FactuurTotaal', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'Voorschot', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'Btw', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'BorgZZP', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'kosten', '', 'textalignleft');
    $("#jqGrid").jqGrid('setLabel', 'Resteert', '', 'textalignleft');



   var thd = jQuery("thead:first", $("#jqGrid")[0].grid.hDiv)[0];
    jQuery("tr.ui-jqgrid-labels th:eq(" + 0 + ")", thd).attr("title", "W: week  4: 4 weken  M: maand");
    jQuery("tr.ui-jqgrid-labels th:eq(" + 1 + ")", thd).attr("title", "W: week  4: 4 weken  M: maand");

});


