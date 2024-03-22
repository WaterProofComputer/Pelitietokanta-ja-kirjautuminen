<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Define database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "video_game_database";

// Initialize variables
$game_id = "";
$game_id_err = "";

// Establish a database connection
$link = mysqli_connect($servername, $username, $password, $dbname);

// Check the database connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve the list of video games from the database
$sql = "SELECT game_id, title FROM video_games";
$result = mysqli_query($link, $sql);

// Process the form submission when the delete button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate game ID
    if (empty(trim($_POST["game_id"]))) {
        $game_id_err = "Please select the Game to delete.";
    } else {
        $game_id = trim($_POST["game_id"]);
    }

    // Check if there are no errors before deleting the record
    if (empty($game_id_err)) {
        // Prepare a delete statement
        $sql = "DELETE FROM video_games WHERE game_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_game_id);

            // Set parameters
            $param_game_id = $game_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Game deleted successfully, you can redirect to a success page if needed
                header("location: games.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Video Game</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        .wrapper {
            width: 300px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .btn-primary {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        .btn-primary:hover {
            background-color: #555;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: #fff;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .btn-back {
            background-color: #6c757d;
            border: none;
            color: #fff;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        .btn-back:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <h1>Delete Video Game</h1>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Select Game</label>
                <select name="game_id" class="form-control <?php echo (!empty($game_id_err)) ? 'is-invalid' : ''; ?>">
                    <option value="">-- Select a Game --</option>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . $row['game_id'] . "'>" . $row['title'] . "</option>";
                    }
                    ?>
                </select>
                <span class="invalid-feedback"><?php echo $game_id_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Delete">
                <a href="games.php" class="btn btn-secondary">Back to Games</a>
            </div>
        </form>
    </div>
</body>
</html>
