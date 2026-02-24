<?php
$statsFile = 'data_stats.json';

// Načtení stávajících dat nebo vytvoření nových
if (file_exists($statsFile)) {
    $data = json_decode(file_get_contents($statsFile), true);
} else {
    $data = [
        'visits' => ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0, 'last_reset' => date('Y-m-d')],
        'orders' => ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0]
    ];
}

// Kontrola resetování (pokud je nový den, týden atd.)
$today = date('Y-m-d');
if ($data['visits']['last_reset'] !== $today) {
    $data['visits']['day'] = 0;
    $data['orders']['day'] = 0;
    // Tady by mohla být složitější logika pro týden/měsíc, 
    // pro začátek budeme jen přičítat a resetovat den.
    $data['visits']['last_reset'] = $today;
}

// Pokud jde o návštěvu (metoda GET), přičti +1
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['action'])) {
    $data['visits']['day']++;
    $data['visits']['week']++;
    $data['visits']['month']++;
    $data['visits']['year']++;
}

// Pokud jde o odeslání formuláře (přes akci v JS)
if (isset($_GET['action']) && $_GET['action'] === 'addOrder') {
    $data['orders']['day']++;
    $data['orders']['week']++;
    $data['orders']['month']++;
    $data['orders']['year']++;
}

file_put_contents($statsFile, json_encode($data));

// Vrátíme data pro JavaScript
header('Content-Type: application/json');
echo json_encode($data);
?>
