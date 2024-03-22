<!DOCTYPE html>
<html>
<head>
    <title>Add Game Company</title>
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
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        a.button {
            display: block;
            margin-top: 10px;
            margin-left: auto;
            margin-right: auto;
            text-decoration: none;
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            text-align: center;
            width: 30%;

        }

        a.button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Add New Game Company</h1>
    <?php
    // Initialize the session
    session_start();
 
    // Check if the user is logged in, if not then redirect him to login page
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
    $company_name = $country = "";
    $company_name_err = $country_err = "";

    // Process the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and sanitize company name
        $company_name = trim($_POST["company_name"]);
        if (empty($company_name)) {
            $company_name_err = "Please enter a company name.";
        } else {
            $company_name = htmlspecialchars($company_name, ENT_QUOTES, 'UTF-8');
        }

        // Validate and sanitize country
        $country = trim($_POST["country"]);
        if (empty($country)) {
            $country_err = "Please enter a country.";
        } else {
            $country = htmlspecialchars($country, ENT_QUOTES, 'UTF-8');
        }

        // Check if there are no errors before inserting into the database
        if (empty($company_name_err) && empty($country_err)) {
            $insert_sql = "INSERT INTO game_companies (company_name, country) VALUES (?, ?)";
            
            if ($stmt = $conn->prepare($insert_sql)) {
                $stmt->bind_param("ss", $company_name, $country);
                
                if ($stmt->execute()) {
                    echo "<p>New game company added successfully!</p>";
                    echo "<a href='company_list.php'>Return to Company List</a>";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }

    // Close the database connection
    $conn->close();
    ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" required><br>
        <label for="country">Country:</label>
        <input type="text" name="country" required><br>
        <input type="submit" value="Add Company">
    </form>
    
    <!-- Button to go to games.php -->
    <a href="games.php" class="button">Go to Games</a>
</body>
</html>
