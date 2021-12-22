<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: x-requested-with, Content-Type, origin, authorization, accept, client-security-token");

if(isset($_POST['filename']) and !empty($_POST['filename'])){
    if(isset($_POST['apikey']) and !empty($_POST['apikey'])){
        echo("<div style='margin:200px auto;width:300px'><img style='width:100%' src='spinner.gif'/><br><div style='text-align:center' id='progress'>Starting...</div>
        
  <div id='btns'></div>      
        </div>
  ");

    ?>

<script>
sendData();

function sendData() {
    let filename = <?php echo json_encode($_POST['filename'] ); ?>;
    let key = <?php echo json_encode($_POST['apikey'] ); ?>;
    var formData = new FormData();
    formData.append(filename, key);
    formData.set('filename', filename);
    formData.set('apikey', key);

    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function() {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (xmlHttp.responseText == "inv_api") {
                document.write("invalid api key.");
            } else if (xmlHttp.responseText == "inv_file") {
                document.write("Error while processing try again later.");
            } else {
                // try {
                    let data = JSON.parse(xmlHttp.responseText);
                    download_csv_file(data["others"], "OTHERS");
                    download_csv_file(data["proxy"], "PROXY");
                    download_csv_file(data["us_real"], "US_REAL");
                    download_csv_file(data["invalid"], "invalid");
                // } catch (e) {
                //     alert("Error while processing try again later.");
                // }

            }
        }
    }
    xmlHttp.open("post", "process.php");
    xmlHttp.send(formData);

}

function download_csv_file(csvFileData, text) {

    //define the heading for each row of the data  
    if(text=="invalid"){
        var csv = 'ip,email\n';
    }else{
        var csv = 'ip,country,email,provider\n';
    }
    //merge the data with CSV  
    csvFileData.forEach(function(row) {
        csv += row.join(',');
        csv += "\n";
    });
    //display the created CSV data on the web browser   
    //    document.write("download sucess");  


    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';

    //provide the name for the CSV file to be downloaded  
    hiddenElement.download = text + '.csv';
    hiddenElement.innerText = text;
    //create button
    var button = document.createElement('button');
    button.appendChild(hiddenElement);
    document.querySelector("#btns").appendChild(button);
    //   hiddenElement.click();  
}
var refreshIntervalId = setInterval(getprogress, 100);


function getprogress() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("progress").innerHTML = this.responseText;

            if (this.responseText == "sucess") {
                clearInterval(refreshIntervalId);
            }
        }
    };
    xhttp.open("GET", "testfile.txt", true);
    xhttp.send();

}
</script>
<?php
 } 
}
?>