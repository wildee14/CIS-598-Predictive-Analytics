<?php
$url =  "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$arr =  explode("=",$url);

if (count($arr) == 1){echo "done"; exit;}

	$uri = 'https://api.instagram.com/oauth/access_token'; 
	$data = [
		'client_id' => 'bbf9d5179ff24ceea0d41e9bf7b6651b', 
		'client_secret' => 'f706a360452f416ab5ad7995d9aa41d0', 
		'grant_type' => 'authorization_code', 
		'redirect_uri' => 'http://138.197.20.231/index.php', 
		'code' => $arr[1]
	];

$apiHost = 'https://api.instagram.com/oauth/access_token';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiHost);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$jsonData = curl_exec($ch);
curl_close($ch);

//var_dump($jsonData);
$user = @json_decode($jsonData,true); 
echo '<h1 class="text-center">';
//echo print_r($user).'<br>';
echo 'Welcome, <code>'.$user['user']['username'].'</code></h1>';
$img = $user['user']['profile_picture'];
//echo '<img class="img-responsive img-rounded center-block" src="'.$img.'"/><br><hr><br>';
$access_token = $user['access_token'];
echo $access_token;
$name = $user['user']['username'];
$servername = "localhost";
<<<<<<< HEAD
$username = "xxxxxxx";
$password = "xxxxxxx";
=======
$username = "xxxxxx";
$password = "xxxxxx";
>>>>>>> f172133904cf180d3c7c59aba2feb12ebd5b8390
$database = "instagram";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$database);
// Check connection
if (!$conn) {
    echo die("Connection failed: " . mysqli_connect_error());
}

$sql4 = "SELECT count(*) as count FROM `tweets` WHERE user='".$user['user']['username']."'";
$res = mysqli_query($conn, $sql4);
while($row2 = mysqli_fetch_array($res)){
echo '<h3 class="text-center text-alert"><kbd>Number of Posts: '.$row2["count"].'</kbd></h3><img class="img-responsive img-rounded center-block" src="'.$img.'"/>';
}

$retun = rudr_instagram_api_curl_connect('https://api.instagram.com/v1/users/self/media/liked?access_token=' . $access_token);
$dat = $retun['data'];
$lat = 123;
$long = 234;
foreach ($dat as $post ) {
        echo '<h3 class="text-center bg-success">Location: '.($dat['0']['location']['name']).'</h3>';
	$loc_id = ($dat['0']['location']['id']);
	$lat = $dat['0']['location']['latitude'];
        $long = $dat['0']['location']['longitude'];
}
echo'
<div class="col-md-6"" id="map"></div>
<script>
      function initMap() {
        var uluru = {lat: '.$lat. ', lng: '.$long.'};
        var map = new google.maps.Map(document.getElementById(\'map\'), {
          zoom: 10,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-2ss-BTJYlrBobo6cvXUY4Hzr5AW2cyQ&callback=initMap">
    </script>';

echo '<br><hr><br>';

//reference https://rudrastyh.com/php/instagram-api-recent-photos.html
function rudr_instagram_api_curl_connect( $api_url ){
	$connection_c = curl_init(); // initializing
	curl_setopt( $connection_c, CURLOPT_URL, $api_url ); // API URL to connect
	curl_setopt( $connection_c, CURLOPT_RETURNTRANSFER, 1 ); // return the result, do not print
	curl_setopt( $connection_c, CURLOPT_TIMEOUT, 20 );
	$json_return = curl_exec( $connection_c ); // connect and get json data
	curl_close( $connection_c ); // close connection
	return json_decode( $json_return ,true); // decode and return
}
$return = rudr_instagram_api_curl_connect('https://api.instagram.com/v1/users/self/media/recent?access_token=' . $access_token);
$data = $return['data'];


foreach ($data as $post ) {
        $caption = mysqli_real_escape_string($conn,$post['caption']['text']);
        $userid = $post['user']['id'];
	$username =mysqli_real_escape_string($conn, $post['user']['username']);
        $image = mysqli_real_escape_string($conn, $post['images']['low_resolution']['url']);
        $tags = $post['tags'];
        $likes = $post['likes']['count'];
        $time = $post['created_time'];
        $ta = '';
        if(empty($tags))$ta = "";
        else {foreach ( $tags as $tag ) {
                $ta.= ' '.$tag;
                }
        }

        //echo($caption. $userid.' '.$username.' '.substr($image,-15)." likes: ".$likes.' Tags: '. $ta.' Time: '.$time.'<br>');
$sql2 = "INSERT INTO `tweets` (`img_src`,`caption`,`userid`,`user`,`tags`,`likes`,`time`)
        VALUES ('$image','$caption','$userid','$username','$ta','$likes','$time')";

if (mysqli_query($conn, $sql2)) {
 //       printf("%d Row inserted.<br>", mysqli_affected_rows($conn));
} else {
 //      echo "Error: " . $sql2 . mysqli_error($conn).'<br>';
}

}
$sql3 = "select * from tweets where user ='". $name."' order by likes desc";
//echo $sql3;
$result = mysqli_query($conn, $sql3);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    echo "<table class=\"table-responsive table table-condensed table-hover table-bordered \">
          <tr><th>Image</th><th>Caption</th> <th>user</th> <th>Tags</th> <th>likes</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr class=\"info\"><th>" ."<img src= \"".$row["img_src"]."\"></th><th>".$row["caption"]."</th><th>".
             $row["user"]. "</th><th>" . $row["tags"]. "</th><th>" . $row["likes"]. "</th></tr>";
    }
    echo "</table>";
} else {
    echo "<h2 class = \"alert alert-danger\">0 Results Returned</h2>";
}

mysqli_close($conn);

exit;
?>
