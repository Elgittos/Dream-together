<?php
require 'vendor/autoload.php'; // Include Composer's autoload file


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $ip = $_SERVER['REMOTE_ADDR'];
 
    // Check if form data is not empty
    if (!empty($name) && !empty($comment)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO dreams (name, comment, date, ip) VALUES (?, ?, NOW(), ?)");
        if ($stmt === false) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        
        $stmt->bind_param("sss", $name, $comment, $ip);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the same page to avoid form resubmission
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Please fill out both fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dreams</title>
    <link href="ressources/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <h1>Tell us about your dreams YOLOOOOOO</h1>
        <img class="logo" src="ressources/img/Picture3.jpg" alt="Logo">
        <ul>
            <li class="navbutton"><a href="index.php">Home</a></li>
            <li class="navbutton"><a href="dreams.php">Dreams</a></li>
            <li class="navbutton"><a href="#">Dreams</a></li>
        </ul>
    </header>
    <main>
        <div>
            <form action="dreams.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <br><br>
                <label for="comment">Dream:</label>
                <textarea id="comment" name="comment" required></textarea>
                <br><br>
                <input type="submit" value="Submit">
            </form>
        </div> 

        <div>
            <h2>Dreams Shared by Others:</h2>
            <?php
            // Display comments
            $sql = "SELECT name, comment, date FROM dreams ORDER BY date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<p><strong>" . htmlspecialchars($row["name"]) . "</strong>: " . htmlspecialchars($row["comment"]) . " <em>on " . $row["date"] . "</em></p>";
                }
            } else {
                echo "<p>No comments yet!</p>";
            }

            $conn->close();
            ?>
        </div>
    </main>
    <footer></footer>
</body>
</html>
