<?php include 'head.php' ?>
<body>
<?php include 'navbar.php' ?>
<div class="container">

<code>
<?php    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if(isset($_POST['Submitmisc'])){ 
        $radioval = $_POST['typemisc'];
        if($radioval=="Law"){
            $db = pg_connect( "host=10.17.50.115 port=5432 dbname=group_13 user=group_13 password=205-265-669" );
            $totquery="
            select billt.year, numbills, numcrimes from 
                (select year, count(*) as numbills from govtbills where upper(ministry) like '%LAW%' group by year order by year) as billt
                ,
                (select year, sum(total_ipc_crimes)  as numcrimes from districtipcdata group by year order by year) as crimet
                where billt.year=crimet.year
            ;
            ";
            $result = pg_query($totquery);
            if(!$result){
                echo "Error<br>";
                echo $result."<br>";
                var_dump($result);
            }else{

            }

        }elseif ($radioval=="Agriculture") {
            # code...
            $db = pg_connect( "host=10.17.50.115 port=5432 dbname=group_13 user=group_13 password=205-265-669" );
            $totquery="
            select billt.year, numbills, prodsum, areasum from 
                (select year, count(*) as numbills from govtbills where upper(ministry) like '%AGRICULTURE%' group by year order by year) as billt
                ,
                (select year, sum(production) as prodsum, sum(area) as areasum from agriculture group by year order by year) as aggt
                where billt.year=aggt.year
            ;
            ";
            $result = pg_query($totquery);
            if(!$result){
                echo "Error<br>";
                echo $result."<br>";
                var_dump($result);
            }else{

            }
        }elseif ($radioval=="Other") {
            # code...
            $textinp = strtoupper($_POST['othertype']);
            if(!empty($textinp)){

                $db = pg_connect( "host=10.17.50.115 port=5432 dbname=group_13 user=group_13 password=205-265-669" );
                $totquery="
                select year, ministry, short_title_of_bill, status from govtbills where upper(ministry) like '%".$textinp."%' order by year,ministry, short_title_of_bill;
                ";
                $result=pg_query($totquery);
                if(!$result){
                    echo "Error<br>";
                    echo $result."<br>";
                    var_dump($result);
                }else{
    
                }
            }
        }
    }
?>

</code>
<div>
<?php
    if($radioval=="Law"){
        echo '<p>
                        Comparison for '.$radioval.' data is :
                    </p>
                    <table>
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Number of bills</th>
                                <th>Number of crimes</th>
                            </tr>
                        </thead>
                        <tbody>';
        while ($row = pg_fetch_array($result)) {
                            echo '<tr>
                                <td>'.$row['year'].'</td>
                                <td>'.$row['numbills'].'</td>
                                <td>'.$row['numcrimes'].'</td>
                            </tr>';
                    }
                    echo '</tbody>
                    </table> <br>';
        
    }elseif ($radioval=="Agriculture") {
        # code...
        echo '<p>
                        Comparison for '.$radioval.' data is :
                    </p>
                    <table>
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Number of bills</th>
                                <th>Agriculture Production</th>
                                <th>Cultivable Area</th>
                            </tr>
                        </thead>
                        <tbody>';
        while ($row = pg_fetch_array($result)) {
                            echo '<tr>
                                <td>'.$row['year'].'</td>
                                <td>'.$row['numbills'].'</td>
                                <td>'.$row['prodsum'].'</td>
                                <td>'.$row['areasum'].'</td>
                            </tr>';
                    }
                    echo '</tbody>
                    </table> <br>';
    }else if($radioval=="Other"){
        echo '<p>
                        Data for '.$radioval.':
                    </p>
                    <table>
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Ministry</th>
                                <th>Short title of bill</th>
                                <th>Status of bill</th>
                            </tr>
                        </thead>
                        <tbody>';
        while ($row = pg_fetch_array($result)) {
                            echo '<tr>
                                <td>'.$row['year'].'</td>
                                <td>'.$row['ministry'].'</td>
                                <td>'.$row['short_title_of_bill'].'</td>
                                <td>'.$row['status'].'</td>
                            </tr>';
                    }
                    echo '</tbody>
                    </table> <br>';
    }
?>
</div>

</div>

<?php include 'footer.php' ?>

</body>

</html>