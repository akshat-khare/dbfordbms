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

        $district = "";
        $choice = 0;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["district"])) {
                $nameErr = "District name is required";
            } else {
                $district = strtoupper(test_input($_POST["district"]));
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z ]*$/",$district)) {
                    $nameErr = "Only letters and white space allowed";
                }
                
                // connect to database
                $db = pg_connect( "host=10.17.50.115 port=5432 dbname=project1 user=group_13 password=205-265-669" );

                if (!empty($_GET["choice"])) {
                    $choice = $_GET["choice"];
                }

                if ($choice == 1) {
                    // Get the districts from the keyword
                    $querydistrict = "SELECT DISTINCT state, dist FROM distoconsti WHERE upper(distoconsti.dist) like '%$district%'";
                    $choosedistrict = pg_query($querydistrict);
                } elseif ($choice == 2) {
                    // Give the queries for the Selected District
                    $queryseasons = "SELECT DISTINCT season FROM agriculture";
                    $querycolumnsipc = "select column_name from information_schema.columns where table_name='districtipcdata' and column_name<>'year' and column_name<>'state_or_ut' and column_name<>'total_ipc_crimes' and column_name<>'district';";
                    $queryconsti = "SELECT candidate_name, party_abbreviation, total_votes_polled, pc_name
                                    FROM (SELECT pc FROM distoconsti
                                    WHERE UPPER(dist)='$district') AS consti, ls2009candi 
                                    WHERE '%' || UPPER(pc) || '%' like '%' || UPPER(pc_name) || '%'
                                    ORDER BY pc_name, position;";
                    $queryagri = "SELECT year, sum(production) FROM agriculture
                                    WHERE UPPER(district)='$district' AND year > 2003 GROUP BY year ORDER BY year";
                    $querytotalipc = "SELECT year, total_ipc_crimes FROM districtipcdata
                                    WHERE UPPER(district)='$district' ORDER BY year";
                    $seasons = pg_query($queryseasons);
                    $columnsipc = pg_query($querycolumnsipc);
                    $consti = pg_query($queryconsti);
                    $agri = pg_query($queryagri);
                    $totalipc = pg_query($querytotalipc);
                }
            }
        }
    ?>

    <!-- Page Content -->
    <div class="container">
        <hr>
        <h1>Parliamentary Data Visualization</h1>
        <p>This is an application created using plain PHP (with some CSS sprinkled in) to demonstrate Parliamentary data.</p>

        <form method="post" action="district.php?choice=1">
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group row">
                        <label for="inputDistrict" class="col-sm-2 col-form-label">District</label>
                        <div class="col-sm-10">
                            <input name="district" value="<?php echo $district; ?>" class="form-control col-sm-8" id="inputDistrict" placeholder="Enter District">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Know Your District</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <?php
            if ($choice == 1) {
                echo 'The following matches were found for the given search keyword. Please choose the desired district. ';
                echo '<form action="district.php?choice=2" method="post">
                        <fieldset class="form-group">
                            <div class="row">
                                <div class="col-sm-10">';
                while ($row = pg_fetch_array($choosedistrict)) {
                    echo '<div class="form-check">
                            <input class="form-check-input" type="radio" name="district" id='.$row['dist'].' value="'.$row['dist'].'" checked>
                            <label class="form-check-label" for="'.$row['dist'].'">
                                '.strtoupper($row['state']).'   ----   '.strtoupper($row['dist']).'
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
            } elseif ($choice == 2) {
                // Data for Agriculture and Crime Statistics ?>
                <hr>
                <u><h3><?php echo $district; ?></h3></u>

                <div class="candidates">
                    <?php
                        // Display data for the candidates of elections.
                        $constiname = "";
                        $newconsti = 0;
                        echo '<strong>Candidates\' data for the Constituencies that overlap with the District in the General Elections 2009:</strong><br>';
                        echo '<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">';

                        while ($row = pg_fetch_array($consti)) {
                            if ($newconsti == 1) {
                                $newconsti = 0;
                            }
                            if ($constiname == "") {
                                $constiname = $row['pc_name'];
                                echo '<li class="nav-item">
                                        <a class="nav-link active" id="pills-'.$constiname.'-tab" data-toggle="pill" href="#pills-'.$constiname.'" role="tab" aria-controls="pills-'.$constiname.'" aria-selected="true">'.$constiname.'</a>
                                    </li>';
                            } elseif ($constiname != $row['pc_name']) {
                                $newconsti = 1;
                                $constiname = $row['pc_name'];
                                echo '<li class="nav-item">
                                        <a class="nav-link" id="pills-'.$constiname.'-tab" data-toggle="pill" href="#pills-'.$constiname.'" role="tab" aria-controls="pills-'.$constiname.'" aria-selected="true">'.$constiname.'</a>
                                    </li>';
                            }   
                        }
                        echo '</ul>
                        <div class="tab-content" id="pills-tabContent">';

                        $constiname = "";
                        $newconsti = 0;
                        pg_result_seek($consti, 0);

                        while ($row = pg_fetch_array($consti)) {
                            if ($newconsti == 1) {
                                $newconsti = 0;
                            }
                            if ($constiname == "") {
                                $constiname = $row['pc_name'];
                                echo '<div class="tab-pane fade show active" id="pills-'.$constiname.'" role="tabpanel" aria-labelledby="pills-'.$constiname.'-tab">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Candidate</th>
                                            <th>Party</th>
                                            <th>Total Votes Polled</th>
                                        </tr>
                                    </thead>
                                <tbody>';
                            } elseif ($constiname != $row['pc_name']) {
                                $newconsti = 1;
                                $constiname = $row['pc_name'];
                                echo '</tbody>
                                </table></div>'.'<div class="tab-pane fade" id="pills-'.$constiname.'" role="tabpanel" aria-labelledby="pills-'.$constiname.'-tab">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Candidate</th>
                                            <th>Party</th>
                                            <th>Total Votes Polled</th>
                                        </tr>
                                    </thead>
                                <tbody>';
                            } 
                            echo '<tr>
                                    <td>'.$row['candidate_name'].'</td>
                                    <td>'.$row['party_abbreviation'].'</td>
                                    <td>'.$row['total_votes_polled'].'</td>
                                </tr>';
                        }
                        echo '</tbody></table></div></div><br>';
                ?>
            </div>

            <p><strong>Compiled Agricultural Produce for the District:</strong></p>
            <table>
                <thead>
                    <tr>
                        <th>Year</th>
                        <?php while ($row = pg_fetch_array($agri)) {?>
                            <th> <?php echo $row['year'] ?> </th>
                        <?php }?>
                    </tr>
                </thead>
                <?php pg_result_seek($agri, 0); ?>
                <tbody>
                    <tr>
                        <td>Production</td>
                        <?php while ($row = pg_fetch_array($agri)) {?>
                            <td> <?php echo $row['sum']; ?> </td>
                        <?php }?>
                    </tr>
                </tbody>
            </table>
            <br>
            <p><strong>Total Crime Statistics for the District:</strong></p>
            <table>
                <thead>
                    <tr>
                        <th>Year</th>
                        <?php while ($row = pg_fetch_array($totalipc)) {?>
                            <th> <?php echo $row['year'] ?> </th>
                        <?php }?>
                    </tr>
                </thead>
                <?php pg_result_seek($totalipc, 0); ?>
                <tbody>
                    <tr>
                        <td>Total IPC Crimes Registered</td>
                        <?php while ($row = pg_fetch_array($totalipc)) {?>
                            <td> <?php echo $row['total_ipc_crimes']; ?> </td>
                        <?php }?>
                    </tr>
                </tbody>
            </table>
        
            <br>
            <br>

            <!-- Comprehensive Agricultural data -->
            <p><strong>Comprehensive season-wise Agricultural Production Data for the District:</strong></p>
            <div class="row">   
                <div class="col-sm-4">
                    <!-- Form for seasons selected -->
                    <form action="district.php?choice=2" method="post">
                        <?php 

                        // Give the details for the selected seasons
                        // $chosen = $_POST['chosen_seasons'];
                        // for($i=0; $i < count($chosen); $i++){
                        //     echo "Selected " . $chosen[$i] . "<br/>";
                        // }
                        while ($row = pg_fetch_array($seasons)) { ?>
                            <div class="form-group row">
                                    <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="grid-<?php echo $row['season']; ?>" name="chosen_seasons[]" value="<?php echo $row['season']; ?>" <?php if (in_array($row['season'], $_POST['chosen_seasons'])) echo "checked='checked'"; ?> >
                                        <label class="form-check-label" for="grid-<?php echo $row['season']; ?>">
                                            <?php echo $row['season']; ?>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <input type="hidden" value="<?php echo $district; ?>" name="district">
                        <div class="form-group row">
                            <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-8">
                    <!-- The details -->
                    <?php
                        $chosen = $_POST['chosen_seasons'];
                        $queryselectedseasons = "SELECT year, sum(production) FROM agriculture WHERE UPPER(district)='$district' AND year > 2003 AND (";
                        for ($i = 0; $i < count($chosen); $i++) {
                            if ($i == 0) {
                                $queryselectedseasons = $queryselectedseasons." trim(UPPER(season)) = '".trim(strtoupper($chosen[$i]))."'";
                            } else {
                                $queryselectedseasons = $queryselectedseasons." OR trim(UPPER(season)) = '".trim(strtoupper($chosen[$i]))."'";
                            }
                        }
                        $queryselectedseasons = $queryselectedseasons.") GROUP BY year";
                        $selectedseasons = pg_query($queryselectedseasons);

                        echo '<table>
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Total Production</th>
                                </tr>
                            </thead>
                            <tbody>';
                        while ($row = pg_fetch_array($selectedseasons)) {
                                echo '<tr>
                                    <td>'.$row['year'].'</td>
                                    <td>'.$row['sum'].'</td>
                                </tr>';
                        }
                        echo '</tbody>
                        </table> <br>';
                    ?>
                </div>
            </div>

<!-- Comprehensive Crime data -->
            <p><strong>Comprehensive crime-wise IPC Data for the District:</strong></p>
            <div class="row">   
                <div class="col-sm-4">
                    <!-- Form for seasons selected -->
                    <form action="district.php?choice=2" method="post">
                        <?php 

                        // Give the details for the selected seasons
                        // $chosen = $_POST['chosen_seasons'];
                        // for($i=0; $i < count($chosen); $i++){
                        //     echo "Selected " . $chosen[$i] . "<br/>";
                        // }
                        while ($row = pg_fetch_array($columnsipc)) { ?>
                            <div class="form-group row">
                                    <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="grid-<?php echo $row['column_name']; ?>" name="chosen_ipctype[]" value="<?php echo $row['column_name']; ?>" <?php if (in_array($row['column_name'], $_POST['chosen_ipctype'])) echo "checked='checked'"; ?> >
                                        <label class="form-check-label" for="grid-<?php echo $row['column_name']; ?>">
                                            <?php echo $row['column_name']; ?>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <input type="hidden" value="<?php echo $district; ?>" name="district">
                        <div class="form-group row">
                            <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-8">
                    <!-- The details -->
                    <?php
                        $chosen = $_POST['chosen_ipctype'];
                        $countchosen = count($chosen);
                        $queryselectedipc = "SELECT year, ";
                        for($x=0;$x<$countchosen;$x++){
                            if($x!=$countchosen-1){
                                $queryselectedipc=$queryselectedipc."sum($chosen[$x]) +";
                            }else{
                                $queryselectedipc=$queryselectedipc."sum($chosen[$x]) as sumcol";
                            }
                            
                        }
                        $queryselectedipc=$queryselectedipc." from districtipcdata where UPPER(district)=trim(upper('$district')) group by year order by year";
                        
                        $resultq = pg_query($queryselectedipc);
                        

                        echo '<table>
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Aggregate selected total crimes</th>
                                </tr>
                            </thead>
                            <tbody>';
                        while ($row = pg_fetch_array($resultq)) {
                                echo '<tr>
                                    <td>'.$row['year'].'</td>
                                    <td>'.$row['sumcol'].'</td>
                                </tr>';
                        }
                        echo '</tbody>
                        </table> <br>';
                    ?>
                </div>
            </div>
        <?php } ?>


            

    </div>



    <?php include 'footer.php' ?>

</body>

</html>
