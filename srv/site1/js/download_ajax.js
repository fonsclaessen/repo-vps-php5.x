function AjaxPdfDownload(factuurnummer, werknemer_id, jaar) {
    var filename = "Factuur" + " " + factuurnummer + ".pdf";
	window.location = '/index.php/pdf/pdfloadnew?pdf=' + encodeURIComponent(filename) + "&werknemer_id=" + encodeURIComponent(werknemer_id) + "&jaar=" + encodeURIComponent(jaar);	 
    return true;
}

function AjaxPdfDownloadLijst(werknemer_id, jaar) {
    var filename = "Facturen" + " " + jaar + ".pdf";
	window.location = '/index.php/pdf/pdfloadnewlijst?pdf=' + encodeURIComponent(filename) + "&werknemer_id=" + encodeURIComponent(werknemer_id) + "&jaar=" + encodeURIComponent(jaar);	 ;	 
    return true;
}
