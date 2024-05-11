
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="MCI I Millionaire Club Indonesia">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Document title -->
    <title>MCI | Millionaire Club Indonesia</title>
    <!-- favicons -->
    <link rel="shortcut icon" href="https://mci-world.com/favicon_new.ico" type="image/x-icon">
    <link rel="manifest" href="https://mci-world.com/asset/2021-login/img/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="https://mci-world.com/asset/2021-login/img/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Stylesheets & Fonts -->
    <link href="https://mci-world.com/asset/2021-login/css/plugins.css" rel="stylesheet">
    <link href="https://mci-world.com/asset/2021-login/css/style-login.css" rel="stylesheet">


</head>

<body data-bg-parallax="https://mci-world.com/asset/2021-login/img/1.jpg">
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Section -->
        <section class="">
            <div class="container">
                <div>
                    <div class="row pd-100">
                        <div class="col-lg-4 center p-50 background-white b-r-6">
                            <div class="text-center m-b-30">
                                <a href="https://mci-world.com/" class="logo">
                                    <img src="https://mci-world.com/asset/mci-logo.png" alt="MCI World">
                                </a>
                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="sr-only">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="John.Doe@example.com" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group m-b-0">
                                    <label class="sr-only">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="********" required>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group p-t-20">
                                  <input type="hidden" name="token" value="09cee3fa86e36bba3ade434a52dcf8b6">
                                    <button type="submit" class="btn">Login</button>
                                </div>
							</form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end: Section -->
            <div class="row bottom-line">
                <div class="col-lg-6 text-bottom">
                        <a href="https://www.mci-world.com">Go to site homepage</a>
                </div>
                    <div class="col-lg-6 text-bottom text-right">
                        <p class="text-white">&copy; 2021 MCI | Millionaire Club Indonesia.</p>
                    </div>
            </div>
    </div>
    <!-- end: Body Inner -->
    <!-- Scroll top -->
    <a id="scrollTop"><i class="icon-chevron-up"></i><i class="icon-chevron-up"></i></a>
    <!--Plugins-->
    <script src="https://mci-world.com/asset/2021-login/js/jquery.js"></script>
    <script src="https://mci-world.com/asset/2021-login/js/plugins.js"></script>
    <!--Template functions-->
    <script src="https://mci-world.com/asset/2021-login/js/functions.js"></script>
</body>

</html>
