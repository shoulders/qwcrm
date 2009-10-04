<!-- Directions.tpl -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Print Directions</title>{$id} {section name=t loop=$customer_details} 
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key={literal}{$company_details[i].GMAPS_API_KEY}{/literal}"></script>
<script type="text/javascript">

    // Create a directions object and register a map and DIV to hold the
    // resulting computed directions
    
    var map;
    var directionsPanel;
    var directions;{literal}
		$caddress = "{/literal}{$caddress2}{literal}";
		$ccity = "{/literal}{$ccity2}{literal}";
		$czip = "{/literal}{$czip2}{literal}";
		$c2address = "{/literal}{$customer_details[t].CUSTOMER_ADDRESS}{literal}";
		$c2city = "{/literal}{$customer_details[t].CUSTOMER_CITY}{literal}";
		$c2zip = "{/literal}{$customer_details[t].CUSTOMER_ZIP}{literal}";
		
		$directionsfrom = ('from: '+ $caddress +', '+ $ccity +', '+ $czip +' to: '+ $c2address +', '+ $c2city +', '+ $c2zip +'');

{/literal}
    function initialize() {literal}{
      map = new GMap2(document.getElementById("map_canvas"));
      directionsPanel = document.getElementById("route");
      directions = new GDirections(map, directionsPanel);
      directions.load($directionsfrom);
	  map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
    }{/literal}
    

    </script>
  </head>
  <body onload="initialize()" onunload="GUnload()" style="font-family: Arial;border: 0 none;">
    <div id="map_canvas" style="width: 70%; height: 480px; float:left; border: 1px solid black;"></div>
    <div id="route" style="width: 25%; height:480px; float:right; border; 1px solid black;"></div>
    <br/>
  </body>
</html>{/section}