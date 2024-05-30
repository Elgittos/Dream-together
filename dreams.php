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

$error_message = ""; // Initialize error message variable

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
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Please fill out both fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dreams</title>
    <link href="ressources/css/dreams.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <h1>
            <img src="ressources/img/dream-small.png">
            <img src="ressources/img/together-small.png">
        </h1>
        <img class="logo" src="ressources/img/Logo.png" alt="Logo">
        <ul class="nav-menu">
            <li class="navbutton"><a href="index.php">Home</a></li>
            <li class="navbutton"><a href="dreams.php">Dreams</a></li>
            <li class="navbutton"><a href="howto.php">How to</a></li>
        </ul>
    </header>
    <div class="dream-machine">
        <h2>
            <img src="ressources/img/dream-small.png">
            <img src="ressources/img/together-small.png">
        </h2>
    </div>
    <main>
        <div class="formen">
            <?php
            if (!empty($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form action="dreams.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name here" required><br><br>
                <label for="comment">What did you experience?</label>
                <textarea id="comment" name="comment" placeholder="Express your experience" required></textarea><br><br>
                <input type="submit" value="Submit">
            </form>
        </div> 

        <div class="dreambox">
            <div class="dream">
                <h2>Experiences Shared by Others</h2>

                <?php
                // Display comments
                $sql = "SELECT name, comment, DATE_FORMAT(date, '%Y-%m-%d %H:%i:%s') as formatted_date
                        FROM dreams
                        ORDER BY date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo 
                        "<div class='comment'>
                            <div class='comment-header'>
                                <span class='comment-name'>" . htmlspecialchars($row["name"]) . "</span>  
                            </div>
                            <div class='comment-body'>
                                <p>" . htmlspecialchars($row["comment"]) . "</p>
                            </div>
                            <div class='comment-footer'>
                                <span class='comment-date'>" . htmlspecialchars($row["formatted_date"]) . "</span>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p>No comments yet!</p>";
                }

                $conn->close();
                ?>
            </div> 
        </div>
    </main>
    <footer></footer>
</body>
</html>
