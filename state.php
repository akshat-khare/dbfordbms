<!DOCTYPE html>
<html lang="en">
<?php include 'head.php' ?>

<body>
    <?php include 'navbar.php' ?>

    <!-- PHP Query Variables -->
    <?php
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $state = "";
        $choice = 0;
        $state_attendance = $avg_age = 0;
        $year1 = $year2 = $limit1 = $limit2 = 10;
        $order1 = $order2 = "DESC";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["state"])) {
                $nameErr = "State name is required";
            } else {
                $state = strtoupper(test_input($_POST["state"]));
                $choice = 1;
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z ]*$/",$state)) {
                    $nameErr = "Only letters and white space allowed";
                }

                // Get Filters
                if (!empty($_POST["year1"])) {
                    $year1 = $_POST["year1"];
                }
                if (!empty($_POST["limit1"])) {
                    $limit1 = $_POST["limit1"];
                }
                if (!empty($_POST["order1"])) {
                    $order1 = $_POST["order1"];
                }
                if (!empty($_POST["year2"])) {
                    $year2 = $_POST["year2"];
                }
                if (!empty($_POST["limit2"])) {
                    $limit2 = $_POST["limit2"];
                }
                if (!empty($_POST["order2"])) {
                    $order2 = $_POST["order2"];
                }

                // connect to database
                $db = pg_connect( "host=localhost port=5432 dbname=project1 user=group_13 password=205-265-669" );

                // Give the queries for the Selected District
                $querystateattendance = "SELECT ROUND((dayssigned*100 + 10)/(totalsittings + 20), 2) AS attendance FROM statewiseattendance WHERE trim(UPPER(state)) = '$state'";
                $querytopmps = "SELECT name, constituency, ROUND((dayssigned*100+5)/(totalsittings+10), 2) AS attendance
                                FROM compiled_attendance WHERE trim(UPPER(state)) = '$state' ORDER BY attendance DESC LIMIT 10";
                $querypartypos = "SELECT count(*) as num, party_abbreviation FROM ls2009candi
                                WHERE trim(UPPER(state_name)) = '$state' AND position = 1 GROUP BY party_abbreviation ORDER BY num";
                $queryavgage = "SELECT ROUND(AVG(candidate_age), 2) as avg_age FROM ls2009candi
                                WHERE trim(UPPER(state_name)) = '$state' AND position = 1 ORDER BY avg_age";
                $querympsex = "SELECT candidate_sex, count(*) FROM ls2009candi WHERE trim(UPPER(state_name)) = '$state' AND position = 1 GROUP BY candidate_sex";
                $querybestdistipc = "SELECT district, sum(total_ipc_crimes) as total_ipc_crimes FROM districtipcdata
                                WHERE trim(UPPER(state_or_ut)) = '$state' AND 2019-year <= $year1 AND UPPER(district)<>'TOTAL' AND UPPER(district)<>'ZZ TOTAL'
                                GROUP BY district ORDER BY total_ipc_crimes $order1 LIMIT $limit1";
                $querybestdistagri = "SELECT district, sum(production) as production FROM agriculture
                                WHERE trim(UPPER(state)) = '$state' AND 2019-year <= $year2
                                GROUP BY district ORDER BY production $order2 LIMIT $limit2";
                $topmps = pg_query($querytopmps);
                $partypos = pg_query($querypartypos);
                $avgage = pg_query($queryavgage);
                $mpsex = pg_query($querympsex);
                $bestdistipc = pg_query($querybestdistipc);
                $bestdistagri = pg_query($querybestdistagri);
                $stateattendance = pg_query($querystateattendance);
                while ($row = pg_fetch_array($avgage)) {
                    $avg_age = $row['avg_age'];
                }
                while ($row = pg_fetch_array($stateattendance)) {
                    $state_attendance = $row['attendance'];
                }
            }
        }
    ?>

    <!-- Page Content -->
    <div class="container">
        <hr>
        <h1>Parliamentary Data Visualization</h1>
        <p>This is an application created using plain PHP (with some CSS sprinkled in) to demonstrate Parliamentary data.</p>

        <form method="post" action="state.php?">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group row">
                        <label for="inputState" class="col-sm-2 col-form-label">State</label>
                        <div class="col-sm-10">
                            <input name="state" value="<?php echo $state; ?>" class="form-control col-sm-8" id="inputState" placeholder="Enter Full Name of State">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Know Your State</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <?php 
            if ($choice == 1) {
            // Data for Agriculture and Crime Statistics ?>
              <hr>
                <u><h3><?php echo $state; ?></h3></u>

                <p>The average attendance of the MPs from your chosen state is: <?php echo $state_attendance; ?></p>
                <p><strong>Demography of MPs elected from this state:</strong></p>
                <p>The average age of MPs from this state is: <?php echo $avg_age; ?></p>                

                <p><strong>MPs with most attendance in Lok Sabha:</strong></p>
                <?php
                    echo '<table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Constituency</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>';
                    while ($row = pg_fetch_array($topmps)) {
                            echo '<tr>
                                <td>'.$row['name'].'</td>
                                <td>'.$row['constituency'].'</td>
                                <td>'.$row['attendance'].'</td>
                            </tr>';
                    }
                    echo '</tbody>
                    </table> <br>';
                ?>

                <p><strong>Position of Districts:</strong></p>
                <p>The position of the districts of this state as per the given filters is as follows: </p>

                <form action="state.php?choice=1" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputLimit1">Number of cities for Crimes</label>
                            <input type="number" class="form-control" id="inputLimit1" placeholder="10" min="10" name="limit1" value="<?php echo $limit1; ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputOrder1">Order of display for Crimes</label>
                            <select id="inputOrder1" class="form-control" name="order1">
                                <option <?php if ($order1 == "DESC") echo "selected"; ?>>DESC</option>
                                <option <?php if ($order1 == "ASC") echo "selected"; ?>>ASC</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputLimit2">Number of cities for Agriculture</label>
                            <input type="number" class="form-control" id="inputLimit2" placeholder="10" min="10" name="limit2" value="<?php echo $limit2; ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputOrder2">Order of display for Agriculture</label>
                            <select id="inputOrder2" class="form-control" name="order2">
                                <option <?php if ($order2 == "DESC") echo "selected"; ?>>DESC</option>
                                <option <?php if ($order2 == "ASC") echo "selected"; ?>>ASC</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo $state; ?>" name="state">
                    <div class="form-row align-items-center" >
                        <div class="form-group col-md-5">
                            <label for="inputYear1">Years for which the data has to be shown for Crimes</label>
                            <input type="number" class="form-control" id="inputYear1" placeholder="10" min="5" name="year1" value="<?php echo $year1; ?>">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="inputYear2">Years for which the data has to be shown for Agriculture</label>
                            <input type="number" class="form-control" id="inputYear2" placeholder="10" min="5" name="year2" value="<?php echo $year2; ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter Query</button>
                        </div>
                    </div>
                </form>
                

                <?php
                    echo '<table class="best-table">
                            <thead>
                                <tr>
                                    <th>District</th>
                                    <th>Total IPC Crimes</th>
                                </tr>
                            </thead>
                        <tbody>';
                    while ($row = pg_fetch_array($bestdistipc)) {
                            echo '<tr>
                                <td>'.$row['district'].'</td>
                                <td>'.$row['total_ipc_crimes'].'</td>
                            </tr>';
                    }
                    echo '</tbody>
                    </table>';
                    echo '<table class="best-table">
                            <thead>
                                <tr>
                                    <th>District</th>
                                    <th>Total Production</th>
                                </tr>
                            </thead>
                        <tbody>';
                    while ($row = pg_fetch_array($bestdistagri)) {
                            echo '<tr>
                                <td>'.$row['district'].'</td>
                                <td>'.$row['production'].'</td>
                            </tr>';
                    }
                    echo '</tbody>
                    </table>';
                ?>
        <?php } ?>
            

    </div>

    <?php include 'footer.php' ?>

</body>

</html>
