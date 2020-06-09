<?php include("includes/init.php");

//gets movie id from the query string parameter
$movie_id = (int) filter_input(INPUT_GET, 'movies_id', FILTER_SANITIZE_STRING);;

//Gets movie and all accompanying information
$sql_query = "SELECT * FROM movies WHERE id = :movie_id";
$parameters = array(':movie_id' => $movie_id);
$result = exec_sql_query($db, $sql_query, $parameters);

if ($result) {
    $movies = $result->fetchAll();
}

$movie = $movies[0];


// function that prints out all the movie information (director, genre, actors)
function print_information($db, $movie_id, $movie)
{
    //Gets all the actor tags associated with the selected movie
    $sql_query = "
    SELECT actors.actor_name
    FROM movies LEFT OUTER JOIN movie_actors
    ON movies.id = movie_actors.movies_id
    LEFT OUTER JOIN actors ON movie_actors.actors_id = actors.id
    WHERE movies.id = :movie_id";
    $parameters = array(':movie_id' => $movie_id);
    $result = exec_sql_query($db, $sql_query, $parameters);

    if ($result) {
        $actors = $result->fetchAll();
    }

    //Only prints genre if there is a genre
    if (!empty($movie["genre"])) {
?>
        <p class="descTags">Genre: <?php echo htmlspecialchars($movie["genre"]) ?></p>
    <?php
    }

    //Only prints the director name if one exists
    if (!empty($movie["director_name"])) {
    ?>
        <p class="descTags">Director: <?php echo htmlspecialchars($movie["director_name"]) ?></p>
    <?php
    }

    //Only prints actors if there are actors
    if (!is_null($actors[0]["actor_name"])) {
        $actors_string = 'Actors: ';
        foreach ($actors as $actor) {
            $actors_string = $actors_string . $actor['actor_name'] . ', ';
        }
        $actors_string = rtrim($actors_string, ", ");

    ?>
        <p class="descTags"><?php echo htmlspecialchars($actors_string) ?></p>
<?php
    }
}


// Converts a youtube link to the correct embed link
// Major Inspiration from: https://stackoverflow.com/questions/19050890/find-youtube-link-in-php-string-and-convert-it-into-embed-code
function getEmbedVideo($url)
{
    $urlParts = explode('/', $url);
    $embedId = explode('&', str_replace('watch?v=', '', end($urlParts)));
    return 'https://www.youtube.com/embed/' . $embedId[0] . '?autoplay=1';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/styles.css">
    <script src="/js/jquery-3.5.0.js"></script>
    <script src="/js/scripts.js"></script>
    <!--Source: https://icons8.com/icons/set/play-button -->
    <link rel="icon" href="icons/favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <title>2300Trailers | <?php echo $movie["movie_title"] ?></title>
</head>

<body>

    <header>
        <?php include("includes/navbar.php"); ?>
    </header>

    <div class="videoContainer">
        <!--Movie image that is used as initial background of video. Remaining logic in javascript scripts-->
        <img src="uploads/movies/<?php echo htmlspecialchars($movie["id"]) .  "." .  $movie["file_ext"] ?>" alt="<?php echo htmlspecialchars($movie["movie_title"]) ?>" class="playImg" data-video="<?php echo getEmbedVideo(htmlspecialchars($movie["trailer_link"])); ?>">

        <!-- Source:https://icons8.com/icons/set/play-button -->
        <!-- Logic for the placement of this icon is in javascript-->
        <a href="#" class="bigPlayIcon"><img src="icons/bigPlay.png" alt="Play Icon"></a>

    </div>

    <div class="videoDesc">
        <h2 class="descTitle"><?php echo htmlspecialchars($movie["movie_title"]) . " (" . htmlspecialchars($movie["release_date"]) . ")" ?></h2>

        <div class="videoTags">
            <?php print_information($db, $movie_id, $movie); ?>
        </div>
    </div>

    <div class="citationsContainer">
        <p class="imageCitation">All images sourced from <a href="https://film-grab.com/" target="_blank">film-grab.com</a></p>
        <p class="imageCitation">All icons sourced from <a href="https://icons8.com/icons" target="_blank">icons8.com</a></p>
    </div>

</body>

</html>
