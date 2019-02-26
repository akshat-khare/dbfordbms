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