<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>TEST</title>
    <link rel="stylesheet" href="app/assets/style.css"/>
</head>
<body>
<div class="wrapper">
    <div class="row">
        <div class="col-4 main-content col-sm-12">
            <div class="container">
                <!-- NAV BAR -->
                <div class="nav row">
                    <div class="col-4 logo-container col-sm-4">
                        <div class="row">
                            <div class="logo"></div>
                            <div class="brand">pineapple.</div>
                        </div>
                    </div>
                    <div class="col-8 col-sm-8">
                        <ul>
                            <li><a href="#">About</a></li>
                            <li><a href="#">How It Works</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content">
                <?php

                if($subscribed) {
                    include('subscribed.php');
                } else {
                    include('form.php');
                }

                ?>
                <hr />
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <ul class="social">
                                <li><a href="#" class="icon fb"></a></li>
                                <li><a href="#" class="icon insta"></a></li>
                                <li><a href="#" class="icon twitter"></a></li>
                                <li><a href="#" class="icon youtube"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8 bg">

        </div>
    </div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="/app/assets/app.js"></script>
</body>
</html>