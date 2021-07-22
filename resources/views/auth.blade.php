<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha512-8bHTC73gkZ7rZ7vpqUQThUDhqcNFyYi2xgDgPDHc+GXVGHXq+xPjynxIopALmOPqzo9JZj0k6OqqewdGO3EsrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <meta name="viewport" content="user-scalable=1.0,width=device-width,initial-scale=1.0"/>
        <title>MailerLite | Authorize</title>
        <style>
            .card-div {
                background-color:rgba(245,250,255,0.9);
                box-sizing:border-box;
                width:450px;
                max-width:90%;
                border-radius:10px;
                padding:25px;
                padding-top:40px;
                padding-bottom:40px;
                box-shadow:0px 0px 5px lightgray;
                margin-top:40px;
            }
            @media (max-width:800px) {
                .card-div {
                    margin-top:0px;
                }
            }
        </style>
    </head>
    <body style="background-image:linear-gradient(45deg, #2a2b2d, gray);padding:20px">
        <center>
            <div class="row">
                <div class="column">
                    <div class="card-div">
                        <form class="ui form" action="/api/auth" method="POST" id="api_form">
                            <h3>Authorize your API Key</h3>
                            <span style="font-size:12px;color:gray">Provide your MailerLite API key for access!</span>
                            <input name="apikey" class="ui input" placeholder="Enter API key here" style="margin-top:15px;display:block;color:steelblue" maxlength="" required/>
                            <button class="ui black button" style="margin-top:15px"><i class="lock icon"></i> Authorize</button>
                        </form>
                    </div>
                </div>
            </div>
        </center>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha512-dqw6X88iGgZlTsONxZK9ePmJEFrmHwpuMrsUChjAw1mRUhUITE5QU9pkcSox+ynfLhL15Sv2al5A0LVyDCmtUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @include('components.alerts')
        <script>
            document.getElementById("api_form").onsubmit = function(event) {
                event.preventDefault();
                var formdata = new FormData(this);
                var xhttp;
                //XMLHttpRequest()
                if (XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                }
                else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var json = JSON.parse(xhttp.responseText);
                        //Handle API Key authorization responses
                        if (json.status == "error") {
                            toastr.error(json.message, "Error", {progressBar:true});
                        }
                        else {
                            //Redirect on success
                            location.href = "/home";
                        }
                    }
                }
                xhttp.open("POST", this.action, true);
                xhttp.send(formdata);
            }
        </script>
    </body>
</html>