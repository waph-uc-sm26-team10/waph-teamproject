<?php
function checklogin_mysql($username, $password) {

    // Load local database settings from .env
    $env = parse_ini_file(__DIR__ . '/.env');

    $mysqli = new mysqli(
        $env['DB_HOST'],
        $env['DB_USER'],
        $env['DB_PASSWORD'],
        $env['DB_NAME']
    );

    if ($mysqli->connect_errno) {
        die("Database connection failed.");
    }

    $sql = "SELECT username FROM users WHERE username=? AND password=MD5(?)";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("Prepared statement failed.");
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['username'];
    }

    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        echo "<h2>Login failed.</h2>";
        echo "<p>Username and password cannot be empty.</p>";
        exit();
    }

    $name = checklogin_mysql($username, $password);

    if ($name) {
        echo "<h2>Login successful!</h2>";
        echo "<p>Welcome, " . htmlentities($name, ENT_QUOTES, 'UTF-8') . "</p>";
    } else {
        echo "<h2>Login failed.</h2>";
        echo "<p>Invalid username or password.</p>";
    }

} else {
    echo "Please submit the login form.";
}
?>
