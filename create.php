<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$dancename = $origin = $characteristics = $musicgenre = "";
$dancename_err = $origin_err = $characteristics_err = $musicgenre_err = "";

// Processing form data when form is submitted  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Validate dancename
    $input_dancename = trim($_POST["dancename"]);
    if (empty($input_dancename)) {
        $dancename_err = "Please enter Artwork dancename.";
    } else {
        $dancename = $input_dancename;
    }

    // Validate origin
    $input_origin = trim($_POST["artistname"]);
    if (empty($input_origin)) {
        $origin_err = "Please enter name of origin.";
    } else {
        $origin = $input_origin; // Fixed variable assignment
    }

    // Validate characteristics
    $input_characteristics = trim($_POST["characteristics"]); // Corrected form field name
    if (empty($input_characteristics)) {
        $characteristics_err = "Please enter characteristics.";
    } else {
        $characteristics = $input_characteristics;
    }

    // Validate Music genre
    $input_musicgenre = trim($_POST["musicgenre"]);
    if (empty($input_musicgenre)) {
        $musicgenre_err = "Please enter the Music genre.";
    } else {
        $musicgenre = $input_musicgenre;
    }

    // Check input errors before inserting in database
    if (empty($dancename_err) && empty($origin_err) && empty($characteristics_err) && empty($musicgenre_err)) { // Fixed condition
        // Prepare an insert statement
        $sql = "INSERT INTO technology (dancename, origin, characteristics, musicgenre) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_dancename, $param_origin, $param_characteristics, $param_musicgenre); // Fixed parameter types

            // Set parameters
            $param_dancename = $dancename;
            $param_origin = $origin;
            $param_characteristics = $characteristics;
            $param_musicgenre = $musicgenre;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Informations created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <dancename>Create Record</dancename>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
           
   </style>
     
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Information</h2>
                    <p>Please fill this form and submit to add technology Information to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Dance Name</label>
                            <input type="text" name="dancename" class="form-control <?php echo (!empty($dancename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dancename; ?>">
                            <span class="invalid-feedback"><?php echo $dancename_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Origin</label>
                            <input type="text" name="artistname" class="form-control <?php echo (!empty($origin_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $origin; ?>">
                            <span class="invalid-feedback"><?php echo $origin_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Characteristics</label>
                            <input type="text" name="characteristics" class="form-control <?php echo (!empty($characteristics_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $characteristics; ?>">
                            <span class="invalid-feedback"><?php echo $characteristics_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Music genre</label>
                            <input type="text" name="musicgenre" class="form-control <?php echo (!empty($musicgenre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $musicgenre; ?>">
                            <span class="invalid-feedback"><?php echo $musicgenre_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
