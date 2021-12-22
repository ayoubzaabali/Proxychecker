<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
    /*            .progress-bar
                        {
                            height: 15px;
                            width: ;
                            background-color: #016094;
                            border-radius: 50px;
                        }*/
    .btn-file {
        position: relative;
        overflow: hidden;
    }

    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    </style>
    <link href="cssJS/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="cssJS/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script>
    $(function() {

        // We can attach the `fileselect` event to all file inputs on the page
        $(document).on('change', ':file', function() {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        // We can watch for our custom `fileselect` event like this
        $(document).ready(function() {
            $(':file').on('fileselect', function(event, numFiles, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log)
                        alert(log);
                }

            });
        });

    });

    // function hideSub() {
    //   $('#deleteZip').prop('checked', false);
    //     if ($('#sub').hasClass('hidden')) {
    //         $('#sub').removeClass('hidden');
    //     } else {
    //         $('#sub').addClass('hidden');
    //     }
    // }
    </script>

    <script type="text/javascript" src="cssJS/md5.js"></script>
    <script type="text/javascript" src="cssJS/upload.js?v=2"></script>
    </div>


</head>

<body>

    <div class="col-md-3">
        <form enctype="multipart/form-data" id="form" action="process1.php" method="POST">

            <div class="input-group">
                <input name="apikey" onpaste="check(this)" onkeyup="check(this)" placeholder="apikey" style=" width: 600px;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;" />
  <span></span>
            </div>
            <input type="hidden" name="filename">
        </form>
        <form id="formUpload" action="" method="post" enctype="multipart/form-data">
            <div class="container" style="margin-top: 20px;">
                <div class="col-lg-6 col-sm-6 col-12">
                    <h4>IP CHECKER</h4>
                    <div class="input-group">
                        <label class="input-group-btn">
                            <span class="btn btn-primary">
                                Browse&hellip; <input id="formUpload-file" type="file" name="file" data-name=""
                                    data-end="" style="display: none;"
                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </span>
                        </label>
                        <input type="text" class="form-control" readonly>
                    </div>
                    <span class="help-block">
                        Try selecting one file and upload
                    </span>
                    <button id="submit-btn" disabled type="submit" class="btn btn-primary">upload</button><br>
                    <!-- <input type="checkbox" name="extractZip" id="extractZip" onclick="hideSub();">&nbsp;Unzip after upload.<br> -->
                    <!-- <span id="sub" class="hidden"><input type="checkbox" name="deleteZip" id="deleteZip">&nbsp;Delete the Zip after extraction.</span><br> -->
                    <div id="progress-bar" class="progress-bar" style="height: 20px; float: none;"></div>
                    <br>
                    <div id="statusBlock" class="help-block text-success"
                        style="height: 40px; width: 100%; line-height: 40px; vertical-align: middle; text-align: center;">
                        Welcome to IP CHECKER,&nbsp;<small>&copy;</div>
                </div>


            </div>
        </form>


    </div>
</body>
<script>
function check(elem) {
    let inp= document.querySelector("#submit-btn");
    inp.disabled=true;
    elem.nextElementSibling.innerText = "";
    var val = elem.value.replace(/\s/g, "");
    elem.value=val;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "0") {
                elem.nextElementSibling.innerText = "invalid API key";
                elem.nextElementSibling.style.color="red";
                inp.disabled=true;
            } else if(this.responseText == "1"){
                elem.nextElementSibling.innerText = "Valid key";
                elem.nextElementSibling.style.color="green";
                inp.disabled=false;

            }
        }
    };
    xhttp.open("GET", "check.php?apikey=" + elem.value, true);
    xhttp.send();
}
</script>

</html>