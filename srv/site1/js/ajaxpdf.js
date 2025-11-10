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


function ajaxPdf(id_pdf, werknemer_id, jaar, flag) {
	if (flag===1) {	
		var trimmed_factuurnummer = id_pdf.trim(); // "Factuur 3315-25005.pdf"
		filename = "Factuur " + trimmed_factuurnummer + ".pdf";
		filepad = "C:/pdf/" + jaar + "/" + werknemer_id + "/" + filename;
		fetch('/index.php/pdf/file_bestaat?file=' + encodeURIComponent(filepad))
		.then(response => {
			console.log('Status:', response.status);
			return response.json();
		})
		.then(data => {
			console.log('Response data:', data);
			if (data.exists) {
				AjaxPdfDownload(trimmed_factuurnummer, werknemer_id, jaar);
			} else {
				alert ("Factuur niet gevonden: " + filename)
			}
			return true;
		})
		.catch(error => {
			console.error('Fetch error:', error);
			alert('Fout bij ophalen: ' + error);
			return false;
		});
	} else {
//oude functionaliteit
		var input_id_pdf = id_pdf;
		$.post("/index.php/pdf/getpdfjson",
		{
		    req_pdf_id: input_id_pdf,
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
	}

};

function ajaxPdfAdz(factuurADZ, werknemer_id, jaar, flag) {
	if (flag===1) {
//		alert(factuurADZ);
		var trimmed_factuurnummer = factuurADZ.trim(); // "Factuur 3315-25005.pdf"
//		alert(trimmed_factuurnummer);
		filename = "Factuur " + trimmed_factuurnummer + ".pdf";
		filepad = "C:/pdf/" + jaar + "/" + werknemer_id + "/" + filename;
		fetch('/index.php/pdf/file_bestaat?file=' + encodeURIComponent(filepad))
		.then(response => {
			console.log('Status:', response.status);
			return response.json();
		})
		.then(data => {
			console.log('Response data:', data);
			if (data.exists) {
				AjaxPdfDownload(trimmed_factuurnummer, werknemer_id, jaar);
			} else {
				alert ("factuur niet gevonden: " + filename)
			}
			return true;
		})
		.catch(error => {
			console.error('Fetch error:', error);
			alert('Fout bij ophalen: ' + error);
			return false;
		});
	} else {
		//oude functionaliteit
		var input_factuurADZ = factuurADZ.trim();
		$.post("/index.php/pdf/getpdfjson",
			{
				req_factuurADZ: input_factuurADZ,
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
		}
	};


function ajax_pdf_download(werknemer_id, jaartal, flag) {

   if (flag==1) {
		var filename = "Facturen" + " " + jaartal + ".pdf";
		filepad = "C:/pdf/" + jaartal + "/" + werknemer_id + "/" + filename;
		fetch('/index.php/pdf/file_bestaat?file=' + encodeURIComponent(filepad))
		.then(response => {
			console.log('Status:', response.status);
			return response.json();
		})
		.then(data => {
			console.log('Response data:', data);
			if (data.exists) {
				AjaxPdfDownloadLijst(werknemer_id, jaartal);
			} else {
				alert ("factuur niet gevonden: " + filename)
			}
			return true;
		})
		.catch(error => {
			console.error('Fetch error:', error);
			alert('Fout bij ophalen: ' + error);
			return false;
		});
	} else {
		//oude functionaliteit
		$.post("/index.php/pdf/getpdfdownloadjson",
		{
		    req_werknemer_id: werknemer_id,
			req_jaartal: jaartal
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
	}
};

function ajax_pdf_downloadtest(werknemer_id, jaartal) {
	alert(werknemer_id + "  " + jaartal);
};


function searchforname(zoek_op)
{
  alert("zoek!");	
  alert(zoek_op);
}