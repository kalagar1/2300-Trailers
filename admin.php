<!-- Sources: Looked at lab 8 as a reference for file uploads -->
<?php include("includes/init.php");

$title = "Admin";

//messages for user (to update them of form progress)
$messages = array();

//Logic for checking if the admin is actually an admin
if (isset($_POST["adminCheck"])) {
    $adminName = filter_input(INPUT_POST, 'adminName', FILTER_SANITIZE_STRING);
    setcookie('adminName', $adminName, time() + 1000);
    $is_admin = TRUE;
} else if (isset($_COOKIE['adminName'])) {
    $adminName = $_COOKIE['adminName'];
    $is_admin = TRUE;
}


if (isset($_POST["uploadMovie"])) {
    $image_upload = ($_FILES['movieImage']);
    $movie_title = filter_input(INPUT_POST, 'movieTitle', FILTER_SANITIZE_STRING);
    $release_date = filter_input(INPUT_POST, 'releaseDate', FILTER_SANITIZE_STRING);
    $trailer_link = filter_input(INPUT_POST, 'trailerLink', FILTER_SANITIZE_STRING);

    if (isset($_POST['directorName'])) {
        $director_name = filter_input(INPUT_POST, 'directorName', FILTER_SANITIZE_STRING);
    } else {
        $director_name = NULL;
    }

    if (isset($_POST['genreName'])) {
        $genre = filter_input(INPUT_POST, 'genreName', FILTER_SANITIZE_STRING);
    } else {
        $genre = NULL;
    }

    if ($image_upload['error'] == UPLOAD_ERR_OK) {
        //Source: looked at my lab for clarification and guidance on this
        //there is no error when uploading the file
        $image_name = basename($image_upload["name"]);
        $image_ext = strtolower(pathinfo($image_name)['extension']);

        $sql_query = "INSERT INTO movies (movie_title, file_ext, release_date, trailer_link, director_name, genre) VALUES (:movie_title, :image_ext, :release_date, :trailer_link, :director_name, :genre)";
        $paramaters = array(
            ':movie_title' => $movie_title,
            ':image_ext' => $image_ext,
            ':release_date' => $release_date,
            ':trailer_link' => $trailer_link,
            ':director_name' => $director_name,
            ':genre' => $genre
        );
        $result = exec_sql_query($db, $sql_query, $paramaters);

        $movie_id = $db->lastInsertId("id");
        $new_path = "uploads/movies/" . $movie_id . '.' . $image_ext;
        move_uploaded_file($image_upload['tmp_name'], $new_path);

        $hyperlink = '<a href=movie.php?movies_id=' . htmlspecialchars($movie_id) . '>' . htmlspecialchars($movie_title) . '</a>';
        $message = $hyperlink . " Has Been Uploaded";
        array_push($messages, $message);
    } else {
        $message = '<p class="errorMessage adminMessage"> Error Uploading Image, Please Try Again <p>';
        array_push($messages, $message);
    }
}

function print_messages()
{
    global $messages;
    foreach ($messages as $message) {
    ?>
        <p class="adminMessage"><?php echo $message ?></p>
    <?php
    }
}

// function that prints out all the current movies. This is used for forms to select which movie to update or delete
function print_movie_options()
{
    global $db;
    $sql_query = "SELECT * FROM movies ORDER BY movie_title";
    $parameters = array();
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $movies = $result->fetchAll();
    }
    foreach ($movies as $movie) {
        $title = $movie["movie_title"];
    ?>
        <option value="<?php echo htmlspecialchars($movie["id"]) ?>"><?php echo htmlspecialchars($title) . " (" . htmlspecialchars($movie["release_date"]) . ")"  ?></option>
    <?php
    }
}

// Prints all exisiting actors. This is used for the update actor form when deciding whether to add an existing actor
function print_actor_options()
{
    global $db;
    $sql_query = "SELECT * FROM actors ORDER BY actor_name";
    $parameters = array();
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $actors = $result->fetchAll();
    }
    foreach ($actors as $actor) {
        $name = $actor["actor_name"];
    ?>
        <option value="<?php echo htmlspecialchars($actor["id"]) ?>"><?php echo htmlspecialchars($name) ?></option>
    <?php
    }
}

// When the user selects a movie to update actors, get the associated movie id, and print out the rest of the form logic
if (isset($_POST['updateMovieId'])) {
    $movie_id = filter_input(INPUT_POST, 'updateMovieId', FILTER_SANITIZE_STRING);

    //prints out the form that allows the user to update the actor tags
    display_update_actor_form($movie_id);
    exit();
}

// This function shows a form that allows the user to delete current actors, add existing actors, and add new actors to a movie.
// This function logic is complicated but makes sense when you look at the php code in conjunction with the javascript in scripts.js
function display_update_actor_form($movie_id)
{
    global $db;

    //Gets movie and all accompanying information
    $sql_query = "SELECT * FROM movies WHERE id = :movie_id";
    $parameters = array(':movie_id' => $movie_id);
    $result = exec_sql_query($db, $sql_query, $parameters);

    if ($result) {
        $movies = $result->fetchAll();
    }
    $movie = $movies[0];

    //Gets all the current actor tags associated with the selected movie
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

    ?>

    <h2 class="actorMovieTitle"><?php echo htmlspecialchars($movie["movie_title"]) . " (" . htmlspecialchars($movie["release_date"]) . ")" ?></h2>

    <form action="admin.php" method="post" class="adminForm" id="updateActorForm">

        <!-- Only displays the option to delete current actor tags if these actors exist -->
        <?php if (!is_null($actors[0]["actor_name"])) { ?>
            <p class="actorFormDesc">Current Actors:</p>

            <?php
            //Adds a new checkbox option for every actor
            foreach ($actors as $actor) {
            //The checkbox name is an array so that infinite many actors can be deleted
            //The id of the input and label are the actors name without any spaces (hence the preg replace method)
            //When the admin submits the form the "deleteActors" post super global contains an array of all actors to be deleted
            ?>
                <div class="formRow formCheckbox">
                    <input type="checkbox" class="updateActorCheckbox" id="<?php echo htmlspecialchars(preg_replace('/\s+/', '', $actor["actor_name"])) ?>" name="deleteActors[]" value="<?php echo htmlspecialchars($actor["actor_name"]) ?>">

                    <label for="<?php echo htmlspecialchars(preg_replace('/\s+/', '', $actor["actor_name"])) ?>"> <?php echo htmlspecialchars($actor["actor_name"]) ?> <img src="icons/delete.png" alt="Delete Icon" class="deleteIcon"></label>
                    <!-- Source: https://icons8.com/icons/set/delete -->
                </div>
        <?php
            }
        }
        ?>


        <p class="actorFormDesc">Add Existing Actors:</p>
        <div id="newExistingActors">

        </div>
        <div class="formRow newActorRow">
            <div class="formRowElement updateActorElement">
                <select name="selectExistingActor" id="selectedActor" class="formInput">
                    <option disabled selected hidden>Select Existing Actor</option>
                    <?php print_actor_options(); ?>
                </select>
            </div>
            <div class="formRowElement">
                <!-- The logic for this button is in the scripts.js file-->
                <button id="newExistingActor" name="newExistingActor" type="button" class="formSubmit">Add</button>
            </div>
        </div>

        <p class="actorFormDesc">Add New Actors:</p>
        <div id="newActors">

        </div>
        <div class="formRow newActorRow">
            <div class="formRowElement updateActorElement">
                <input class="formInput" id="newActorName" name="newActorName" type="text" placeholder="Add New Actor">
            </div>
            <div class="formRowElement">
                <!-- The logic for this button is in the scripts.js file -->
                <button id="brandNewActor" name="brandNewActor" type="button" class="formSubmit">Add</button>
            </div>
        </div>


        <button name="updateActorId" type="submit" class="formSubmit" value="<?php echo htmlspecialchars($movie["id"]) ?>">Update</button>

        <button type="reset" class="formSubmit cancelButton" onclick="location.reload(true);">Cancel</button>

    </form>

<?php
}


if (isset($_POST['updateActorId'])) {
    $movie_id = filter_input(INPUT_POST, 'updateActorId', FILTER_SANITIZE_STRING);

    //Gets movie and all accompanying information
    $sql_query = "SELECT * FROM movies WHERE id = :movie_id";
    $parameters = array(':movie_id' => $movie_id);
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $movies = $result->fetchAll();
    }
    $movie = $movies[0];

    //are there any actors to delete?
    if (isset($_POST['deleteActors'])) {
        //used FILTER_REQUIRE_ARRAY instead of sanitize string
        $deleteActors = filter_input(INPUT_POST, 'deleteActors', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($deleteActors as $actor_name) {
            $sql_query = "
        DELETE FROM movie_actors
        WHERE movies_id = :movie_id
        AND actors_id = (
            SELECT id FROM actors WHERE actor_name = :actor_name)";
            $parameters = array(':actor_name' => $actor_name, ':movie_id' => $movie_id);
            $result = exec_sql_query($db, $sql_query, $parameters);
            if ($result) {
                $result->fetchAll();
            }
        }
    }

    //Are there existing actors to update?
    if (isset($_POST['addExistingActors'])) {
        $addActors = filter_input(INPUT_POST, 'addExistingActors', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($addActors as $actor_id) {
            if (!is_null($actor_id)) {
                //"INSERT OR IGNORE" ensures that the unique constraint isn't violated
                $sql_query = "INSERT OR IGNORE INTO movie_actors(movies_id, actors_id) VALUES (:movie_id, :actor_id);";
                $parameters = array(':movie_id' => $movie_id, ':actor_id' => $actor_id);
                $result = exec_sql_query($db, $sql_query, $parameters);
                if ($result) {
                    $result->fetchAll();
                }
            }
        }
    }

    //Are there new actors to insert?
    if (isset($_POST['addNewActors'])) {
        $addActors = filter_input(INPUT_POST, 'addNewActors', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($addActors as $actor_name) {
            if (!is_null($actor_name)) {
                //Insert new actor into the actors table. "INSERT OR IGNORE" ensures that the unique constraint isn't violated
                $db->beginTransaction();
                $sql_query = "INSERT OR IGNORE INTO actors(actor_name) VALUES (:actor_name);";
                $parameters = array(':actor_name' => $actor_name);
                $result = exec_sql_query($db, $sql_query, $parameters);
                if ($result) {
                    $result->fetchAll();
                }
                $db->commit();


                //Get correct actor id. (Can't use the built in function as the record may not be inserted if it's a duplicate)
                $sql_query = "SELECT id FROM actors WHERE actor_name = :actor_name";
                $parameters = array(':actor_name' => $actor_name);
                $result = exec_sql_query($db, $sql_query, $parameters);
                if ($result) {
                    $actor_ids = $result->fetchAll();
                }
                $actor_id = $actor_ids[0]["id"];


                // //Insert the record in the movie_actors table
                $db->beginTransaction();
                $sql_query = "INSERT OR IGNORE INTO movie_actors(movies_id, actors_id) VALUES (:movie_id, :actor_id);";
                $parameters = array(':movie_id' => $movie_id, ':actor_id' => $actor_id);
                $result = exec_sql_query($db, $sql_query, $parameters);
                if ($result) {
                    $result->fetchAll();
                }
                $db->commit();
            }
        }
    }

    //Outputs message with a hyperlink to ease in checking if the actors were updated
    $hyperlink = '<a href=movie.php?movies_id=' . htmlspecialchars($movie_id) . '>' . htmlspecialchars($movie["movie_title"])  . "'s" . '</a>';
    $message = $hyperlink . " Actors Have Been Updated";
    array_push($messages, $message);
}

if (isset($_POST['deleteMovieId'])) {
    $movie_id = filter_input(INPUT_POST, 'deleteMovieId', FILTER_SANITIZE_STRING);

    //Gets movie and all accompanying information
    $sql_query = "SELECT * FROM movies WHERE id = :movie_id";
    $parameters = array(':movie_id' => $movie_id);
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $movies = $result->fetchAll();
    }
    $movie = $movies[0];

    $movie_title = $movie["movie_title"];

    //builds path to disk
    $path = "uploads/movies/";
    $file_ext = $movie["file_ext"];
    $filename = $path . $movie_id . "." . $file_ext;

    //deletes image off of disk
    unlink($filename);

    //delete from movie table
    $sql_query = "
        DELETE FROM movies
        WHERE id = :movie_id";
    $parameters = array(':movie_id' => $movie_id);
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $result->fetchAll();
    }

    //delete relationships in movie_actors table
    $sql_query = "
        DELETE FROM movie_actors
        WHERE movies_id = :movie_id";
    $parameters = array(':movie_id' => $movie_id);
    $result = exec_sql_query($db, $sql_query, $parameters);
    if ($result) {
        $result->fetchAll();
    }

    $message = $movie_title  . " Has Been Deleted";
    array_push($messages, $message);
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
    <title>2300Trailers | Admin</title>
</head>

<body>

    <header>
        <?php include("includes/navbar.php"); ?>
    </header>


    <?php if ($is_admin) { ?>

        <p class="adminNameMessage">Welcome, <?php echo htmlspecialchars($adminName); ?>!</p>

        <?php print_messages();  ?>

        <p class="adminSection">Upload New Movie</p>

        <p class="imageCitation">Note: All images have been sourced from <a href="https://film-grab.com/" target="_blank">film-grab.com</a></p>

        <form action="admin.php" method="POST" enctype="multipart/form-data" id="movieUpload" class="adminForm">
            <!-- hidden input for required max file size -->
            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
            <div class="formRow">
                <div class="formRowElement">
                    <input class="formInput" id="movieTitle" name="movieTitle" type="text" placeholder="Movie Title*" required>
                </div>

                <div class="fileUpload formRowElement">
                    <label for="movieImage">Movie Image*:</label>
                    <input id="movieImage" type="file" name="movieImage" accept="image/*" required>
                </div>
            </div>
            <div class="formRow">
                <div class="formRowElement">
                    <input class="formInput" id="releaseDate" name="releaseDate" type="text" placeholder="Release Date*" required>
                </div>
                <div class="formRowElement">
                    <input class="formInput" id="trailerLink" name="trailerLink" type="text" placeholder="Trailer Youtube Link*" required>
                </div>
            </div>

            <div class="formRow">
                <div class="formRowElement">
                    <input class="formInput" id="directorName" name="directorName" type="text" placeholder="Director">
                </div>
                <div class="formRowElement">
                    <input class="formInput" id="genreName" name="genreName" type="text" placeholder="Genre">
                </div>
            </div>

            <button name="uploadMovie" type="submit" class="formSubmit">Upload</button>
            <button type="reset" class="formSubmit cancelButton" onclick=" document.getElementById('movieUpload').reset();">Cancel</button>

        </form>


        <p class="adminSection">Update Actor Tags</p>

        <form action="admin.php" method="POST" class="adminForm updateMovieSelector">
            <div class="formRow">
                <div class="formRowElement updateActorElement">
                    <select name="updateMovieId" class="formInput">
                        <option value="" disabled selected hidden>Select Movie to Update</option>
                        <?php print_movie_options(); ?>
                    </select>
                </div>
                <div class="formRowElement">
                    <button name="actorUpdate" type="submit" class="formSubmit">Select</button>
                </div>
            </div>
        </form>

        <div id="updateActorFormDiv"></div>




        <p class="adminSection">Delete Movie</p>

        <form action="admin.php" method="POST" class="adminForm">
            <div class="formRow">
                <div class="formRowElement deleteMovieElement">
                    <select name="deleteMovieId" class="formInput">
                        <option value="" disabled selected hidden>Select Movie to Delete</option>
                        <?php print_movie_options(); ?>
                    </select>
                </div>
                <div class="formRowElement">
                    <button name="deleteMovie" type="submit" class="formSubmit">Delete</button>
                </div>
            </div>
            <div class="formRow deleteCheck">
                <input type="checkbox" id="deleteCheck" required>
                <label for="deleteCheck">I aknowledge that this is irreversible</label>
            </div>
        </form>


        <div class="citationsContainer">
            <p class="imageCitation">All icons sourced from <a href="https://icons8.com/icons" target="_blank">icons8.com</a></p>
        </div>



    <?php } else { ?>

        <p class="adminLogInMessage">Please Verify Admin Status</p>

        <form action="admin.php" method="POST" class="adminForm">
            <div class="formRow logInRow">
                <input type="text" class="formInput" placeholder="Name" name="adminName" required>
            </div>
            <div class="formRow logInRow">
                <input type="checkbox" id="adminCheck" required>
                <label for="adminCheck">I'm an admin</label>
                <button name="adminCheck" type="submit" class="formSubmit logInSubmit" value="admin">Log In</button>
            </div>
        </form>

    <?php } ?>

</body>

</html>
