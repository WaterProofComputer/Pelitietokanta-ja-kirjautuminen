<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Replace with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "video_game_database";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$title = $release_year = $genre = $company_id = "";
$title_err = $release_year_err = $genre_err = $company_id_err = "";

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize title
    $title = trim($_POST["title"]);
    if (empty($title)) {
        $title_err = "Please enter a title.";
    } else {
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    }

    // Validate release year
    $release_year = trim($_POST["release_year"]);
    if (empty($release_year) || !is_numeric($release_year)) {
        $release_year_err = "Please enter a valid release year.";
    }

    // Validate and sanitize genre
    $genre = trim($_POST["genre"]);
    if (empty($genre)) {
        $genre_err = "Please enter a genre.";
    } else {
        $genre = htmlspecialchars($genre, ENT_QUOTES, 'UTF-8');
    }

    // Validate and sanitize company ID
    $company_id = trim($_POST["company_id"]);
    if (empty($company_id) || !is_numeric($company_id)) {
        $company_id_err = "Please select a valid company.";
    }

    // Check if there are no errors before inserting into the database
    if (empty($title_err) && empty($release_year_err) && empty($genre_err) && empty($company_id_err)) {
        $insert_sql = "INSERT INTO video_games (title, release_year, genre, company_id) VALUES (?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($insert_sql)) {
            $stmt->bind_param("sisi", $title, $release_year, $genre, $company_id);
            
            if ($stmt->execute()) {
                echo "New game added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

// Retrieve the list of companies for the form
$companies_sql = "SELECT company_id, company_name FROM game_companies";
$companies_result = $conn->query($companies_sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Game</title>
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
        form {
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
        input[type="text"],
        input[type="number"],
        select {
            width: 80%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .button-container {
            display: flex;
            justify-content: space-between; /* Arrange items horizontally with space between them */
            margin-top: 20px; /* Added margin for space between dropdown and buttons */
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        /* CSS for Go to Games button */
        .goto-games button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        .goto-games button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Add New Game</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="title">Title:</label>
        <input type="text" name="title" required>
        <span class="error"><?php echo $title_err; ?></span><br>
        <label for="release_year">Release Year:</label>
        <input type="number" name="release_year" required>
        <span class="error"><?php echo $release_year_err; ?></span><br>
        <label for="genre">Genre:</label>
        <input type="text" name="genre" required>
        <span class="error"><?php echo $genre_err; ?></span><br>
        <label for="company_id">Company:</label>
        <select name="company_id" required>
            <option value="">-- Select a Company --</option>
            <?php
            if ($companies_result->num_rows > 0) {
                while ($row = $companies_result->fetch_assoc()) {
                    echo "<option value='{$row['company_id']}'>" . htmlspecialchars($row['company_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
            }
            ?>
        </select>
        <span class="error"><?php echo $company_id_err; ?></span><br>
        <div class="button-container">
            <input type="submit" value="Add Game">
            <a href="games.php" class="goto-games"><button type="button">Go to Games</button></a>
        </div>
    </form>
</body>
</html>
