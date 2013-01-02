<?php session_start();
    // called by ajax
    header('Content-type: application/json');

    require_once('Database.php');

    if (isset($_POST['img'])) {
        $img = $_POST['img'];
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $memberId = $_SESSION['activeUserId'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $data = base64_decode($img);

        $path = '../alster/';
        date_default_timezone_set('Europe/Berlin');
        $file = $memberId .'_'. date("YmdHis") . rand(100, 999);
        $ext = '.png';
        
        $pathAndFile = $path . $file . $ext;
        $success = file_put_contents($pathAndFile, $data);
    }

    if ($success) {

        $handle = fopen($pathAndFile, 'wb');
        fwrite($handle, $data);
        fclose($handle);
       
        // database
        $db = new Database();
        $db->createDatabase();

        $query = "INSERT INTO Alster (externalUserId, alsterUrl, lat, lng)
                      VALUES (:externalUserId, :alsterUrl, :lat, :lng)";

        $param = array( ':externalUserId' => $memberId, 
                        ':alsterUrl'      => $file.$ext,
                        ':lat'            => $lat,
                        ':lng'            => $lng);

        $ret = $db->insert($query, $param);
        $lastInsertId = $db->lastInsertId();

        echo json_encode($lastInsertId); // ajax callback
    }

?>