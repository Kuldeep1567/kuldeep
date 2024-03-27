<?php
// Include config file
require_once "config.php"; 

// Define variables and initialize with empty values
$dancename = $origin = $characteristics = $musicgenre = "";
$dancename_err = $origin_err = $characteristics_err = $musicgenre_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

     // Validate dancename
     $input_dancename = trim($_POST["dancename"]);
     if (empty($input_dancename)) {
         $dancename_err = "Please enter the dancename.";
     } else {
         $dancename = $input_dancename;
     }

    // Validate origin
    $input_origin = trim($_POST["origin"]);
    if (empty($input_origin)) {
        $origin_err = "Please enter an origin.";
    } else {
        $origin = $input_origin;
    }

    // Validate characteristics
    $input_characteristics = trim($_POST["characteristics"]);
    if (empty($input_characteristics)) {
        $characteristics_err = "Please enter the characteristics.";
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
    if (empty($dancename_err) && empty($origin_err) && empty($characteristics_err) && empty($musicgenre_err)) {
        // Prepare an update statement
$sql = "UPDATE folkdances SET dancename=?, origin=?, characteristics=?, musicgenre=? WHERE id=?";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssi", $param_dancename, $param_origin, $param_characteristics, $param_musicgenre, $param_id);

    // Set parameters
    $param_dancename = $dancename;
    $param_origin = $origin;
    $param_characteristics = $characteristics;
    $param_musicgenre = $musicgenre;
    $param_id = $id;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM folkdances WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $dancename = $row["dancename"];
                    $origin = $row["origin"];
                    $characteristics = $row["characteristics"];
                    $musicgenre = $row["musicgenre"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <dancename>Update Record</dancename>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the folkdances Record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>dancename</label>
                            <input type="text" name="dancename" class="form-control <?php echo (!empty($dancename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dancename; ?>">
                            <span class="invalid-feedback"><?php echo $dancename_err; ?></span>
                        </div>   
                    <div class="form-group">
                            <label>origin</label>
                            <input type="text" name="origin" class="form-control <?php echo (!empty($origin_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $origin; ?>">
                            <span class="invalid-feedback"><?php echo $origin_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>characteristics</label>
                            <input type="text" name="characteristics" class="form-control <?php echo (!empty($characteristics_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $characteristics; ?>">
                            <span class="invalid-feedback"><?php echo $characteristics_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Music genre</label>
                            <input type="text" name="musicgenre" class="form-control <?php echo (!empty($musicgenre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $musicgenre; ?>">
                            <span class="invalid-feedback"><?php echo $musicgenre_err; ?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>