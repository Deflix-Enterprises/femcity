<?php
//Add the session handler for getting session information
require_once("session_handler.php"); #This adds the dbInfo class for getting database records too

//Get the logged in user info
$user_info = MySessionHandler::GetLoggedUserInfo();

/*AJAX POST REQUESTS*/
if(isset($_POST["action"]))
{
    //If it is a post request, we will need the functions in db handler
    require_once("db_handler.php"); #Contains functions for manipulating database records
    
    switch($_POST["action"])
    {
        //CATEGORY FUNCTIONS
        case "CreateCategory": #Superuser ~ Create a category
            $data = $_POST["data"];
            $create_status = DbHandler::CreateCategory($data);
            echo $create_status;
        break;
            
        case "UpdateCategory": #Superuser ~ Update a category
            $id = $_POST["id"];
            $data = $_POST["data"];
            
            $update_status = DbHandler::UpdateCategory($id,$data);
            echo $update_status;
        break;
        
        case "DeleteCategory": #Superuser ~ Delete a category
            $id = $_POST["id"];
            $delete_status = DbHandler::DeleteCategory($id);
            
            echo $delete_status;
        break;
            
        //ADMIN ACCOUNT FUNCTIONS
        case "CreateAdminAccount": #Superuser ~ Create an admin account
            $data = $_POST["data"];
            $data["password"] = PasswordHandler::Encrypt($data["password"]);#Encrypt the password
            $data["subbed"] = (int)$data["subbed"];
            $create_status = DbHandler::CreateAdminAcc($data);
            
            echo $create_status;
        break;
        
        case "UpdateAdminAccount": #Superuser ~ Update an admin account
            $id = $_POST["id"];
            $data = $_POST["data"];
            
            $update_status = DbHandler::UpdateAdminAcc($id,$data);
            echo $update_status;
        break;

        case "DeleteAdminAccount": #Superuser ~ Delete an admin account
            $id = $_POST["id"];
            $delete_status = DbHandler::DeleteAdminAcc($id);
            
            echo $delete_status;
        break;
            
        //FEATURED ITEM FUNCTIONS
        case "CreateFeaturedItem": #Superuser ~ Create a featured item
            echo "<p>Creating featured item</p>";
        break;   
     
        case "UpdateFeaturedItem": #Superuser ~ Update a featured item
            $id = $_POST["id"];
            $data = $_POST["data"];
            
            $update_status = DbHandler::UpdateFeaturedItem($id,$data);
            echo $update_status;
        break;   
     
        //OFFER FUNCTIONS
        case "CreateOffer": #Superuser ~ Create an offer
            $data = $_POST["data"];
            $create_status = DbHandler::CreateOffer($data);

            echo $create_status;
        break;
        
        case "UpdateOffer": #Superuser ~ Update an offer
            $id = $_POST["id"];
            $data = $_POST["data"];
            
            $update_status = DbHandler::UpdateOffer($id,$data);
            var_dump($update_status);
            echo $update_status;
        break;
        
        case "DeleteOffer": #Superuser ~ Delete an offer
            $id = $_POST["id"];
            $delete_status = DbHandler::DeleteOffer($id);
            
            echo $delete_status;
        break;
        
        //ACCOUNT REQUEST FUNCTIONS
        case "AcceptAccountRequest": #Superuser ~ Accept account request
            echo "<p>Accepting account request</p>";
        break;
            
        case "DenyAccountRequest": #Superuser ~ Deny account request
            echo "<p>Denying account request</p>";
        break;
            
        //ITEM FUNCTIONS
        case "CreateItem":
            echo "Creating item"; #Admin ~ Create/post item
        break;
            
        case "UpdateItem":
            echo "Updating item"; #Admin ~ Create/post item
        break;
            
        case "DeleteItem": #Admin & Superuser ~ Delete item
            $id = $_POST["id"];
            $delete_status = DbHandler::DeleteItem($id);
            
            echo $delete_status;
        break;
            
        default:
            echo "Invalid action";
    }
}

/*AJAX GET REQUESTS*/
if(isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        
    }
}