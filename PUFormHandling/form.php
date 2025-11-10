
<?php

$choice1Err = $choice2Err = $levelErr = $rErr = $surnameErr = $firstnameErr = $othernamesErr = "";
$placeofbirthErr = $nationalityErr = $hometownErr = $regionofhometownErr = $religionErr = $address1Err = "";
$telephonenoErr = $emailaddressErr = "";
$educationalbackgroundErr = $examinationdetailsErr = "";
$errors = array();
 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	
    
    if (empty($_POST["choice1"])) {
        $choice1Err = "First choice is required";
        array_push($errors, $choice1Err);
    }
    if (empty($_POST["choice2"])) {
        $choice2Err = "Second choice is required";
        array_push($errors, $choice2Err);
    }
    
    
    if (empty($_POST["level"])) {
        $levelErr = "Level Applied for is required";
        array_push($errors, $levelErr);
    }
	
    
    if (empty($_POST["surname"])) {
       $surnameErr = "Surname is required";
        array_push($errors, $surnameErr);
		
    }
	
	
    
    
    if (empty($_POST["firstname"])) {
        $firstnameErr = "First name is required";
        array_push($errors, $firstnameErr);
    }
    if (empty($_POST["othernames"])) {
        $othernamesErr = "Other names are required";
        array_push($errors, $othernamesErr);
    }
    if (empty($_POST["placeofbirth"])) {
        $placeofbirthErr = "Place of birth is required";
        array_push($errors, $placeofbirthErr);
    }
    if (empty($_POST["nationality"])) {
        $nationalityErr = "Nationality is required";
        array_push($errors, $nationalityErr);
    }
    if (empty($_POST["hometown"])) {
        $hometownErr = "Hometown is required";
        array_push($errors, $hometownErr);
    }
    if (empty($_POST["regionofhometown"])) {
        $regionofhometownErr = "Region of hometown is required";
        array_push($errors, $regionofhometownErr);
    }
    if (empty($_POST["religion"])) {
        $religionErr = "Religion is required";
        array_push($errors, $religionErr);
    }
    if (empty($_POST["address"])) { 
        $address1Err = "Address is required";
        array_push($errors, $address1Err);
    }
    if (empty($_POST["telephoneno"])) {
        $telephonenoErr = "Telephone number is required";
        array_push($errors, $telephonenoErr);
    }
    if (empty($_POST["email"])) {
        $emailaddressErr = "Email address is required";
        array_push($errors, $emailaddressErr);
    }
   

	
    
    if (count($errors) == 0) {
        
        echo "Form submitted successfully!";
        
    } else {
        
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
	
}


	
?>
