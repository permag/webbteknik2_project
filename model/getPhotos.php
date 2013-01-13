 <?php
    $tag = $_GET['tag'];
    $output = '';
    //skapa en array med alla parametrarna
    $params = array(
        'api_key'   => '485269b267d9e8a9e5210a774d6ee337',
        'method'    => 'flickr.photos.search',
        'tags'      => $tag,
        'per_page'  => 150,
        'format'    => 'php_serial'
    );
    // Bygga urlen
    $encoded_params = array();
    foreach ($params as $k => $v){
            $encoded_params[] = urlencode($k).'='.urlencode($v);
            }
    $url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
    
    $rsp = file_get_contents($url);
    $rsp_obj = unserialize($rsp);
    
    // Om stat == ok, visa resultatet
    if ($rsp_obj['stat'] == 'ok') {
        // Loopar igenom hela arrayen med foton.
        foreach ($rsp_obj['photos']['photo'] as $photo)
        {
            $farmid = $photo['farm'];
            $serverid = $photo['server'];
            $photoid = $photo['id'];
            $secret = $photo['secret'];
            
             // Skapar URL f?r bilden genom informationen som ges pÎ http://www.flickr.com/services/api/misc.urls.html
            $photourl = "http://farm" . $farmid . ".static.flickr.com/" . $serverid . "/" . $photoid ."_" . $secret . "_t.jpg";
            $output .= "<img class=\"photoSrc\" src='$photourl' />";
        }
    } else {
        $output .= "<div>No photos found.</div>";
    }

    echo $output;
 ?>