<?php
session_start();
require_once "signupdb.php";

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);

    // Search query to fetch location and steps
    $sql_search = "SELECT * FROM subjects WHERE name LIKE '%$search_query%'";

    $result_search = mysqli_query($conn, $sql_search);

    if (!$result_search) {
        die("Error performing search: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA Notes Hub</title>
    <link rel="stylesheet" href="css/sem.css">
</head>

<body>
    <div class="sub-header">
        <h1>Search Results</h1>
    </div>

    <main>
        <?php if (mysqli_num_rows($result_search) > 0) { ?>
            <div class="search-results">
                <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
                <ul>
                    <?php while ($row = mysqli_fetch_assoc($result_search)) { ?>
                        <li>
                            <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                            <p>Location:
                                <?php
                                $location = htmlspecialchars($row['location']);
                                if (filter_var($location, FILTER_VALIDATE_URL)) {
                                    echo '<a href="' . $location . '" target="_blank">' . $location . '</a>';
                                } else {
                                    echo $location;
                                }
                                ?>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } else { ?>
            <div class="search-results">
                <h1>No results found for "<?php echo htmlspecialchars($search_query); ?>"</h1>
            </div>
        <?php } ?>
    </main>

</body>

</html>