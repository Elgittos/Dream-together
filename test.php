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
    <title>Dream together</title>
    <link href="ressources/css/test.css" rel="stylesheet" type="text/css">
</head>
<body>

    <header>
        <h1>
            <img src="ressources/img/dream-small.png">
            <img src="ressources/img/together-small.png">
        </h1>
        <img class="logo" src="ressources/img/Logo.png">
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
    <div class="text1">
        <figure>
            <img src="ressources/img/Brian-Gyson.png" class="brian-gyson">
            <figcaption>Brion Gysin (1916-1986) British-Canadian painter, writer, sound poet, performance artist and inventor of experimental devices. </figcaption>
        </figure>
        <div class="introduction">
            <h2>INTRODUCTION</h2>
            <p>
            Inspired by the flickering lights of fire, embers pulsating, the starlit night sky, flames and shadows dancing over faces, and the stories told and shared by the bonfire, we wish to bring people together around our light installation at Roskilde Festival.<br><br>
            
Our intention with the installation is to create space and time to come together and reflect on the relationships, and ethical inter-dependency that entangles us with humans and more-than-humans alike.How are we responsible for ourselves and others, and how can we dream together in solidarity to bring forth change that benefits us and the world we share?<br><br>

At Roskilde Festival we want to examine if the installation offers festival goers a break from the hyper social context of huge masses of people, while at the same time bringing people together to reflect on how to establish sustainable connections with themselves and others. Furthermore, the installation offers participants new ways of relating to and reflecting on entangled dreams of the past/present/future, and the intimacy, conversations, and presence afforded and mediated by the flickering lights during nighttime.
            </p>
        </div>
    </div>

    <div class="text2">
        <div class="fireplace">
            <h2>THE FIREPLACE</h2>
            <p>
            Control of fire as a technology changed human society and our relationship with more-than-humans. Our ancestor’s anatomy changed, new diets and cooking practice impacted gut volume and brain size. Beyond these impacts, the mastery of fire also afforded new ways of relating to one another and the surrounding world. Hours of light were extended by the fire during nighttime, providing safety, and creating possibilities for different social exchanges by the firelight.<br><br>

In her study <span class="italics">‘Embers of society: Firelight talk among the Ju/’hoansi Bushmen’</span> Polly W. Wiessner (2014) explores the differences between conversation topics during day- and nighttime of the Ju/’hoansi tribes of Botswana and Namibia. Based on her anthropological inquiry, Wiessner hypothesizes that our ancestors’ social interactions by the fire at night awakened the imagination of the supernatural. The accentuated facial expressions by the fire, either softened or hardened by the flickering flames, created a new space and time for relations. Wiessner proposes that the firelit nighttime hours gave context for understanding thoughts and emotions of others, as well as bonding within the group. Further, the nighttime conversations by the fire constituted generation, regulation and transmission of cultural institutions. Can our design emulate these notions, and can our collective dreams and stories impact our cultural institutions?

            </p>
        </div>
    </div>
    <div class="text3">
        <div class="dream-machine-info">
            <h2>THE DREAM MACHINE</h2>
            <p>The original Dreamachine was devised by artist Brion Gysin. He was inspired by Ian Sommerville’s <span class="italics">Flicker machine</span> and the effect created when driving fast past trees with sunlight behind them, flickering rapidly and creating an altered state of consciousness. The Dreamachine has since been used as a drug-free way of entering areas of one’s own unconscious Dream State. The Dreamachine is designed to be looked at with closed eyes, with the intention of opening the inner vistas for travel and exploration. It’s a machine for dreams and it can be built by anyone, anywhere with the instructions freely available online.<br><br>

<span class="italics">“With the current vogue for high-technology brain-machines at an unparalleled height, the original concept (...) is a welcome reminder that whilst technology advances apace, the conceptual base of interior research is as ancient as the Sun and the trees. Its beauty is in its simplicity, and in its ecologically sound construction which requires no more than a sheet of card and the recycling of a redundant record player”</span> (Temple Press, 1992, p. 1-2).<br><br>

The Dreamachine invites the user to immerse themselves in dreams and visions brought forth when looking at the lamp close to its center with closed eyes. The flickering intensity is said to make the user more awake and aware of the world around them after use. It’s designed not to make people <span class="italics">‘sleep’</span> but to make them more <span class="italics">‘aware’.</span> Can we use the Dreamachine design to make people at Roskilde Festival more aware of their connectedness with themselves, others, and the world?
            </p> 
        </div>
    </div>

    <div class="howto">
            <h2>HOW TO DREAM</h2>
            <ol>
                <li>Relax. Breathe.</li>
                <li>Position yourself close to the rotating cylinder.</li>
                <li>Close your eyes.</li>
                <li>Look deep into the Dreamachine.</li>
                <li>Relax and breathe as you get used to the flickering.</li>
                <li>Stay relaxed, with eyes closed.</li>
                <li>Immerse yourself for as long as possible.</li>
                <li>Share your experience and internal visions.</li>
            </ol>
            <p>At least 15 minutes of immersion with closed eyes is recommended to lucid dream. These instructions are recommendations. Do not do anything that makes you feel uncomfortable.</p>

        </div>

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



    <footer>
        <div class="logo">
            <a href="https://fablab.ruc.dk/" target="_blank"><img src="ressources/img/fablablogo.png"></a>
            <a href="https://www.roskilde-festival.dk/" target="_blank"><img src="ressources/img/rf24logo2.png"></a>
            <a href="https://ruc.dk/" target="_blank"><img src="ressources/img/ruclogo.png"></a>
        </div>
        <div class="text">
            <p>&copy; 2024 Dreamtogether.online. All rights reserved.</p>
            <p>This site is part of the Dream Together project and not intended for commercial use.</p>
            <p>Any content shared here is free for non-commercial purposes.</p>
            <p>Contact: luis@dreamtogether.online</p>
        </div>
    </footer>
</body>
</html>