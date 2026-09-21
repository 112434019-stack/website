<?php

session_start();

// Check login
if (!isset($_SESSION["member_id"])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION["name"];

$message = "";

// Save workout
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $day = $_POST["day"];
    $exercise = $_POST["exercise"];
    $sets = $_POST["sets"];
    $reps = $_POST["reps"];

    $message = "Workout added successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Power Gym - Workout Plan</title>

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

        .back {
            background: #d00000;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        h2 {
            color: #d00000;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 13px;
            margin-top: 25px;
            border: none;
            border-radius: 6px;
            background: #d00000;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #a00000;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }

        .plan {
            margin-top: 30px;
            padding: 20px;
            background: #f8f8f8;
            border-left: 5px solid #d00000;
            border-radius: 6px;
        }

        .plan h3 {
            color: #d00000;
        }

        .plan p {
            margin: 8px 0;
        }

    </style>

</head>

<body>


<header>

    <h1>POWER GYM</h1>

    <a href="dashboard.php" class="back">
        Dashboard
    </a>

</header>


<div class="container">

    <div class="box">

        <h2>Workout Plan</h2>

        <p>
            Welcome,
            <strong>
                <?php echo htmlspecialchars($name); ?>
            </strong>
        </p>


        <?php if ($message != "") { ?>

            <div class="success">
                <?php echo $message; ?>
            </div>

        <?php } ?>


        <form method="POST" onsubmit="return validateWorkout()">


            <label for="day">
                Workout Day
            </label>

            <select id="day" name="day" required>

                <option value="">
                    Select Day
                </option>

                <option value="Monday">
                    Monday
                </option>

                <option value="Tuesday">
                    Tuesday
                </option>

                <option value="Wednesday">
                    Wednesday
                </option>

                <option value="Thursday">
                    Thursday
                </option>

                <option value="Friday">
                    Friday
                </option>

                <option value="Saturday">
                    Saturday
                </option>

            </select>


            <label for="exercise">
                Exercise
            </label>

            <input
                type="text"
                id="exercise"
                name="exercise"
                placeholder="Example: Bench Press"
                required
            >


            <label for="sets">
                Sets
            </label>

            <input
                type="number"
                id="sets"
                name="sets"
                min="1"
                max="10"
                placeholder="Example: 3"
                required
            >


            <label for="reps">
                Repetitions
            </label>

            <input
                type="number"
                id="reps"
                name="reps"
                min="1"
                max="100"
                placeholder="Example: 12"
                required
            >


            <button type="submit">
                ADD WORKOUT
            </button>

        </form>


        <?php if ($message != "") { ?>

            <div class="plan">

                <h3>Workout Added</h3>

                <p>
                    <strong>Day:</strong>
                    <?php echo htmlspecialchars($day); ?>
                </p>

                <p>
                    <strong>Exercise:</strong>
                    <?php echo htmlspecialchars($exercise); ?>
                </p>

                <p>
                    <strong>Sets:</strong>
                    <?php echo htmlspecialchars($sets); ?>
                </p>

                <p>
                    <strong>Repetitions:</strong>
                    <?php echo htmlspecialchars($reps); ?>
                </p>

            </div>

        <?php } ?>

    </div>

</div>


<script>

function validateWorkout() {

    let exercise =
        document.getElementById("exercise").value.trim();

    let sets =
        document.getElementById("sets").value;

    let reps =
        document.getElementById("reps").value;


    if (exercise === "") {

        alert("Please enter an exercise.");

        return false;
    }


    if (sets < 1) {

        alert("Sets must be at least 1.");

        return false;
    }


    if (reps < 1) {

        alert("Repetitions must be at least 1.");

        return false;
    }


    return true;
}

</script>


</body>

</html>