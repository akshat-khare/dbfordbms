<!DOCTYPE html>
<html lang="en">
<?php include 'head.php' ?>

<body>
    <?php include 'navbar.php' ?>

    <header>
        <img src="./img/parliament-of-india.png" class="rounded mx-auto d-block figure" alt="Image for Parliament of India.">
    </header>

    <!-- Page Content -->
    <div class="container">
        <hr>
        <h1>Parliamentary Data Visualization</h1>
        <p>This is an application created using plain PHP (with some CSS sprinkled in) to demonstrate Parliamentary data.</p>
        
        <form method="post" action="mp.php">
            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group row">
                        <label for="inputDistrict" class="col-sm-2 col-form-label">District</label>
                        <div class="col-sm-10">
                            <input name="district" class="form-control col-sm-8" id="inputDistrict" placeholder="Enter District">
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

    <?php include 'footer.php' ?>

</body>

</html>
