<?php
require_once ("database_connection.php");

function initializeSession(): void {
    // Configurations for added session security
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0);
    ini_set('session.use_trans_sid', 0);
    ini_set('session.sid_length', 48);
    ini_set('session.sid_bits_per_character', 6);
    
    session_start();

    // Check for errors (session timed out/invalid input)
    // if page was refreshed by the 'Save' button's submit action
    if(isset($_POST['submit']) && (isSessionTimedOut() || isInputInvalid())) {
        // Refresh page and terminate current page execution
        header("Location: " . $_SERVER['REQUEST_URI'], true, 303);

        exit();
    }

    // Sets/resets session timer
    $_SESSION['time_out_timer'] = time();
}

function isSessionTimedOut(): bool {
    // Check if the session timer has exceeded the X second limit
    if(time() - $_SESSION['time_out_timer'] >= 600) { // X = 600 -> 10 minutes
        $_SESSION['error_flags']['timed_out'] = true;

        return true;
    }

    return false;
}

function isInputInvalid(): bool {
    // 'name' input can only contain letters and whitespaces, and must contain at least 1 letter
    if(preg_match_all("/[a-z]/i", $_POST['name'])) {
        if(preg_match_all("/[^a-z\s]/i", $_POST['name'])) {
            $_SESSION['error_flags']['invalid_input'] = true;

            return true;
        }
    } else {
        $_SESSION['error_flags']['invalid_input'] = true;

        return true;
    }
    

    // 'terms' input can only contain a string value of "on"
    if($_POST['terms'] != "on") {
        $_SESSION['error_flags']['invalid_input'] = true;

        return true;
    }

    // 'sectors' input array can only contain integers and must contain at least 1 valid element
    if(isset($_POST['sectors'])) {
        foreach($_POST['sectors'] as $sectorId) {
            if(preg_match_all("/\D/", $sectorId)) {
                $_SESSION['error_flags']['invalid_input'] = true;
                
                return true;
            }
        }
    } else {
        $_SESSION['error_flags']['invalid_input'] = true;

        return true;
    }
    
    return false;
}

function initializeContent($datasource): void {
    // Check if error flags are present
    if(isset($_SESSION['error_flags'])) {
        // Resets error flags and displays the relevant error message
        handleErrors();
    } else {
        // Starts database update and primes data for form fill if no errors were detected
        databaseTransaction($datasource);
    }
}

function handleErrors(): void {
    if($_SESSION['error_flags']['timed_out']) {
        // Completely resets session and cached data
        unset($_COOKIE);
        unset($_POST);
        unset($_REQUEST);
        unset($_SERVER);
        unset($_SESSION);
        session_destroy();
        session_start();
        session_regenerate_id();

        print "<label>Session timed out, please refresh the page.</label></body></html>";
    }
        
    if($_SESSION['error_flags']['invalid_input']) {
        unset($_SESSION['error_flags']);

        print "<label>Invalid input, please refresh the page and enter valid data.</label></body></html>";
    }

    // Terminate current page execution
    exit();
}

function databaseTransaction($datasource): void {
    // Start database transaction
    $datasource->getPDO()->beginTransaction();

    // Updates database and primes user entered data for form fill
    // if the 'Save' button was pressed
    if(isset($_POST['submit'])) {
        postAction($datasource);

        $datasource->query(
            "SELECT users.user_id, users.user_name, users.terms_agreed, user_sector_data.sector_id 
            FROM users INNER JOIN user_sector_data ON user_sector_data.user_id = ? AND users.user_id = ?",
            'user_data', array($_SESSION['user_id'], $_SESSION['user_id']));
    }

    // Prime sector data for form fill
    $datasource->query("SELECT * FROM sectors", 'sector_data');
    
    // End database transaction and commit changes
    $datasource->getPDO()->commit();
}

function postAction($datasource): void {
    $name = $_POST['name'];
    $sectors = $_POST['sectors'];
    $terms = ($_POST['terms'] == "on")? 1 : 0;
    $userId = &$_SESSION['user_id'];
    $insertUserSectors = "INSERT INTO user_sector_data (user_id, sector_id) VALUES";

    // Check if user has previously entered data
    if(isset($userId)) {
        $datasource->execute("UPDATE users SET user_name=?, terms_agreed=?
            WHERE user_id=?", array($name, $terms, $userId));

        $datasource->execute("DELETE FROM user_sector_data
            WHERE user_id=?", array($userId));
    } else {
        $datasource->execute("INSERT INTO users (user_name, terms_agreed)
            VALUES (?, ?)", array($name, $terms));

        $userId = $datasource->getPDO()->lastInsertId();
    }

    // Prepare string to enter all user sector data at once
    foreach($sectors as $sectorId) {
        $insertUserSectors .= " (" . $userId . ", " . $sectorId . "),";
    }
    $insertUserSectors = chop($insertUserSectors, ",");
    
    $datasource->execute($insertUserSectors);
}

function getSelectOptions($datasource): void {
    // Get user sector data from primed data
    $sectorData = $datasource->getData('sector_data');
    $userData = $datasource->getData('user_data');

    // Return hierarchically sorted select box options
    populateSelect($sectorData, $userData);
}

function populateSelect(array $sectorData, array $userData,
    int $sector_id = 0, int $level = 0): void {
    $currentLevel = $level;

    // Start building the <option> HTML element
    foreach($sectorData as $element) {
        if($element['parent_sector_id'] == $sector_id) {
            $option = "<option ";

            // Check if sector was selected by user
            foreach($userData as $sector) {
                if($sector['sector_id'] == $element['sector_id']) {
                    $option .= "selected ";
                    break;
                }
            }

            // Insert sector_id value, apply indent level, insert sector name, and close element
            $option .= 'value="' . $element['sector_id'] . '">' .
                str_repeat("&emsp;", $currentLevel) .
                ($element['sector_name']) . '</option>';
            print $option;

            // Find child sectors of element
            populateSelect($sectorData, $userData,
                $element['sector_id'], $currentLevel + 1);
        }
    }
}

function getNameValue($datasource): string {
    // Get user name from primed data
    if(isset($_SESSION['user_id'])) {
        return print($datasource->getData('user_data')[0]['user_name']);
    }

    return "";
}

function getTermsChecked($datasource): string {
    // Get checkbox state from primed data
    if(isset($_SESSION['user_id'])) {
        $checked = $datasource->getData('user_data')[0]['terms_agreed'];

        return print($checked == 1? "checked" : "");
    }

    return "";
}