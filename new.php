<?php
require_once '_db.php';

$rooms = $db->query('SELECT * FROM rooms');

// Перевірка та форматування дат із GET
$start = isset($_GET['START']) ? $_GET['START'] : '';
$end = isset($_GET['END']) ? $_GET['END'] : '';

if ($start) {
    $start = date('Y-m-d H:i:s', strtotime($start));
}
if ($end) {
    $end = date('Y-m-d H:i:s', strtotime($end));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Reservation</title>
    <link type="text/css" rel="stylesheet" href="media/layout.css" />    
    <script src="js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
</head>
<body>
    <form id="f" action="backend_create.php" method="post" style="padding:20px;">
        <h1>New Reservation</h1>
        <div>Name: </div>
        <div><input type="text" id="name" name="NAME" value="" /></div>
        <div>Start:</div>
        <div><input type="text" id="start" name="START" value="<?php echo htmlspecialchars($start); ?>" /></div>
        <div>End:</div>
        <div><input type="text" id="end" name="END" value="<?php echo htmlspecialchars($end); ?>" /></div>
        <div>Room:</div>
        <div>
            <select id="room" name="room">
                <?php 
                    foreach ($rooms as $room) {
                        $selected = (isset($_GET['resource']) && $_GET['resource'] == $room['id']) ? ' selected="selected"' : '';
                        $id = $room['id'];
                        $name = $room['NAME'];
                        echo "<option value='$id' $selected>$name</option>";
                    }
                ?>
            </select>
        </div>
        <div class="space">
            <input type="submit" value="Save" />
            <a href="javascript:close();">Cancel</a>
        </div>
    </form>
</body>
</html>
