<?php
	session_start();
	include 'db.php';


	if (isset($_POST['theme'])) {
		setcookie("theme", $_POST['theme'], time() + (30 * 24 * 60 * 60), "/"); 
		$_SESSION['theme'] = $_POST['theme'];
		header("Location: recommend.php");
		exit();
	}


	$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';


	if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['theme'])) {
		$_SESSION['genre'] = $_POST['genre'] ?? '';
		$_SESSION['language'] = $_POST['language'] ?? '';
		$_SESSION['era'] = $_POST['era'] ?? '';
	}


	$genre = $_SESSION['genre'] ?? '';
	$language = $_SESSION['language'] ?? '';
	$era = $_SESSION['era'] ?? '';

	$year_condition = ($era == '1900') ? "release_year BETWEEN 1900 AND 1999" : "release_year BETWEEN 2000 AND 2025";

	$sql = "SELECT title, genre, language, release_year, rating FROM movies 
			WHERE ('$genre' = '' OR genre LIKE '%$genre%') 
			AND ('$language' = '' OR language = '$language') 
			AND ('$era' = '' OR $year_condition)
			ORDER BY rating DESC";

	$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Recommendations</title>
    <link rel="stylesheet" href="phpstyle.css">
    <style>
        body {
            background-color: <?php echo ($theme == 'dark') ? '#141414' : '#ffffff'; ?>;
            color: <?php echo ($theme == 'dark') ? 'white' : 'black'; ?>;
        }
        .movie-card {
            background-color: <?php echo ($theme == 'dark') ? '#222' : '#f0f0f0'; ?>;
            color: <?php echo ($theme == 'dark') ? 'white' : 'black'; ?>;
        }
    </style>
</head>
<body>

    <h2>Recommended Movies</h2>

    <form action="recommend.php" method="POST">
        <button type="submit" name="theme" value="light">Light Mode</button>
        <button type="submit" name="theme" value="dark">Dark Mode</button>
    </form>

    <div class="movies-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imageName = strtolower(str_replace(' ', '_', $row['title'])) . ".jpg";
                ?>
                <div class="movie-card">
                    <div class="movie-poster" style="background-image: url('images/<?php echo $imageName; ?>');"></div>
                    <h3><?php echo $row['title']; ?></h3>
                    <p>Genre: <?php echo $row['genre']; ?></p>
                    <p>Language: <?php echo $row['language']; ?></p>
                    <p>Year: <?php echo $row['release_year']; ?></p>
                    <p>Rating: <?php echo $row['rating']; ?>/10</p>
                </div>
                <?php
            }
        } else {
            echo "<p>Oops!! No movies found. Try different preferences.</p>";
        }
        ?>
    </div>

</body>
</html>

<?php $conn->close(); ?>
