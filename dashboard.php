<?php

session_start();

// Check if member is logged in
if (!isset($_SESSION["member_id"])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION["name"];
$username = $_SESSION["username"];
$plan = $_SESSION["plan"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Power Gym - Dashboard</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        header {
            background: #111;
            color: white;
            padding: 20px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            color: #ff3333;
        }

        .logout {
            background: #d00000;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
        }

        .logout:hover {
            background: #a00000;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        .welcome h2 {
            margin-top: 0;
            color: #d00000;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(
                auto-fit,
                minmax(220px, 1fr)
            );

            gap: 20px;
            margin-top: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            text-align: center;
        }

        .card h3 {
            color: #d00000;
        }

        .card p {
            color: #555;
        }

        .menu {
            margin-top: 30px;

            display: grid;
            grid-template-columns: repeat(
                auto-fit,
                minmax(180px, 1fr)
            );

            gap: 15px;
        }

        .menu a {
            text-decoration: none;
            background: #111;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 7px;
        }

        .menu a:hover {
            background: #d00000;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #777;
        }

    </style>

</head>

<body>


<header>

    <h1>POWER GYM</h1>

    <a class="logout" href="logout.php">
        Logout
    </a>

</header>


<div class="container">


    <div class="welcome">

        <h2>
            Welcome, <?php echo htmlspecialchars($name); ?>! 💪
        </h2>

        <p>
            Welcome to your Power Gym member dashboard.
        </p>

        <p>
            <strong>Username:</strong>
            <?php echo htmlspecialchars($username); ?>
        </p>

        <p>
            <strong>Membership Plan:</strong>
            <?php echo htmlspecialchars($plan); ?>
        </p>

    </div>


    <div class="cards">

        <div class="card">

            <h3>My Profile</h3>

            <p>
                View your member information.
            </p>

        </div>


        <div class="card">

            <h3>Workout Plans</h3>

            <p>
                View your gym workout plans.
            </p>

        </div>


        <div class="card">

            <h3>Membership</h3>

            <p>
                <?php echo htmlspecialchars($plan); ?>
            </p>

        </div>


        <div class="card">

            <h3>Gym Support</h3>

            <p>
                Contact Power Gym.
            </p>

        </div>

    </div>


    <div class="menu">

        <a href="#">
            My Profile
        </a>

        <a href="#">
            Workout Plans
        </a>

        <a href="#">
            Trainers
        </a>

        <a href="#">
            Payments
        </a>

        <a href="index.php">
            Home
        </a>

    </div>


</div>


<footer>

    Build Your Body, Build Your Confidence

</footer>


</body>

</html>