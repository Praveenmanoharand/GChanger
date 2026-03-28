<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST['text'] ?? '';

    if (empty($text)) {
        echo json_encode(["error" => "Please enter a paragraph."]);
        exit;
    }

    // LanguageTool API for advanced grammar checking
    $apiUrl = "https://api.languagetool.org/v2/check";
    $postData = [
        'text'     => $text,
        'language' => 'en-US'
    ];

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

    $response = curl_exec($ch);
    if ($response === false) {
        echo json_encode(["error" => "API request failed."]);
        curl_close($ch);
        exit;
    }
    curl_close($ch);

    $result = json_decode($response, true);
    if (!isset($result["matches"])) {
        echo json_encode(["error" => "Unexpected API response."]);
        exit;
    }

    // Apply grammar corrections
    $corrections = [];
    foreach ($result["matches"] as $match) {
        if (!empty($match["replacements"]) && isset($match["offset"]) && isset($match["length"])) {
            $corrections[] = [
                "offset"      => $match["offset"],
                "length"      => $match["length"],
                "replacement" => $match["replacements"][0]["value"]
            ];
        }
    }

    usort($corrections, function ($a, $b) {
        return $a["offset"] - $b["offset"];
    });

    $offsetDelta = 0;
    foreach ($corrections as $correction) {
        $actualOffset = $correction["offset"] + $offsetDelta;
        $text = substr_replace($text, $correction["replacement"], $actualOffset, $correction["length"]);
        $offsetDelta += strlen($correction["replacement"]) - $correction["length"];
    }

    // Extra contraction fixes
$contractions = [
    "do not" => "don't", "does not" => "doesn't", "did not" => "didn't", "is not" => "isn't", 
    "are not" => "aren't", "was not" => "wasn't", "were not" => "weren't", "will not" => "won't", 
    "shall not" => "shan't", "should not" => "shouldn't", "could not" => "couldn't", "would not" => "wouldn't", 
    "cannot" => "can't", "might not" => "mightn't", "must not" => "mustn't", "I am" => "I'm", 
    "you are" => "you're", "he is" => "he's", "she is" => "she's", "it is" => "it's", "we are" => "we're", 
    "they are" => "they're", "that is" => "that's", "who is" => "who's", "what is" => "what's", 
    "where is" => "where's", "when is" => "when's", "why is" => "why's", "how is" => "how's", 
    "I have" => "I've", "you have" => "you've", "we have" => "we've", "they have" => "they've", 
    "he has" => "he's", "she has" => "she's", "it has" => "it's", "that has" => "that's", "who has" => "who's", 
    "what has" => "what's", "where has" => "where's", "I will" => "I'll", "you will" => "you'll", 
    "he will" => "he'll", "she will" => "she'll", "it will" => "it'll", "we will" => "we'll", 
    "they will" => "they'll", "that will" => "that'll", "who will" => "who'll", "what will" => "what'll", 
    "where will" => "where'll", "I would" => "I'd", "you would" => "you'd", "he would" => "he'd", 
    "she would" => "she'd", "it would" => "it'd", "we would" => "we'd", "they would" => "they'd", 
    "that would" => "that'd", "who would" => "who'd", "what would" => "what'd", "where would" => "where'd",
    "let us" => "let's", "she had" => "she'd", "he had" => "he'd", "they had" => "they'd", 
    "we had" => "we'd", "I had" => "I'd", "you had" => "you'd", "that had" => "that'd",
    "it had" => "it'd", "she would have" => "she'd've", "he would have" => "he'd've", 
    "they would have" => "they'd've", "I would have" => "I'd've", "you would have" => "you'd've",
    "we would have" => "we'd've", "it would have" => "it'd've","(OS)" => "Operating System","Ave " => " Avenue",
];



    foreach ($contractions as $full => $contracted) {
        $text = str_replace($full, $contracted, $text);
    }

    echo json_encode(["corrected" => $text]);
    exit;
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
          <a href="welcome.html">Home</a>
          <a href="about.html">About</a>
          <a href="service.html">Service</a>
          <a href="help.html">Contact</a>
          <button class="btnLogin-popup" onclick="location.href='logout.php'">Logout</button>
        </nav>
      </header>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>GChanger Menu</h2>
        <ul>
            <li><a href="welcome.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="index.html"><i class="fas fa-user"></i> Chatbot</a></li>
            <li><a href="chat_project/chat_login.php"><i class="fas fa-comments"></i> Chat</a></li>
            <li><a href="grammar_corrector.php"><i class="fas fa-cog"></i> ---- </a></li>
            <li><a href="gweb/gweb.html"><i class="fas fa-cog"></i>Mistake Tracker</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Sidebar Toggle Button -->
    <div class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>


    <h1></h1>
    <textarea id="inputText" placeholder="Enter your text here..."></textarea><br>
    <button onclick="correctText()">Correct Grammar</button>
    <div class="output" id="outputText"></div>

    <script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");
}
      

// Function to go back to the previous page
function goBack() {
    window.history.back();
}

        function correctText() {
            let text = document.getElementById("inputText").value;

            fetch("", {  // Use the same PHP file
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "text=" + encodeURIComponent(text)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById("outputText").innerText = "Error: " + data.error;
                } else {
                    document.getElementById("outputText").innerText = "Corrected: " + data.corrected;
                }
            })
            .catch(error => console.error("Error:", error));
        }
    </script>

</body>
</html>
