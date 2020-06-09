<?php include("includes/init.php");

$title = "Movies";

//messages for user (so they know what they searched for)
$messages = array();

//For updating a movie to favorites
if (isset($_POST["updateFavorite"])) {
  $movieId = filter_input(INPUT_POST, 'movieID', FILTER_SANITIZE_STRING);
  $newFavorite = filter_input(INPUT_POST, 'updateFavorite', FILTER_SANITIZE_STRING);
  $sql_query =
    "UPDATE movies
    SET favorited = :newFavorite
    WHERE movies.id = :movieId";
  $parameters = array(
    ":newFavorite" => $newFavorite,
    ":movieId" => $movieId
  );
  $result = exec_sql_query($db, $sql_query, $parameters);
  if ($result) {
    $temp = $result->fetchAll();
  }
}

//function that prints out an array of movies
function print_movies($movies)
{
  foreach ($movies as $movie) {
?>
    <div class="movieWrap">
      <!-- Image Source: https://film-grab.com/ -->
      <img src="uploads/movies/<?php echo htmlspecialchars($movie["id"]) .  "." .  htmlspecialchars($movie["file_ext"]) ?>" alt="<?php echo htmlspecialchars($movie["movie_title"]) ?>" class="movieImg">

      <!-- Logic for the ajax favorite icon -->
      <form method="POST" class="favoritesForm">
        <input type="hidden" name="movieID" value="<?php echo htmlspecialchars($movie["id"]); ?>">

        <?php if ($movie["favorited"]) {   ?>
          <input type="hidden" name="updateFavorite" value="0" class="updateFavorite">
        <?php } else { ?>
          <input type="hidden" name="updateFavorite" value="1" class="updateFavorite">
        <?php } ?>

        <button type="submit" class="favoriteIcon <?php if ($movie["favorited"]) {
                                                    echo "favorited";
                                                  } ?>">&#9733;</button>
      </form>

      <!--Source: https://icons8.com/icons/set/info -->
      <!--Note: I never ended up implementing anything with this in the "final" submitted version. However, I'm still including this for the aesthetic and for symmetry. In the future this could be used for info on hover. -->
      <span class="infoIcon"><a><img src="icons/info.png" alt="Info"></a></span>

      <a href="movie.php?<?php echo http_build_query(array('movies_id' => $movie["id"])); ?>">
        <p class="movieTitle"><?php echo htmlspecialchars($movie["movie_title"]) . " (" . htmlspecialchars($movie["release_date"]) . ")" ?></p>
      </a>

      <!--Source: https://icons8.com/icons/set/play-button -->
      <!--On click this sends the user to the selected movie page -->
      <span class="playIcon">
        <a href="movie.php?<?php echo http_build_query(array('movies_id' => $movie["id"])); ?>">
          <img src="icons/play.png" alt="Play">
        </a>
      </span>


    </div>
  <?php
  }
}

// For browsing by genre, director, or actor
if (isset($_GET["genre"])) {
  $genre = filter_input(INPUT_GET, 'genre', FILTER_SANITIZE_STRING);
  $sql_query =
    "SELECT * FROM movies
  WHERE movies.genre = :genre";
  $parameters = array(":genre" => $genre);

  $message = "Genre: " . $genre;
  array_push($messages, $message);
} elseif (isset($_GET["director"])) {
  $director = filter_input(INPUT_GET, 'director', FILTER_SANITIZE_STRING);
  $sql_query =
    "SELECT * FROM movies
    WHERE movies.director_name = :director";
  $parameters = array(":director" => $director);

  $message = "Director: " . $director;
  array_push($messages, $message);
} elseif (isset($_GET["actor"])) {

  $actor = filter_input(INPUT_GET, 'actor', FILTER_SANITIZE_STRING);
  $sql_query =
    "SELECT movies.id, movies.movie_title, movies.file_ext, movies.release_date, movies.trailer_link, movies.director_name, movies.genre, movies.favorited FROM movies LEFT OUTER JOIN movie_actors
    ON movies.id = movie_actors.movies_id
    LEFT OUTER JOIN actors ON movie_actors.actors_id = actors.id
    WHERE actors.actor_name = :actor";
  $parameters = array(":actor" => $actor);

  $message = "Actor: " . $actor;
  array_push($messages, $message);
} elseif (isset($_GET["favorites"])) {
  $title = "Favorites";
  $favorited = filter_input(INPUT_GET, 'favorites', FILTER_SANITIZE_STRING);
  $sql_query =
    "SELECT * FROM movies
    WHERE favorited = :favorited";
  $parameters = array(":favorited" => $favorited);

  $message = "Favorite Movies";
  array_push($messages, $message);
} elseif (isset($_GET["search"])) {
  $search_query = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $sql_query =
    "SELECT * FROM movies
    WHERE movie_title LIKE '%' || :search_query || '%'
    ORDER BY movie_title ASC";
  $parameters = array(":search_query" => $search_query);

  $message = "Search: " . $search_query;
  array_push($messages, $message);
} else {
  $sql_query = "SELECT * FROM movies ORDER BY movie_title ASC";
  // $sql_query = "SELECT * FROM movies ORDER BY random()";
  $parameters = array();

  $message = "Viewing All Movies";
  array_push($messages, $message);
}

$result = exec_sql_query($db, $sql_query, $parameters);
if ($result) {
  $movies = $result->fetchAll();
}

function print_messages()
{
  global $messages;
  foreach ($messages as $message) {
  ?>
    <p class="galleryMessage"><?php echo htmlspecialchars($message) ?></p>
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
  <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
  <script src="/js/jquery-3.5.0.js"></script>
  <script src="/js/scripts.js"></script>
  <!--Source: https://icons8.com/icons/set/play-button -->
  <link rel="icon" href="icons/favicon.ico" type="image/x-icon" />
  <title>2300Trailers | <?php echo $title ?></title>
</head>

<body class="homeGallery">

  <header>
    <?php include("includes/navbar.php"); ?>
  </header>

  <?php print_messages(); ?>

  <div class="movieList">
    <div class="grid-container">

      <?php print_movies($movies); ?>

    </div>
  </div>

  <?php if(empty($movies)){ ?>

    <p class="noResultsMessage">Sorry, No Results Founds</p>

    <?php
  } else { ?>
  <div class="citationsContainer">
    <p class="imageCitation">All images sourced from <a href="https://film-grab.com/" target="_blank">film-grab.com</a></p>
    <p class="imageCitation">All icons sourced from <a href="https://icons8.com/icons" target="_blank">icons8.com</a></p>
  </div>
  <?php } ?>

</body>

</html>
