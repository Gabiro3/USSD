<?php
include('functions.php');
include('AT.php');
include('config.php');

header('Content-type: text/plain');

$text = $_POST['text'];
$textArray = explode('*', $text);
$userResponse = trim(end($textArray));
$response = "";
if (empty($userResponse)) {
    // Initial prompt
    $response = "CON Murakaza Neza kuri Moja Tickets system. \nGura Itike bikoroheye!: \n";
    $response .= "1. Gura Itike.\n";
    $response .= "2. Uko bagura itike.\n";
} else if ($text == "1") {
    // Language selected, prompt for province
    $response = "CON Hitamo intara ugiyemo: \n";
    $response .= "1. Amajyaruguru\n";
    $response .= "2. Amajyepfo\n";
    $response .= "3. Iburasirazuba\n";
    $response .= "4. Iburengerazuba\n";

} else if ($text == "2"){
    $response = "END Moja Tickets igufasha kugura itike ya bus utavunitse. \n Dore uko bikorwa: \n";
    $response .= "1. Hitamo intara ugiyemo\n";
    $response .= "2. Hitamo agence uri butege (tukwereka agence zijya mu ntara ugiyemo ukihitiramo iyo ushaka gutega)\n";
    $response .= "3. Usabwa guhitamo isaha y'urugendo bitewe n'imyanya ihari kuri buri saha bus ihagurukiraho\n";
    $response .= "4. Uhabwa code y'urugendo wereka umu agent akaguha itike yawe kuri ya saha wahisemo haruguru\n";
    $response .= "KANDA *channel# UGURE ITIKE\n";

} else if (strlen($text) == 3) {
    // Province selected, display agencies
    $agencies = getAgencies($userResponse); // Implement this function
    $response = "CON Hitamo agence y'urugendo:\n";
    foreach ($agencies as $key => $agency) {
        $response .= $agency['id'] . ". " . $agency['name'] . "\n";
    }
} else if (strlen($text) == 5) {
    // Agency selected, display available hours
    $available_hours = getHours($userResponse); // Implement this function
    $response = "CON Hitamo Isaha y'urugendo:\n";
    foreach ($available_hours as $key => $hour) {
        $response .= ($key + 1) . ". " . $hour['hour'] . " Imyanya isigaye: " . $hour['availableSeats'] . "\n";
    }
} else if (strlen($text) == 7) {
    $response = "CON Shyiramo numero yawe ya telephone";
} else if (strlen($text) == 18) {
    // Final step, generate ticket code
    $ticket_code = generateRandomCode();
    $response = "END Code yawe ni: $ticket_code\niroherezwa via SMS kuri nomero yawe\nMurakoze guhitamo Moja Tickets... Urugendo Ruhire!\n";
    postData($text[2], $text[4], $text[6], $userResponse, $ticket_code);
} else {
    $response = "END Dear Client, An error occurred!";
}

echo $response;

