<?php include 'head.php' ?>
<body>
<?php include 'navbar.php' ?>
<div class="container">
<code>
<?php    
if(isset($_POST['SubmitButtonQuery'])){ //check if form was submitted
  $input = $_POST['inputQuery']; //get input text
  echo "You are trying to execute: <br>".$input."<br>";
  $db = pg_connect( "host=10.17.50.115 port=5432 dbname=group_13 user=group_13 password=205-265-669" );
    $query = $input;
  $result = pg_query($query);
  if(!$result){
      echo "Error <br>";
  }else{
      echo "Success <br>";
  }
  unset($_POST);
}    
if(isset($_POST['Submitcandi'])){
    echo "Ready to insert <br>";
    $inputarr = array(
        $_POST['st_code'],
        $_POST['state_name'],
        $_POST['month'],
        $_POST['year'],
        $_POST['pc_number'],
        $_POST['pc_name'],
        $_POST['pc_type'],
        $_POST['candidate_name'],
        $_POST['candidate_sex'],
        $_POST['candidate_category'],
        $_POST['candidate_age'],
        $_POST['party_abbreviation'],
        $_POST['total_votes_polled'],
        $_POST['position']
    );
    $inputarrtype = array(
        0,
        0,
        1,
        1,
        1,
        0,
        0,
        0,
        0,
        0,
        1,
        0,
        1,
        1
    );
    // 0 is varchar 1 is int
    $inputarrlength = count($inputarr);
    $inputcheck=true;
    for($x=0;$x<$inputarrlength;$x++){
        if(empty($inputarr[$x])){
            $inputcheck=false;
            break;
        }
    }
    if($inputcheck){
        $db = pg_connect( "host=10.17.50.115 port=5432 dbname=group_13 user=group_13 password=205-265-669" );
        $insertquery = "INSERT INTO ls2009candi values (";
        for($x=0;$x<$inputarrlength;$x++){
            if($inputarrtype[$x]==0){
                $insertquery = $insertquery.("'$inputarr[$x]'");
            }else{
                $insertquery = $insertquery.($inputarr[$x]);
            }
            if($x!=$inputarrlength-1){
                $insertquery = $insertquery.", ";
            }else{
                $insertquery = $insertquery.")";
            }
        }
        echo "Executing query $insertquery <br>";
        $result = pg_query($insertquery);
        if(!$result){
            echo "Error<br> ";
            echo $result;
            var_dump($result);
            echo "<br>";
        }else{
            echo "Success<br> ";
            echo $result;
            var_dump($result);
            echo "<br>";
        }
    }
    unset($_POST);
}
if(isset($_POST['Submitagri'])){
    echo "Ready to insert <br>";
    $inputarr = array(
        $_POST['state'],
        $_POST['district'],
        $_POST['year'],
        $_POST['season'],
        $_POST['production'],
        $_POST['area']
    );
    $inputarrtype = array(
        0,
        0,
        1,
        0,
        1,
        1
    );
    // 0 is varchar 1 is int
    $inputarrlength = count($inputarr);
    $inputcheck=true;
    for($x=0;$x<$inputarrlength;$x++){
        if(empty($inputarr[$x])){
            $inputcheck=false;
            break;
        }
    }
    if($inputcheck){
        $db = pg_connect( "host=10.17.50.115 port=5432 dbname=group_13 user=group_13 password=205-265-669" );
        $insertquery = "INSERT INTO agriculture values (";
        for($x=0;$x<$inputarrlength;$x++){
            if($inputarrtype[$x]==0){
                $insertquery = $insertquery.("'$inputarr[$x]'");
            }else{
                $insertquery = $insertquery.($inputarr[$x]);
            }
            if($x!=$inputarrlength-1){
                $insertquery = $insertquery.", ";
            }else{
                $insertquery = $insertquery.")";
            }
        }
        echo "Executing query $insertquery <br>";
        $result = pg_query($insertquery);
        if(!$result){
            echo "Error<br> ";
            echo $result;
            var_dump($result);
            echo "<br>";
        }else{
            echo "Success<br> ";
            echo $result;
            var_dump($result);
            echo "<br>";
        }
    }
    unset($_POST);
}
if(isset($_POST['Submitattendupdate']) || isset($_POST['Submitattendadd'])){
    if(isset($_POST['Submitattendupdate'])){
        $typequery = 0;
    }else{
        $typequery = 1;
    }
    echo "Ready to insert <br>";
    $inputarr = array(

        $_POST['division'],

        $_POST['session'],

        $_POST['totalsittings'],
        $_POST['dayssigned']
    );
    $inputarrtype = array(

        1,

        1,

        1,
        1
    );
    $forminparrhelp = array(
  
        'division',

        'session',

        'totalsittings',
        'dayssigned'
    );
    // 0 is varchar 1 is int
    $inputarrlength = count($inputarr);
    $inputcheck=true;
    for($x=0;$x<$inputarrlength;$x++){
        if(empty($inputarr[$x])){
            $inputcheck=false;
            break;
        }
    }
    if($inputcheck){
        $db = pg_connect( "host=10.17.50.115 port=5432 dbname=group_13 user=group_13 password=205-265-669" );
        if($typequery==0){
            $insertquery = "UPDATE attendancedata set totalsittings = ".$inputarr[$inputarrlength-2].", dayssigned = ".$inputarr[$inputarrlength-1]." where ";
        }else{
            $insertquery = "UPDATE attendancedata set totalsittings = totalsittings + ".$inputarr[$inputarrlength-2].", dayssigned = dayssigned + ".$inputarr[$inputarrlength-1]." where ";
        }
        for($x=0;$x<$inputarrlength-2;$x++){
            if($inputarrtype[$x]==0){
                $insertquery = $insertquery."uppercase(".($forminparrhelp[$x]).") = uppercase('".($inputarr[$x])."') ";

            }else{
                $insertquery = $insertquery.($forminparrhelp[$x])." = ".($inputarr[$x])." ";
            }
            if($x!=$inputarrlength-3){
                $insertquery = $insertquery."and ";
            }else{
                $insertquery = $insertquery."";
            }
        }
        echo "Executing query $insertquery <br>";
        $result = pg_query($insertquery);
        if(!$result){
            echo "Error<br> ";
            echo $result;
            var_dump($result);
            echo "<br>";
        }else{
            echo "Success<br> ";
            echo $result;
            var_dump($result);
            echo "<br>";
        }
    }
    unset($_POST);
}
?>
</code>


<!-- main form part now -->
    <div>
            Reload Button <br>
            <form action="" method="post">
            <input type="submit" name="Reload" value="Reload">
            </form>
    </div>

    <div>
        Execute query form <br>
        <form action="" method="post">
        <?php echo $message; ?>
        <input type="text" name="inputQuery" style="width:80%; height:4rem"/>
        <input type="submit" name="SubmitButtonQuery" value="Query"/>
        </form>  
   </div> 
    <div>
    <form action="" method="post"> 
        Add to ls2009candi table <br>
        <?php
            $forminparr = array(
                "st_code",
                "state_name",
                "month",
                "year",
                "pc_number",
                "pc_name",
                "pc_type",
                "candidate_name",
                "candidate_sex",
                "candidate_category",
                "candidate_age",
                "party_abbreviation",
                "total_votes_polled",
                "position"
            );
            $forminparrlen = count($forminparr);
            for($x=0;$x<$forminparrlen;$x++){
                echo "<input type='text' name='$forminparr[$x]' placeholder='$forminparr[$x]'/>";
            }
        ?>
        <input type="submit" name="Submitcandi" value="Candidate"/>
    </form>
    </div>
            <div>
    <form action="" method="post">
            Add into agriculture table<br>
        <?php
            $forminparr = array(
                "state",
                "district",
                "year",
                "season",
                "production",
                "area"
            );
            $forminparrlen = count($forminparr);
            for($x=0;$x<$forminparrlen;$x++){
                echo "<input type='text' name='$forminparr[$x]' placeholder='$forminparr[$x]'/>";
            }
        ?>
        <input type="submit" name="Submitagri" value="Agriculture"/>
    

    </form>
    </div>
    <div>
    <form action="" method="post">
            Update/Add into attendance table<br>
        <?php
            $forminparr = array(

                "division",

                "session",

                "totalsittings",
                "dayssigned"
            );
            $forminparrlen = count($forminparr);
            for($x=0;$x<$forminparrlen;$x++){
                echo "<input type='text' name='$forminparr[$x]' placeholder='$forminparr[$x]'/>";
            }
        ?>
        <input type="submit" name="Submitattendupdate" value="Update Attendance"/>
        <input type="submit" name="Submitattendadd" value="Add Attendance"/>
    

    </form>
    </div>
    <?php include 'footer.php' ?>
</body>
