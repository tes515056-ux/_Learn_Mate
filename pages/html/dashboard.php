<?php

session_start();
include '../../dblink.php';

/*
|--------------------------------------------------------------------------
| Check Login (IMPORTANT FIX)
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];


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

/*
|--------------------------------------------------------------------------
| Default Scores
|--------------------------------------------------------------------------
*/

$math = 0;
$science = 0;
$english = 0;
$computer = 0;

/*
|--------------------------------------------------------------------------
| Helper function to get subject average
|--------------------------------------------------------------------------
*/

function getAvgScore($conn, $user_id, $subject)
{
    $result = mysqli_query(
        $conn,
        "SELECT AVG(score) AS avg_score
         FROM quiz_results
         WHERE user_id = $user_id
         AND subject = '$subject'"
    );

    if ($row = mysqli_fetch_assoc($result)) {
        return round($row['avg_score'] ?? 0);
    }

    return 0;
}

/*
|--------------------------------------------------------------------------
| Subject Scores
|--------------------------------------------------------------------------
*/

$math = getAvgScore($conn, $user_id, 'Math');
$science = getAvgScore($conn, $user_id, 'Science');
$english = getAvgScore($conn, $user_id, 'English');
$computer = getAvgScore($conn, $user_id, 'Computer Science');

/*
|--------------------------------------------------------------------------
| Weakest Subject + Topic
|--------------------------------------------------------------------------
*/

$weakestSubject = "No Data";
$weakestTopic = "No Recommendation Yet";

$weakestQuery = mysqli_query(
    $conn,
    "SELECT subject, topic, AVG(score) AS avg_score
     FROM quiz_results
     WHERE user_id = $user_id
     GROUP BY topic
     ORDER BY avg_score ASC
     LIMIT 1"
);

if ($weakestQuery && $row = mysqli_fetch_assoc($weakestQuery)) {
    $weakestSubject = $row['subject'];
    $weakestTopic = $row['topic'];
}

/*
|--------------------------------------------------------------------------
| AI Recommendation
|--------------------------------------------------------------------------
*/

$recommendedLesson = $weakestTopic;

/*
|--------------------------------------------------------------------------
| Recent Quiz Results (Latest 5)
|--------------------------------------------------------------------------
*/

$recentResults = mysqli_query(
    $conn,
    "SELECT *
     FROM quiz_results
     WHERE user_id = $user_id
     ORDER BY created_at DESC
     LIMIT 5"
);

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

        

          <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">

                    <h3 class="mb-1">
                        Welcome to LearnMate
                    </h3>

                    <p class="text-muted mb-0">
                        Track your learning progress and receive personalized recommendations.
                    </p>

                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Math -->
        <div class="col-lg-3 col-md-6 mb-4">

            <div class="card">

                <div class="card-body">

                    <span class="fw-semibold d-block mb-1">
                        Mathematics
                    </span>

                    <h3 class="card-title mb-2">
                        <?php echo $math; ?>%
                    </h3>

                    <div class="progress">
                        <div
                            class="progress-bar"
                            style="width: <?php echo $math; ?>%">
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Science -->
        <div class="col-lg-3 col-md-6 mb-4">

            <div class="card">

                <div class="card-body">

                    <span class="fw-semibold d-block mb-1">
                        Science
                    </span>

                    <h3 class="card-title mb-2">
                        <?php echo $science; ?>%
                    </h3>

                    <div class="progress">
                        <div
                            class="progress-bar bg-success"
                            style="width: <?php echo $science; ?>%">
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- English -->
        <div class="col-lg-3 col-md-6 mb-4">

            <div class="card">

                <div class="card-body">

                    <span class="fw-semibold d-block mb-1">
                        English
                    </span>

                    <h3 class="card-title mb-2">
                        <?php echo $english; ?>%
                    </h3>

                    <div class="progress">
                        <div
                            class="progress-bar bg-warning"
                            style="width: <?php echo $english; ?>%">
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Computer -->
        <div class="col-lg-3 col-md-6 mb-4">

            <div class="card">

                <div class="card-body">

                    <span class="fw-semibold d-block mb-1">
                        Computer Science
                    </span>

                    <h3 class="card-title mb-2">
                        <?php echo $computer; ?>%
                    </h3>

                    <div class="progress">
                        <div
                            class="progress-bar bg-info"
                            style="width: <?php echo $computer; ?>%">
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-8 mb-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        AI Recommendation
                    </h5>

                </div>

                <div class="card-body">
       <p>Subjects with a score below <strong>70%</strong> need additional practice in the Learning Hub.</p>
                    <p class="mb-3">

                        Based on your recent performance,
                        we recommend focusing on

                        <strong>
                            <?php echo $weakestSubject; ?>
                        </strong>

                    </p>

                    <h5 class="text-primary mb-3">

                        Recommended Lesson:
                        <?php echo $recommendedLesson; ?>

                    </h5> 
                    <a href="learning_hub.php" class="btn btn-primary me-3">Go to Learning Hub</a>

                    <a href="ai_tutor.php" class="btn btn-primary">Go to Ai Tutor</a>


                    
                </div>

            </div>

        </div>

        <div class="col-lg-4 mb-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Learning Summary
                    </h5>

                </div>

                <div class="card-body">

                    <p>
                        Subjects Tracked:
                        <strong>4</strong>
                    </p>

                    <p>
                        Quiz-Based Adaptive Learning
                    </p>

                    <p class="mb-0">
                        Personalized AI Tutor Support
                    </p>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Recent Quiz Results
                    </h5>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table">

                            <thead>

                                <tr>
                                    <th>Topic</th>
                                    <th>Subject</th>
                                    <th>Score</th>
                                </tr>

                            </thead>

                            <tbody>

                            <?php

                            $recent = mysqli_query(
                                $conn,
                                "SELECT *
                                 FROM quiz_results
                                 WHERE user_id='$user_id'
                                 ORDER BY id DESC
                                 LIMIT 5"
                            );

                            while($row = mysqli_fetch_assoc($recent))
                            {
                            ?>

                                <tr>

                                    <td>
                                        <?php echo $row['topic']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['subject']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['score']; ?>%
                                    </td>

                                </tr>

                            <?php
                            }
                            ?>

                            </tbody>

                        </table>

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
  </body>
</html>
