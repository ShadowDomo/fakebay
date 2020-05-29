<!doctype html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <!-- <script
            src="https://www.paypal.com/sdk/js?client-id=AePRm1rdA51yIgECneOiet1gCCGoljdrcrmK4UFmYD_x2ntmyBch9sq35bgkNiyj89LJaH-jeZYAb5yE">
    </script> -->

    <script>
    
    $(document).ready(function() {
        // log you out after inactivity
    var ints = setInterval(() => {
        $.ajax({
            type: "post",
            url: "<?php echo base_url(); echo "Login/timeout"?>",
            data: "data",
            success: function (response) {
                html = $.parseHTML(response);
                // console.log($(html).text());
                if ($(html).text() === "logout") {
                    $.ajax({
                        type: "post",
                        url: "<?php echo base_url(); echo "Login/logout"?>",
                        data: "data",
                        success: function (response) {
                            window.location = "<?php echo base_url(); echo "Login" ?>"
                        }
                    });
                } else {
                    console.log($(html).text())
                }
            }
        });

    }, 1000);
});
    
    
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fakebay</title>
    <div class="container-fluid">
        <div class="row alert alert-warning mb-0" awd>
            <div class="col-md-2 "></div>
            <div class="col-md-4 ">
                <h2>Welcome to Fakebay</h2>

            </div>

            <div class="col-md-4">
                <div class="row d-flex justify-content-end">
                    <?php 
						if (!$this->session->has_userdata('user_id')) {
							 echo form_open('Login/index') ?>
                            <button name="submit" type="submit" class="btn btn-link" value="log in">Login</button>
                            <?php echo form_close();
                            
							echo form_open('Login/registerform'); ?>
                            <button name="submit" type="submit" class="btn btn-link" value="register">Register</button>
                            <?php echo form_close();
							
						} else {
                            echo form_open('Products/viewListPage'); ?>
                            <button name="submit" type="submit" class="btn btn-link" value="listing">Sell something</button>
                            <?php echo form_close();

                            echo form_open('Login/viewProfile'); ?>
                            <button name="submit" type="submit" class="btn btn-link" value="profile">My Profile</button>
                            <?php echo form_close();

							echo form_open('Login/logout'); ?>
                            <button name="submit" type="submit" class="btn btn-link" value="logout">Logout</button>
                            <?php echo form_close();
						}
					?>
                </div>

            </div>

            <div class="col-md-2"></div>
        </div>
    </div>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- alerts are for debugging TODO remove -->
            <div class="col-2"></div>
            <div class="col-8 alert alert-info">
