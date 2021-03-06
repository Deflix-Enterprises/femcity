<?php
@session_start();
require_once("db_connect.php");#Database connection file

//This interface determines what public functions need to be implemented in this class
interface DbInfoInterface
{
    //Get all records from a table  
    public static function GetAllCategories();
    public static function GetAllFeaturedItems();
    public static function GetAllOtherItems();
    public static function GetAllItems();
    public static function GetAllOffers();
    
    //Accounts
    public static function GetAllAdminAccounts();
    public static function GetAllSuperuserAccounts();
    public static function GetAllAccountRequests();
    public static function GetValidAdminAccounts();
    public static function GetBannedAdminAccounts();
    
    //Get records based on primary keys
    public static function GetCategoryById($cat_id);
    public static function GetFeaturedById($f_item_id);
    public static function GetItemById($item_id);
    public static function GetOfferById($promo_id);
    public static function GetOfferByCategory($cat_id);
    
    //Get records based on foreign keys
    public static function GetFeaturedByItemId($item_id);
    public static function GetAdminByCategory($cat_id);
    public static function GetOfferByItemId($item_id);
    public static function GetItemsByAccId($acc_id,$excluded_id);        
    public static function GetAllItemImages();
    public static function GetItemImagesByItem($item_id);
    
    //Locations
    public static function GetAllCountries();
    public static function GetAllRegions();
    public static function GetRegionsInCountry($country_id);
    public static function GetCountryByRegion($region_id);
    public static function GetCountryById($country_id);
    
    //Phone views
    public static function GetAllPhoneViews();
    public static function GetAdminPhoneViews($acc_id);
}

//This class deals with retrieval of records from the database
class DbInfo implements DbInfoInterface
{
    /*HELPER FUNCTIONS ~ PRIVATE FUNCTIONS USED INTERNALLY FOR CONVENIENCE*/
    //Checks if a single property exists. Private function - only used as convenience by other functions
    private static function SinglePropertyExists($table_name,$column_name,$prop_name,$prop_type="i",$prepare_error="<p>Error preparing data retrieval query </p>")#prop type is string used for bind_params
    {
        global $dbCon;

        $select_query = "SELECT * FROM $table_name WHERE $column_name=?";
        if ($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param($prop_type,$prop_name);
            
            if($select_stmt->execute())
            {
                $result = $select_stmt->get_result();
                if($result->num_rows>0)#found records
                {
                    return $result;
                }   
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else #failed to prepare the query for data retrieval
        {
            echo "<p>".($prepare_error)."</p>";
            return null;
        }
    }
    
    //Get a  single record ~ this will be used for getting records using primary keys
    protected static function GetSingleRecordUsingProperty($table_name,$column_name,$prop_name,$prop_type="i")
    {
        $records = self::SinglePropertyExists($table_name,$column_name,$prop_name,$prop_type);
        
        if($records)
        {
            foreach($records as $record_found)
            {
                return $record_found;
            }
        }
        return $records;

    }
    
    //Get all records from a specific table
    private static function GetAllRecordsFromTable($table_name)
    {
        global $dbCon;
        
        $select_query = "SELECT * FROM $table_name";
        if($select_stmt = $dbCon->prepare($select_query))
        {
            //Try executing the query
            if($select_stmt->execute())
            {
                return $select_stmt->get_result();
            }
            else #failed to run the query
            {
                return false;
            }
        }
        else # failed to prepare the query
        {
            return null;
        }
    }
    
    /*GET ALL RECORDS FROM A TABLE*/
    public static function GetAllCategories()
    {
        return self::GetAllRecordsFromTable("categories");
    }
    //Get all featured items
    public static function GetAllFeaturedItems()
    {
        global $dbCon;
        $select_query="SELECT * FROM featured_items INNER JOIN items ON featured_items.item_id = items.item_id";
        $result = $dbCon->query($select_query);
        return $result;
    }
    
    //Get all other items ~ items that are not featured
    public static function GetAllOtherItems()
    {
        global $dbCon;
        $featured_items = self::GetAllFeaturedItems();
        $featured_ids = array(); # Array containing featured item ids
        
        //If any featured items were found
        if($featured_items && $featured_items->num_rows>0)
        {
            //Add the featured item ids to the featured ids
            foreach($featured_items as $item)
            {
                array_push($featured_ids,$item["item_id"]);
            }
        }
        
        $all_items = self::GetAllItems();
        $other_items = array();
        
        //If there are featured items, filter them off the other items list
        if(!empty($featured_ids) && $all_items && $all_items->num_rows>0)
        {
            foreach($all_items as $item)
            {
                #If the item is in the featured item_ids variable, it is considered a featured item
                $is_featured = in_array($item["item_id"],$featured_ids);
                
                #If the item is not a featured item ~ it is considered to be "other item"
                if(!$is_featured)
                {
                    array_push($other_items,$item);
                }
            }
            
            return $other_items;
        }
        else #No featured items, return all items
        {
            return $all_items;
        }

    }
    
    //Get all items
    public static function GetAllItems()
    {
        return self::GetAllRecordsFromTable("items");
    }
    
    //Get all item images
    public static function GetAllItemImages()
    {
        global $dbCon;
        
        $select_query  ="SELECT * FROM item_images INNER JOIN items ON item_images.item_id = items.item_id";
        
        return ($dbCon->query($select_query));
    }
    //Get all offers
    public static function GetAllOffers()
    {
        return self::GetAllRecordsFromTable("offers");
    }
    
    //Get all admin accounts ~ select everything except the password
    public static function GetAllAdminAccounts()
    {
        global $dbCon;
        $select_query = "SELECT acc_id,first_name,last_name,business_name,business_description,admin_accounts.region_id,specific_location,cat_id,email,phone,subbed,date_created,date_activated,date_expires, regions.region_name,regions.country_id
        FROM admin_accounts INNER JOIN regions ON regions.region_id = admin_accounts.region_id";
        
        $select_status =($dbCon->query($select_query));
        
        #Any error debug info here //echo $dbCon->error;
        
        return $select_status;
    }
    
    //Get all admin accounts ~ select everything except the password for valid admin accounts
    public static function GetValidAdminAccounts()
    {
        global $dbCon;
        $select_query = "SELECT acc_id,first_name,last_name,business_name,business_description,region_id,specific_location,cat_id,email,phone,subbed,date_created,date_activated,date_expires FROM admin_accounts WHERE is_banned=0";
        
        return ($dbCon->query($select_query));
    }
    
    //Get all admin accounts ~ select everything except the password for banned admin accounts
    public static function GetBannedAdminAccounts()
    {
        global $dbCon;
        $select_query = "SELECT acc_id,first_name,last_name,business_name,business_description,region_id,specific_location,cat_id,email,phone,subbed,date_created,date_activated,date_expires FROM admin_accounts WHERE is_banned=1";
        
        return ($dbCon->query($select_query));
    }
    
    public static function GetAllSuperuserAccounts()
    {
        global $dbCon;
        $select_query = "SELECT acc_id,first_name,last_name,username,email,subbed,date_created FROM superuser_accounts";
        
        return ($dbCon->query($select_query));
    }
    
    public static function GetAllAccountRequests()
    {
        return self::GetAllRecordsFromTable("account_requests");
    }
    
    /*GET RECORDS BASED ON PRIMARY KEYS*/
    //Get Category by it's primary key (cat_id)
    public static function GetCategoryById($cat_id)
    {
        return (self::GetSingleRecordUsingProperty("categories","cat_id",$cat_id));
    }
    
    //Get Featured item by it's primary key (feature_id)
    public static function GetFeaturedById($f_item_id)
    {
        return (self::GetSingleRecordUsingProperty("featured_items","feature_id",$f_item_id));
    }
    
    //Get Item by it's primary key (item_id)
    public static function GetItemById($item_id)
    {
        return (self::GetSingleRecordUsingProperty("items","item_id",$item_id));
    }
    
    //Get Promotion by it's primary key (promo_id)
    public static function GetOfferById($offer_id)
    {
        return (self::GetSingleRecordUsingProperty("offers","offer_id",$offer_id));
    }
    public static function GetOfferByCategory($cat_id)
    {
        return (self::GetSingleRecordUsingProperty("offers","cat_id",$cat_id));
    }
    
    /*GET RECORDS BASED ON FOREIGN KEYS*/
    
    //Get admin by category
    public static function GetAdminByCategory($cat_id)
    {
        return self::SinglePropertyExists("admin_accounts","cat_id",$cat_id);
    }
    
    //Get Featured item by item_id
    public static function GetFeaturedByItemId($item_id)
    {
        return self::SinglePropertyExists("featured_items","item_id",$item_id);
    }
    
    //Get Promotion item by item_id
    public static function GetOfferByItemId($item_id)
    {
        return self::SinglePropertyExists("offers","item_id",$item_id);
    }
    
    //Get item by acc_id ~ the id of the account that added it
    public static function GetItemsByAccId($acc_id,$excluded_id=0)
    {
        global $dbCon;
        $select_query = "SELECT * FROM items WHERE acc_id=? AND (NOT item_id=?)";
        
        if($select_stmt = $dbCon->prepare($select_query))
        {
            //If the excluded id has been provided
            $select_stmt->bind_param("ii",$acc_id,$excluded_id);    
            $exec_status = ($select_stmt->execute());
            
            if($exec_status)
            {
                return $select_stmt->get_result();
            }
            else
            {
                return $exec_status;
            }
            
        }
        else
        {
            return null;
        }
    }
    
    //Get item images based on an item Id
    public static function GetItemImagesByItem($item_id)
    {
        global $dbCon;
        $item_id = htmlspecialchars($item_id);
        $select_query = "SELECT * FROM item_images INNER JOIN items ON item_images.item_id = items.item_id WHERE item_images.item_id=".$item_id;
        
        return ($dbCon->query($select_query));
    }
    
    //Get a single item image
    public static function GetSingleItemImage($item_id)
    {
        $item_images = self::GetItemImagesByItem($item_id);
        if($item_images && !empty($item_images))
        {
            foreach($item_images as $img)
            {
                return $img;
            }
        }
        return $item_images;
    }
    
    //Locations
    #Get all countries
    public static function GetAllCountries()
    {
        global $dbCon;
        $select_query = "SELECT * FROM countries";
        
        return($dbCon->query($select_query));
    }
    
    #Get all regions
    public static function GetAllRegions()
    {
        global $dbCon;
        
        $select_query = "SELECT * FROM regions INNER JOIN countries ON regions.country_id = countries.country_id";
        
        return($dbCon->query($select_query));
    }
    
    #Get all regions belonging to a specific country
    public static function GetRegionsInCountry($country_id)
    {
        global $dbCon;
        
        $select_query = "SELECT * FROM regions INNER JOIN countries ON regions.country_id = countries.country_id WHERE regions.country_id=?";
        
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("i",$country_id);
            
            $select_status  = ($select_stmt->execute());
            if($select_status)
            {
                return $select_stmt->get_result();
            }
            else
            {
                return $select_status;
            }
        }
        else
        {
            return null;
        }
        
    }
    
    public static function GetCountryByRegion($region_id)
    {
        global $dbCon;
        
        $select_query = "SELECT country_id FROM regions WHERE region_id=?";
        
        //Prepare the query
        if($select_stmt = $dbCon->prepare($select_query))
        {
            $select_stmt->bind_param("i",$region_id);
            $select_status = $select_stmt->execute();
            
            if($select_status)
            {
                $results = $select_stmt->get_result();
                if(@$results && $results->num_rows>0)
                {
                    //Return the first country id found
                    foreach($results as $result)
                    {
                        return ($result["country_id"]);
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return $select_status;
            }
        }
        else
        {
            return null;
        }
    }
    
    //Get search results
    public static function GetSearchResults($search_query,$region_id,$specific_location)
    {
        #TODO ~ consider adding search by category
        global $dbCon;
        //FILTERS (WHERE) ~ region_id
        //GENERIC (LIKE) ~ business_name, specific_location, item_name, item_description,  
        $select_query = "SELECT * FROM items INNER JOIN admin_accounts ON admin_accounts.acc_id = items.acc_id 
        WHERE 
        (
            admin_accounts.business_name LIKE ? OR 
            admin_accounts.specific_location LIKE ? OR
            items.item_name LIKE ? OR 
            items.description LIKE ?
        ) 
        AND (admin_accounts.region_id = ?)
        ";
        
        
        //Attempt to prepare query
        if($select_stmt = $dbCon->prepare($select_query))
        {   
            $search_query = "%$search_query%";
            $specific_location = "%$specific_location%";
            
            $select_stmt->bind_param("ssssi",$search_query,$specific_location,$search_query,$search_query,$region_id);
            
            if($select_stmt->execute())
            {
                return $select_stmt->get_result();
            }
            else #Failed to execute select stmt
            {
                echo "<div class='well'><p><b>Technical error:</b>Failed to execute the query to get search results</p></div><br>";
                return false;
            }
        }
        else
        {
            echo $dbCon->error;
            return null;
        }
    }
    
    //Get a country by its id
    public static function GetCountryById($country_id)
    {
       return self::GetSingleRecordUsingProperty("countries","country_id",$country_id) ;
    }
    
    //Get all phone views
    public static function GetAllPhoneViews()
    {
        return self::GetAllRecordsFromTable("phone_views");
    }
    
    //Get specific admin account phone views
    public static function GetAdminPhoneViews($acc_id)
    {
        return self::SinglePropertyExists("phone_views","acc_id",$acc_id);
    }
}
