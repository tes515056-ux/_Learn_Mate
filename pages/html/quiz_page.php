<?php

session_start();
include '../../dblink.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$userQuery = mysqli_query(
    $conn,
    "SELECT name,email
     FROM users
     WHERE id = $user_id
     LIMIT 1"
);

$user = mysqli_fetch_assoc($userQuery);


$quizTopics = mysqli_query(
    $conn,
    "SELECT
        qt.id,
        qt.title,
        qt.description,
        qt.difficulty,
        s.subject_name
     FROM quiz_topics qt
     INNER JOIN subjects s
        ON qt.subject_id = s.id
     ORDER BY qt.created_at DESC"
);
$subjects = mysqli_query(
    $conn,
    "SELECT id, subject_name
     FROM subjects
     ORDER BY subject_name ASC"
);



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

    <div class="mb-4">
        <h2 class="fw-bold">
            Quiz Library
        </h2>

        <p class="text-muted">
            Select a topic and start an AI generated quiz.
        </p>
    </div>

   <div class="row mb-4 align-items-center">

    <!-- Search -->
    <div class="col-md-5 mb-3">

        <div class="input-group">

            <span class="input-group-text">
                <i class="bx bx-search"></i>
            </span>

            <input
                type="text"
                id="quizSearch"
                class="form-control"
                placeholder="Search quiz topics...">

        </div>

    </div>

    <!-- Subject Filter -->
    <div class="col-md-5 mb-3">

        <select
            id="subjectFilter"
            class="form-select">

            <option value="">
                All Subjects
            </option>

            <?php while($subject = mysqli_fetch_assoc($subjects)): ?>

                <option value="<?php echo strtolower($subject['subject_name']); ?>">
                    <?php echo htmlspecialchars($subject['subject_name']); ?>
                </option>

            <?php endwhile; ?>

        </select>

    </div>

    <!-- Reset Button -->
    <div class="col-md-2 mb-3">

        <button
            id="resetFilters"
            class="btn btn-outline-primary w-100">

            <i class="bx bx-refresh me-1"></i>
            Reset

        </button>

    </div>

</div>

<div class="row" id="quizContainer">

    <?php while($quiz = mysqli_fetch_assoc($quizTopics)): ?>

        <?php
        $difficultyClass = "success";

        if ($quiz['difficulty'] == "Medium") {
            $difficultyClass = "warning";
        }

        if ($quiz['difficulty'] == "Hard") {
            $difficultyClass = "danger";
        }
        ?>

<div
    class="col-lg-4 col-md-6 mb-4 quiz-card"
    data-title="<?php echo strtolower($quiz['title']); ?>"
    data-subject="<?php echo strtolower($quiz['subject_name']); ?>"
>
<div class="card h-100 shadow-sm border-0 d-flex flex-column">
                <div class="card-body">

                    <!-- Subject & Difficulty -->
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="badge bg-label-primary">
                            <?php echo htmlspecialchars($quiz['subject_name']); ?>
                        </span>

                        <span class="badge bg-<?php echo $difficultyClass; ?>">
                            <?php echo htmlspecialchars($quiz['difficulty']); ?>
                        </span>

                    </div>

                    <!-- Quiz Title -->
                    <h5 class="fw-bold">
                        <?php echo htmlspecialchars($quiz['title']); ?>
                    </h5>

                    <!-- Quiz Description -->
                    <p class="text-muted">
                        <?php echo htmlspecialchars($quiz['description']); ?>
                    </p>

                </div>

                <div class="card-footer bg-white border-0">

                    <a
                        href="quiz_generate.php?topic=<?php echo urlencode($quiz['title']); ?>"
                        class="btn btn-primary w-100">

                        Start Quiz

                    </a>

                </div>

            </div>

        </div>

    <?php endwhile; ?>

    <div
    id="noResults"
    class="text-center py-5 d-none">

    <i class="bx bx-search-alt bx-lg text-muted"></i>

    <h5 class="mt-3 mb-2">
        No quizzes found
    </h5>

    <p class="text-muted mb-0">
        Try another search keyword or subject filter.
    </p>

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

const searchInput = document.getElementById('quizSearch');
const subjectFilter = document.getElementById('subjectFilter');
const cards = Array.from(document.querySelectorAll('.quiz-card'));
const noResults = document.getElementById('noResults');

const quizzesPerPage = 6;
let currentPage = 1;

const paginationContainer = document.createElement('div');
paginationContainer.className = 'd-flex justify-content-center mt-4';
paginationContainer.id = 'pagination';

document.getElementById('quizContainer')
    .insertAdjacentElement('afterend', paginationContainer);

function filterAndPaginate() {

    const searchValue =
        searchInput.value.toLowerCase().trim();

    const selectedSubject =
        subjectFilter.value.toLowerCase();

    let filteredCards = [];

    cards.forEach(card => {

        const title =
            card.dataset.title.toLowerCase();

        const subject =
            card.dataset.subject.toLowerCase();

        const matchesSearch =
            title.includes(searchValue);

        const matchesSubject =
            selectedSubject === '' ||
            subject === selectedSubject;

        if (matchesSearch && matchesSubject) {
            filteredCards.push(card);
        }

        card.style.display = 'none';

    });

    if (filteredCards.length === 0) {

        noResults.classList.remove('d-none');
        paginationContainer.innerHTML = '';
        return;

    }

    noResults.classList.add('d-none');

    const totalPages =
        Math.ceil(filteredCards.length / quizzesPerPage);

    if (currentPage > totalPages) {
        currentPage = 1;
    }

    const start =
        (currentPage - 1) * quizzesPerPage;

    const end =
        start + quizzesPerPage;

    filteredCards
        .slice(start, end)
        .forEach(card => {
            card.style.display = '';
        });

    renderPagination(totalPages);

}

function renderPagination(totalPages) {

    if (totalPages <= 1) {

        paginationContainer.innerHTML = '';
        return;

    }

    let html = '<nav><ul class="pagination">';

    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                Previous
            </a>
        </li>
    `;

    for (let i = 1; i <= totalPages; i++) {

        html += `
            <li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">
                    ${i}
                </a>
            </li>
        `;
    }

    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                Next
            </a>
        </li>
    `;

    html += '</ul></nav>';

    paginationContainer.innerHTML = html;

}

function changePage(page) {

    currentPage = page;
    filterAndPaginate();

}

searchInput.addEventListener('keyup', () => {

    currentPage = 1;
    filterAndPaginate();

});

subjectFilter.addEventListener('change', () => {

    currentPage = 1;
    filterAndPaginate();

});

filterAndPaginate();


document
.getElementById('resetFilters')
.addEventListener('click', function(){

    searchInput.value = '';
    subjectFilter.value = '';

    currentPage = 1;

    filterAndPaginate();

});

</script>

  </body>
</html>