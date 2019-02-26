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
                $queryname = "SELECT candidate_name FROM ls2009candi WHERE pc_name='$constituency' AND position=1";
                $queryattendance = "SELECT session, totalsittings, dayssigned FROM attendancedata WHERE constituency='$constituency' ORDER BY session";
                $name = pg_query($queryname);
                $attendance = pg_query($queryattendance);
            }
        }

    ?>

    <div class="container">
        <hr>
        <h1>Know Your MP</h1>
        <p>The given constituency/district was: 
            <?php if (!empty($constituency)) {
                echo "<strong>$constituency</strong>.Your Member of Parliament for Lok Sabha is:";
            } else {
                // Change for multiple constituencies
                echo "<strong>$district</strong>.Your Member of Parliament for Lok Sabha is:";
            }?>
        </p>
        
        <?php while ($row = pg_fetch_array($name)) {?>
            <h3><?php echo $row['candidate_name']; ?></h3>
        <?php }?>

        <p>
            His attendance for the fifteenth Lok Sabha is as follows:
        </p>
        <table>
            <thead>
                <tr>
                    <th>Session</th>
                    <th>Total Sittings</th>
                    <th>Days Signed</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = pg_fetch_array($attendance)) {?>
                <tr>
                    <td> <?php echo $row['session']; ?> </td>
                    <td> <?php echo $row['totalsittings']; ?> </td>
                    <td> <?php echo $row['dayssigned']; ?> </td>
                </tr>
                <?php }?>
            </tbody>

        </table>
    </div>

    <?php include 'footer.php' ?>

</body>