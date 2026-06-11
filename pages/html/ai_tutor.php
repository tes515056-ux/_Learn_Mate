<?php

session_start();
include '../../dblink.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Fetch User Info (name + email)
|--------------------------------------------------------------------------
*/

$user = null;

$userQuery = mysqli_query(
    $conn,
    "SELECT name, email
     FROM users
     WHERE id = $user_id
     LIMIT 1"
);

if ($userQuery) {
    $user = mysqli_fetch_assoc($userQuery);
}
?>
<!DOCTYPE html>


<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>LearnMate</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php include 'sidebar.php'; ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

         <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- LearnMate Title -->
    <div class="navbar-nav align-items-center">
      <span class="fw-bold fs-5 text-primary">Learn & Grow</span>
    </div>

              <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../assets/img/avatars/11.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="../assets/img/avatars/11.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                        <div class="flex-grow-1">
    <span class="fw-semibold d-block">
        <?php echo $user['name']; ?>
    </span>

    <small class="text-muted">
        <?php echo $user['email']; ?>
    </small>
</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="profile.php">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    
                    <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar ****************************** -->

          <!-- AI Tutor Content -->
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card border-0 shadow-none">

        <div class="card-body">

            <!-- Welcome Area -->
            <div class="text-center mb-2">
                <h1 class="fw-bold display-5">
                    Welcome to AI Tutor
                </h1>

                <p class="text-muted fs-5">
                    Ask any learning question and get instant AI-powered explanations.
                </p>
            </div>


            <!-- Chat Messages -->
            <div id="chat-box"
                 class="border rounded p-4 mb-4"
                 style="height:330px; overflow-y:auto; background:#fafafa;">

                <div class="mb-3">
                    <span class="badge bg-label-primary">AI Tutor</span>

                    <div class="mt-2 p-3 bg-white rounded border">
Hello! Ask any learning question. You can also generate a quiz or get a simpler explanation.                    </div>
                </div>

            </div>

            <!-- Chat Input -->
            <div class="card border">

                <div class="card-body p-0">

                    <textarea
                        id="message"
                        class="form-control border-0 shadow-none"
                        rows="3"
                        placeholder="Ask AI Tutor anything..."></textarea>

                    <hr class="m-0">

                    <div class="d-flex justify-content-between align-items-center p-3">

                        <div>
    <button
        type="button"
        class="btn btn-sm btn-outline-info me-2"
        onclick="explainSimpler()">

        <i class="bx bx-bulb"></i>
        Simpler
    </button>

    <button
        type="button"
        class="btn btn-sm btn-outline-success"
        onclick="generateQuiz()">

        <i class="bx bx-task"></i>
        Quiz
    </button>
</div>

                        <button
                            id="sendBtn"
                            class="btn btn-primary">

                            <i class="bx bx-send"></i>
                            Send
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



          <!-- Footer -->
           <footer class="content-footer footer bg-footer-theme border-top mt-4">
    <div class="container-xxl py-4">

        <div class="row align-items-center">

            <!-- Left -->
            <div class="col-md-6 mb-3 mb-md-0">

                <h5 class="fw-bold text-primary mb-1">
                    LearnMate
                </h5>

                <p class="text-muted mb-0">
                    Empowering students with AI-powered learning, personalized lessons, quizzes, and career guidance.
                </p>

            </div>

            <!-- Right -->
            <div class="col-md-6 text-md-end">

                <a href="dashboard.php" class="footer-link me-3">
                    Dashboard
                </a>

                <a href="learning_hub.php" class="footer-link me-3">
                    Learning Hub
                </a>

                <a href="ai_tutor.php" class="footer-link me-3">
                    AI Tutor
                </a>

                <a href="profile.php" class="footer-link">
                    Profile
                </a>

            </div>

        </div>

        <hr class="my-3">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">

            <div class="text-muted small">
                © <script>document.write(new Date().getFullYear())</script>
                LearnMate. All Rights Reserved.
            </div>

            <div class="mt-2 mt-md-0">

                <a href="#" class="text-muted me-3">
                    <i class="bx bx-envelope"></i>
                </a>

                <a href="#" class="text-muted me-3">
                    <i class="bx bxl-facebook"></i>
                </a>

                <a href="#" class="text-muted me-3">
                    <i class="bx bxl-linkedin"></i>
                </a>

                <a href="#" class="text-muted">
                    <i class="bx bxl-github"></i>
                </a>

            </div>

        </div>

    </div>
</footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

   <script>

let currentTopic = "";
let lastAIResponse = "";

document.getElementById("sendBtn").addEventListener("click", sendMessage);

function sendMessage(){

    let messageInput = document.getElementById("message");
    let message = messageInput.value.trim();

    if(message === "") return;

    currentTopic = message;

    let chatBox = document.getElementById("chat-box");

    // User Message
    chatBox.innerHTML += `
        <div class="text-end mb-3">

            <span class="badge bg-primary">
                You
            </span>

            <div class="mt-2 p-3 bg-primary text-white rounded d-inline-block">
                ${message}
            </div>

        </div>
    `;

    messageInput.value = "";

    // Loading
    let loadingId = "loading-" + Date.now();

    chatBox.innerHTML += `
        <div id="${loadingId}" class="mb-3">

            <span class="badge bg-label-primary">
                AI Tutor
            </span>

            <div class="mt-2 p-3 bg-white rounded border">
                Thinking...
            </div>

        </div>
    `;

    chatBox.scrollTop = chatBox.scrollHeight;

    fetch("chat_process.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:"message=" + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {

        document.getElementById(loadingId).remove();

        let reply =
            data.reply ||
            data.error ||
            "No response received.";

        lastAIResponse = reply;

        chatBox.innerHTML += `
            <div class="mb-3">

                <div class="d-flex justify-content-between align-items-center">

                    <span class="badge bg-label-primary">
                        AI Tutor
                    </span>

                    <button
                        class="btn btn-sm btn-icon btn-outline-secondary"
                        onclick="copyText(this)"
                        data-text="${encodeURIComponent(reply)}">

                        <i class="bx bx-copy"></i>

                    </button>

                </div>

                <div class="mt-2 p-3 bg-white rounded border">
${reply.replace(/\n\n/g,'<br><br>').replace(/\n/g,'<br>')}
                </div>

                <div class="mt-2 d-flex gap-2">

                    <button
                        class="btn btn-sm btn-outline-info"
                        onclick="explainSimpler()">

                        <i class="bx bx-bulb"></i>
                        Simpler

                    </button>

                    <button
                        class="btn btn-sm btn-outline-success"
                        onclick="generateQuiz()">

                        <i class="bx bx-task"></i>
                        Quiz

                    </button>

                </div>

            </div>
        `;

        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(error => {

        document.getElementById(loadingId).remove();

        chatBox.innerHTML += `
            <div class="alert alert-danger">
                Failed to contact AI.
            </div>
        `;

        console.log(error);
    });
}

// Simpler Explanation
function explainSimpler(){

    if(lastAIResponse === ""){

        alert("Ask a question first.");
        return;
    }

    document.getElementById("message").value =
    "Give a simpler explanation of: " +
    currentTopic;

    sendMessage();
}

// Generate Quiz
function generateQuiz(){

    if(currentTopic === ""){

        alert("Ask a question first.");
        return;
    }

    window.location.href =
        "quiz_generate.php?topic=" +
        encodeURIComponent(currentTopic);
}

// Copy AI Response
function copyText(button){

    let text = decodeURIComponent(
        button.dataset.text
    );

    navigator.clipboard.writeText(text)
    .then(() => {

        button.innerHTML =
            `<i class="bx bx-check"></i>`;

        setTimeout(() => {

            button.innerHTML =
                `<i class="bx bx-copy"></i>`;

        }, 2000);

    })
    .catch(error => {

        console.log(error);

    });
}

// Enter To Send
document.getElementById("message")
.addEventListener("keypress", function(e){

    if(
        e.key === "Enter" &&
        !e.shiftKey
    ){

        e.preventDefault();

        sendMessage();
    }

});

</script>

  </body>
</html>