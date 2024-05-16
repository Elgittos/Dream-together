<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dream together</title>
    <link href="ressources/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <h1>Dream together</h1>
        <img class="logo" src="ressources/img/Picture3.jpg">
        <ul>
            <li class="navbutton"><a href="index.html">Home</a></li>
            <li class="navbutton"><a href="dreams.html">Dreams</a></li>
            <li class="navbutton"><a href="#">Dreams</a></li>
        </ul>
    </header>
    <main>
        <div>
            <img src="ressources/img/Picture1.jpg" alt="IMAGE">
            <video class="video" src="ressources/mp4/flames.mp4" autoplay muted loop></video>
            <h2>Om Brion Gyson's Dream Machine</h2>
        </div> 
       
        
        <div>
            <h2>Dreams Shared by Others:</h2>
            <?php include 'dreams.php'; ?>
        </div>


    </main>
    <footer></footer>
</body>
</html>