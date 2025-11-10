<?php
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Database Test</title>
    <style>
        body { 
            font-family: sans-serif; 
            padding: 40px; 
            background-color: #f0f2f5; 
        }
        .container { 
            background-color: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h1 { 
            color: #1c1e21; 
            margin-bottom: 20px;
        }
        .success { 
            color: #2e7d32; 
            background: #e8f5e9; 
            padding: 15px; 
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error { 
            color: #c62828; 
            background: #ffebee; 
            padding: 15px; 
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Database Test - Users Tabel</h1>
        
        <?php if ($error): ?>
            <div class="error">
                <strong>❌ Database Fout:</strong><br>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php else: ?>
            <div class="success">
                <strong>✅ Database Connectie Succesvol!</strong><br>
                Gevonden: <?php echo count($results); ?> gebruiker(s)
            </div>
            
            <?php if (count($results) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Naam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row->naam); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Geen gebruikers gevonden in de tabel.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>

