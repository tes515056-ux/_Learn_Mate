
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

if(
    !isset($_SESSION['quiz_questions']) ||
    empty($_SESSION['quiz_questions'])
){
    die("No quiz found.");
}

$questions = $_SESSION['quiz_questions'];
$topic = $_SESSION['quiz_topic'] ?? 'Quiz';

?>

<!DOCTYPE html>

<!-- beautify ignore:start -->
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

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

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

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header">

            <h3 class="mb-0">
                <?php echo htmlspecialchars($topic); ?>
                Quiz
            </h3>

        </div>

        <div class="card-body">

            <form action="submit_quiz.php" method="POST">

                <?php foreach($questions as $index => $question): ?>

                    <div class="mb-5">

                        <h5>
                            <?php echo ($index + 1); ?>.
                            <?php echo htmlspecialchars($question['question']); ?>
                        </h5>

                        <div class="form-check mt-3">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="q<?php echo $index; ?>"
                                value="A"
                                required>

                            <label class="form-check-label">

                                A.
                                <?php echo htmlspecialchars($question['a']); ?>

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="q<?php echo $index; ?>"
                                value="B">

                            <label class="form-check-label">

                                B.
                                <?php echo htmlspecialchars($question['b']); ?>

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="q<?php echo $index; ?>"
                                value="C">

                            <label class="form-check-label">

                                C.
                                <?php echo htmlspecialchars($question['c']); ?>

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="q<?php echo $index; ?>"
                                value="D">

                            <label class="form-check-label">

                                D.
                                <?php echo htmlspecialchars($question['d']); ?>

                            </label>

                        </div>

                    </div>

                <?php endforeach; ?>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Submit Quiz

                </button>

            </form>

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