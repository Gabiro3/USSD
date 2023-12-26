<?php
include('functions.php');
include('AT.php');
include('config.php');
header('Content-type: text/plain');

$session_id = $_POST['sessionId'];
$user_input = $_POST['text'];
$textArray = explode('*', $user_input);
$user_response = trim(end($textArray));
$response = "";
$response_array = array();
if (empty($user_input)) {
    // Step 1: Language selection
    $response = "CON Welcome to the TugendeBus. Please select your language:\n";
    $response .= "1. English\n";
    $response .= "2. Kinyarwanda";
} else {
    if ($user_response == "1") {
        // User selected English
        // Proceed to province selection
        $response = "CON Select your province:\n";
        $response .= "1. North\n";
        $response .= "2. South\n";
        $response .= "3. East\n";
        $response .= "4. West";
        $response_array[0] = $user_response;
    } elseif ($user_response == "2" && empty($response_array[1])) {
        // User selected Kinyarwanda
        // Proceed to province selection in Kinyarwanda
        $response = "CON Hitamo Intara:\n";
        $response .= "1. Amajyaruguru\n";
        $response .= "2. Amajyepfo\n";
        $response .= "3. Iburasirazuba\n";
        $response .= "4. Iburengerazuba";
        $response_array[1] = $user_response;
    } elseif ($user_response == "1" || $user_response == "2" || $user_response == "3" ||$user_response == "4" && empty($response_array[0])) {
        // User selected a province (North, South, East, West)
        $province = $user_response;

        // Query the database to get agencies for the selected province
        $agencies = getAgencies($user_response); // Implement this function

        // Display agency options to the user
        $response = "CON Hitamo Agence:\n";
        foreach ($agencies as $key => $agency) {
            $response .= $agency['id'] . ". " . $agency['name'] . "\n";
        }
    } elseif ($user_response == "1" || $user_response == "2" || $user_response == "3" || $user_response == "4" || $user_response == "5" || $user_response == "6" || $user_response == "7" || $user_response == "8" && empty($response_array[2])) {
        // User selected an agency
        $agency_id = $user_response;
        $response_array[2] == $user_response;
        // Query the database to get available hours for the selected agency
        $available_hours = getHours($user_response); // Implement this function

        // Display available hours to the user
        $response = "CON Hitamo isaha y'urugendo:\n";
        foreach ($available_hours as $key => $hour) {
            $response .= ($key + 1) . ". " . $hour['hour'] . " Imyanya isigaye: " . $hour['availableSeats'] . "\n";
        }
    } elseif (in_array($user_response, ["1", "2", "3", "4", "5", "6", "7"]) && empty($response_array[3])) {
        // User selected an available hour
        $hour_id = $user_response;
        $response_array[3] = $user_response;
        // Proceed to passenger input
        $response = "CON Andika umubare w'abagenzi (max: 2):\n";
    } elseif (in_array($user_response, ["1", "2"])) {
        // User entered the number of passengers
        $passengers = $user_response;

        // Generate a random 10-digit code
        $ticket_code = generateRandomCode();

        // You can proceed with the booking and save data to the database here.
        // Insert data into the bookings table, update fully_booked, and send email confirmation.
        $result = processTicketBooking($province, $agency_id, $hour_id, $passengers, $ticket_code);
        // You can return the result to the USSD application.

        // Display the ticket code to the user
        $response = "END Your ticket code is: $ticket_code\nThank you for choosing our service!";
    }
}

echo $response;

// Implement the necessary functions to handle database queries and other logic.
// Implement the getProvinceName, getAgencyId, and getHourId functions here.
?>
