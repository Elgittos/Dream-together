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
    <title>Dream Together</title>
    <link href="ressources/css/newindex.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header class="grid-container">  
        <div class="nav-menu">
            <nav>
            <a href="#share">Share</a> 
                <a href="#start">Welcome</a>
                <a href="#how-to">How-to</a>
                <a href="#info">Info</a>
                <a href="#experiences">Experiences</a>
            </nav>
        </div>
        <div class="head">
            <figure class="logo">
                <img src="ressources/img/Logo.png" alt="logo">
            </figure>
            <div class="main-title">
                <img src="ressources/img/dream-small.png" alt="dream-small" class="dream-small">
                <img src="ressources/img/together-small.png" alt="together-small" class="together-small">
            </div>
        </div>
    </header>
    <main class="grid-container">
        <div class="moving-banner-container">
            <div class="moving-banner">
                <img src="ressources/img/dream-small.png" alt="dream-small" class="dream-small">
                <img src="ressources/img/together-small.png" alt="together-small" class="together-small">
            </div>
        </div>
        <div id="start" class="hero-section">
            <!--<figure>
                <img src="ressources/img/Enhanced_Picture3.jpg" class="hero-img">
            </figure>-->
            <div class="introduction">
                <h2>
                    Welcome to Dream Together Online
                </h2>
                <h3>     
                     Have you ever wondered how shared dream experiences impact our connections with others and the world?
                </h3>
                <h4>
                    Dream Together invites us to explore this question and immerse ourselves in its pulsating lights. How can shared dream experiences foster a sense of community and understanding?
                </h4>
                <p> 
                     Step into a space where light, shadow, art and technology converge to alter consciousness to be shared with others at Roskilde Festival 2024. Inspired by <span>Brion Gysin’s</span> Dreamachine and the transformative effects of fire, the installation uses Flicker Light Stimulation to induce a dream-like state, allowing participants to experience mild hallucinations and heightened sensory perception. This unique interplay of light and imagination is designed to evoke introspection, empathy and a deeper connection with the human and more-than-human entities around us.
                </p>
                <p class="warning">
                    WARNING: This installation may potentially trigger seizures for people with photosensitive epilepsy. Interaction discretion is advised.
                </p>
            </div>
        </div>  
        <div id="how-to" class="howto">
            <div class="instructions">
                <h2>HOW TO DREAM</h2>
                <h3>
                    Welcome, Dreamer. Here is how you can fully immerse yourself in the Dream Together experience:
                </h3>
                <ol>
                    <li><span>Relax and breathe</span> – Take a deep breath, center yourself and take this opportunity to dive into your dreams.</li>
                    <li><span>Position yourself</span> – Come in close, experiment with different distances, find a comfortable position within 5-15 cm of the cylinder.</li>
                    <li><span>Close your eyes</span> – Slowly, let go of the world around you. It will still be here when you return.</li>
                    <li><span>Look deep into the Dream</span> – Gaze through your eyelids at the flickering lights, let yourself explore the internal patterns and colors.</li>
                    <li><span>Relax and breathe</span> – Continue to breathe deeply as you acclimate to the flickering, feel the rhythm and trust the experience.</li>
                    <li><span>Immerse yourself</span> – Take your time, 15 minutes of immersion with closed eyes is recommended to approach a state of lucid dreaming. However, listen to your comfort and intuition. Do not push yourself or do anything that feels uncomfortable.</li>
                    <li><span>Share your experience</span> – After your journey, consider how it feels to wake up again to the world around you. Share your visions and experiences digitally here (link) and physically. Your internal world is part of our collective dream.</li>
                </ol>         
            </div>
            <figure class="howto-figure">
                <img src="ressources/img/Picture1.jpg" class="howto-img">
            </figure>
        </div>
        <div id="info" class="info-section">
            <!--<h2>INFO</h2>-->
            <div class="info">  
                <h3>Introduction</h3>
                <p>
                    The flickering bonfire, its pulsating embers, and the dancing flames and shadows over faces inspired us to create Dream Together. Our intention was to create a light phenomenon that enhances our ability to feel connected to ourselves and to the world.
                </p>
                <p class="question">
                    How are we responsible for ourselves and others?
                </p>
                <p class="question">
                    How can we dream together in solidarity, to foster change that benefits both humans and more-than-humans alike?
                </p>
                <p>
                    At Roskilde Festival 2024, we aim to provide participants with a respite from the hyper-social context of large crowds. Our installation invites you to relax and reflect on how to establish sustainable connections with yourself and others. Through flicker light stimulation it encourages a journey inward, to renew the ways in which we relate to the world around us. Brion Gysin believed his Dreamachine made people more awake, and we hope that Dream Together will do exactly that:</p>   
                </p>
                <p class="yolo">
                    Waking us up to an entangled world, where our individuality always is dependent on our responsibility towards the diverse collective.
                </p>
            </div>
            <div class="bonfire">
                <h3>The bonfire</h3>
                <p>
                    Control of fire as a technology changed human society and our relationships with more-than-humans. Our ancestor’s anatomy changed, new diets and cooking practices impacted gut volume and brain size. Beyond these impacts, the mastery of fire also afforded new ways of relating to one another and the surrounding world.
                </p>
                <p>
                    Hours of light were extended by the fire during nighttime, providing safety, and creating possibilities for different social exchanges. Conversations around the bonfire provided a context for understanding the thoughts and emotions of others. But the history of fire is older than humans.
                </p>
                <p>
                    Our planet is inherently and scientifically a fire-planet. The evolution of life is deeply connected to fire. Since plants began providing fuel and oxygen, fire has been a part of the planet’s geological history and evolution.
                </p>
                <p>
                    Ever since our human ancestors mastered fire, we have shaped our environments for hunting, farming and clearing grounds for settling. Fire is transformative, both destructive and regenerative and how we relate to it, use it or misuse it, affects us all.
                </p>
            </div>
        </div>
        <div class="gyson">
            <figure>
                <img src="ressources/img/Brian-Gyson.png" class="brian-gyson">
                <figcaption>Brion Gysin (1916-1986) British-Canadian painter, writer, sound poet, performance artist and inventor of experimental devices. </figcaption>
            </figure>
            <div class="dreamachine">
                <h3>The Dreamachine</h3>
                <p>
                    In the 1960’s writer and artist Walter Burroughs (1914-1997) read neurophysiologist William Grey Walter’s (1910-1977) book <span class="italics">‘The Living Brain’</span> and became interested in the hallucinating flicker-effect of Flicker Light Stimulation. When reading about the effect, Burroughs was reminded of a story told by his friend, the poet and artist Brion Gysin (1916-1986). In 1958 Gysin took the train to Southern France, where he, in the fast-moving train from his passenger seat, experienced the flicker-effect by passing rows of trees with sunlight behind them. The rapid flickering created an altered state of mind, that inspired Gysin’s work.
                </p>
                <p>
                    The idea of creating a low-fidelity stroboscopic machine was realized when Gysin contacted mathematician and engineer Ian Sommerville (1940-1976). The pattern for the Dreamachine was designed to be cut out in cardboard, glued together as a cylinder, and placed on a 78-rpm turntable, with a lightbulb extended into the center of the cylinder. Making it easily accessible to be recreated. When the light shines through the slits of the rotating cylinder, a frequency of 8-10 Hz affects the human brains alpha waves. Designed to be looked at through closed eyes, the intention is that the machine opens the inner vistas for travel and exploration. The machine invites us to immerse ourselves in a dream-like state, not to become ‘sleepy’, but instead more ‘aware’.
                </p>
                <p>

                </p>
                
            </div>
        </div>
        <!--<div class="the-process">
            <h2>THE PROCESS</h2>
            <p>
                 Interested in our process? Look through our image library of our experiments, prototypes and developments. Through our hard work, passion and problem solving, we want to share how the messy and iteratively unfolding process shaped the Dream Together (Online) experience.
            </p>
        </div>
        <div class="gallery">
            <h2>GALLERY</h2>
        </div>-->
        <div id="share" class="god-div">
            <div class="share">
                <h2>SHARE YOUR EXPERIENCE</h2>
                <h3>We value the diverse perspectives of our participants and encourage you to share your thoughts and experiences.</h3>
                <p>
                    Browse and explore the experiences shared from those who have journeyed through Dream Together. How do our differences connect us? What does it feel like to share a dream-like state with others in a physical and digital space? How can Dream Together promote empathy and shared understanding among diverse groups? Sharing your experiences might help us understand these questions better. 
                    Thank you for sharing responsibly and with care.
                </p>
            </div>
            <div class="form-container">
                <div class="formen">
                    <?php
                    if (!empty($error_message)) {
                        echo "<p style='color: red;'>$error_message</p>";
                    }
                    ?>
                    <form action="index.php" method="post">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name here" required><br><br>
                        <label for="comment">What did you experience?</label>
                        <textarea id="comment" name="comment" placeholder="Express your experience" required></textarea><br><br>
                        <input type="submit" value="Submit">
                    </form>
                </div> 
            </div>
        </div>
        <div id="experiences" class="yo">
            <div class="comments">
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
        <div class="credits">
         <h2>CREDITS</h2>
            <p><span>Project group:</span> Luis David Chaname Sevilla, Shaban Yousaf, Alex Eriksen and Mathias Jonas Wathne Bruhn</p> 
            <p><span>Mentors and advisors:</span> Nicolas Padfield, Nikolaj Møbius, Bo Thorning, Schack Lindemann, Sara Daugbjerg, Michael Haldrup Pedersen, Mads Høbye, Birgitte Rasmussen, Johanne Aarup Hansen & Benjamin Rask Hansen</p>
            <p><span>Collaborators:</span> FabLab RUC, Roskilde Festival</p> 
        </div>
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