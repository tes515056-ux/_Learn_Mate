<?php

session_start();
include '../../dblink.php';


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

if (!isset($_GET['subject_id'])) {
    die("Subject not found");
}

$subject_id = (int)$_GET['subject_id'];

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM courses
     WHERE subject_id = $subject_id
     ORDER BY course_name"
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
        .course-card .card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    transition:all .3s ease;
    background:#fff;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.course-card .card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(105,108,255,.18);
}

.course-icon{
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

    box-shadow:0 10px 25px rgba(105,108,255,.25);

    margin-bottom:20px;
}

.course-icon i{
    font-size:40px;
}

.course-title{
    font-size:20px;
    font-weight:700;
    color:#566a7f;
    min-height:50px;
}

.course-desc{
    color:#8592a3;
    font-size:14px;
    min-height:70px;
}

.course-badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:30px;
    background:#eef1ff;
    color:#696cff;
    font-size:13px;
    font-weight:600;
    margin-bottom:15px;
}

.course-btn{
    border-radius:12px;
    padding:10px 22px;
    font-weight:600;
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
<div class="container-xxl flex-grow-1 container-p-y">

    <h3 class="mb-4">
        Courses
    </h3>

<div class="mb-3">
   <div class="input-group mb-4">

    <span class="input-group-text">
        <i class="bx bx-search"></i>
    </span>

    <input
        type="text"
        id="courseSearch"
        class="form-control"
        placeholder="Search courses..."
    >

</div>

<div class="row" id="courseContainer">
        <?php while($course = mysqli_fetch_assoc($result)){ ?>
<div class="col-xl-3 col-lg-4 col-md-6 mb-4 course-card">

    <div class="card h-100">

        <div class="card-body text-center p-4 d-flex flex-column">

            <div class="course-icon">
                <i class="bx bx-book-open"></i>
            </div>

            

            <h5 class="course-title">
                <?php echo htmlspecialchars($course['course_name']); ?>
            </h5>

            <a
                href="course_lessons.php?course_id=<?php echo $course['id']; ?>"
                class="btn btn-primary course-btn mt-auto">

                View Lessons

            </a>

        </div>

    </div>

</div>

<div id="noResults" class="alert alert-warning text-center" style="display:none;">
    No courses found.
</div>

        <?php } ?>


    </div>

</div>

<div
    id="pagination"
    class="d-flex justify-content-center mt-4">
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

const allCards =
    Array.from(document.querySelectorAll('.course-card'));

const coursesPerPage = 8;

let currentPage = 1;

const paginationContainer =
    document.getElementById('pagination');

const searchInput =
    document.getElementById('courseSearch');

function getFilteredCards()
{
    const value =
        searchInput.value.toLowerCase();

    return allCards.filter(card => {

        const title =
            card.querySelector('.course-title')
                .innerText
                .toLowerCase();

        const desc =
            card.querySelector('.course-title')
                .innerText
                .toLowerCase();

        return title.includes(value) ||
               desc.includes(value);

    });
}

function showPage(page)
{
    const filteredCards =
        getFilteredCards();

    allCards.forEach(card => {
        card.style.display = 'none';
    });

    const totalPages =
        Math.ceil(filteredCards.length / coursesPerPage);

    if(page > totalPages && totalPages > 0)
    {
        page = 1;
    }

    currentPage = page;

    const start =
        (currentPage - 1) * coursesPerPage;

    const end =
        start + coursesPerPage;

    filteredCards
        .slice(start, end)
        .forEach(card => {
            card.style.display = '';
        });

    document.getElementById('noResults').style.display =
        filteredCards.length === 0
        ? 'block'
        : 'none';

    renderPagination(totalPages);
}

function renderPagination(totalPages)
{
    if(totalPages <= 1)
    {
        paginationContainer.innerHTML = '';
        return;
    }

    let html = '<nav><ul class="pagination">';

    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#"
               onclick="changePage(${currentPage - 1});return false;">
               Previous
            </a>
        </li>
    `;

    for(let i = 1; i <= totalPages; i++)
    {
        html += `
            <li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#"
                   onclick="changePage(${i});return false;">
                   ${i}
                </a>
            </li>
        `;
    }

    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#"
               onclick="changePage(${currentPage + 1});return false;">
               Next
            </a>
        </li>
    `;

    html += '</ul></nav>';

    paginationContainer.innerHTML = html;
}

function changePage(page)
{
    showPage(page);
}

searchInput.addEventListener('keyup', () => {

    currentPage = 1;
    showPage(1);

});

showPage(1);

</script>

  </body>
</html>