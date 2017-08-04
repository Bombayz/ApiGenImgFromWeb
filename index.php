<?php
    // echo "Bay";
    $url = 'https://wall.alphacoders.com/search.php?search=rem'; 
  
    // $data = "fn=login&test=1";
    
    /*$data = array(
            'fn' => "login" 
        );*/
    
    $i = []; 
    $src = []; 
    
    try{
        error_reporting(E_ERROR | E_PARSE);

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        // curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        $content = curl_exec( $ch );
        curl_close($ch);


        
        // array_push($str,"เชียงใหม่");
        // echo $str[0] ;
        // echo strpos($content,"<img src=");

        $doc = new DOMDocument();
        $doc->loadHTML($content);    
        $selector = new DOMXPath($doc);

        $result = $selector->query('//div[@class="boxgrid"]/a');

        // loop through all found items
        foreach($result as $node) {
            // echo "<p><a href=".$node->getAttribute('src').">".$node->getAttribute('src')."</a></p>";
            
            // echo "<p><a href=".$node->getAttribute('href')." >".$node->getAttribute('href')."</a></p>";
            // echo "<br>";
            array_push($i,$node->getAttribute('href'));
        }

        $result2 = $selector->query('//div[@class="boxgrid"]/a/img');

        // loop through all found items
        foreach($result2 as $node) {
            // echo "<p><a href=".$node->getAttribute('src').">".$node->getAttribute('src')."</a></p>";
            
            // echo "<p><a href=".$node->getAttribute('src')." >".$node->getAttribute('src')."</a></p>";
            // echo "<br>";
            array_push($src,$node->getAttribute('src'));
        }

        // print_r($i);
        // print_r($src);

        $myData = array();
        $data = array();

        for ($x=0; $x < count($i); $x++) { 
            $json = array();
            $json['id'] = $i[$x];
            $json['src'] = $src[$x];

            // echo $i[$x];
            // echo $src[$x];
            array_push($data,$json);
        }

        $myData['data'] = $data;


        $myData['status'] = "ok";

        header('Content-Type: application/json');
        echo json_encode($myData);

        


        // print_r($content);
        // var_dump($content);



        // echo($content);
        
    }catch(Exception $ex){
    
        echo $ex;
    }