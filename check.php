<?php 
require __DIR__ . '/vendor/autoload.php';
if(isset($_GET['apikey'])){
    $proxycheck_options = array(
        'API_KEY' =>htmlentities ($_GET['apikey']) , // Your API Key.
        'TLS_SECURITY' => 0, // Enable or disable transport security (TLS).
        'STAT_SELECTION' => 'usage', // Stats to view: detections, usage or queries
        'LIMIT' => '10', // Specify how many entries to view (applies to detection stats only)
        'OFFSET' => '0' // Specify an offset in the entries to view (applies to detection stats only)
      );
          
    $result_array = \proxycheck\proxycheck::stats($proxycheck_options);
    
    if(isset($result_array["error"])){
    echo("0");
    }else{
    echo("1");
    }
    
    
}



?>