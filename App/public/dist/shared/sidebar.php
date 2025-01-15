
<div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- Section principale -->
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="http://localhost/Youdemy_plateform/App/public/dist/dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <!-- Gestion des étudiants -->
                        <div class="sb-sidenav-menu-heading">Étudiants</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                            Étudiants
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseStudents" aria-labelledby="headingStudents" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="total-students.php">Voir total</a>
                                <a class="nav-link" href="manage-students.php">Gérer les étudiants</a>
                            </nav>
                        </div>

                        <!-- Gestion des enseignants -->
                        <div class="sb-sidenav-menu-heading">Enseignants</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTeachers" aria-expanded="false" aria-controls="collapseTeachers">
                            <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            Enseignants
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseTeachers" aria-labelledby="headingTeachers" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="total-teachers.php">Voir total</a>
                                <a class="nav-link" href="validate-teachers.php">Valider les enseignants</a>
                            </nav>
                        </div>

                        <!-- Gestion des contenus -->
                        <div class="sb-sidenav-menu-heading">Contenus</div>
                        <!-- Tags -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTags" aria-expanded="false" aria-controls="collapseTags">
                            <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                            Tags
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseTags" aria-labelledby="headingTags" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="http://localhost/Youdemy_plateform/App/views/tags.php">Tags</a>
                                <a class="nav-link" href="http://localhost/Youdemy_plateform/App/views/add_tag.php">Add tag</a>
                            </nav>
                        </div>

                        <!-- Catégories -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories">
                            <div class="sb-nav-link-icon"><i class="fas fa-folder"></i></div>
                            Catégories
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCategories" aria-labelledby="headingCategories" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="http://localhost/Youdemy_plateform/App/views/categories.php">catégories</a>
                                <a class="nav-link" href="http://localhost/Youdemy_plateform/App/views/add_category.php">Add catégory</a>
                            </nav>
                        </div>

                        <!-- Cours -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCourses" aria-expanded="false" aria-controls="collapseCourses">
                            <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                            Cours
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCourses" aria-labelledby="headingCourses" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="view-courses.php">Voir cours</a>
                                <a class="nav-link" href="manage-courses.php">Gérer les cours</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Pied de page de la barre latérale -->
                <div class="sb-sidenav-footer">
                    <div class="small">Connecté en tant que :</div>
                    Administrateur
                </div>
            </nav>
        </div>