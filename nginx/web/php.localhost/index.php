<!DOCTYPE html>
<html>
    <head>
        <title>Sample Calculator</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            h1 {
                color: #333;
            }
            form {
                margin-top: 20px;
            }
            input,
            select,
            button {
                margin: 5px 0;
                padding: 10px;
                font-size: 16px;
            }
        </style>
    </head>
    <body>
        <h1>Sample Calculator (serverside)</h1>
        <p>Use the form below to perform basic arithmetic operations:</p>

        <form method="post">
            <input type="number" name="num1" placeholder="Enter first number" required>
            <input type="number" name="num2" placeholder="Enter second number" required>
            <select name="operation">
                <option value="add">Add</option>
                <option value="subtract">Subtract</option>
                <option value="multiply">Multiply</option>
                <option value="divide">Divide</option>
                <option value="modulo">Modulo</option>
            </select>
            <button type="submit">Calculate</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $num1 = $_POST['num1'];
            $num2 = $_POST['num2'];
            $operation = $_POST['operation'];
            $result = 0;

            switch ($operation) {
                case "add":
                    $result = $num1 + $num2;
                    break;
                case "subtract":
                    $result = $num1 - $num2;
                    break;
                case "multiply":
                    $result = $num1 * $num2;
                    break;
                case "divide":
                    if ($num2 != 0) {
                        $result = $num1 / $num2;
                    } else {
                        $result = "Error: Division by zero";
                    }
                    break;
                case "modulo":
                    $result = $num1 % $num2;
                    break;
            }
            echo "<p>Result: $result</p>";
        }
        ?>
    </body>
</html>
