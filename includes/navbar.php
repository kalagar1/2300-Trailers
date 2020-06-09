<nav class="nav">
    <a href="index.php"><img src="icons/logo.png" alt="Logo"></a>
    <p class="navbarTitle"><a href="index.php"> 2300Trailers.com</a></p>

    <ul>
        <li class="navbarLink <?php if ($title == "Movies") {
                                    echo "currentPage";
                                } ?>"><a href=" index.php" class="nav-link">Movies</a></li>
        <li class="navbarLink <?php if ($title == "Favorites") {
                                    echo "currentPage";
                                } ?>"><a href=" index.php?<?php echo http_build_query(array('favorites' => 1)); ?>" class="nav-link">Favorites</a></li>
        <li class="navbarLink <?php if ($title == "Browse") {
                                    echo "currentPage";
                                } ?>"><a href=" browse.php" class="nav-link">Browse By</a></li>
        <li class="navbarLink <?php if ($title == "Admin") {
                                    echo "currentPage";
                                } ?>"><a href=" admin.php" class="nav-link">Admin</a></li>
    </ul>

    <!-- Form for the search bar -->
    <form method="GET" action="index.php" id="searchForm">
        <input type="text" id="searchInput" placeholder="Search Movie Title" name="search" required>
        <!--Source: https://icons8.com/icons/set/search-icon -->
        <button type="submit" id="searchButton"><img src="icons/search.png" alt="Search Icon"></button>
    </form>

</nav>
