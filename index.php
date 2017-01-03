<?php
  session_start();
  if (!isset($_SESSION["zipcode"])) { $_SESSION["zipcode"] = 'usa'; }
?>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>

  <style media="screen">
  #entry {
    margin: 2em 1em;
  }

  #location {
    margin: 1em;
  }
  </style>

</head>
<body>

  <h3>The previous zipcode saved in session using PHP was <?php echo $_SESSION["zipcode"]; ?></h3>

  <div id="entry">
    Zip Code: <input id="zipcode" type="text" name="zipcode" value="">
    <button id="ajax-button" type="button">Submit</button>
  </div>

  <div id="location">

  </div>

  <iframe
    id="map"
    width="600"
    height="450"
    frameborder="0" style="border:0"
    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDYMzEKDNUBtn2MNgEgAPm5TTL9QXhk4fQ&q=usa" allowfullscreen>
  </iframe>

  <script>

  var api = 'http://maps.googleapis.com/maps/api/geocode/json'

  // FIND LOCATION BY ZIPCODE USING GOOGLE API
  // (GET REQUEST)
  function findLocation () {
    var zipcode = document.getElementById('zipcode')
    var url = api + '?address=' + zipcode.value;

    // 1. Create XMLHttp Object
    var xhr = new XMLHttpRequest();

    // 2. Open a request
    xhr.open('GET', url, true);

    // 3. Set up onreadystatechange callback function
    xhr.onreadystatechange = function () {
      if (xhr.readyState < 4) {
        showLoading();
      }

      if (xhr.readyState == 4 && xhr.status == 200) {
        outputLocation(xhr.responseText);
        setLocation(xhr.responseText);
      }
    }

    // 4. Call send to trigger the request
    xhr.send();
  }

  // SHOW 'Loading...' TEXT WHILE FINDING RESULTS
  function showLoading () {
    var target = document.getElementById('location');
    target.innerHTML = "Loading...";
  }

  // DISPLAY LOCATION AND CHANGE MAP ZIPCODE
  function outputLocation(data) {
    var iframe = document.getElementById('map')
    var target = document.getElementById('location');
    var json = JSON.parse(data);
    var address = json.results[0].formatted_address;
    var zipcode = json.results[0].address_components[0].long_name
    target.innerHTML = address;
    iframe.src = "https://www.google.com/maps/embed/v1/place?key=AIzaSyDYMzEKDNUBtn2MNgEgAPm5TTL9QXhk4fQ&q=" + zipcode
  }

  // SET LOCATION IN SESSION (POST REQUEST)
  function setLocation(data) {

    var json = JSON.parse(data);
    var zipcode = json.results[0].address_components[0].long_name
    console.log(zipcode);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'location.php', true); // POST REQUEST
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded'); // Set Header Content-type
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        console.log('Result: ' + result);
      };
    };
    xhr.send("zipcode=" + zipcode); // send zipcode
  }

  var button = document.getElementById('ajax-button');
  button.addEventListener("click", findLocation)

  </script>


</body>
</html>
