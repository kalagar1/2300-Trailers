<?php include("includes/init.php");

$title = "Browse";

function print_genres()
{
    global $db;
    $sql_query =
        "SELECT movies.genre FROM movies
    GROUP BY movies.genre
    ORDER BY count(*) DESC";
    $parameters = array();
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $genres = $result->fetchAll();
    }

    foreach ($genres as $genre) {
?>
        <a href="index.php?<?php echo http_build_query(array('genre' => $genre[0])); ?>">
            <p class="browseTags"><?php echo htmlspecialchars($genre[0]) ?></p>
        </a>
    <?php
    }
}

function print_directors()
{
    global $db;
    $sql_query =
        "SELECT movies.director_name FROM movies
    GROUP BY movies.director_name
    ORDER BY count(*) DESC";
    $parameters = array();
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $directors = $result->fetchAll();
    }

    foreach ($directors as $director) {
    ?>
        <a href="index.php?<?php echo http_build_query(array('director' => $director[0])); ?>">
            <p class="browseTags"><?php echo htmlspecialchars($director[0]) ?></p>
        </a>
    <?php
    }
}

function print_actors()
{
    global $db;
    $sql_query =
        "SELECT actors.actor_name
        FROM movies INNER JOIN movie_actors
        ON movies.id = movie_actors.movies_id
        INNER JOIN actors ON movie_actors.actors_id = actors.id
        GROUP BY actors.actor_name
        ORDER BY count(*) DESC";
    $parameters = array();
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $actors = $result->fetchAll();
    }

    foreach ($actors as $actor) {
    ?>
        <a href="index.php?<?php echo http_build_query(array('actor' => $actor[0])); ?>">
            <p class="browseTags"><?php echo htmlspecialchars($actor[0]) ?></p>
        </a>
<?php
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/styles.css">
    <script src="/js/jquery-3.5.0.js"></script>
    <!--Source: https://icons8.com/icons/set/play-button -->
    <link rel="icon" href="icons/favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <title>2300Trailers | Browse By</title>
</head>

<body>

    <header>
        <?php include("includes/navbar.php"); ?>
    </header>


    <div class="browseCategories">
        <p class="browseTip">Note: Tags are Ordered by Movie Count</p>

        <p>Genres:</p>
        <div class="tagContainer">
            <?php print_genres(); ?>
        </div>

        <p>Directors:</p>
        <div class="tagContainer">
            <?php print_directors(); ?>
        </div>

        <p>Actors:</p>
        <div class="tagContainer">
            <?php print_actors(); ?>
        </div>
    </div>


    <div class="citationsContainer">
        <p class="imageCitation">All images sourced from <a href="https://film-grab.com/" target="_blank">film-grab.com</a></p>
        <p class="imageCitation">All icons sourced from <a href="https://icons8.com/icons" target="_blank">icons8.com</a></p>
    </div>

</body>

</html>
