/**
 * 
 */

$(document).ready(function() {
    $('#cbfilterfiat').click(function () {
        var cb_checkboxstate = 0;
        var checkboxstate = $(this).is(':checked');  //dit is de omgekeerde waarde van wat er op scherm te zien was voor de klik, daarom werkt het als toggle
        if (checkboxstate) {
            cb_checkboxstate = 1;
        } else {
            cb_checkboxstate = 0;
        }
    
        $.post("/index.php/pdf/ajaxcheckboxfiatjson",
            {
                req_cbstate: cb_checkboxstate
            },
            function (resp, resultaat) {
                if (resp.errorr == "succes") {
                    window.location = "/index.php/pdf/fiatteren";
                }
                if (resp.errorr == "no-data") {
                    alert("checkbox state fout.");
                }
            },
            'json'
            );
        return true;

    });
 });

function ajaxPdf(id_pdf) {
	$.post("/index.php/pdf/ ",
		{
			req_pdf_id: id_pdf,
			req_adz: 0
		},
		function(resp, resultaat)  {
		    if (resp.errorr == "succes") {
		        window.location = "/index.php/pdf/pdfload?pdf=" + resp.pdf;
			}
			if (resp.errorr=="no-data") {
				alert("geen pdf gevonden, no pdf available.");
			}			
		}, 
		'json'
		);
	return true;
};

function ajaxPdfAdz(factuurADZ) {
	//alert("adz factuur ophalen : " + factuurADZ);
	$.post("/index.php/pdf/getpdfjson",
		{
			req_factuurADZ: factuurADZ,
			req_adz: 1
		},
		function(resp, resultaat)  {
			if (resp.errorr=="succes") {
				window.location = "/index.php/pdf/pdfload?pdf=" + resp.pdf;			
			}
			if (resp.errorr=="no-data") {
				alert("geen pdf gevonden, no pdf available.");
			}			
		}, 
		'json'
		);
	return true;
};


function searchforname(zoek_op)
{
  alert("zoek!");	
  alert(zoek_op);
}