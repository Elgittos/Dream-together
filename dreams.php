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
    $typedreams = $_POST["typedreams"];
    $ip = $_SERVER['REMOTE_ADDR'];

    // Check if form data is not empty
    if (!empty($name) && !empty($comment) && !empty($typedreams)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO dreams (name, comment, date, ip, typedream) VALUES (?, ?, NOW(), ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $stmt->bind_param("ssss", $name, $comment, $ip, $typedreams);

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
    <link href="ressources/css/dreams.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <h1>Tell us about your dreams</h1>
        <img class="logo" src="ressources/img/Picture3.jpg" alt="Logo">
        <ul class="nav-menu">
            <li class="navbutton"><a href="index.php">Home</a></li>
            <li class="navbutton"><a href="dreams.php">Dreams</a></li>
            <li class="navbutton"><a href="#">Dreams</a></li>
        </ul>
    </header>
    <main>
        <div class="yolo">
            <form action="dreams.php" method="post"><!-- The form tag creates an HTML form input, the action attribute tells which server side script will handle the form submission, in this case the dreams.php handles the form submision. The method attribute specifies which HTTP method to be used then sending form data, in this case I use POST because it sends the data as an HTTP request body, this makes it more suitable for sending large or sensitive information.-->


                <label for="name">Name:</label><!--Det her koder navnet på formen, dvs at her så bliver tekst feltet kaldt for name. The for attribute needs to match the id attribute of the unput field, so they are matched-->
                <input type="text" id="name" name="name" placeholder="Enter your name here" required><br><br><!--Det her er koden til selve tekst feltet. The id attribute needs to match the for attribute of the label tag to link them together. The name attribute if a key word that the server script looks for to find the data submisison-->


                <label for="comment">Dream:</label><!-- Just like the name label tag, this tag provides the name for the text area-->
                <textarea id="comment" name="comment" placeholder="Enter your dream here" required></textarea><br><br><!--Just like the input tag from before just with the difference being that instead of input tag the textarea tag provides a large amount of space to write-->


                <!--Her kommer den nye function der vil gøre det muligt for brugerne at vælge melle forskellige kategorier drømme-->
                <label for="typedreams">Category:</label><br>
                <select id="typedreams" name="typedreams" required><!--This tag creates a drobdown menu of which the option tags are the options contained within, in this case I have defined 4 options-->
                    <option value="" disabled selected>Select a category</option> <!-- This option is disabled and selected by default -->
                    <option value="Nightmare">Nightmare</option><!--The value attribute of the option tag is what is send to the server script or logic, this value is what is going to be stored in the database-->
                    <option value="Lucid">Lucid</option>
                    <option value="Recurring">Recurring</option>
                    <option value="Fantasy">Fantasy</option>
                </select>


                <input type="submit" value="Submit"><!--This input tag is defined by its type attributte which is submit, this will create a submit button that submits the data in the form that contains it. The value attibutte specifies the text which will appear on the button-->

            </form>
        </div> 

    <div class="dreambox">
        <div class="recurring">
            <h2 onclick="toggleContent('recurring-content')">Recurring Dreams Shared by Others</h2>

            <div id="recurring-content" class="content">
            <?php
            // Display comments
            $sql = "SELECT name, comment, DATE_FORMAT(date, '%Y-%m-%d %H:%i:%s') as formatted_date, typedream 
                    FROM dreams WHERE typedream = 'Recurring' 
                    ORDER BY date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='comment'>
                    <div class='comment-header'>
                        <span class='comment-name'>" . htmlspecialchars($row["name"]) . "</span>
                        <span class='comment-type-recurring'>" . htmlspecialchars($row["typedream"]) . "</span>
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
    ?>
           </div> 

        </div> 

        <div class="Nightmare">
            <h2>Nightmares Shared by Others</h2>
            <?php
            // Display comments
            $sql = "SELECT name, comment, DATE_FORMAT(date, '%Y-%m-%d %H:%i:%s') as formatted_date, typedream 
                    FROM dreams 
                    WHERE typedream = 'Nightmare'
                    ORDER BY date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='comment'>
                    <div class='comment-header'>
                        <span class='comment-name'>" . htmlspecialchars($row["name"]) . "</span>
                        <span class='comment-type-nightmare'>" . htmlspecialchars($row["typedream"]) . "</span>
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


            ?>
        </div>

        <div class="lucid">
            <h2>Lucid Dreams Shared by Others</h2>
            <?php
            // Display comments
            $sql = "SELECT name, comment, DATE_FORMAT(date, '%Y-%m-%d %H:%i:%s') as formatted_date, typedream 
                    FROM dreams 
                    WHERE typedream = 'lucid'
                    ORDER BY date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='comment'>
                    <div class='comment-header'>
                        <span class='comment-name'>" . htmlspecialchars($row["name"]) . "</span>
                        <span class='comment-type-lucid'>" . htmlspecialchars($row["typedream"]) . "</span>
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

            ?>
        </div> 

        <div class="fantasy">
            <h2>Fantasy Dreams Shared by Others</h2>
            <?php
            // Display comments
            $sql = "SELECT name, comment, DATE_FORMAT(date, '%Y-%m-%d %H:%i:%s') as formatted_date, typedream 
                    FROM dreams 
                    WHERE typedream = 'fantasy'
                    ORDER BY date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='comment'>
                    <div class='comment-header'>
                        <span class='comment-name'>" . htmlspecialchars($row["name"]) . "</span>
                        <span class='comment-type-fantasy'>" . htmlspecialchars($row["typedream"]) . "</span>
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
    
    <script>
        function toggleContent(id) {
            var content = document.getElementById(id);
            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    </script>

</body>
</html>
