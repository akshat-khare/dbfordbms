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

        $district = $constituency = $gender = "";
        $choice = 0;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["district"]) && empty($_POST["constituency"])) {
                $nameErr = "Either district or constituency is required";
            } else {
                $district = strtoupper(test_input($_POST["district"]));
                $constituency = strtoupper(test_input($_POST["constituency"]));
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z ]*$/",$district) || !preg_match("/^[a-zA-Z ]*$/",$constituency)) {
                    $nameErr = "Only letters and white space allowed";
                }

                // connect to database
                $db = pg_connect( "host=localhost port=5432 dbname=project1 user=group_13 password=205-265-669" );

                if (!empty($_GET["choice"])) {
                    $choice = $_GET["choice"];
                }

                if ($choice == 0) {
                    // Choose the repective MP
                    if (!empty($constituency)) {
                        $querychoose = "SELECT candidate_name, pc_name FROM ls2009candi WHERE upper(pc_name) like '%$constituency%' AND position=1";
                        $chooseconsti = pg_query($querychoose);
                    } else {
                        // Get the districts from the keyword
                        $querydistrict = "SELECT candidate_name, distoconsti.pc AS pc_name FROM distoconsti, ls2009candi WHERE upper(distoconsti.dist) like '%$district%' AND upper(ls2009candi.pc_name) like '%' ||distoconsti.pc || '%' AND ls2009candi.position=1";
                        $chooseconsti = pg_query($querydistrict);
                        // while ($row = pg_fetch_array($chooseconsti)) {
                        //     echo $row['candidate_name'];
                        // }
                    }
                } else {
                    // Give the queries for the selected MP
                    $queryname = "SELECT candidate_name, pc_name, candidate_sex as gender FROM ls2009candi WHERE upper(pc_name)='$constituency' AND position=1";
                    $queryattendance = "SELECT session, totalsittings, dayssigned FROM attendancedata WHERE upper(constituency)='$constituency' ORDER BY session";
                    $querycompiled = "SELECT totalsittings, dayssigned FROM compiled_attendance WHERE upper(constituency)='$constituency'";
                    $name = pg_query($queryname);
                    $attendance = pg_query($queryattendance);
                    $compiled = pg_query($querycompiled);
                }
            }
        }

    ?>

    <div class="container">
        <hr>
        <h1>Know Your MP</h1>
        <p>The given constituency/district was: 
            <?php if (!empty($constituency)) {
                echo "<strong>$constituency</strong>.";
            } else {
                // Change for multiple constituencies
                echo "<strong>$district</strong>. ";
            }?>
        </p>
        
        <?php

            if ($choice == 0) {
                echo 'The following matches were found for the given search keyword. Please choose the desired constituency. ';
                echo '<form action="mp.php?choice=1" method="post">
                        <fieldset class="form-group">
                            <div class="row">
                                <div class="col-sm-10">';
                while ($row = pg_fetch_array($chooseconsti)) {
                    echo '<div class="form-check">
                            <input class="form-check-input" type="radio" name="constituency" id='.$row['pc_name'].' value="'.$row['pc_name'].'" checked>
                            <label class="form-check-label" for="'.$row['pc_name'].'">
                                '.strtoupper($row['candidate_name']).'   ----   '.strtoupper($row['pc_name']).'
                            </label>
                        </div>';
                }
                echo '</div>
                </div>
            </fieldset>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>';
            } else {
                while ($row = pg_fetch_array($name)) {
                    if ($row['gender'] == 'M')
                        $gender = 'His';
                    else
                        $gender = 'Her';
                    echo 'Your chosen Member of Parliament is: <h2>'.$row['candidate_name'];
                    echo '</h2><p>
                        '.$gender.' attendance for the fifteenth Lok Sabha is as follows:
                    </p>
                    <table>
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Total Sittings</th>
                                <th>Days Signed</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($row = pg_fetch_array($attendance)) {
                            echo '<tr>
                                <td>'.$row['session'].'</td>
                                <td>'.$row['totalsittings'].'</td>
                                <td>'.$row['dayssigned'].'</td>
                            </tr>';
                    }
                    echo '</tbody>
                    </table> <br>';
                    while ($row = pg_fetch_array($compiled)) {
                        echo $gender.' attendance for the <strong>Fifteenth</strong> Lok Sabha is: '.($row['dayssigned']/$row['totalsittings']*100);
                    } 
                }
            }
        ?>

    </div>

    <?php include 'footer.php' ?>

</body>
