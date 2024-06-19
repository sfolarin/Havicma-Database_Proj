<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "driver_activation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to process SSN
function process_ssn($ssn) {
    $ssn_parts = explode('-', $ssn);
    $last_four = end($ssn_parts);
    return $last_four;
}


// Function to handle file upload
function upload_file($file, $destination_dir) {
    $target_file = $destination_dir . basename($file["name"]);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file["size"] > 5000000) {
        throw new Exception("File is too large.");
    }

    if (!in_array($file_type, ['jpg', 'jpeg', 'png', 'pdf'])) {
        throw new Exception("Only JPG, JPEG, PNG & PDF files are allowed. File type: $file_type");
    }

    if (!is_writable($destination_dir)) {
        throw new Exception("The directory is not writable: $destination_dir");
    }

    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        throw new Exception("There was an error uploading your file.");
    }

    return $target_file;
}

// Get form data
$name = $_POST['name'];
$fullLegalName = $_POST['fullLegalName'];
$legalBusinessName = $_POST['legalBusinessName'];
$ssn = $_POST['ssn'];
$activationCode = $_POST['activationCode'];
$sourceApp = $_POST['sourceApp'];
$driverAccountActivation = $_POST['driverAccountActivation'];
$email = $_POST['email'];
$homeAddress = $_POST['homeAddress'];
$phoneNumber = $_POST['phoneNumber'];
$isAdult = $_POST['isAdult'];
$typeOfService = $_POST['typeOfService'];
$vehicleMake = $_POST['vehicleMake'];
$vehicleModel = $_POST['vehicleModel'];
$vehicleYear = $_POST['vehicleYear'];
$termsAccepted = isset($_POST['termsAccepted']) ? 1 : 0;
$backgroundCheckRequired = isset($_POST['backgroundCheckRequired']) ? 1 : 0;
$motorVehicleReportRequired = isset($_POST['motorVehicleReportRequired']) ? 1 : 0;
$drugScreenRequired = isset($_POST['drugScreenRequired']) ? 1 : 0;
$wishToContinue = isset($_POST['wishToContinue']) ? 1 : 0;
$joinAsIndividual = isset($_POST['joinAs']) && $_POST['joinAs'] === 'Individual' ? 1 : 0;
$usDotNumber = $_POST['usDotNumber'];
$mcNumber = $_POST['mcNumber'];
$shortScrollableTerms = $_POST['shortScrollableTerms'];
$disclosureAuthorizationAgreement = $_POST['disclosureAuthorizationAgreement'];
$signature = $_POST['signature'];
$driverLicenseNumber = $_POST['driverLicenseNumber'];
$issuingState = $_POST['issuingState'];
$policyExpirationDate = $_POST['policyExpirationDate'];
$ein = $_POST['ein'];
$autoInsuranceProvider = $_POST['autoInsuranceProvider'];
$confirmFullLegalName = $_POST['confirmFullLegalName'];
$confirmLegalBusinessName = $_POST['confirmLegalBusinessName'];
$todaysDate = $_POST['todaysDate'];
$submissionIP = $_POST['submissionIP'];

$combined_ssn = process_ssn($ssn);

$submissionDate = date('Y-m-d H:i:s');
$lastUpdateDate = date('Y-m-d H:i:s');
$approvalStatus = 'Pending';

$upload_dir = '/var/www/html/driver_app/uploads/';

try {
    $dlFront = isset($_FILES['dlFront']) ? upload_file($_FILES['dlFront'], $upload_dir) : null;
    $dlBack = isset($_FILES['dlBack']) ? upload_file($_FILES['dlBack'], $upload_dir) : null;
    $insurancePolicy = isset($_FILES['insurancePolicy']) ? upload_file($_FILES['insurancePolicy'], $upload_dir) : null;
    echo "Files uploaded successfully.<br>";
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$sql = "INSERT INTO DriverApplications (
    SubmissionDate, ApprovalStatus, Name, FullLegalName, LegalBusinessName, SSN, ActivationCode, SourceApp, DriverAccountActivation,
    Email, HomeAddress, PhoneNumber, IsAdult, TypeOfService, VehicleMake, VehicleModel, VehicleYear, TermsAndConditionsAccepted,
    BackgroundCheckRequired, MotorVehicleReportRequired, DrugScreenRequired, WishToContinue, JoinAsIndividual, USDOTNumber, MCNumber,
    ShortScrollableTerms, DisclosureAuthorizationAgreement, Signature, DriverLicenseNumber, IssuingState, DLFrontPhoto, DLReversePhoto,
    PolicyExpirationDate, InsurancePolicyDeclarationPage, EIN, AutoInsuranceProvider, ConfirmFullLegalName,
    ConfirmLegalBusinessName, TodaysDate, SubmissionIP, LastUpdateDate
) VALUES (
   ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the SQL statement: " . $conn->error);
} else {
    echo "SQL statement prepared successfully.<br>";
}

// Ensure the number of parameters matches the number of placeholders in the SQL query
$stmt->bind_param("sssssssssssissssissssssssssssisssssssssss", 
    $submissionDate, $approvalStatus, $name, $fullLegalName, $legalBusinessName, $combined_ssn, $activationCode, $sourceApp, $driverAccountActivation,
    $email, $homeAddress, $phoneNumber, $isAdult, $typeOfService, $vehicleMake, $vehicleModel, $vehicleYear, $termsAccepted,
    $backgroundCheckRequired, $motorVehicleReportRequired, $drugScreenRequired, $wishToContinue, $joinAsIndividual, $usDotNumber, $mcNumber,
    $shortScrollableTerms, $disclosureAuthorizationAgreement, $signature, $driverLicenseNumber, $issuingState, $dlFront, $dlBack,
    $policyExpirationDate, $insurancePolicy, $ein, $autoInsuranceProvider, $confirmFullLegalName,
    $confirmLegalBusinessName, $todaysDate, $submissionIP, $lastUpdateDate);

if ($stmt->execute()) {
    echo "New record created successfully.<br>";
} else {
    echo "Error executing the SQL statement: " . $stmt->error . "<br>";
    error_log("SQL Error: " . $stmt->error); // Log the error to the error log
}

$stmt->close();
$conn->close();
?>

