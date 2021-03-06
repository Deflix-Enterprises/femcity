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
        case "AddFeaturedItem": #Superuser ~ Add a featured item
            $id = $_POST["id"];
            $description = "";
            $data = array("item_id"=>$id,"description"=>$description);

            $add_status = DbHandler::AddFeaturedItem($data);

            echo $add_status;
        break;

        case "RemoveFeaturedItem": #Superuser ~ Remove a featured item
            $id = $_POST["id"];
            $remove_status = DbHandler::RemoveFeaturedItem($id);

            echo $remove_status;
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

        //ACCOUNT REQUEST FUNCTION
        case "AcceptAccountRequest": #Superuser ~ Accept account request
            $id = $_POST["id"];
            $data = $_POST["data"];

            $data["password"] = PasswordHandler::Encrypt($data["password"]);#Encrypt the password
            $data["subbed"] = (int)$data["subbed"];#cast to int (probably uneccesary)

            $accept_status = DbHandler::AcceptAccRequest($id,$data);
            echo $accept_status;
        break;

        case "DenyAccountRequest": #Superuser ~ Deny account request
            $id = $_POST["id"];

            $deny_status = DbHandler::DenyAccRequest($id);
            echo $deny_status;
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
        case "DeleteFeaturedItem": #Admin & Superuser ~ Delete item
            $id = $_POST["id"];
            $remove_status = DbHandler::RemoveFeaturedItem($id);
            $delete_status = DbHandler::DeleteItem($id);

            echo ($remove_status && $delete_status);
        break;

        //ACCOUNT RELATED FUNCTIONS
        case "SuperuserSignup":
            $data = $_POST["data"];

            $signup_status = DbHandler::CreateSuperuserAccount($data);

            echo $signup_status;
        break;

        case "SuperuserLogin":
            $data = $_POST["data"];

            $login_status = MySessionHandler::SuperuserLogin($data["email"],$data["password"]);
            echo $login_status;
        break;

        //Ban and unban accounts
        case "BanAdminAccount":
            $acc_id = $_POST["acc_id"];

            $ban_status = DbHandler::BanAdminAccount($acc_id);
            echo $ban_status;
        break;

        case "UnbanAdminAccount":
            $acc_id = $_POST["acc_id"];

            $ban_status = DbHandler::UnbanAdminAccount($acc_id);
            echo $ban_status;
        break;

        //Reset admin accounts
        case "ResetAdminAccount":
            $acc_id = $_POST["acc_id"];

            #Get the admin account matching that acc id
            $reset_status = DbHandler::ResetAdminAccountPassword($acc_id);
            echo $reset_status;
        break;

        //Create country
        case "CreateCountry":
            $data = $_POST["data"];

            $create_status = DbHandler::AddCountry($data);

            echo $create_status;
        break;

        //Create region
        case "CreateRegion":
            $data = $_POST["data"];

            $create_status = DbHandler::AddRegion($data);
            echo $create_status;
        break;

        //Request an admin account
        case "RequestAdminAccount":
          $data = $_POST["data"];

          $request_status = DbHandler::RequestAdminAccount($data);

          echo $request_status;
        break;
        case "ViewPhone":
            $acc_id = @$_POST["acc_id"];
            if(isset($acc_id) && !empty($acc_id))
            {
                $admin_acc = MySessionHandler::GetAdminById($acc_id);
                if($admin_acc)
                {
                    $phone = $admin_acc["phone"];
                    
                    #Add the phone check entry to the db
                    $add_status = DbHandler::AddPhoneView($acc_id);
                    
                    #Ensure that entries from the same session don't count as multiple checks ~ consider
                    
                    if($add_status)
                    {
                        #echo the phone number as response
                        echo $phone; 
                    }
                    else #Failed to add the phone number check to the database
                    {
                        echo false;
                    }

                }
                else #Admin account was not found
                {
                    echo false;
                }
            }
            else
            {
                echo false;#Acc id was not set, return false 
            }
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
        case "GetRegionsInCountry":
            $country_id = htmlspecialchars(@$_GET["country_id"]);
            $regions = DbInfo::GetRegionsInCountry($country_id);

            if(@$regions && $regions->num_rows>0)
            {
                /*return data should be valid json
                    {{region_name,region_id},{}}
                */
                $regions_array = array();
                foreach($regions as $region)
                {
                    $region_data = array("region_id"=>$region["region_id"],"region_name"=>$region["region_name"]);

                    array_push($regions_array,$region_data);
                }

                echo json_encode($regions_array);
            }
            else
            {
                echo false;
            }
        break;
    }
}
