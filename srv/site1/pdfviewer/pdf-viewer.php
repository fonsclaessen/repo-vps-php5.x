<?php
// filepath: /srv/zzp/site1/pdf-viewer.php
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Werknemer Viewer</title>
    
    <!-- ← FIX: Allow iframe from same origin -->
    <meta http-equiv="Content-Security-Policy" content="frame-src 'self' data: blob:;">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column; /* Zorgt dat de containers onder elkaar komen */
            gap: 30px; /* Voegt ruimte toe tussen de containers */
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }
        
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .status {
            margin-top: 25px;
            padding: 15px;
            border-radius: 8px;
            font-weight: 500;
            display: none;
        }
        
        .status.loading {
            background: #e3f2fd;
            color: #1976d2;
            border: 1px solid #bbdefb;
        }
        
        .status.error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        
        .status.success {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        
        /* PDF Modal */
        .pdf-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .pdf-modal-content {
            position: absolute;
            top: 5%;
            left: 5%;
            width: 90%;
            height: 90%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        
        .pdf-modal-header {
            background: #f5f5f5;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .pdf-modal-title {
            font-weight: 600;
            color: #333;
        }
        
        .pdf-modal-close {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .pdf-modal-close:hover {
            background: #ff3838;
        }
        
        .pdf-frame {
            width: 100%;
            height: calc(100% - 60px);
            border: none;
        }
        
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📄 PDF Werknemer</h1>
        
        <form id="pdfForm">
            <div class="form-group">
                <label for="werknemerID">Werknemer ID:</label>
                <input type="number" id="werknemerID" name="werknemerID" value="129" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="jaar">Jaar:</label>
                <input type="number" id="jaar" name="jaar" value="2025" min="2020" max="2030" required>
            </div>
            
            <button type="submit" id="submitBtn">
                <span id="buttonText">PDF Ophalen</span>
            </button>
        </form>
        
        <div class="status" id="status"></div>
    </div>

<!--
    <div class="container">
        <h1>📄 PDF Factuur overzicht</h1>
        <form id="pdfFormWN">
            <div class="form-group">
                <label for="factuurNummer">FactuurNummer:</label>
                <input type="string" id="factuurNummer" name="factuurNummer" value="3910-25005" min="1" required>
            </div>

            <button type="submit" id="submitBtnWN">
                <span id="buttonText">PDF Ophalen</span>
            </button>
        </form>
        <div class="status" id="status"></div>
    </div>
-->

    <!-- PDF Modal -->
    <div class="pdf-modal" id="pdfModal">
        <div class="pdf-modal-content">
            <div class="pdf-modal-header">
                <div class="pdf-modal-title" id="pdfTitle">PDF Viewer</div>
                <button class="pdf-modal-close" id="closeModal">Sluiten</button>
            </div>
            <iframe class="pdf-frame" id="pdfFrame"></iframe>
        </div>
    </div>

    <script>
        const form = document.getElementById('pdfForm');
        const submitBtn = document.getElementById('submitBtn');
        const buttonText = document.getElementById('buttonText');
        const status = document.getElementById('status');
        const pdfModal = document.getElementById('pdfModal');
        const pdfFrame = document.getElementById('pdfFrame');
        const pdfTitle = document.getElementById('pdfTitle');
        const closeModal = document.getElementById('closeModal');
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const werknemerID = parseInt(document.getElementById('werknemerID').value);
            const jaar = parseInt(document.getElementById('jaar').value);
            
            // UI Reset
            submitBtn.disabled = true;
            buttonText.innerHTML = '<span class="spinner"></span>Genereren...';
            showStatus('loading', '⏳ JWT wordt gegenereerd en PDF opgehaald...');
            
            try {
                console.log('Calling API:', {WerknemerID: werknemerID, Jaar: jaar});
                
                // ← Via Apache proxy naar Debian API
                const response = await fetch('/api/pdf-download-api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        WerknemerID: werknemerID,
                        Jaar: jaar
                    })
                });
                
                console.log('Response status:', response.status);
                
                const data = await response.json();
                console.log('Response data:', data);
                
                if (!response.ok || !data.success) {
                    throw new Error(data.error || `HTTP ${response.status}`);
                }
                
                // Success - toon PDF
                showStatus('success', `✅ PDF gegenereerd! (${Math.round(data.size/1024)} KB)`);
                
                pdfTitle.textContent = `Werknemer ${werknemerID} - ${jaar}`;
                
                // ← FIX: Converteer Docker IP naar Apache proxy URL
                // API returnt: "http://127.0.0.1:8888/view-pdf.php?file=werknemer_21_2025_xxx.pdf"
                // We willen:    "/api/view-pdf.php?file=werknemer_21_2025_xxx.pdf"
                
                let pdfUrl = data.url.replace('http://127.0.0.1:8888/', '/api/');
                
                // ← DEBUG: Log URLs voor troubleshooting
                console.log('Original API URL:', data.url);
                console.log('Converted Proxy URL:', pdfUrl);
                
                // Zet PDF in iframe
                pdfFrame.src = pdfUrl;
                pdfModal.style.display = 'block';
                
                // ← FIX: Add iframe load handlers voor debugging
                pdfFrame.onload = function() {
                    console.log('✅ PDF loaded successfully in iframe');
                };
                
                pdfFrame.onerror = function() {
                    console.error('❌ PDF failed to load in iframe');
                    showStatus('error', '❌ PDF laden mislukt - probeer directe link');
                    
                    // Fallback: open PDF in new tab
                    window.open(pdfUrl, '_blank');
                };
                
            } catch (error) {
                console.error('Error:', error);
                showStatus('error', `❌ Fout: ${error.message}`);
            } finally {
                submitBtn.disabled = false;
                buttonText.textContent = 'PDF Ophalen';
            }
        });
        
        // Modal sluiten
        closeModal.addEventListener('click', () => {
            pdfModal.style.display = 'none';
            pdfFrame.src = 'about:blank';
        });
        
        // Klik buiten modal = sluiten
        pdfModal.addEventListener('click', (e) => {
            if (e.target === pdfModal) {
                pdfModal.style.display = 'none';
                pdfFrame.src = 'about:blank';
            }
        });
        
        // ESC key = sluiten
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && pdfModal.style.display === 'block') {
                pdfModal.style.display = 'none';
                pdfFrame.src = 'about:blank';
            }
        });
        
        function showStatus(type, message) {
            status.className = `status ${type}`;
            status.textContent = message;
            status.style.display = 'block';
            
            // Auto-hide success/error na 5 seconden
            if (type !== 'loading') {
                setTimeout(() => {
                    status.style.display = 'none';
                }, 5000);
            }
        }
    </script>
</body>
</html>
