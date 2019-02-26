<?php include 'head.php' ?>

<body>
    <?php include 'navbar.php' ?>
    
    <!-- PHP Query -->
    <?php     
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $district = $constituency = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["district"]) && empty($_POST["constituency"])) {
                $nameErr = "Either district or constituency is required";
            } else {
                $district = test_input($_POST["district"]);
                $constituency = test_input($_POST["constituency"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z ]*$/",$district)) {
                    $nameErr = "Only letters and white space allowed";
                }
        
                $db = pg_connect( "host=localhost port=5432 dbname=project1 user=group_13 password=205-265-669" );
                $query = "SELECT candidate_name FROM ls2009candi WHERE pc_name='Lucknow' AND position=1";
                $rows = pg_query($query);
            }
        }

    ?>

    <div class="container">
        <hr>
        <h1>Know Your MP</h1>

        <?php while ($row = pg_fetch_array($rows)) {
            echo $row['candidate_name'];
        }?>
    </div>

    <?php include 'footer.php' ?>

</body>