<?php
    // called by ajax

    header('Content-type: application/json');


    if (isset($_POST['img'])) {
        $img = $_POST['img'];
        $memberId = $_POST['memberId'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $data = base64_decode($img);

        $path = '../alster/';
        date_default_timezone_set('Europe/Berlin');
        $file = $memberId .'_'. date("YmdHis") . rand(1000, 9999);
        $ext = '.png';
        
        $pathAndFile = $path . $file . $ext;
        $success = file_put_contents($pathAndFile, $data);
    }

    if ($success) {

        $handle = fopen($pathAndFile, 'wb');
        fwrite($handle, $data);
        fclose($handle);

        // Save filename in DB
      //  $saveFileName = new InsertCreationInDB();
      //  $id = $saveFileName -> saveToDB($file.'.jpg', $memberId); // $id = inserted it in db

       // echo json_encode($id); // ajax callback
    }

?>