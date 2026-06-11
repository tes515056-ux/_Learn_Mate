<?php

session_start();
include '../../dblink.php';

/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Get User Info
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

/*
|--------------------------------------------------------------------------
| Get Saved Lessons
|--------------------------------------------------------------------------
*/

$result = mysqli_query(
    $conn,
    "SELECT l.*
     FROM saved_lessons s
     JOIN lessons l ON s.lesson_id = l.id
     WHERE s.user_id = $user_id
     ORDER BY s.created_at DESC"
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

    <style>
        .lesson-card .card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    transition:all .3s ease;
    background:#fff;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.lesson-card .card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(105,108,255,.18);
}

.lesson-icon{
    width:67px;
    height:67px;
    margin:auto;
    border-radius:18px;

    display:flex;
    align-items:center;
    justify-content:center;

    background:linear-gradient(
        135deg,
        #696cff,
        #03c3ec
    );

    color:#fff;

    margin-bottom:20px;

    box-shadow:0 10px 25px rgba(105,108,255,.25);
}

.lesson-icon i{
    font-size:40px;
}

.lesson-title{
    font-size:18px;
    font-weight:700;
    color:#566a7f;
    min-height:55px;
}

.lesson-badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:30px;
    background:#eef1ff;
    color:#696cff;
    font-size:13px;
    font-weight:600;
    margin-bottom:15px;
}

.lesson-btn{
    border-radius:12px;
    padding:10px 22px;
    font-weight:600;
}

.bookmark-btn{
    position:absolute;
    top:12px;
    right:12px;
    width:40px;
    height:40px;
    border-radius:12px;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.saved-header{
    background:#fff;
    border-radius:20px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}
    </style>
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


<div class="container-xxl p-4">

<div class="saved-header">

    <h2 class="fw-bold mb-2">
        Saved Lessons
    </h2>

    <p class="text-muted mb-0">
        Quickly access your bookmarked lessons anytime.
    </p>

</div>
<div class="row mb-4">

    <div class="col-md-10">

        <div class="input-group">

            <span class="input-group-text">
                <i class="bx bx-search"></i>
            </span>

            <input
                type="text"
                id="lessonSearch"
                class="form-control"
                placeholder="Search saved lessons..."
            >

        </div>

    </div>

    <div class="col-md-2">

        <button
            type="button"
            id="resetSearch"
            class="btn btn-outline-primary w-100">

            <i class="bx bx-refresh me-1"></i>
            Reset

        </button>

    </div>

</div>

    <div class="row">

        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

            <?php while ($lesson = mysqli_fetch_assoc($result)) { ?>

                <?php
                    // check saved (always true here because it's saved page)
                    $isSaved = true;
                ?>

<div class="col-xl-3 col-lg-4 col-md-6 mb-4 lesson-card">
                       <div class="card h-100">

    <a href="toggle_save_lesson.php?id=<?php echo $lesson['id']; ?>"
       class="btn bookmark-btn">

        <i class="bx bxs-bookmark"
           style="font-size:20px;color:#ffab00;">
        </i>

    </a>

    <div class="card-body text-center p-4 d-flex flex-column">

        <div class="lesson-icon">
            <i class="bx bx-book-open"></i>
        </div>

        <span class="lesson-badge">
            Lesson <?php echo $lesson['lesson_order']; ?>
        </span>

        <h5 class="lesson-title">
            <?php echo htmlspecialchars($lesson['lesson_title']); ?>
        </h5>

        <a href="lesson_detail.php?id=<?php echo $lesson['id']; ?>"
           class="btn btn-primary lesson-btn mt-auto">

            Start Lesson

        </a>

    </div>

</div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="col-12">
                <div class="alert alert-info">
                    No saved lessons yet.
                </div>
            </div>

        <?php } ?>

        <div id="noResults" class="text-center mt-4" style="display:none;">
    <div class="alert alert-warning">
        No saved lessons found.
    </div>
</div>
<div id="pagination" class="d-flex justify-content-center mt-4"></div>

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

const cards =
    Array.from(document.querySelectorAll('.lesson-card'));

const lessonsPerPage = 4;

let currentPage = 1;

const paginationContainer =
    document.getElementById('pagination');

function getVisibleCards()
{
    return cards.filter(card =>
        card.dataset.hidden !== "true"
    );
}

function showPage(page)
{
    currentPage = page;

    cards.forEach(card => {

        if(card.dataset.hidden !== "true")
        {
            card.style.display = "none";
        }

    });

    const visibleCards = getVisibleCards();

    const start =
        (page - 1) * lessonsPerPage;

    const end =
        start + lessonsPerPage;

    visibleCards
        .slice(start, end)
        .forEach(card => {

            card.style.display = "block";

        });

    renderPagination();
}

function renderPagination()
{
    const visibleCards = getVisibleCards();

    const totalPages =
        Math.ceil(
            visibleCards.length /
            lessonsPerPage
        );

    if(totalPages <= 1)
    {
        paginationContainer.innerHTML = '';
        return;
    }

    let html =
        '<nav><ul class="pagination">';

    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link"
               href="#"
               onclick="changePage(${currentPage - 1}); return false;">
                Previous
            </a>
        </li>
    `;

    for(let i = 1; i <= totalPages; i++)
    {
        html += `
            <li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link"
                   href="#"
                   onclick="changePage(${i}); return false;">
                    ${i}
                </a>
            </li>
        `;
    }

    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link"
               href="#"
               onclick="changePage(${currentPage + 1}); return false;">
                Next
            </a>
        </li>
    `;

    html += '</ul></nav>';

    paginationContainer.innerHTML = html;
}

function changePage(page)
{
    const visibleCards = getVisibleCards();

    const totalPages =
        Math.ceil(
            visibleCards.length /
            lessonsPerPage
        );

    if(page < 1 || page > totalPages)
    {
        return;
    }

    showPage(page);
}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

document
.getElementById("lessonSearch")
.addEventListener("keyup", function(){

    let value =
        this.value.toLowerCase();

    let found = 0;

    cards.forEach(function(card){

        let title =
            card.querySelector(".lesson-title")
            .innerText
            .toLowerCase();

        if(title.includes(value))
        {
            card.dataset.hidden = "false";
            found++;
        }
        else
        {
            card.dataset.hidden = "true";
            card.style.display = "none";
        }

    });

    document
    .getElementById("noResults")
    .style.display =
        found === 0
        ? "block"
        : "none";

    currentPage = 1;

    showPage(1);

});

cards.forEach(card => {
    card.dataset.hidden = "false";
});

showPage(1);


document
.getElementById("resetSearch")
.addEventListener("click", function(){

    document.getElementById("lessonSearch").value = "";

    cards.forEach(card => {
        card.dataset.hidden = "false";
    });

    document.getElementById("noResults").style.display = "none";

    currentPage = 1;

    showPage(1);

});

</script>

  </body>
</html>