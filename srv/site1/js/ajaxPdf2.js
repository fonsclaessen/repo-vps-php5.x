function ajaxPdf_dXXXX(factuurnummer) {

    //fetch('/get_factuur_d.php', {
	fetch('/index.php/pdf/pdftest', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            FactuurNummer: factuurnummer,
        })
    })
        .then(response => response.json())
        .then(data => {

            //je krijgt de data-URL uit de response
            var pdf_filename = data.pdf_filename; // bv. "data:application/pdf;base64,JVBERi0xLjQ..."
            //alert(pdf_filename);
            alert("https://zzp.coopinfo.nl/index.php/pdf/pdfload?pdf=" + pdf_filename);

            //MAC
            //window.location = "http://localhost:8000/loadpdf?pdf=" + pdf_filename;

            //MAC
            //window.location = "https://zzp.coopinfo.nl/index.php/pdf/pdfload?pdf=" + "pdf_2759_878FC117-4335-4B26-A2BB-241A0B6DD3DA.pdf";

            //SERVER
            window.location = "/index.php/pdf/pdfload?pdf=" + pdf_filename;

        })
        .catch(error => {
            //  document.getElementById('result').innerHTML = 'Fout: ' + error;
			alert(error);
        });

};

function ajaxPdf_d(factuurnummer) {

	//fetch('/get_factuur_d.php', {
	fetch('http://invoice/dordtonline.nl/proxy_sas_factuur.php', {		
		method: 'POST',
		headers: { 'Content-Type': 'application/json' 
	},
		body: JSON.stringify({ FactuurNummer: factuurnummer })
	})
	.then(response => response.text())
	.then(text => {
		console.log('Rauwe output:', text);
		document.getElementById('result').innerHTML = '<pre>' + text + '</pre>';
		window.location = "/index.php/pdf/pdfload?pdf=" + "pdf_2759_878FC117-4335-4B26-A2BB-241A0B6DD3DA.pdf";
	})
    .catch(error => {
		alert(error);
    });
}

function ajaxPdf_d_zonder_shellexec(factuurnummer_werkt_maar_shellexec_niet) {

	//fetch('/get_factuur_d.php', {
	fetch('/get_test.php', {		
		method: 'POST',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify({ FactuurNummer: factuurnummer })
	})
	.then(response => response.text())
	.then(text => {
		console.log('Rauwe output:', text);
		document.getElementById('result').innerHTML = '<pre>' + text + '</pre>';
		window.location = "/index.php/pdf/pdfload?pdf=" + "pdf_2759_878FC117-4335-4B26-A2BB-241A0B6DD3DA.pdf";
	})
    .catch(error => {
		alert(error);
    });
}

function ajaxPdf_dDEZE(factuurnummer) {

    //fetch('/get_factuur_d.php', {
	fetch('/index.php/pdf/pdftest', {		
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            FactuurNummer: factuurnummer,
        })
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').innerHTML =
                data.pdf_filename ? 'File: <a href="' + data.pdf_filename + '" target="_blank">' + data.pdf_filename + '</a>' :
                    '<pre>' + JSON.stringify(data, null, 2) + '</pre>';

            //je krijgt de data-URL uit de response
            var pdf_filename = data.pdf_filename;
            //alert(pdf_filename);
            alert("https://zzp.coopinfo.nl/index.php/pdf/pdfload?pdf=" + pdf_filename);

            //MAC
            //window.location = "http://localhost:8000/loadpdf?pdf=" + pdf_filename;

            //MAC
            //window.location = "https://zzp.coopinfo.nl/index.php/pdf/pdfload?pdf=" + "pdf_2759_878FC117-4335-4B26-A2BB-241A0B6DD3DA.pdf";

            //SERVER
            window.location = "/index.php/pdf/pdfload?pdf=" + pdf_filename;
			

        })
        .catch(error => {
            //  document.getElementById('result').innerHTML = 'Fout: ' + error;
        });

};

function ajaxPdf_p(factuurnummer) {

    fetch('get_factuur_p.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            FactuurNummer: factuurnummer,
        })
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').innerHTML =
                data.url ? 'URL: <a href="' + data.url + '" target="_blank">' + data.url + '</a>' :
                    '<pre>' + JSON.stringify(data, null, 2) + '</pre>';

            //je krijgt de data-URL uit de response
            var dataUrl = data.url; // bv. "data:application/pdf;base64,JVBERi0xLjQ..."

            var base64 = dataUrl.split(',')[1]; // alles na de komma
            var binary = atob(base64); // decodeer naar binaire string

            // Omzetten naar Uint8Array voor gebruik in Blob of PDF.js:
            var len = binary.length;
            var bytes = new Uint8Array(len);
            for (var i = 0; i < len; i++) {
                bytes[i] = binary.charCodeAt(i);
            }
            // Nu kun je een Blob maken:
            var blob = new Blob([bytes], { type: "application/pdf" });
            var url = URL.createObjectURL(blob);
            //window.open(url, "_blank");
            var popup = window.open(url, '_blank', 'width=800,height=600');

        })
        .catch(error => {
            //   document.getElementById('result').innerHTML = 'Fout: ' + error;
        });


}


function ajaxPdf_px(factuurnummer) {
    fetch('proxy_sas_factuur.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            WerknemerID: werknemerid, // remote veldnaam
            Jaar: jaar // remote veldnaam
        })
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').innerHTML =
                data.url ? 'URL: <a href="' + data.url + '" target="_blank">' + data.url + '</a>' :
                    '<pre>' + JSON.stringify(data, null, 2) + '</pre>';

            // Stel: je krijgt de data-URL uit de response
            //var dataUrl = response.url; // bv. "data:application/pdf;base64,JVBERi0xLjQ..."
            var dataUrl = data.url; // bv. "data:application/pdf;base64,JVBERi0xLjQ..."

            var base64 = dataUrl.split(',')[1]; // alles na de komma
            var binary = atob(base64); // decodeer naar binaire string

            // Omzetten naar Uint8Array voor gebruik in Blob of PDF.js:
            var len = binary.length;
            var bytes = new Uint8Array(len);
            for (var i = 0; i < len; i++) {
                bytes[i] = binary.charCodeAt(i);
            }
            // Nu kun je een Blob maken:
            var blob = new Blob([bytes], { type: "application/pdf" });
            var url = URL.createObjectURL(blob);
            //window.open(url, "_blank");
            var popup = window.open(url, '_blank', 'width=800,height=600');

        })
        .catch(error => {
            document.getElementById('result').innerHTML = 'Fout: ' + error;
        });
}


function ajaxPdfAdz_dx(id_factuurnummer) {
    fetch('proxy_sas_factuurlijst.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            WerknemerID: werknemerid, // remote veldnaam
            Jaar: jaar // remote veldnaam
        })
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').innerHTML =
                data.url ? 'URL: <a href="' + data.url + '" target="_blank">' + data.url + '</a>' :
                    '<pre>' + JSON.stringify(data, null, 2) + '</pre>';

            // Stel: je krijgt de data-URL uit de response
            //var dataUrl = response.url; // bv. "data:application/pdf;base64,JVBERi0xLjQ..."
            var dataUrl = data.url; // bv. "data:application/pdf;base64,JVBERi0xLjQ..."

            var base64 = dataUrl.split(',')[1]; // alles na de komma
            var binary = atob(base64); // decodeer naar binaire string

            // Omzetten naar Uint8Array voor gebruik in Blob of PDF.js:
            var len = binary.length;
            var bytes = new Uint8Array(len);
            for (var i = 0; i < len; i++) {
                bytes[i] = binary.charCodeAt(i);
            }
            // Nu kun je een Blob maken:
            var blob = new Blob([bytes], { type: "application/pdf" });
            var url = URL.createObjectURL(blob);
            //window.open(url, "_blank");
            var popup = window.open(url, '_blank', 'width=800,height=600');

        })
        .catch(error => {
            document.getElementById('result').innerHTML = 'Fout: ' + error;
        });
}


function ajaxPdf_p2(id_pdf) {
    var input_id_pdf = id_pdf;
    $.post("/index.php/pdf/getpdfjson",
        {
            req_pdf_id: input_id_pdf,
            req_adz: 0
        },
        function (resp, resultaat) {
            if (resp.errorr == "succes") {
                window.location = "/index.php/pdf/pdfload?pdf=" + resp.pdf;
            }
            if (resp.errorr == "no-data") {
                alert("geen pdf gevonden, no pdf available.");
            }
        },
        'json'
    );
    return true;
};

function ajaxPdfAdz_p(factuurADZ) {
    var input_factuurADZ = factuurADZ.trim();
    //alert("adz factuur ophalen : " + factuurADZ);
    $.post("/index.php/pdf/getpdfjson",
        {
            req_factuurADZ: input_factuurADZ,
            req_adz: 1
        },
        function (resp, resultaat) {
            if (resp.errorr == "succes") {
                window.location = "/index.php/pdf/pdfload?pdf=" + resp.pdf;
            }
            if (resp.errorr == "no-data") {
                alert("geen pdf gevonden, no pdf available.");
            }
        },
        'json'
    );
    return true;
};


function ajaxPdf_d2(id_pdf) {
    var input_id_pdf = id_pdf;
    $.post("/index.php/pdf/getpdfjson",
        {
            req_pdf_id: input_id_pdf,
            req_adz: 0
        },
        function (resp, resultaat) {
            if (resp.errorr == "succes") {
                window.location = "/index.php/pdf/pdfload?pdf=" + resp.pdf;
            }
            if (resp.errorr == "no-data") {
                alert("geen pdf gevonden, no pdf available.");
            }
        },
        'json'
    );
    return true;
};

function ajaxPdfAdz_d(factuurADZ) {
    var input_factuurADZ = factuurADZ.trim();
    //alert("adz factuur ophalen : " + factuurADZ);
    $.post("/index.php/pdf/getpdfjson",
        {
            req_factuurADZ: input_factuurADZ,
            req_adz: 1
        },
        function (resp, resultaat) {
            if (resp.errorr == "succes") {
                window.location = "/index.php/pdf/pdfload?pdf=" + resp.pdf;
            }
            if (resp.errorr == "no-data") {
                alert("geen pdf gevonden, no pdf available.");
            }
        },
        'json'
    );
    return true;
};

function ajax_pdf_download_d(werknemer_id, jaartal) {
    //alert(werknemer_id + "  " + jaartal);
    $.post("/index.php/pdf/getpdfdownloadjson",
        {
            req_werknemer_id: werknemer_id,
            req_jaartal: jaartal
        },
        function (resp, resultaat) {
            //alert(resp.errorr + "   "  + resp.pdf);
            if (resp.errorr == "succes") {
                window.location = "/index.php/pdf/pdfload?pdf=" + resp.pdf;
            }
            if (resp.errorr == "no-data") {
                alert("geen pdf gevonden, no pdf available.");
            }
        },
        'json'
    );
    return true;
};
