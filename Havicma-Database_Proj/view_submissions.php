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

// Fetch submissions
$sql = "SELECT * FROM DriverApplications";
$result = $conn->query($sql);

// Check for errors in the SQL query
if ($result === false) {
    die("Error: " . $conn->error);
}

// Check if there are any submissions
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr>
            <th>SubmissionID</th>
            <th>SubmissionDate</th>
            <th>ApprovalStatus</th>
            <th>Name</th>
            <th>FullLegalName</th>
            <th>LegalBusinessName</th>
            <th>SSN</th>
            <th>ActivationCode</th>
            <th>SourceApp</th>
            <th>DriverAccountActivation</th>
            <th>Email</th>
            <th>HomeAddress</th>
            <th>PhoneNumber</th>
            <th>IsAdult</th>
            <th>TypeOfService</th>
            <th>VehicleMake</th>
            <th>VehicleModel</th>
            <th>VehicleYear</th>
            <th>TermsAndConditionsAccepted</th>
            <th>BackgroundCheckRequired</th>
            <th>MotorVehicleReportRequired</th>
            <th>DrugScreenRequired</th>
            <th>WishToContinue</th>
            <th>JoinAsIndividual</th>
            <th>USDOTNumber</th>
            <th>MCNumber</th>
            <th>ShortScrollableTerms</th>
            <th>DisclosureAuthorizationAgreement</th>
            <th>Signature</th>
            <th>DriverLicenseNumber</th>
            <th>IssuingState</th>
            <th>DLFrontPhoto</th>
            <th>DLReversePhoto</th>
            <th>PolicyExpirationDate</th>
            <th>InsurancePolicyDeclarationPage</th>
            <th>EIN</th>
            <th>AutoInsuranceProvider</th>
            <th>ConfirmFullLegalName</th>
            <th>ConfirmLegalBusinessName</th>
            <th>TodaysDate</th>
            <th>SubmissionIP</th>
            <th>LastUpdateDate</th>
          </tr>";

    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['SubmissionID']}</td>
                <td>{$row['SubmissionDate']}</td>
                <td>{$row['ApprovalStatus']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['FullLegalName']}</td>
                <td>{$row['LegalBusinessName']}</td>
                <td>{$row['SSN']}</td>
                <td>{$row['ActivationCode']}</td>
                <td>{$row['SourceApp']}</td>
                <td>{$row['DriverAccountActivation']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['HomeAddress']}</td>
                <td>{$row['PhoneNumber']}</td>
                <td>{$row['IsAdult']}</td>
                <td>{$row['TypeOfService']}</td>
                <td>{$row['VehicleMake']}</td>
                <td>{$row['VehicleModel']}</td>
                <td>{$row['VehicleYear']}</td>
                <td>{$row['TermsAndConditionsAccepted']}</td>
                <td>{$row['BackgroundCheckRequired']}</td>
                <td>{$row['MotorVehicleReportRequired']}</td>
                <td>{$row['DrugScreenRequired']}</td>
                <td>{$row['WishToContinue']}</td>
                <td>{$row['JoinAsIndividual']}</td>
                <td>{$row['USDOTNumber']}</td>
                <td>{$row['MCNumber']}</td>
                <td>{$row['ShortScrollableTerms']}</td>
                <td>{$row['DisclosureAuthorizationAgreement']}</td>
                <td>{$row['Signature']}</td>
                <td>{$row['DriverLicenseNumber']}</td>
                <td>{$row['IssuingState']}</td>
                <td><a href='/driver_app/uploads/{$row['DLFrontPhoto']}'>View DL Front</a></td>
                <td><a href='/driver_app/uploads/{$row['DLReversePhoto']}'>View DL Back</a></td>
                <td>{$row['PolicyExpirationDate']}</td>
                <td><a href='/driver_app/uploads/{$row['InsurancePolicyDeclarationPage']}'>View Insurance Policy</a></td>
                <td>{$row['EIN']}</td>
                <td>{$row['AutoInsuranceProvider']}</td>
                <td>{$row['ConfirmFullLegalName']}</td>
                <td>{$row['ConfirmLegalBusinessName']}</td>
                <td>{$row['TodaysDate']}</td>
                <td>{$row['SubmissionIP']}</td>
                <td>{$row['LastUpdateDate']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>

