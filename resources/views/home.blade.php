<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.semanticui.min.css" integrity="sha512-Z6aNXv6992eleT+jDj3n70dHvdpYFC6Xz2gmmlCeTzIxUT5/jNKVpPxvOuxTzexywssRZeR0g5NziBFMEs0Hcw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha512-8bHTC73gkZ7rZ7vpqUQThUDhqcNFyYi2xgDgPDHc+GXVGHXq+xPjynxIopALmOPqzo9JZj0k6OqqewdGO3EsrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <meta name="viewport" content="user-scalable=1.0,width=device-width,initial-scale=1.0"/>
        <style>
            .sorting, .sorting_desc, .sorting_asc {
                cursor:pointer!important;
            }
        </style>
        <title>MailerLite | Home</title>
    </head>
    <body style="background-color:#fefefe">
        @include('components.nav')
        <div style="padding:10px;box-sizing:border-box">
            <div class="ui grid">
                <div class="eleven wide column">
                    <div class="ui card" style="width:100%;padding:20px;padding-bottom:40px">
                        <h4>My subscribers</h4>
                        <span style="color:gray;font-size:12px">Below is a list of all subscribers on your platform</span>
                        <br/>
                        <br/>
                        <table class="ui celled table" id="datatables">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Country</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($subscribers as $i) {
                                        $country = "";
                                        foreach ($i["fields"] as $f) {
                                            if ($f["key"] == "country") {
                                                $country = $f["value"];
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td data-label="name"><?php echo $i["name"]; ?></td>
                                            <td style="color:cornflowerblue;cursor:pointer" data-label="email" onclick="edit_subscriber(`<?php echo $i['name']; ?>`,`<?php echo $i['email']; ?>`,`<?php echo $country; ?>`,`<?php echo $i['id']; ?>`)"><?php echo $i["email"]; ?></td>
                                            <td data-label="country"><?php echo $country; ?></td>
                                            <td style="width:100px">
                                                <?php echo date("Y-m-d H:i", strtotime($i["date_created"])); ?>
                                            </td>
                                            <td data-label="edit-action" style="width:50px">
                                                <button class="ui red button tiny" onclick="delete_subscriber('<?php echo $i['id']; ?>')"><i class="trash icon"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="five wide column">
                    <div class="ui card" style="width:100%;padding:20px">
                        <h4>Create a Subscriber</h4>
                        <span style="color:gray;font-size:12px">Add a new subscriber to your platform</span>
                        <form class="ui form" id="create_subscriber" action="/api/create">
                            <input name="name" type="text" class="ui input" placeholder="Enter name" style="margin-top:15px;display:block" required/>
                            <input name="email" type="email" class="ui input" placeholder="Enter email" style="margin-top:15px;display:block" required/>
                            <input name="country" type="text" class="ui input" placeholder="Enter country" style="margin-top:15px;display:block" required/>
                            <button class="ui green button" style="margin-top:15px"><i class="check icon"></i> Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui small modal" id="edit">
            <i class="close icon"></i>
            <div class="header">
                Edit Subscriber
            </div>
            <div class="scrolling content">
                <form id="edit_subscriber" class="ui form" action="/api/edit">
                    <input id="name" name="name" type="text" class="ui input" placeholder="Enter name" style="display:block" required/>
                    <input id="email" type="email" class="ui input" placeholder="Enter email" style="margin-top:15px;display:block" required readonly disabled/>
                    <input id="country" name="country" type="text" class="ui input" placeholder="Enter country" style="margin-top:15px;display:block" required/>
                    <input type="hidden" name="id" id="id"/>
                    <button class="ui blue button" style="margin-top:15px"><i class="pencil icon"></i> Edit</button>
                </form>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js" integrity="sha512-dqw6X88iGgZlTsONxZK9ePmJEFrmHwpuMrsUChjAw1mRUhUITE5QU9pkcSox+ynfLhL15Sv2al5A0LVyDCmtUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.semanticui.min.js" integrity="sha512-8B38KJBwxAoS9vVowfY3gU7EAtDzk/9HVK6fo9PKY5yJPWKWuRw+DXOCGOJ2nCFQNvX5VGVz9oSWEUGzk2A4Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            let edit_subscriber = function(name, email, country, id) {
                $('#edit').modal('show');
                /*
                    Get fields from the selected row and popup modal
                */
                document.getElementById("name").value = name.toString();
                document.getElementById("email").value = email.toString();
                document.getElementById("country").value = country.toString();
                document.getElementById("id").value = id.toString();
            }
            let delete_subscriber = function(id) {
                var formdata = new FormData();
                formdata.append("id", id.toString());
                formdata.append("dt", new Date().toString());
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
                        //Handle responses
                        if (json.status == "error") {
                            toastr.error(json.message, "Error", {progressBar:true});
                        }
                        else {
                            toastr.success(json.message, "Success", {progressBar:true});
                            setTimeout(function() {
                                location.reload();
                            }, 3500);
                        }
                    }
                }
                xhttp.open("POST", "/api/delete", true);
                xhttp.send(formdata);
            }
            document.getElementById("create_subscriber").onsubmit = function() {
                event.preventDefault();
                var formdata = new FormData(this);
                formdata.append("dt", new Date().toString());
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
                        //Handle responses
                        if (json.status == "error") {
                            toastr.error(json.message, "Error", {progressBar:true});
                        }
                        else {
                            toastr.success(json.message, "Success", {progressBar:true});
                            setTimeout(function() {
                                location.reload();
                            }, 3500);
                        }
                    }
                }
                xhttp.open("POST", this.action, true);
                xhttp.send(formdata);
            }
            document.getElementById("edit_subscriber").onsubmit = function() {
                event.preventDefault();
                var formdata = new FormData(this);
                formdata.append("dt", new Date().toString());
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
                        //Handle responses
                        if (json.status == "error") {
                            toastr.error(json.message, "Error", {progressBar:true});
                        }
                        else {
                            toastr.success(json.message, "Success", {progressBar:true});
                            setTimeout(function() {
                                location.reload();
                            }, 3500);
                        }
                    }
                }
                xhttp.open("POST", this.action, true);
                xhttp.send(formdata);
            }
            $(document).ready(function() {
                $("#datatables").DataTable();
            });
        </script>
        @include('components.footer')
        @include('components.alerts')
    </body>
</html>