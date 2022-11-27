<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!==true){
    header("location: login.php");
}
require_once "config.php";

$login_email = $_SESSION['email'];
$no_application = "xxx";
$firstname ="xxx";
$lastname = "";
$iitgmail = $login_email;
$rollno = "xxxxxxxxx";
$mobile = "xxxxxxxxx";
$gender = "xxxx";
$dob = "xx-xx-xxxx";
$department ="xxxxxxxxx";
$course =  "xxxxxxxxx";
$cpi = "xxxx";

$sql = "SELECT NO_of_application, Fname, Lname, Semail, rollno, Phone_NO, gender, Dob, department, Course, cpi FROM student WHERE Semail = '$login_email'";
$result = $conn->prepare($sql);
$result->execute();
    if($row = $result->fetch()){
        $no_application = $row['NO_of_application'];
        
       
        $firstname = $row["Fname"];
        $lastname = $row["Lname"];
        $iitgmail = $row["Semail"];
        
        $rollno = $row["rollno"];
        $mobile = $row["Phone_NO"];
        $gender = $row["gender"];
        
        $dob = $row["Dob"];
        $department = $row["department"];
        $course = $row["Course"];
        $cpi = $row["cpi"];
    }
$result = null;

?>


<!DOCTYPE html>
<html lang="en">

<head> 
     <link rel="stylesheet" href="sty.css" /> 
  </head>

<body>
    <!-- Google Tag Manager (noscript) -->
   <div class="bd">
    <nav class="hide-on-small-only navbar">
        <ul class="side-nav fixed section table-of-contents">

            <li class="logo">
                <a id="logo-container" aria-label="Navigate to the beginning of the page" href="#intro" class="brand-logo grey-blue-text">
                    <img src="logo_iitg.jpeg" class="circle img-responsive profile-pic" alt="avatar">
                </a>
            </li>

            <li class="bold">
                <a aria-label="Navigate to the About section" href="index.php" class="waves-effect waves-dark teal-text"><i
            class="small"></i><span class="small2" >Home</span></a>
            </li>
            <li class="bold">
                <a aria-label="Navigate to the Experience section" href="registration.php" class="waves-effect waves-dark teal-text"><i
            class=" small"></i><span class="small2">Registration</span></a>
            </li>

            <li class="bold">
                <a aria-label="Navigate to the Projects section" href="job_application.php" class="waves-effect waves-dark teal-text"><i
            class=" small"></i><span>Job Application</span></a>
            </li>
            
             <li class="bold">
                <a aria-label="Navigate to the Skills section" href="logout.php" class="waves-effect waves-dark teal-text"><i
            class=" small"></i><span>logout</span></a>
            </li>

            
        </ul>
    </nav>
 <form class="form2 st_cp">
    <div ><h1 class="hea"></h1></div>
    <div class="reg_form">
        <div class="reg_form1">
            <h3>Name</h3>
            <h3><?php echo $firstname?><?php echo $lastname?></h3>
         </div>
        <div class="reg_form1">
            <h3>Email</h3>
            <h3><?php echo $iitgmail?></h3>
        </div>
        </div>
        <div class="reg_form">
        <div class="reg_form1">
            <h3>Roll No</h3>
            <h3><?php echo $rollno?></h3>
        </div>
        <div class="reg_form1">
            <h3>DoB</h3>
            <h3><?php echo $dob?></h3>
       </div>
        </div>
        <div class="reg_form">
        <div class="reg_form1">
            <h3>Gender</h3>
          <h3><?php echo $gender?></h3>
       </div>
        <div class="reg_form1">
        <h3>Phone No</h3>
          <h3><?php echo $mobile?></h3>
        </div>
        </div>
        <div class="reg_form">
        <div class="reg_form1">
             <h3>Department</h3>
          <h3><?php echo $department?></h3>
        </div>
        <div class="reg_form1">
             <h3>Course</h3>
          <h3><?php echo $course?></h3>
        </div>
        </div>
        <div class="reg_form">
        <div class="reg_form1">
             <h3>Cpi</h3>
          <h3><?php echo $cpi?></h3>
         </div>
        <div class="reg_form1">
            <h3>No of Application</h3>
            <h3><?php echo $no_application?></h3>
        </div>
        </div>
</form>
<div>
    <?php
    if($conn->query("SELECT rollno,job_id FROM get_offer WHERE rollno='$rollno'")->rowCount()>0){
        $cname = $jobtitle = $jobID = $cID = "";
        if($job = $conn->query("SELECT job_id FROM get_offer WHERE rollno='$rollno'")->fetch()){
            $jobID = $job['job_id'];
        }
        if($job = $conn->query("SELECT job_title, Cid FROM job WHERE job_id = '$jobID'")->fetch()){
            $jobtitle = $job['job_title'];
            $cID = $job['Cid'];
            if($c = $conn->query("SELECT CName FROM company_reg WHERE Cid='$cID'")->fetch()){
                $cname = $c['CName'];
            }
        }
        $hide = true; $rejected = false;
        if(isset($_REQUEST['accept'])){
            $jobid=$_REQUEST['applyId'];
            $rollno=$_REQUEST['applyRoll'];
            // $sql3="INSERT INTO apply_for (jobID,rollno) VALUES ('$jobid','$rollno')";
            // $stmt= $conn->query($sql3);
            // $stmt=null;
            $hide = false;
            }
            if(isset($_REQUEST['reject'])){
            $jobid=$_REQUEST['withdrawId'];
            $rollno=$_REQUEST['withdrawRoll'];
            $sql4="DELETE FROM get_offer WHERE job_id='$jobid' AND rollno='$rollno'";
            $stmt= $conn->query($sql4);
            $stmt=null;
            $hide = false;
            $rejected = true;
            }

        if(!$rejected) echo "<h2 style='text-align:center; color:black; margin-top:10%;'>Congratulations you got the offer from ". $cname . " Company and Job Profile is "
        . $jobtitle . "
        </h2>";
        if($hide) {echo '<div style="display:flex; justify-content: space-evenly; ">
        <form action="" method="POST"><input type="hidden" name="applyId" value=' . $jobID. '>
        <input type="hidden" name="applyRoll" value=' . $rollno . '>
        <input type="submit" class="btn" name="accept" value="Accept"></form> 
        <form action="" method="POST"><input type="hidden" name="withdrawId" value=' . $jobID . '>
        <input type="hidden" name="withdrawRoll" value=' . $rollno . '>
        <input style="background-color:red;" type="submit" class="btn" name="reject" value="Reject"></form>
        </div>';
        }
    }
    
    ?>
</div>
    </body>
    </html>