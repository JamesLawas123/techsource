<?php
require('conn/db.php');
include_once("assets/classes/rndPass.class.php");
session_start();
// error_reporting(0);
$conn = connectionDB();

// Initialize sessiontokencode
$sessiontokencode = "";

// Has a cookie been initiated previously?
if (! isset($_COOKIE['cookusername']) && !isset ($_COOKIE['cookuserpassword'])) 
{
	   $username = $_GET['username'];
	   $pswd = $_GET['pswd'];
	   $remember = $_GET['remember'];

/*
	   if ($remember!="off")
	   {
		$remember="yes";
	   }
	   else
	   {
	     $remember="no";
	   }
*/	   
	  	  
	   //convert to upper
	   	$username = strtoupper($username);
		$remember = strtoupper($remember);
	   	
	   	/*echo "Username: ".$username."<br>";
	    echo "Password: ".$pswd."<br>";	  
		echo "Remember Me: ".$remember."<br>";*/	 
	
		// First check if this is the default password "1234"
		$is_default_password = ($pswd === "1234");

		// Look for the user in the users table - modified query to only check username
		$query = "SELECT * FROM sys_usertb WHERE username='$username' AND user_statusid=1";
		$result = mysqli_query($conn, $query);
         if (mysqli_num_rows($result) == 1) {
			$row3 = mysqli_fetch_assoc($result);
			$stored_password = $row3['password'];
			
			// Check if password matches (either default "1234" or properly hashed)
			if ($is_default_password || password_verify($pswd, $stored_password)) {
				$usergroupid = $row3['user_groupid'];					
				$userid = $row3['id'];					
				$username = $row3['username'];					
				$userstatid = $row3['user_statusid'];					
				$userfirstname = $row3['user_firstname'];					
				$userlastname = $row3['user_lastname'];					
				$userlevelid = $row3['user_levelid'];					
				$password = $row3['password'];
				
				// Continue with existing session creation code...
				$_SESSION['userid']=$userid;
				$_SESSION['usergroupid']=$usergroupid;
				$_SESSION['password'] = $password;
				$_SESSION['username'] = $username;
				$_SESSION['userstatid'] = $userstatid;
				$_SESSION['userfirstname'] = $userfirstname;
				$_SESSION['userlastname'] = $userlastname;
				$_SESSION['userlevelid'] = $userlevelid;	
				
				//create session token code
				$tokencodex=new rndPass(15);
				$_SESSION['sessiontokencode'] = $tokencodex->PassGen();
				
				//store sessions to variables
				$userid = $_SESSION['userid'];
				$usergroupid = $_SESSION['usergroupid'];
				$username = $_SESSION['username'];
				$userstatid=$_SESSION['userstatid'];	
				$userfirstname=$_SESSION['userfirstname'];	
				$userlevelid=$_SESSION['userlevelid'];
				$sessiontokencode=$_SESSION['sessiontokencode'];
				 
				//set cookie remember? 					  
				if(isset($_GET['remember']))
				{										
					 @setcookie("cooklogid", $logid, time()+60*60*24*100, "/");
					 @setcookie("cookuserid", $userid, time()+60*60*24*100, "/");
					 @setcookie("cookusername", $userusername, time()+60*60*24*100, "/");
					 @setcookie("cookuserpassword", $userpassword, time()+60*60*24*100, "/");
					 @setcookie("cookuserstatid", $userstatid, time()+60*60*24*100, "/");
					 @setcookie("cookcompanyid", $companyid, time()+60*60*24*100, "/");
					 @setcookie("cookuserfirstname", $userfirstname, time()+60*60*24*100, "/");
					 @setcookie("cookuseremail", $useremail, time()+60*60*24*100, "/");
					 @setcookie("cookuserposition", $userposition, time()+60*60*24*100, "/");	
					 @setcookie("userlevelid", $userlevelid, time()+60*60*24*100, "/");
					 @setcookie("sessiontokencode", $sessiontokencode, time()+60*60*24*100, "/");							 
				}	
				// Look for the lastuser in the loggedusers table.
			
				$query2x = "SELECT * FROM sys_loginlogstb ORDER BY logintime DESC";
				$result2x = mysqli_query($conn, $query2x);
				if (mysqli_num_rows($result2x) < 1){
					// echo "1";
					$logid_old = 0;
					$_SESSION['logid']=$logid_old+1;
					@$logid = $_SESSION['logid'];
				}else{
					// echo "2";
					while($row4x = mysqli_fetch_assoc($result2x)){
						$logid_old = $row4x['id'];					
						$_SESSION['logid']=$logid_old+1;
						$logid = $_SESSION['logid'];
					}
				}
				// echo "logs:".$logid;
				//insert data for log users
				$queryadd="INSERT INTO sys_loginlogstb (userid,logintime,logid,isol,dateonly) VALUES ($userid,now(),$logid,0,now())";
				$add_id = mysqli_query($conn, $queryadd);
				
				$queryupdate_auditnssw = "UPDATE sys_usertb SET sessiontokencode = '".$sessiontokencode."' WHERE id = '".$userid."'";							
				$resultupdate_nssw = mysqli_query($conn,$queryupdate_auditnssw);
												
				$loc="Record found";
				$isok = 1;
				$unitidtry = $userid;
				echo $loc . "|" . $isok. "|" . $unitidtry. "|";
				
				die();
			} else {
				$loc="Invalid Credentials";
				$isok = 2;
				$error_msg = "Invalid username or password.";
				echo $loc . "|" . $isok . "|" . $error_msg;   
			}
		} else {
			$loc="Invalid Credentials";
			$isok = 2;
			$error_msg = "Invalid username or password";
			echo $loc . "|" . $isok . "|" . $error_msg;   
		}
} 
else // The user has returned. Offer a welcoming note.
{
		//check cookies -walang cookies
		if (! isset($_COOKIE['cookusername']) && !isset ($_COOKIE['cookuserpassword'])) 
		{	
			//set variables
			 $logid = $_SESSION['logid'];
			 $userid = $_SESSION['userid'];
			 $usergroupid = $_SESSION['usergroupid'];
			 $username = $_SESSION['username'];
			 $userstatid=$_SESSION['userstatid'];	
			 $companyid=$_SESSION['companyid'];	
			 $userfirstname=$_SESSION['userfirstname'];	
			 // $useremail=$_SESSION['useremail'];
			 $userposition=$_SESSION['userposition'];	
			 $userlevelid=$_SESSION['userlevelid'];
			 // $parentuserid=$_SESSION['parentuserid'];	
			 // $usergroupmasterid=$_SESSION['usergroupmasterid'];	
			 $sessiontokencode=$_SESSION['sessiontokencode'];			 
		}
		else
		{	
			//set session from cookies			
			$_SESSION['userid']= $_COOKIE['cooklogid'];
			$_SESSION['usergroupid']= $_COOKIE['cooklogid'];
			$_SESSION['username']= $_COOKIE['cookusername'];
			$_SESSION['password']= $_COOKIE['cookuserpassword'];			
			$_SESSION['userstatid']= $_COOKIE['cookuserstatid'];	
			$_SESSION['companyid']= $_COOKIE['cookcompanyid'];	
			$_SESSION['userfirstname']= $_COOKIE['cookuserfirstname'];	
			// $_SESSION['useremail']= $_COOKIE['cookuseremail'];
			$_SESSION['userposition']= $_COOKIE['cookuserposition'];	
			$_SESSION['userlevelid']= $_COOKIE['userlevelid'];
			// $_SESSION['parentuserid']= $_COOKIE['parentuserid'];	
			// $_SESSION['usergroupmasterid']= $_COOKIE['usergroupmasterid'];	
			$_SESSION['sessiontokencode']= $_COOKIE['sessiontokencode']; 
			 
			 //set variables
			$userid = $_SESSION['userid'];
			$usergroupid = $_SESSION['usergroupid'];
			$username = $_SESSION['username'];
			$userstatid=$_SESSION['userstatid'];	
			$companyid=$_SESSION['companyid'];	
			$userfirstname=$_SESSION['userfirstname'];	
			// $useremail=$_SESSION['useremail'];
			$userposition=$_SESSION['userposition'];
			$userlevelid=$_SESSION['userlevelid'];
			// $parentuserid=$_SESSION['parentuserid'];	
			// $usergroupmasterid=$_SESSION['usergroupmasterid'];	
			$sessiontokencode=$_SESSION['sessiontokencode'];	
		
		}
	
}

// Remove the debug message
// echo "test:".$sessiontokencode;
?>