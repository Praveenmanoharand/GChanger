<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST["text"];

    $url = "http://localhost:5000/correct"; // URL of the Flask API
    $data = json_encode(["text" => $text]);

    $options = [
        "http" => [
            "header" => "Content-Type: application/json",
            "method" => "POST",
            "content" => $data
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "Error calling the API.";
    } else {
        $response = json_decode($result, true);
        echo "<div class='output'>Corrected Text: " . htmlspecialchars($response["corrected_text"]) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grammar Corrector</title>
    <link rel="icon" type="image/png" href="assets/images/123.png"  />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,500;0,600;0,700;0,800;1,100;1,400;1,500;1,600;1,700&display=swap');
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
header {
    position: fixed;
    inset-block-start: 15px;
    inset-inline-start: 7px;
    inline-size: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
    padding-block-end: 20px;
}

.logo {
    margin-inline-start: 110px;
    margin-block-start: 10px;
    padding: 5px 5px;
    font-weight: bold;
    font-size: 2em;
    color:rgb(73, 146, 106);
    border: 5px black;
    user-select: none;
    font-family: 'Poppins', sans-serif;
}

.logo1{
    display: flex;
    margin-inline-start: -900px;
    margin-block-start: -135px;
    user-select: none;
}
.navigation a {
    border: right 20px;
    inline-size: right 40px;
    margin-block-start: 20px;
    margin-block-end:10px;
    position: relative;
    font-size: 1.1em;
    color:black;
    text-decoration: none;
    font-weight: 500;
    margin-inline-start: 40px;
    transition: color 0.3s ease;
}
.navigation a::after {
    content: '';
    position: absolute;
    inset-inline-start: 0;
    inset-block-end: -6px;
    inline-size: 100%;
    block-size: 3px;
    background: black;
    border-radius: 5px;
    transform: scaleX(0);
    transition: transform 0.5s;
}
.navigation a:hover::after{
    transform: scaleX(1);
}
.navigation .btnLogin-popup {
    block-size: 50px;
    margin-inline-end: 100px;
    padding-inline-start: 40px;
    padding-inline-end:40px;
    background: transparent;
    border: 2px solid black;
    outline: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    color: black;
    font-weight: 500;
    margin-inline-start: 40px;
    transition: .5s;
}

.navigation .btnLogin-popup:hover {
    background-color: black;
    color: white;
}



/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

/* Sidebar Styles */
.sidebar {
    inline-size: 280px;
    block-size: 100%;
    background:rgba(255, 255, 255, 0.8);
    color: black;
    position: fixed;
    inset-inline-start: -280px;
    inset-block-start: 0;
    transition: left 0.4s ease-in-out;
    padding-block-start: 10px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
}

.sidebar h2 {
    text-align: center;
    margin-block-end: 40px;
    font-size: 22px;
    font-weight: bold;
    letter-spacing: 1px;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li {
    padding: 15px;
    border-block-end: 1px solid rgba(255, 255, 255, 0.2);
    transition: 0.3s;
}

.sidebar ul li:hover {
    background: rgba(123, 138, 139, 0.2);
}

.sidebar ul li a {
    color: black;
    text-decoration: none;
    font-size: 18px;
    display: flex;
    align-items: center;
}

.sidebar ul li a i {
    margin-inline-end: 10px;
    font-size: 20px;
}

/* Sidebar Toggle Button */
.sidebar-toggle {
    position: fixed;
    inset-inline-start: 10px;
    inset-block-start: 10px;
    background:rgb(0, 0, 0);
    color: black;
    padding: 10px;
    border-radius: 5px;
    border-color: black;
    cursor: pointer;
    transition: 0.3s;
    z-index: 1000;
}

.sidebar-toggle:hover {
    background: #2980b9;
}

/* When Sidebar is Active */
.sidebar.active {
    inset-inline-start: 0;
}

/* Chat Container */
.chat-container {
    margin-inline-start: 20px;
    padding: 20px;
    transition: 0.3s;
}

/* Adjust Chat Position When Sidebar is Open */
.sidebar.active + .chat-container {
    margin-inline-start: 270px;
}

/* Responsive Sidebar */
@media screen and (max-width: 768px) {
    .sidebar {
        inline-size: 200px;
    }

    .sidebar ul li a {
        font-size: 16px;
    }

    .sidebar-toggle {
        inset-inline-start: 5px;
        inset-block-start: 5px;
    }

    .chat-container {
        margin-inline-start: 0;
    }

    .sidebar.active + .chat-container {
        margin-inline-start: 220px;
    }
}


  /* Page Transitions */
  body {
    animation: fadeIn 1s ease-out;
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

/* General body styling */
body {
  background-image: url('assets/images/grammarbackground.jpg');
  background-repeat: no-repeat;
  background-size: cover; /* Ensures the background covers the entire screen */
  background-position: center; /* Centers the background image */
  font-family: 'Poppins', sans-serif;/* A more specific font stack */
  color: #333; /* Softer black for better readability */
  text-align: center;
  margin-inline-start: 38%;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-block-size: 100vh; /* Ensures the body takes at least the full viewport height */
}

/* Textarea styling */
textarea {
  inline-size: 80%; /* More responsive width */
  max-inline-size: 600px; /* Limits maximum width for larger screens */
  block-size: 200px;
  margin: 20px 0; /* Simplified margin */
  padding: 15px; /* Adds padding for better text input experience */
  font-size: 16px;
  font-family: 'Poppins', sans-serif;
  border: 2px solid #ccc; /* Subtle border */
  border-radius: 8px; /* Rounded corners */
  resize: vertical; /* Allows vertical resizing only */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow */
}

/* Button styling */
button {
  padding: 12px 24px;
  background-color: black; /* Modern blue color */
  color: white;
  border-color: black;
  border: solid;
  border-radius: 8px; /* Rounded corners */
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: background-color 0.3s ease; /* Smooth hover effect */
}

button:hover {
  background-color: white; /* Darker blue on hover */
  color:black;
}

button:active {
  background-color: white; /* Even darker blue on click */
}

/* Output styling */
.output {
  margin-block-start: 30px;
  font-family: 'Poppins', sans-serif;
  font-size: 18px;
  font-weight: bold;
  color: black; /* Consistent with body text color */
  background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
  padding: 20px;
  border-radius: 8px; /* Rounded corners */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  max-inline-size: 80%; /* Ensures output doesn't stretch too wide */
  word-wrap: break-word; /* Ensures long text doesn't overflow */
}

/* Responsive adjustments */
@media (max-width: 768px) {
  textarea {
    inline-size: 90%; /* Wider textarea on smaller screens */
  }

  .output {
    inline-size: 90%; /* Wider output on smaller screens */
  }
}        

 /* textarea Transitions */
 textarea {
    animation: popUp 1s ease-in-out forwards;
  }


 
  @keyframes popUp {
  0% {
    transform: translateY(100%) scale(0.5);
    opacity: 0;
  }
  50% {
    transform: translateY(50%) scale(1.1);
    opacity: 0.5;
  }
  100% {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

/* button Transitions */
button {
    animation: blurup 1s ease-in-out forwards;
  }
 
  @keyframes blurUp {
  0% {
    transform: translateY(100%);
    opacity: 0;
    filter: blur(10px);
  }
  100% {
    transform: translateY(0);
    opacity: 1;
    filter: blur(0);
  }
}

/* Logo Animation */


@keyframes wavePulse {
    0% { transform: scale(1); filter: hue-rotate(0deg); }
    50% { transform: scale(1.1); filter: hue-rotate(180deg); }
    100% { transform: scale(1); filter: hue-rotate(360deg); }
}

.logo1 {
    inline-size: 100px;
    animation: wavePulse 2s infinite alternate ease-in-out;
}

</style>
</head>
<body>
<header1>
<img src="assets/images/logo1.png" alt="GChanger logo" class="logo1" width="100", height="100">
</header1>

<header>
<h2 class="logo">GChanger</h2>
        <nav class="navigation">
          <a href="dashboard.html">Home</a>
          <a href="#">About</a>
          <a href="#">Service</a>
          <a href="#">Contact</a>
          <button class="btnLogin-popup" onclick="location.href='logout.php'">Logout</button>
        </nav>
      </header>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>GChanger Menu</h2>
        <ul>
            <li><a href="dashboard.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="index.html"><i class="fas fa-user"></i> Chatbot</a></li>
            <li><a href="chat_project/chat_login.php"><i class="fas fa-comments"></i> Chat</a></li>
            <li><a href="grammar_corrector.php"><i class="fas fa-cog"></i> ---- </a></li>
            <li><a href="story.html"><i class="fas fa-cog"></i> Generate Story</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Sidebar Toggle Button -->
    <div class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>


    <h1></h1>
    <form method="post">
    <textarea name="text" placeholder="Enter your text here..."></textarea><br>
    <button type="submit">Correct Grammar</button>
</form>
    


<!-- HTML Form: Add this in your HTML where you need the form -->
<form method="post">
    <textarea name="text" placeholder="Enter your text here..."></textarea><br>
    <button type="submit">Correct Grammar</button>
</form>
<script>
function correctText() {
    const text = document.getElementById('inputText').value;
    if (text.trim() === "") {
        alert("Please enter some text.");
        return;
    }

    fetch('http://localhost:5000/correct', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ text: text })
    })
    .then(response => response.json())
    .then(data => {
        if (data.corrected_text) {
            document.getElementById('outputText').innerText = data.corrected_text;
        } else {
            document.getElementById('outputText').innerText = "Error correcting text.";
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById('outputText').innerText = "Error calling API.";
    });
}
</script>
</body>
</html>
