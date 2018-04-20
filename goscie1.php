<html lang="pl">
<head>
<meta http-equiv="Refresh" content="100" />
<title>Zdunowski</title>
</head>


<body>

<?php 
        
        //25509958_lab1    
        $dbhost="lukasz-zdunowski.com.pl"; 
        $dbuser="25509958_lab1"; 
        $dbpassword="zaq12wsx";  
        $dbname="25509958_lab1";
        $polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
        mysqli_select_db($polaczenie, $dbname);
        
        $rezultat = mysqli_query ($polaczenie, "SELECT * FROM goscieportalu");
        print "<TABLE CELLPADDING=5 BORDER=1>";
            print "<TR><TD>idt</TD><TD>Adres</TD><TD>Data</TD><TD>City</TD><TD>Country</TD><TD>Region</TD><TD>Localization</TD><TD>Link to Google</TD></TR>\n";
 	
                    
        $ipaddress = $_SERVER["REMOTE_ADDR"];
        function ip_details($ip) {
        $json = file_get_contents ("http://ipinfo.io/{$ip}/geo");
        $details = json_decode ($json);
        return $details;
        }
        $details = ip_details($ipaddress);
        $region = $details -> region;
        $country =  $details -> country;
        $city = $details -> city; 
        $loc = $details -> loc;
        $ip = $details -> ip; 
        
        //$insert_bd = mysqli_query($polaczenie, "INSERT INTO goscieportalu" );   
       
		while ($wiersz = mysqli_fetch_array ($rezultat))
	   {
        
         
             $czas2 = date("F j, Y, g:i a"); 
               
            $idt1 = $wiersz ['id'];
            $adres1 = $wiersz['adres'];
            $czass1 = $wiersz['datee']; //$wiersz['datee'];
            $region1 = $wiersz['region'];
            $country1 = $wiersz['country'];
            $city1 = $wiersz['city'];
            $loc1 = $wiersz['loc'];
			$gMaps = $linkToMaps;

            

       	    $locY = substr($loc, 8);
            $locX = substr($loc, 0, -8);
            $wwwMaps = "https://www.google.pl/maps/@";
            $gMaps = $wwwMaps.$loc;
            $linkToMaps =  "<a href=".$gMaps.">Go to Google Maps</a>";


   			$attempts1 = mysqli_query($polaczenie,  "SELECT adres FROM goscieportalu");
   			 if ($row = $attempts1->fetch_assoc()) {                                                                     // 
                     $attempts= $row['adres']."<br>";
                    } 

            if($attempts==$ip){
            	echo "takie adres jest juz w bazie";


            }   else{
            	echo "nowy adres do bazy";
            	                     echo $attempts;

            }     


            $adres_bd = mysqli_query($polaczenie,  "UPDATE goscieportalu SET adres=('$ip') WHERE id='$idt'");
            $czas_bd = mysqli_query($polaczenie,  "UPDATE  goscieportalu SET datee=('$czas2') WHERE id=('$idt')"); 
            $city_bd = mysqli_query($polaczenie,  "UPDATE  goscieportalu SET city=('$city') WHERE id=('$idt')"); 
            $country_bd = mysqli_query($polaczenie,  "UPDATE  goscieportalu SET country=('$country') WHERE id=('$idt')");
            $region_bd = mysqli_query($polaczenie,  "UPDATE  goscieportalu SET region=('$region') WHERE id=('$idt')"); 
            $loc_bd = mysqli_query($polaczenie,  "UPDATE  goscieportalu SET loc=('$loc') WHERE id=('$idt')");
            $link_bd = mysqli_query($polaczenie,  "UPDATE  goscieportalu SET link=('$linkToMaps') WHERE id=('$idt')");

           
            $insert_bd = mysqli_query($polaczenie, "INSERT INTO goscieportalu (`id`, `adres`, `datee`, `city`, `country`, `region`, `loc`) VALUES ('idt' ,'$adres', '$czas','$city', '$country', '$region', '$loc')");   

/*
            $sql2 = "SELECT * FROM goscieportalu GROUP BY adres"; //wyciąganie danych z bazy
            $result = $polaczenie->query($sql2); 

            $sql = "INSERT INTO goscieportalu (adres,datee,city,country,region,loc) VALUES ('$ip', '$czas2','$city', '$country', '$region', '$loc')"; // wprowadzanie anych do bazy
             $polaczenie->query($sql);*/
              
       /*     $query =  "SELECT adres FROM goscieportalu WHERE adres='$adres' ";
                
                 if ($result=mysqli_query($polaczenie,$query))
                  {
                   if(mysqli_num_rows($result) > 0)
                    {
                       echo "exist";
                    }
                  else
                      echo "Doesn't exist";
                 }*/
               

            print "<TR><TD>$idt1</TD><TD>$adres1</TD><TD>$czass1</TD><TD>$region1</TD><TD>$country1</TD><TD>$city1</TD><TD>$loc1</TD><TD>$linkToMaps</TD></TR>\n";
            
       
            }
        print "</TABLE>";

?>
</body>
</html>