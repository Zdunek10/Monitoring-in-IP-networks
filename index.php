<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"pl-PL\">
<head>
    <meta http-equiv="refresh" content="10" /> 
  
    <title>Zdunowski</title>
    
 
</script>

</head>

<body>
    <h1> Monitorowanie usług w sieciach IP </h1>
    
<?php

    function time_difference($time1, $time2) { //różnica miedzy czasem aktualnego polaczenia a ostatniego
        $time1 = strtotime("1/1/1980 $time1"); 
        $time2 = strtotime("1/1/1980 $time2"); 
        if ($time2 < $time1) 
     { 
        $time2 = $time2 + 86400; 
     } 
        $asd = (($time2 - $time1));
        
       $hours = floor($asd / 3600);
       $mins = floor($asd / 60 % 60);
       $secs = floor($asd % 60);
        
       return $hours.':'.$mins.':'.$secs;
}     
        //polaczenie z baza danych 
        $dbhost="lukasz-zdunowski.com.pl"; 
        $dbuser="25509958_lab1"; 
        $dbpassword="zaq12wsx"; 
        $dbname="25509958_lab1";
        $polaczenie = mysqli_connect ($dbhost, $dbuser, $dbpassword);
        mysqli_select_db ($polaczenie, $dbname);
        
        $rezultat = mysqli_query ($polaczenie, "SELECT * FROM domeny");
        print "<TABLE CELLPADDING=5 BORDER=1>";
        print "<TR><TD>ID</TD><TD>Name</TD><TD>Status</TD><TD>Attempts</TD><TD>Last Connected</TD><TD>Last Attempt to Connect</TD><TD>How long is Down [h:m:s]</TD></TR>\n";
        
               
        while ($wiersz = mysqli_fetch_array ($rezultat))
            { 
            $localTime = date('G:i:s');                                                                                 //utowrzenie lokalnego czasu z formatem 24godzinnym
            //$localTime =date("F j, Y, G:i a");
        
            $idt = $wiersz [0];
            $nazwa = $wiersz [1];
            $status = $wiersz [2];
            $attempts = $wierz[3];
            $lastConn = $wiersz[4];
            $curConn = $localTime;
            $dif = $wiersz[6];
      
             $host = $nazwa;
             $port = '80';
             $fp = @fsockopen($host, $port, $errno, $errstr, 30);
          
            if ($fp) { 
                $status = 'OK'; 
                $dif = '-';
                $try = 0;
                $attempts1 = mysqli_query($polaczenie,  "UPDATE domeny SET attempts=('$try') WHERE adres='$nazwa'");        //aktualizacja do bazy danych wartosci '0'
                $attempts1 = mysqli_query($polaczenie,  "SELECT attempts FROM domeny WHERE adres=('$nazwa')");              //pobranie wartosci z bazy
                
                if ($row = $attempts1->fetch_assoc()) {                                                                     // 
                     $attempts= $row['attempts']."<br>";
                    }   
                $cC = mysqli_query($polaczenie,  "UPDATE  domeny SET ltime=('$curConn') WHERE attempts=('$try')");          //aktualizacja czasu do bazdy danych przy poprawnym polaczeniu
                $lastConn = $curConn;                                                                                       // aktualny czas polaczanie = ostatni udany czas polaczenia
    
                
            }else {$status = 'Down';                                                                                        //status na Down jesli nie uzyskano  polaczenia
         
                  $attempts2 =  mysqli_query($polaczenie, "UPDATE domeny SET attempts=attempts+1 WHERE adres='$nazwa'");    //aktualizacja do bazy danych wartosci o ++1 przy nieudanym polaczeniu
                  $attempts2 = mysqli_query($polaczenie,  "SELECT attempts FROM domeny WHERE adres=('$nazwa')");            //pobranie z BD ilosci nieudanych polaczen

             if ($row = $attempts2->fetch_assoc()) {
                    $attempts= $row['attempts']."<br>";
            } 
             $dif = time_difference($lastConn, $curConn);                                                                   // wykonanie funkcji ktory odejmuje czas i zwraca jak dlugo jest status 'Down'
            }  
           print "<TR><TD>$idt</TD><TD>$nazwa</TD><TD>$status</TD><TD>$attempts</TD><TD>$lastConn</TD><TD>$curConn</TD><TD>$dif</TD></TR>\n";   
           } 
            print "</TABLE>" 
?>
  </tr></table>

</body>
</html>