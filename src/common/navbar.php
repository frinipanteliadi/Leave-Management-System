<section class="section">
    <div class="container">
        <nav class="navbar" role="navigation" aria-label="main navigation">

            <!--The left side of the navigation bar-->
            <div class="navbar-brand">
                <a class="navbar-item" href="https://www.epignosishq.com/">
                    <img src="https://www.epignosishq.com/wp-content/themes/epignosishq/dist/images/logo.svg" width="112" height="28">
                </a>
                <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item is-active">
                        Home
                    </a>
                    <a class="navbar-item">
                        Documentation
                    </a>
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            More
                        </a>
                        <div class="navbar-dropdown">
                            <a class="navbar-item">
                                About
                            </a>
                            <a class="navbar-item">
                                Contact
                            </a>
                            <hr class="navbar-divider">
                            <a class="navbar-item">
                                Report an issue
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                include_once dirname(__FILE__). '/auth.php';
                if (isLoggedIn()) {
                    $name_disp = $_SESSION['first_name'].' '.$_SESSION['last_name'];
                    echo '<div class="navbar-end">
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <div style="padding: 5px">'.$name_disp.'</div>
                                <img src="'.$_SESSION["image_url"].'" style="border-radius: 50%;">
                            </a>
                            <div class="navbar-dropdown">
                                <a class="navbar-item">
                                    Profile
                                </a>
                                <a class="navbar-item">
                                    Settings & Privacy
                                </a>
                                <a class="navbar-item">
                                    Help & Support
                                </a>
                                <hr class="navbar-divider">
                                <a class="navbar-item" href="/common/logout.php">
                                    Log Out
                                </a>
                            </div>
                        </div>
                    </div>';
                }
                ?>

            </div>
        </nav>
    </div>
</section>