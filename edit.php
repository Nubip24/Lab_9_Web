<?php
require_once '_db.php';

// Перевірка id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Error: Missing or invalid reservation ID');
}

$stmt = $db->prepare("SELECT * FROM reservations WHERE id = :id");
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();

$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    die('Error: Reservation not found');
}

$rooms = $db->query("SELECT * FROM rooms");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Reservation</title>
</head>
<body>
    <form id="f" action="backend_update.php" method="post" style="padding:20px;">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>" />
        
        <div>Name:</div>
        <div><input type="text" name="name" value="<?php echo htmlspecialchars($reservation['NAME']); ?>" /></div>
        
        <div>Start:</div>
        <div><input type="text" name="start" value="<?php echo htmlspecialchars($reservation['START']); ?>" /></div>
        
        <div>End:</div>
        <div><input type="text" name="end" value="<?php echo htmlspecialchars($reservation['END']); ?>" /></div>
        
        <div>Room:</div>
        <div>
            <select name="room">
                <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['id'] ?>" <?= $room['id'] == $reservation['room_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($room['NAME']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>Status:</div>
        <div>
            <select name="status">
                <?php
                    $statuses = ["New", "Confirmed", "Arrived", "CheckedOut"];
                    foreach ($statuses as $status) {
                        $selected = ($reservation['STATUS'] == $status) ? 'selected' : '';
                        echo "<option value='$status' $selected>$status</option>";
                    }
                ?>
            </select>
        </div>
        
        <div>Paid:</div>
        <div>
            <select name="paid">
                <?php
                    $paidOptions = [0, 50, 100];
                    foreach ($paidOptions as $option) {
                        $selected = ($reservation['paid'] == $option) ? 'selected' : '';
                        echo "<option value='$option'>{$option}%</option>";
                    }
                ?>
            </select>
        </div>
        
        <div class="space">
            <input type="submit" value="Save Changes" /> <a href="javascript:close();">Cancel</a>
        </div>
    </form>
</body>
</html>
