<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome to 386E Timesheet</title>
        <meta name="description" content="386E Timesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="/favicon.ico">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
        <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>
        <style type="text/css">
            body {
                background-color: #fbfbfb;
            }
            @media (min-width: 991.98px) {
                main {
                    padding-left: 240px;
                }
            }

            /* Sidebar */
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                padding: 58px 0 0; /* Height of navbar */
                box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
                width: 240px;
                z-index: 600;
            }

            @media (max-width: 991.98px) {
                .sidebar {
                    width: 100%;
                }
            }
            .sidebar .active {
                border-radius: 5px;
                box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
            }

            .sidebar-sticky {
                position: relative;
                top: 0;
                height: calc(100vh - 48px);
                padding-top: 0.5rem;
                overflow-x: hidden;
                overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
            }

            th.min, td.min {
                width: 1%;
                white-space: nowrap;
                padding: 0px 5px;
            }

            .gap-3
            {
                padding-bottom: 30px;
            }

            .hidden
            {
                display: none;
            }

            /*            .card-header
                        {
                            color: #fff;
                            background-color: #0d6efd;
                            border-color: #0d6efd;
                        }*/

            .card-header {
                background-color: rgba(0,0,0,.12);
            }

            .cursor-pointer
            {
                cursor: pointer;
            }

            td.table-active .btn-quickcheckin,
            td.table-dark .btn-quickcheckin
            {
                display: none;
            }
        </style>

    </head>
    <body>

        <!--Main Navigation-->
        <header>
            <!-- Sidebar -->
            <nav
                id="sidebarMenu"
                class="collapse d-lg-block sidebar collapse bg-white"
                >
                <div class="position-sticky">
                    <div class="list-group list-group-flush mx-3 mt-4">
                        <a id="menu-userlist"
                           href="{site_url}users"
                           class="list-group-item list-group-item-action py-2 ripple"
                           aria-current="true"
                           >
                            <i class="fas fa-users fa-fw me-3"></i
                            ><span>D.s. nhân viên</span>
                        </a>
                        <a id="menu-timesheet"
                           href="{site_url}request"
                           class="list-group-item list-group-item-action py-2 ripple"
                           >
                            <i class="fas fa-chart-area fa-fw me-3"></i
                            ><span>Bảng chấm công</span>
                        </a>
                        <a id="menu-createrequest"
                           href="{site_url}request/create"
                           class="list-group-item list-group-item-action py-2 ripple"
                           >
                            <i class="fas fa-file-circle-plus fa-fw me-3"></i
                            ><span>Tạo yêu cầu</span>
                        </a>
                        {is_lead_funs}
                        <a id="menu-listrequest"
                           href="{site_url}request/list"
                           class="list-group-item list-group-item-action py-2 ripple"
                           ><i class="fas fa-calendar fa-fw me-3"></i
                            ><span>D.s. yêu cầu</span></a
                        >
                        {/is_lead_funs}
                        {is_admin_funs}
                        <a id="menu-listrequest"
                           href="{site_url}request/list"
                           class="list-group-item list-group-item-action py-2 ripple"
                           ><i class="fas fa-calendar fa-fw me-3"></i
                            ><span>D.s. yêu cầu</span></a
                        >
                        <a id="menu-holiday"
                           href="{site_url}home/holidays"
                           class="list-group-item list-group-item-action py-2 ripple"
                           ><i class="fas fa-face-grin-hearts fa-fw me-3"></i
                            ><span>Ngày lễ trong năm</span></a
                        >
                        <a id="menu-download"
                           href="{site_url}users/download"
                           class="list-group-item list-group-item-action py-2 ripple"
                           ><i class="fas fa-file-arrow-down fa-fw me-3"></i
                            ><span>Tải dữ liệu</span></a
                        >
                        <a id="menu-settings"
                           href="{site_url}settings"
                           class="list-group-item list-group-item-action py-2 ripple"
                           ><i class="fas fa-screwdriver-wrench fa-fw me-3"></i
                            ><span>Thiết lập hệ thống</span></a
                        >
                        {/is_admin_funs}
                        {is_logged_user}
                        <a id="menu-createibaoviet"
                           href="{site_url}request/createibaoviet"
                           class="list-group-item list-group-item-action py-2 ripple"
                           >
                            <i class="fas fa-building-circle-exclamation fa-fw me-3"></i
                            ><span>Y/c bồi thường</span>
                        </a>
                        <a id="menu-profile"
                           href="{site_url}users/profile"
                           class="list-group-item list-group-item-action py-2 ripple"
                           ><i class="fas fa-lock fa-fw me-3"></i><span>Thông tin cá nhân</span></a
                        >
                        <a
                            href="{site_url}users/logout"
                            class="list-group-item list-group-item-action py-2 ripple"
                            ><i class="fas fa-fw fa-right-from-bracket me-3"></i></i
                            ><span>Đăng xuất</span></a
                        >
                        {/is_logged_user}
                        {not_logged_user}
                        <a
                            href="{site_url}users/login"
                            class="list-group-item list-group-item-action py-2 ripple"
                            ><i class="fas fa-fw fa-right-from-bracket me-3"></i></i
                            ><span>Đăng nhập</span></a
                        >
                        {/not_logged_user}
                    </div>
                </div>
            </nav>
            <!-- Sidebar -->

            <!-- Navbar -->
            <nav
                id="main-navbar"
                class="navbar navbar-expand-lg navbar-light bg-dark fixed-top"
                >
                <!-- Container wrapper -->
                <div class="container-fluid">

                    <!-- Brand -->
                    <a class="navbar-brand" href="{site_url}">
                        <img
                            src="https://568e.vn/corp/images/logo.png"
                            height="25"
                            alt=""
                            loading="lazy"
                            />
                    </a>

                    <!-- Toggle button -->

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#sidebarMenu"
                            aria-controls="sidebarMenu"
                            aria-expanded="false" aria-label="Hiển thị menu">
                        <i class="fa-sharp fa-solid fa-bars text-white"></i>
                    </button>

                </div>
                <!-- Container wrapper -->
            </nav>
            <!-- Navbar -->
        </header>
        <!--Main Navigation-->

        <!--Main layout-->
        <main style="margin-top: 58px">
            <div class="container pt-4">

                <div id="isMissingCheckin" class="alert alert-warning hidden" role="alert">
                    Hệ thống chưa thấy lượt check-in của bạn trên máy chấm công, <a href="{site_url}users/checklogin/2">bấm vào đây để thực hiện check-in</a>.
                </div>