<!DOCTYPE html>
<html>
<head>
    <title>Game Companies and Their Games</title>
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
        h2 {
            background-color: #444;
            color: #fff;
            padding: 8px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #fff;
            padding: 5px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        p {
            background-color: #fff;
            padding: 5px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .welcome-button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none; /* Remove default link underline */
            display: block;
            width: 150px; /* Adjust the width as needed */
            margin: 20px auto; /* Center the button horizontally */
            text-align: center;
        }

        .welcome-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <?php
    // Initialize the session
    session_start();
 
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
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

    // Retrieve the list of companies and their games
    $sql = "SELECT company_id, company_name FROM game_companies";
    $result = $conn->query($sql);

    // HTML for displaying companies and their games
    echo "<h1>Game Companies and Their Games</h1>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h2>{$row['company_name']}</h2>";

            // Retrieve games for the current company
            $games_sql = "SELECT title, release_year, genre FROM video_games WHERE company_id = {$row['company_id']}";
            $games_result = $conn->query($games_sql);

            if ($games_result->num_rows > 0) {
                echo "<ul>";
                while ($game_row = $games_result->fetch_assoc()) {
                    echo "<li>{$game_row['title']} ({$game_row['release_year']}) - {$game_row['genre']}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No games found for this company.</p>";
            }
        }
    } else {
        echo "<p>No companies found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
 <a href="welcome.php" class="welcome-button">Go to Welcome Page</a>
</body>
</html>
