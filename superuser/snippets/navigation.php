<?php

//Handles the superuser's navigation
class SuperuserNavigation
{
    const ACTIVE_CLASS = "active";
    //Navigation section classes
    public $categories_class;
    public $users_class;
    public $featured_class;
    public $offers_class;
    public $requests_class;
    public $profile_class;
    public $locations_class;
    public $banned_acc_class;
    public $settings_class;
    public $account_views_class;
    
    function __construct($active_tab)
    {   
        $this->categories_class = $this->users_class = $this->featured_class = $this->offers_class = $this->requests_class = $this->profile_class = $this->locations_class = $this->banned_acc_class = $this->settings_class = $this->account_views_class = "";
        
        //Setting the active classes
        switch($active_tab)
        {
            case "categories":
                $this->categories_class = self::ACTIVE_CLASS;
            break;
            case "accounts":
                $this->users_class = self::ACTIVE_CLASS;
            break;
            case "featured":
                $this->featured_class = self::ACTIVE_CLASS;
            break;
            case "offers":
                $this->offers_class = self::ACTIVE_CLASS;
            break;
            case "requests":
                $this->requests_class = self::ACTIVE_CLASS;
            break;
            case "profile":
                $this->profile_class = self::ACTIVE_CLASS;
                $this->settings_class = self::ACTIVE_CLASS;
            break;
            case "locations":
                $this->locations_class = self::ACTIVE_CLASS;
                $this->settings_class = self::ACTIVE_CLASS;
            break;
            case "banned_accounts":
                $this->banned_acc_class = self::ACTIVE_CLASS;
                $this->settings_class = self::ACTIVE_CLASS;
            break;
            case "account_views":
                $this->account_views_class = self::ACTIVE_CLASS;
                $this->settings_class = self::ACTIVE_CLASS;
            break;                

            default:
                $this->categories_class = self::ACTIVE_CLASS;
        }
?>
        <nav class="navbar navbar-default">
          <div class="container-fluid">
              
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#superuserNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> 
              </button>
              <a class="navbar-brand" href="../index.php">Femcity Superuser Panel</a>
            </div>
          <div class="collapse navbar-collapse" id="superuserNavbar">
            <ul class="nav navbar-nav">
              <li class="<?php echo $this->categories_class;?>"><a href="?p=categories">Categories</a></li>
              <li class="<?php echo $this->users_class;?>"><a href="?p=accounts">Accounts</a></li>
              <li class="<?php echo $this->featured_class;?>"><a href="?p=featured">Featured</a></li>
              <li class="<?php echo $this->offers_class;?>"><a href="?p=offers">Offers</a></li>
              <li class="<?php echo $this->requests_class;?>"><a href="?p=requests">Requests</a></li>
            </ul>
            
            <div class="container">
               <ul class="nav navbar-nav navbar-right">
                  <li class="<?php echo $this->settings_class;?>">
                      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                          <span class="glyphicon glyphicon-cog"></span> Settings 
                          <span class="caret"></span>
                      </a>
                    <ul class="dropdown-menu">
                        <li class="<?php echo $this->profile_class;?>" title="Personal profile. You can change your account information here.">
                            <a href="?p=profile">
                                <span class="glyphicon glyphicon-user"></span> Profile
                            </a>
                        </li>
                        <li class="<?php echo $this->locations_class;?>" title="Create and manage locations here">
                            <a href="?p=locations">
                                <span class="glyphicon glyphicon-map-marker"></span> Locations
                            </a>
                        </li>
                        <li class="<?php echo $this->banned_acc_class;?>" title="Account views. View how many times certain accounts were viewed based on how many people clicked view phone.">
                            <a href="?p=account_views">
                                <span class="glyphicon glyphicon-info-sign"></span> Account Views
                            </a>
                        </li>
                        <li class="<?php echo $this->account_views_class;?>" title="Banned accounts. You can view and manage banned accounts here">
                            <a href="?p=banned_accounts">
                                <span class="glyphicon glyphicon-ban-circle"></span> Banned Accounts
                            </a>
                        </li>
                        <li title="Logout of your account">
                            <a href="?action=logout">
                                <span class="glyphicon glyphicon-off"></span> Logout
                            </a>
                        </li>
                    </ul>
                      
                   </li>
                </ul>
              </div>
          </div>
          </div>
        </nav>
<?php
    }
}

require_once("../handlers/message_display.php");#Controls displaying of message boxes