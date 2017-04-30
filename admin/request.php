<?php
    require_once("../handlers/db_info.php");
    require_once("../handlers/message_display.php");#Controls displaying of message boxes

    function DisplayAccountRequestBox()
    {
?>
                <div id="signupbox" style="margin-top:25px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Admin Request account
                                <small class="pull-right">Have an account? <a href="login.php">Login</a></small>
                            </div>
                            
                        </div>  
                        <div class="panel-body" >
                            <form id="signupform" class="form-horizontal" role="form">
                                <div id="signupalert" class="alert alert-danger hidden">
                                    <p>Error:</p>
                                    <span></span>
                                </div>

                                <!--First name-->
                                <div class="form-group">
                                    <label for="firstname" class="col-md-3 control-label">First Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="firstname" placeholder="First Name (Required)">
                                    </div>
                                </div>
                                
                                <!--Last name-->
                                <div class="form-group">
                                    <label for="lastname" class="col-md-3 control-label">Last Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="lastname" placeholder="Last Name (Required)">
                                    </div>
                                </div>      
                                
                                <!-- Business name-->
                                <div class="form-group">
                                    <label for="businessname" class="col-md-3 control-label">Business</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="businessname" placeholder="Business Name (Required)">
                                    </div>
                                </div>
                                
                                <!--Business description-->
                                 <div class="form-group">
                                    <label for="username" class="col-md-3 control-label">Description</label>
                                    <div class="col-md-9">
                                        <textarea style="resize:vertical;" type="text" class="form-control" id="businessdescr" placeholder="Business Description (Required)"></textarea>
                                    </div>
                                </div>
                                
                                <!--Business category-->
                                <div class="form-group">
                                    <label for="email" class="col-md-3 control-label">Category</label>
                                    <div class="col-md-9">
                                        <select id="business_category" required title="Category the business belongs to" class="form-control">
                                        <?php
                                            $categories = DbInfo::GetAllCategories();

                                            //If there are any categories
                                            if($categories):
                                                foreach($categories as $cat):
                                        ?>
                                            <option value="<?php echo $cat["cat_id"];?>"><?php echo $cat["cat_name"];?></option>
                                        <?php
                                                endforeach;
                                            else:
                                        ?>
                                        <option value="0">No Categories found</option>
                                        <?php
                                            endif;
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!--Email address-->
                                <div class="form-group">
                                    <label for="email" class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" id="email" placeholder="Email Address (Required)">
                                    </div>
                                </div>
                                
                                <!--Phone number-->
                                <div class="form-group">
                                    <label for="phone" class="col-md-3 control-label">Phone</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="phone" placeholder="Phone Number (Required)">
                                    </div>
                                </div>
                                
                                <!--Password-->
                                <div class="form-group">
                                    <label for="password" class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="password" placeholder="Password (Required)">
                                    </div>
                                </div>
                                
                                <!--Confirm password-->
                                <div class="form-group">
                                    <label for="confirm_password" class="col-md-3 control-label">Confirm</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="confirm_pass" placeholder="Confirm password (Required)">
                                    </div>
                                </div>
                                
                                <!--Google maps location -->
<!--
                                <div class="form-group">
                                    <label for="confirm_password" class="col-md-3 control-label">Location</label>
                                    <div class="col-md-9" id="map">
                                        
                                    </div>
                                </div>
-->
                                
                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-xs-12">
                                        <button id="btn-signup" type="submit" class="btn btn-info pull-right">Request Account</button>
                                    </div>
                                </div>
                            </form>
                         </div>
                    </div>
                </div> 
<?php
    }

    //Require db handler for entering records into the database
    require_once("../handlers/db_handler.php");
    require_once("../handlers/session_handler.php");#session handler
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Femcity | Superuser Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/toastr.min.css" rel="stylesheet">
</head>
    <body>
        <main>
            <div class="container">
                <?php
                    //If admin is logged in ~ redirect to index.php
                    if(MySessionHandler::AdminIsLoggedIn())
                    {
                        echo "<p>Logged in. Redirecting you to the admin panel...</p>";
                        header("Location:index.php");
                    }
                    else
                    {
                        //Display informative message
                        echo "<div class='container'><br>";
                        MessageDisplay::PrintInfo("<b>Thank you for expressing interest in Femcity.</b> You can request an account for your business here and we'll get back to you as soon as possible.");
                        echo "</div>";
                        DisplayAccountRequestBox();
                    }
                    
                ?>
            </div>
        </main>
        
        <script src="../js/jquery.min.js"></script>
        <script src="../js/toastr.min.js"></script>
        
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>