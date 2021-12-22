
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: x-requested-with, Content-Type, origin, authorization, accept, client-security-token");
$myfilename="testfile.txt";
$myfile = fopen($myfilename, "w");
$target_dir = "uploads/";
require __DIR__ . '/vendor/autoload.php';
// Input your options for this query including your optional API Key and query flags.

if(isset($_POST['filename']) and !empty($_POST['filename'])){
    $fp = file("uploads/".$_POST['filename']);
   if(isset($_POST['apikey']) and !empty($_POST['apikey'])){
    $proxycheck_options = array(
        'API_KEY' => $_POST['apikey'], // Your API Key.
        'ASN_DATA' => 1, // Enable ASN data response.
        'VPN_DETECTION' => 1, // Check for both VPN's and Proxies instead of just Proxies.
    );
    
    $file = fopen("uploads/".$_POST['filename'],"r");
    $i=0;
    while(! feof($file))
     {
      $arr[]=fgetcsv($file);
      if($i==0){
        $index=array_search('ip', $arr[0]);
        $index_email=array_search('email', $arr[0]);
        $list=array();
        $list["proxy"]=array();
        $list["others"]=array();
        $list["us_real"]=array();
        $list["invalid"]=array();
      }else{
        // ip tests and get data from api
        $result=\proxycheck\proxycheck::check($arr[$i][$index], $proxycheck_options);
        if($result["status"]=="ok"){
        $data=$result[$arr[$i][$index]];
        $ip=$arr[$i][$index];
        $email=$arr[$i][$index_email];
        if(isset($data["provider"])){
            $provider=$data["provider"];
           }else{
            $provider="unknown"; 
        }
        if(isset($data["country"])){
            $country=$data["country"];
           }else{
            $country="unknown"; 
        }


        if($data["isocode"]=="US" and $data["proxy"]=="no"){
            array_push($list["us_real"],array($ip,$country,$email,$provider));
        } else if($data["isocode"]!="US" and $data["proxy"]=="no"){
            array_push($list["others"],array($ip,$country,$email,$provider));
        }else if($data["proxy"]=="yes"){
            array_push($list["proxy"],array($ip,$country,$email,$provider));
        }
        
   
      }else if($result["status"]=="error"){
        $ip=$arr[$i][$index];
        $email=$arr[$i][$index_email];
            array_push($list["invalid"],array($ip,$email)); 
      }
     }

      $i++;
     file_put_contents($myfilename,$i." of ".count($fp) );
     usleep(500);
    }
    
    fclose($file);
    echo json_encode($list);
    file_put_contents($myfilename,"completed");
}else {
    echo("inv_api");  
}
}else{
    echo("inv_file");
}


?>