//alert("test");

$(document).ready(function () {
    var zoekenmet = jQuery("#searchfor").val();
//alert("admin");
    $("#jqGridAdmin").jqGrid({
        url: '/index.php/pdf/getadministratorjson',
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
