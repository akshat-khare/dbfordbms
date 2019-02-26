<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>COL 362/632 - Project 1 - Demo PHP App</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

    <!-- Link to all CSS files -->
    <link rel="stylesheet" href="./css/custom.css" />
</head>

<body>
    <?php include 'navbar.php' ?>

    <header>
        <img src="./img/parliament-of-india.png" class="rounded mx-auto d-block figure" alt="Image for Parliament of India.">
    </header>

    <!-- Page Content -->
    <div class="container">
        <h1>Parliamentary Data Visualization</h2>
        <p>This is an application created using plain PHP (with some CSS sprinkled in) to demonstrate Parliamentary data.</p>
        
        <form>
            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group row">
                        <label for="inputConsti" class="col-sm-2 col-form-label">Constituency</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control col-sm-8" id="inputConsti" placeholder="Enter Consitutency Name">
                        </div>
                    </div>
                    <p class="text-center"><strong>OR</strong></p>
                    <div class="form-group row">
                        <label for="inputDistrict" class="col-sm-2 col-form-label">District</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control col-sm-8" id="inputDistrict" placeholder="Enter District">
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Know Your MP</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>


    <footer style="margin-top: 50px;">
    <hr>
        <span>
            Built by
            <a href="https://github.com/akshat-khare" target="_blank">@akshat-khare</a>, 
            <a href="https://github.com/srijan-sinha" target="_blank">@srijan-sinha</a> and
            <a href="https://github.com/DivyanshuSaxena" target="_blank">@DivyanshuSaxena</a>
            using
            <a href="http: //php.net/manual/en/intro-whatis.php" target="_blank">PHP</a> &
            <a href="https://code.visualstudio.com" target="_blank">VSCode</a>.
        </span>

    <script src="js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
