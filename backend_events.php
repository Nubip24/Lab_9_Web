<?php
require_once '_db.php';

if (!isset($_GET['start']) || !isset($_GET['end'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Відсутні параметри start або end']);
    exit;
}

$start_date = $_GET['start'];
$end_date = $_GET['end'];

$start = $start_date . ' 00:00:00';
$end = $end_date . ' 23:59:59';

try {
    $stmt = $db->prepare("SELECT * FROM reservations WHERE NOT ((`END` <= :start) OR (`START` >= :end))");
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':end', $end);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = [];

    foreach ($result as $row) {
        $events[] = [
            'id' => $row['id'],              // маленькими
            'text' => $row['NAME'],          // великими
            'start' => $row['START'],        // великими
            'end' => $row['END'],            // великими
            'resource' => $row['room_id'],   // маленькими
            'bubbleHtml' => "Reservation details: " . $row['NAME'],
            'status' => $row['STATUS'],      // великими
            'paid' => $row['paid'],          // маленькими
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($events);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Помилка запиту: ' . $e->getMessage()]);
}
