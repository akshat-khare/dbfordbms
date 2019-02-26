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
    <!-- Navigation -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Home</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.html">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBlog" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Blog
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBlog">
                            <a class="dropdown-item" href="blog-home-1.html">Blog Home 1</a>
                            <a class="dropdown-item" href="blog-home-2.html">Blog Home 2</a>
                            <a class="dropdown-item" href="blog-post.html">Blog Post</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header>
        <img src="./img/parliament-of-india.png" class="rounded mx-auto d-block figure" alt="Image for Parliament of India.">
    </header>

    <!-- Page Content -->
    <div class="container">
        <h1>Parliamentary Data Visualization</h2>
        <p>This is a demo application created using plain PHP (with some CSS sprinkled in).</p>
    
        <h2 class="mt-5 ">Playstore Reviews Sentiment Analysis</h1>
        <p class="lead">Showing the results of: SELECT * FROM playstore ORDER BY random() LIMIT 25</p>
    
        <?php
        pg_connect("host=ip dbname=db_name user=user_name password=pswd");
        $query = "SELECT * FROM playstore ORDER BY random() LIMIT 25";
        $rows = pg_query($query);
        ?>
    
        <table>
            <thead>
                <tr>
                    <th>App Name</th>
                    <th>Review</th>
                    <th>Sentiment</th>
                    <th>Polarity</th>
                    <th>Subjectivity</th>
                </tr>
            </thead>
    
            <tbody>
                <?php while ($row = pg_fetch_array($rows)) {?>
                <tr>
                    <td> <?php echo $row['app_name']; ?> </td>
                    <td> <?php echo $row['review']; ?> </td>
                    <td> <?php echo $row['senti']; ?> </td>
                    <td> <?php echo $row['polarity']; ?> </td>
                    <td> <?php echo $row['subjectivity']; ?> </td>
                </tr>
                <?php }?>
            </tbody>
    
        </table>
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
