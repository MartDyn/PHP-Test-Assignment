<?php
require_once ("functions.php");

initializeSession();
$dbConnection = new DatabaseConnection("mysql:host=127.0.0.1;dbname=test;charset=utf8mb4");

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Sector Experience</title>
    </head>
    <body>
        <?php initializeContent($dbConnection); ?>
        <form method="post">
            <label>Please enter your name and pick the Sectors you are currently involved in.</label>
            <br>
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php getNameValue($dbConnection); ?>">
            <br>
            <br>
            <label for="sectors" >Sectors:</label>
            <select id="sectors" name="sectors[]" multiple size="5">
            <?php
                getSelectOptions($dbConnection);
            ?>
            </select>
            <br>
            <br>
            <input type="checkbox" name="terms" id="terms" <?php getTermsChecked($dbConnection); ?>>
            <label for="terms">Agree to terms</label>
            <br>
            <br>
            <input type="submit" name="submit" value="Save">
        </form>
    </body>
</html>
