<?php
// getinvoices.php - 3 knoppen met invoervelden en AJAX-functies
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturen AJAX Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .row {
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            min-width: 28px;
            font-weight: bold;
        }
        input[type="text"] {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
            min-width: 120px;
            transition: background 0.2s;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
    <!-- <script src="js/ajaxSasPDF.js"></script> -->
    <script src="/js/ajaxPdf2.js"></script>
    <script src="/js/download_ajax.js"></script>
</head>
<body>
<!--     
    <div class="container">
        <h1>Facturen AJAX Demo</h1>
        <div class="row">
            <button type="button" onclick="factuur1(document.getElementById('factuurnummer1').value)">Factuur 1</button>
            <label for="factuurnummer1">Factuurnummer:</label>
            <input type="text" id="factuurnummer1" name="factuurnummer1" placeholder="Factuurnummer 1" value="3315-25005">

        </div>
        <div class="row">
                        <button type="button" onclick="factuur1()">Factuur 2</button>
            <label for="factuurnummer2">Factuurnummer:</label>
            <input type="text" id="factuurnummer2" name="factuurnummer2" placeholder="Factuurnummer 2" value="202501319">

        </div>
        <div class="row">
            <button type="button" onclick="factuurlijst(document.getElementById('lijst_id').value), document.getElementById('lijst_jaar').value">Factuur lijst</button>
            <label for="lijst_id">ID:</label>
            <input type="text" id="lijst_id" name="lijst_id" placeholder="ID">
            <label for="lijst_jaar" style="margin-left:20px;">Jaar:</label>
            <input type="text" id="lijst_jaar" name="lijst_jaar" placeholder="Jaar">
            
        </div>
    </div> -->
    <div class="container">
        <h1>Facturen Proxy Demo</h1>
        <div class="row">
            <button type="button" onclick="ajaxPdf_d(document.getElementById('factuurnummer').value)">Factuur 1</button>
            <label for="factuurnummer">Factuurnummer:</label>
            <input type="text" id="factuurnummer" name="factuurnummer" placeholder="Factuurnummer L" value="3315-25005">

        </div>
        <div class="row">
            <button type="button" onclick="ajaxPdf_d(document.getElementById('factuurnummer_R').value)">Factuur 2</button>
            <label for="factuurnummer_R">Factuurnummer:</label>
            <input type="text" id="factuurnummer_R" name="factuurnummer_R" placeholder="Factuurnummer R" value="202501319">
        </div>
        <div class="row">
            <button type="button" onclick="factuurlijst(document.getElementById('lijst_id').value), document.getElementById('lijst_jaar').value">Factuur lijst</button>
            <label for="lijst_id">ID:</label>
            <input type="text" id="lijst_id" name="lijst_id" placeholder="ID">
            <label for="lijst_jaar" style="margin-left:20px;">Jaar:</label>
            <input type="text" id="lijst_jaar" name="lijst_jaar" placeholder="Jaar">     
        </div>
        <div class="row">
            <button type="button" onclick="AjaxPdfDownload(document.getElementById('nummer').value, document.getElementById('werknemer_id').value, )">Factuur + WerknemerID ###</button>
            <label for="werknemer_id">Werknemer ID:</label>
            <input type="text" id="werknemer_id" name="werknemer_id" placeholder="ID" value="2315">
            <label for="nummer">Factuurnummer:</label>
            <input type="text" id="nummer" name="nummer" placeholder="Factuurnummer + WerknemerID PFF lokaal" value="3315-25005">
        </div>
    </div>

        <div id="result"></div>

</body>
</html>
