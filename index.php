<?php
    // <---- Author: Bombay Pobthorn ------>
    // <---- Date: 5/8/2017 ------>

    if($_GET['seacrh']){
        $url = 'https://wall.alphacoders.com/search.php?search=';
        $url .= $_GET['seacrh']; 

        if($_GET['page']){
            $url .= '&page='; 
            $url .= $_GET['page']; 
        }

        $i = []; 
        $src = []; 
    
        try{
            error_reporting(E_ERROR | E_PARSE);

            // request html 
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            $content = curl_exec( $ch );
            curl_close( $ch );

            // put html to DomDocument
            $doc = new DOMDocument();
            $doc->loadHTML( $content );    
            $selector = new DOMXPath( $doc );

            $result = $selector->query('//div[@class="boxgrid"]/a');

            // loop through all found items
            foreach( $result as $node ) {
                array_push( $i,$node->getAttribute('href') );
            }

            $result2 = $selector->query('//div[@class="boxgrid"]/a/img');

            // loop through all found items
            foreach( $result2 as $node ) {
                array_push($src,$node->getAttribute('src'));
            }

            $myData = array();
            $data = array();

            // loop put img & big img in json
            for ($x=0; $x < count($i); $x++) { 
                $json = array();
                $json['id'] = $i[$x];
                $json['src'] = $src[$x];
                array_push($data,$json);
            }

            $myData['data'] = $data;

            // check empty 
            if( !empty( $myData['data'] ) ){
                $find = $selector->query('//h1[@class="center title"]');
                foreach( $find as $node ) {
                    $myData['msg'] = "Found ".$node->nodeValue ;
                }
                
            }else{
                $myData['msg'] = "Not Found" ;
            }

            $myData['status'] = "ok";

            // make header to json format
            header('Content-Type: application/json');
            echo json_encode($myData);
            
        }catch(Exception $ex){
            echo $ex;
        }

    }else{
        $myData = array();
        $myData['msg'] = "Nothing to do";
        $myData['status'] = "ok";
        header('Content-Type: application/json');
        echo json_encode($myData);
    }

    