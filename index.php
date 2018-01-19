<?php
$locations=array();
$uname="root";
$pass="";
$servername="127.0.0.1";
$dbname="map";
$db=new mysqli($servername,$uname,$pass,$dbname);
$query =  $db->query("SELECT * FROM markers");
/*$number_of_rows = mysqli_num_rows($db);
echo $number_of_rows;*/
while( $row = $query->fetch_assoc() ){
    $name = $row['name'];
    $longitude = $row['lng'];
    $latitude = $row['lat'];
    $link=$row['type'];
    /* Each row is added as a new array */
    $locations[]=array( 'name'=>$name, 'lat'=>$latitude, 'lng'=>$longitude, 'lnk'=>$link );
}
//echo $locations[0]['name'].": In stock: ".$locations[0]['lat'].", sold: ".$locations[0]['lng'].".<br>";
//echo $locations[1]['name'].": In stock: ".$locations[1]['lat'].", sold: ".$locations[1]['lng'].".<br>";
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCPHailpg6U0hbtgXHkUS0L7nlsdP5H-kM"></script>
<script type="text/javascript">
    var map;
    var Markers = {};
    var infowindow;
    var locations = [
        <?php for($i=0;$i<sizeof($locations);$i++){ $j=$i+1;?>
        [
            'AMC Service',
            '<p><?php echo $locations[$i]['lnk']?></p>',
            <?php echo $locations[$i]['lat'];?>,
            <?php echo $locations[$i]['lng'];?>,
            0
        ]<?php if($j!=sizeof($locations))echo ","; }?>
    ];
    function initialize() {
        var mapOptions = {
            zoom: 6,
            center: new google.maps.LatLng(29.3631412, 53.1688696),
        };
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        infowindow = new google.maps.InfoWindow();
        for(i=0; i<locations.length; i++) {
            var position = new google.maps.LatLng(locations[i][2], locations[i][3]);
            var marker = new google.maps.Marker({
                position: position,
                map: map,
            });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][1]);
                    infowindow.setOptions({maxWidth: 200});
                    infowindow.open(map, marker);
                }
            }) (marker, i));
            Markers[locations[i][4]] = marker;
        }
        locate(0);
    }
    function locate(marker_id) {
        var myMarker = Markers[marker_id];
        var markerPosition = myMarker.getPosition();
        map.setCenter(markerPosition);
        google.maps.event.trigger(myMarker, 'click');
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<body id="map-canvas">
</body>