<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Addresses</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <?php
    include "includes/header.php";
    ?>
    <br/><br/>

    <form method="post" id="import_form" enctype="multipart/form-data">

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
                <input type="file" name="file" id="file" accept="text/xml" class="custom-file-input">
                <label class="custom-file-label" for="file">Choose XML file</label>
            </div>
        </div>
        <br/>
        <input type="submit" name="submit" id="submit" class="button" value="Import XML">
    </form>
    <br/>

    <div class="alert alert-success" id="success-message">
        <strong class="info"></strong>
    </div>
    <div class="alert alert-danger" id="error-message">
        <strong class="info"></strong>
    </div>

    <!--<div id="success-message" class="alert alert-success" role="alert">

    </div>


    <div id="error-message">

    </div>-->


    <script>
        $(document).ready(function () {
            $('#import_form').on('submit', function () {
                event.preventDefault();
                $.ajax({
                    url: "import.php",
                    method: "POST",
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#submit').attr('disabled', 'disabled').val('Importing...').css("opacity", '0.6');
                    },
                    success: function (data) {
                        var status = data.status;
                        var output = data.output;

                        if (status == 'Yes') {
                            $('#error-message').hide();
                            $('#success-message').show();
                            $('#success-message .info').text(output);
                            $('#import_form')[0].reset();

                        } else {
                            $('#success-message').hide();
                            $('#error-message').show();
                            $('#error-message .info').text(output);
                        }

                        $('#submit').attr('disabled', false).val('Import XML').css("opacity", '1');

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.status);
                        alert(textStatus);
                        alert(errorThrown);
                    }
                });

            });
        });
    </script>
</div>
</body>
</html>