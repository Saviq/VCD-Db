<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2007 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Kernel
 * @version $Id$
 * @since  0.985
  */
?>
<?php

require_once(dirname(__FILE__) . '/settings/settingsFacade.php');
require_once(dirname(__FILE__) . '/user/userFacade.php');
require_once(dirname(__FILE__) . '/pornstar/pornstarFacade.php');
require_once(dirname(__FILE__) . '/cdcover/cdcoverFacade.php');
require_once(dirname(__FILE__) . '/vcd/vcdFacade.php');

/**
 * Provide the Web UI access to the User Services.  All errors that occur beneath this layer
 * are catched here in and displayed in the Web UI.  Since the errors are re-thrown in the 
 * User business class, the webservices can now handle its own exception logic and deal with 
 * Exceptions and throw them as soap_fault instead of getting prepared javascript messages 
 * that were originally intended for the Web UI.
 *
 */
class UserServices extends VCDServices {
	
		
	/**
	 * Get user By ID
	 *
	 * @param int $user_id
	 * @return userObj
	 */
	public static function getUserByID($user_id) {
		try {
			
			return self::User()->getUserByID($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update User, return true on success otherwise false
	 *
	 * @param userObj $userObj
	 * @return bool
	 */
	public static function updateUser(userObj $userObj) {
		try {
			
			return self::User()->updateUser($userObj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete User
	 *
	 * @param int $user_id | The userID
	 * @param bool $erase_data | Delete all user data including movie list
	 */
	public static function deleteUser($user_id, $erase_data = false) {
		try {
			
			self::User()->deleteUser($user_id, $erase_data);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new User to VCD-db, returns true on success otherwise false
	 *
	 * @param userObj $userObj | The userObj to create
	 * @return bool
	 */
	
	public static function addUser(userObj $userObj) {
		try {
			
			return self::User()->addUser($userObj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all Users in VCD-db, returns array of User objects
	 *
	 * @return array
	 */
	public static function getAllUsers() {
		try {
			
			return self::User()->getAllUsers();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all users that have added a movie to VCD-db, return array of User objects
	 *
	 * @return array
	 */
	public static function getActiveUsers() {
		try {
			
			return self::User()->getActiveUsers();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get User by specific username
	 *
	 * @param string $user_name | The username
	 * @return userObj
	 */
	public static function getUserByUsername($user_name) {
		try {
			
			return self::User()->getUserByUsername($user_name);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Store the users session after user has requested to be remembered during login.
	 *
	 * @param string $session_id | The hashed session id
	 * @param int $user_id | The User ID
	 */
	public static function addSession($session_id, $user_id) {
		try {
			
			self::User()->addSession($session_id, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Check if the specified session in valid for authentication.
	 * Returns true if the session is still valid, otherwise false.
	 *
	 * @param string $session_id | The session ID from cookie
	 * @param string $session_time | The original session save time
	 * @param int $user_id | The User ID
	 * @return bool
	 */
	public static function isValidSession($session_id, $session_time, $user_id) {
		try {
			
			return self::User()->isValidSession($session_id, $session_time, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
			
	/**
	 * Get all user roles in VCD-db.  Returns array of UserRole objects.
	 *
	 * @return array
	 */
	public static function getAllUserRoles() {
		try {
			
			return self::User()->getAllUserRoles();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all users in specific role.  Returns array of Users objects.
	 *
	 * @param int $role_id | The ID of the userrole to seek by
	 * @return array
	 */
	public static function getAllUsersInRole($role_id) {
		try {
			
			return self::User()->getAllUsersInRole($role_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	

	/**
	 * Delete a specific user role, error is thrown is some user/s is still using this role.
	 * Returns true if actions secceds otherwise false. 
	 *
	 * @param int $role_id | The userrole ID to delete
	 * @return bool
	 */
	public static function deleteUserRole($role_id) {
		try {
			
			return self::User()->deleteUserRole($role_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the default role assigned to new users that are created/registered in VCD-db.
	 *
	 * @return userRoleObj
	 */
	public static function getDefaultRole() {
		try {
			
			return self::User()->getDefaultRole();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Assign a specific role as a default role to new users.
	 *
	 * @param int $role_id | The ID of the UserRole to use.
	 */
	public static function setDefaultRole($role_id) {
		try {
			
			self::User()->setDefaultRole($role_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}

	/**
	 * Get all user properties in VCD-db.  Returns array of userProperties objects.
	 *
	 * @return array
	 */
	public static function getAllProperties() {
		try {
			
			return self::User()->getAllProperties();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific userProperties object by ID.
	 *
	 * @param int $property_id | The ID of the userProperty
	 * @return userPropertiesObj
	 */
	public static function getPropertyById($property_id) {
		try {
			
			return self::User()->getPropertyById($property_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific userProperty object by the property key.
	 *
	 * @param string $property_key | The property key
	 * @return userPropertiesObj
	 */
	public static function getPropertyByKey($property_key) {
		try {
			
			return self::User()->getPropertyByKey($property_key);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new userProperty object to VCD-db.
	 *
	 * @param userPropertiesObj $obj
	 */
	public static function addProperty(userPropertiesObj $obj) {
		try {
			
			self::User()->addProperty($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a specific user Property object.
	 *
	 * @param int $property_id | The ID of the userProperty object to delete.
	 */
	public static function deleteProperty($property_id) {
		try {
			
			self::User()->deleteProperty($property_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update specific userProperties object.
	 *
	 * @param userPropertiesObj $obj
	 */
	public static function updateProperty(userPropertiesObj $obj) {
		try {
			
			self::User()->updateProperty($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
			
	/**
	 * Add new userProperty to a specific user.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @param int $user_id | The ID of the user to add the property to
	 */
	public static function addPropertyToUser($property_id, $user_id) {
		try {
			
			self::User()->addPropertyToUser($property_id, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Remove specific property from the selected User.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @param int $user_id | The ID of the user to remove the property from
	 */
	public static function deletePropertyOnUser($property_id, $user_id) {
		try {
			
			self::User()->deletePropertyOnUser($property_id, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all users that are associated to a a specific userProperty, returns array of User objects.
	 *
	 * @param int $property_id | The ID of the userProperties object
	 * @return array
	 */
	public static function getAllUsersWithProperty($property_id) {
		try {
			
			return self::User()->getAllUsersWithProperty($property_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the users with most movie count in database.
	 * Returns array of User objects sorted by highest movie count.
	 *
	 * @return array
	 */
	public static function getUserTopList() {
		try {
			
			return self::User()->getUserTopList();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
}

/**
 * Provide the Web UI access to the Settings Services.  All errors that occur beneath this layer
 * are catched here in and displayed in the Web UI.  Since the errors are re-thrown in the 
 * Settings business class, the webservices can now handle its own exception logic and deal with 
 * Exceptions and throw them as soap_fault instead of getting prepared javascript messages 
 * that were originally intended for the Web UI.
 *
 */
class SettingsServices extends VCDServices {
	
	
	/**
	 * Get all Settings objects in VCD-db.  Returns array of Settings objects.
	 *
	 * @return array
	 */
	public static function getAllSettings() {
		try {
			
			return self::Settings()->getAllSettings();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific settings string by key.
	 *
	 * @param string $key | The settings key that identifies the object
	 * @return string
	 */
	public static function getSettingsByKey($key) {
		try {
			
			return self::Settings()->getSettingsByKey($key);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific settings object by ID.
	 *
	 * @param int $settings_id | The ID of the settings object
	 * @return settingsObj
	 */
	public static function getSettingsByID($settings_id) {
		try {
			
			return self::Settings()->getSettingsByID($settings_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new settings object to VCD-db.  Param settings can either be settingsObj or an
	 * array of settings objects.
	 *
	 * @param mixed $settings
	 */
	public static function addSettings($settings) {
		try {
			
			self::Settings()->addSettings($settings);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update specific settings object.
	 *
	 * @param settingsObj $obj
	 */
	public static function updateSettings(settingsObj $obj) {
		try {
			
			self::Settings()->updateSettings($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete specific settings object.  Returns true on success otherwise false.
	 *
	 * @param int $settings_id | The ID of the settings object to delete
	 * @return bool
	 */
	public static function deleteSettings($settings_id) {
		try {
			
			return self::Settings()->deleteSettings($settings_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get all sourcesite objects in VCD-db.  Returns array of sourceSite objects.
	 *
	 * @return array
	 */
	public static function getSourceSites() {
		try {

			return self::Settings()->getSourceSites();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific sourceSite object by ID.
	 *
	 * @param int $source_id | The ID of the sourceSite object to get
	 * @return sourceSiteObj
	 */
	public static function getSourceSiteByID($source_id) {
		try {
			
			return self::Settings()->getSourceSiteByID($source_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific sourceSite object by the sourcesite Alias.
	 *
	 * @param string $alias | The alias of the sourcesite.  EG "imdb"
	 * @return sourceSiteObj
	 */
	public static function getSourceSiteByAlias($alias) {
		try {
			
			return self::Settings()->getSourceSiteByAlias($alias);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new sourceSite object to VCD-db.
	 *
	 * @param sourceSiteObj $obj
	 */
	public static function addSourceSite(sourceSiteObj $obj) {
		try {

			self::Settings()->addSourceSite($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete specific sourceSite object from VCD-db.
	 *
	 * @param int $source_id | The ID of the sourceSite object to delete.
	 */
	public static function deleteSourceSite($source_id) {
		try {
			
			self::Settings()->deleteSourceSite($source_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Delete NFO file
	 *
	 * @param int $metadata_id | The Id of the metadata containing the NFO entry
	 */
	public static function deleteNFO($metadata_id) {
		try {
			
			self::Settings()->deleteNFO($metadata_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);	
		}
	}
	
	
	/**
	 * Update a specific sourceSite object.
	 *
	 * @param sourceSiteObj $sourceSiteObj
	 */
	public static function updateSourceSite(sourceSiteObj $sourceSiteObj) {
		try {
			
			self::Settings()->updateSourceSite($sourceSiteObj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get all mediaType objects in VCD-db.  Returns array of mediaType objects.
	 *
	 * @return array
	 */
	public static function getAllMediatypes() {
		try {
			
			return self::Settings()->getAllMediatypes();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific mediaType object by ID.
	 *
	 * @param int $media_id | The ID of the specific mediaType object
	 * @return mediaTypeObj
	 */
	public static function getMediaTypeByID($media_id) {
		try {
			
			return self::Settings()->getMediaTypeByID($media_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new mediaType object to VCD-db.
	 *
	 * @param mediaTypeObj $mediaTypeObj
	 */
	public static function addMediaType(mediaTypeObj $mediaTypeObj) {
		try {
			
			self::Settings()->addMediaType($mediaTypeObj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a mediaType object from VCD-db.  Returns true on success, otherwise false.
	 *
	 * @param int $mediatype_id | The ID of the mediaType object to delete.
	 * @return bool
	 */
	public static function deleteMediaType($mediatype_id) {
		try {
			
			return self::Settings()->deleteMediaType($mediatype_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update a specific mediaType object in VCD-db.
	 *
	 * @param mediaTypeObj $mediaTypeObj
	 */
	public static function updateMediaType(mediaTypeObj $mediaTypeObj) {
		try {
			
			self::Settings()->updateMediaType($mediaTypeObj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all mediatype objects that are available on the specified movie object.
	 * Returns array of mediaType objects.
	 *
	 * @param int $vcd_id | The ID of the movie to get mediatype objects by
	 * @return array
	 */
	public static function getMediaTypesOnCD($vcd_id) {
		try {
			
			return self::Settings()->getMediaTypesOnCD($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get array of mediatype data that user uses in all his movies.
	 * Return array of data
	 *
	 * @param int $user_id | The User ID to seek mediaType objects by.
	 * @return array
	 */
	public static function getMediaTypesInUseByUserID($user_id) {
		try {
			
			return self::Settings()->getMediaTypesInUseByUserID($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get all media types that are being used by the system
	 *
	 * @return array | Array of mediatype objects
	 */
	public static function getMediaTypesInUse() {
		try {
			
			return self::Settings()->getMediaTypesInUse();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the count of media's in the specified movie category
	 *
	 * @param int $category_id | The category ID to use
	 * @return array
	 */
	public static function getMediaCountByCategory($category_id) {
		try {
			
			return self::Settings()->getMediaCountByCategory($category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get the movie count of all movies belonging to a specific category.
	 *
	 * @param int $user_id | The User ID of the user to get results from
	 * @param int $category_id | The ID of the category to filter by
	 * @return array
	 */
	public static function getMediaCountByCategoryAndUserID($user_id, $category_id) {
		try {
			
			return self::Settings()->getMediaCountByCategoryAndUserID($user_id, $category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a specific mediaType object by it's name.
	 *
	 * @param string $name | The name of the mediaType object
	 * @return mediaTypeObj
	 */
	public static function getMediaTypeByName($name) {
		try {
			
			return self::Settings()->getMediaTypeByName($name);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get all movieCategory objects available in VCD-db.
	 * Returns array of movieCategory objects.
	 *
	 * @return array
	 */
	public static function getAllMovieCategories() {
		try {
			
			return self::Settings()->getAllMovieCategories();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get collection of all movieCategory objects that are in use in VCD-db.
	 * Returns array of movieCategory
	 *
	 * @return array
	 */
	public static function getMovieCategoriesInUse() {
		try {
			
			return self::Settings()->getMovieCategoriesInUse();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a specific movieCategory object by category ID.
	 *
	 * @param int $category_id | The ID of the movieCategory object
	 * @return movieCategoryObj
	 */
	public static function getMovieCategoryByID($category_id) {
		try {
			
			return self::Settings()->getMovieCategoryByID($category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new movieCategory object to VCD-db.
	 *
	 * @param movieCategoryObj $obj
	 */
	public static function addMovieCategory(movieCategoryObj $obj) {
		try {
			
			self::Settings()->addMovieCategory($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a movieCategory object from VCD-db.
	 *
	 * @param int $category_id | The ID of the movieCategory object to delete
	 */
	public static function deleteMovieCategory($category_id) {
		try {
			
			self::Settings()->deleteMovieCategory($category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update a movieCategory object in VCD-db.
	 *
	 * @param movieCategoryObj $obj
	 */
	public static function updateMovieCategory(movieCategoryObj $obj) {
		try {
			
			self::Settings()->updateMovieCategory($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the ID of a specific movieCategory object by using its name as identifier.
	 *
	 * @param string $name | The name of the moviecategory object to seek by
	 * @param bool $localized | Is the category name translated ?
	 * @return int | Returns the ID of the movieCategory object
	 */
	public static function getCategoryIDByName($name, $localized=false) {
		try {
			
			return self::Settings()->getCategoryIDByName($name, $localized);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get the categoryId of an item by the itemID
	 *
	 * @param int $itemId | The itemID to perform the lookup on
	 * @return int | The category ID of the item
	 */
	public static function getCategoryIDByItemId($itemId) {
		try {
			
			return self::Settings()->getCategoryIDByItemId($itemId);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all movieCategory objects in use by specific User ID.
	 * Returns array of movieCategory objects.
	 *
	 * @param int $user_id | The User ID of the user to filter by
	 * @return array
	 */
	public static function getCategoriesInUseByUserID($user_id) {
		try {
			
			return self::Settings()->getCategoriesInUseByUserID($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a specific borrower object by the object ID.
	 *
	 * @param int $borrower_id | The ID of the borrower object to seek by.
	 * @return borrowerObj
	 */
	public static function getBorrowerByID($borrower_id) {
		try {
			
			return self::Settings()->getBorrowerByID($borrower_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all the borrower objects created by a specific User.
	 * Returns array of borrower objects.
	 *
	 * @param int $user_id | The User ID of the user that is the owner of the borrower objects
	 * @return array
	 */
	public static function getBorrowersByUserID($user_id) {
		try {
			
			return self::Settings()->getBorrowersByUserID($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new borrower to VCD-db.
	 *
	 * @param borrowerObj $obj
	 */
	public static function addBorrower(borrowerObj $obj) {
		try {
			
			self::Settings()->addBorrower($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update a borrower object.
	 *
	 * @param borrowerObj $obj
	 */
	public static function updateBorrower(borrowerObj $obj) {
		try {
			
			self::Settings()->updateBorrower($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a specific borrower object
	 *
	 * @param borrowerObj $obj
	 */
	public static function deleteBorrower(borrowerObj $obj) {
		try {
			
			self::Settings()->deleteBorrower($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Flag specific movies as loaned in the loan system.
	 *
	 * @param int $borrower_id | The ID of the borrower that is getting the CD's
	 * @param array $arrMovieIDs | Array of movie ID's
	 */
	public static function loanCDs($borrower_id, $arrMovieIDs) {
		try {

			self::Settings()->loanCDs($borrower_id, $arrMovieIDs);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Returns a specific movie from a loan, using the ID of the loan entry.
	 *
	 * @param int $loan_id | The ID of the loan entry.
	 */
	public static function loanReturn($loan_id) {
		try {
			
			self::Settings()->loanReturn($loan_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all the loan objects belonging to a specific VCD-db User.
	 * Returns array of loan objects.
	 *
	 * @param int $user_id | The User ID of the loaner
	 * @param bool $show_returned | Include loans that have been returned
	 * @return array
	 */
	public static function getLoans($user_id, $show_returned) {
		try {
			
			return self::Settings()->getLoans($user_id, $show_returned);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all loan entries by a specific borrower. Returns array of loan objects.
	 *
	 * @param int $user_id | The owner of the loan
	 * @param int $borrower_id | The borrower ID to seek by
	 * @param bool $show_returned | Show loans that have been returned or not
	 * @return array
	 */
	public static function getLoansByBorrowerID($user_id, $borrower_id, $show_returned = false) {
		try {
			
			return self::Settings()->getLoansByBorrowerID($user_id, $borrower_id, $show_returned);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
		
	/**
	 * Add new Rss Feed to VCD-db.
	 *
	 * @param rssObj $obj
	 */
	public static function addRssfeed(rssObj $obj) {
		try {

			self::Settings()->addRssfeed($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific Rss feed by the feed ID.
	 *
	 * @param int $feed_id | The ID of the Rss feed.
	 * @return rssObj
	 */
	public static function getRssfeed($feed_id) {
		try {
			
			return self::Settings()->getRssfeed($feed_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get All Rss Feeds that belong to specific User.
	 * Returns array of rss objects.
	 *
	 * @param int $user_id | The User ID of the Rss feed owner
	 * @return array
	 */
	public static function getRssFeedsByUserId($user_id) {
		try {
			
			return self::Settings()->getRssFeedsByUserId($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a specific Rss feed
	 *
	 * @param int $feed_id | The ID of the feed to delete
	 */
	public static function delFeed($feed_id) {
		try {
			
			self::Settings()->delFeed($feed_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update a specific Rss feed.
	 *
	 * @param rssObj $obj
	 */
	public static function updateRssfeed(rssObj $obj) {
		try {
			
			self::Settings()->updateRssfeed($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Add a movie to users wishlist.
	 *
	 * @param int $vcd_id | The ID of the movie to add to the wishlist
	 * @param int $user_id | The User ID of the wishlist owner
	 */
	public static function addToWishList($vcd_id, $user_id) {
		try {

			self::Settings()->addToWishList($vcd_id, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a wishlist by User ID.  Returns assoc array containg keys [id, title, mine] 
	 *
	 * @param int $user_id | The Owner of the wishlist
	 * @return array
	 */
	public static function getWishList($user_id) {
		try {
			
			return self::Settings()->getWishList($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Check is a specified movie is on users wishlist.
	 *
	 * @param int $vcd_id | The ID of the movie to check
	 * @return bool
	 */
	public static function isOnWishList($vcd_id) {
		try {
			
			return self::Settings()->isOnWishList($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Remove a movie from users wishlist.
	 *
	 * @param int $vcd_id | The ID of the movie to remove
	 * @param int $user_id | The Owner ID of the wishlist
	 */
	public static function removeFromWishList($vcd_id, $user_id) {
		try {
			
			self::Settings()->removeFromWishList($vcd_id, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Check if the users wishlist is public
	 *
	 * @param int $user_id | The owner ID of the wishlist
	 * @return bool
	 */
	public static function isPublicWishLists($user_id) {
		try {
			
			return self::Settings()->isPublicWishLists($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Add a new comment.
	 *
	 * @param commentObj $obj
	 */
	public static function addComment(commentObj $obj) {
		try {
			
			self::Settings()->addComment($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete comment.
	 *
	 * @param int $comment_id | The ID of the comment to delete
	 */
	public static function deleteComment($comment_id) {
		try {
			
			self::Settings()->deleteComment($comment_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a specific comment by ID.
	 *
	 * @param int $comment_id | The ID of the comment
	 * @return commentObj
	 */
	public static function getCommentByID($comment_id) {
		try {
			
			return self::Settings()->getCommentByID($comment_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all comments by specific user ID, returns array of comment objects.
	 *
	 * @param int $user_id | The Owner ID of the comments.
	 * @return array
	 */
	public static function getAllCommentsByUserID($user_id) {
		try {
			
			return self::Settings()->getAllCommentsByUserID($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all comments that have been made on a specific movie.
	 * Returns array of comments objects.
	 *
	 * @param int $vcd_id | The ID of the movie to get comments
	 * @return array
	 */
	public static function getAllCommentsByVCD($vcd_id) {
		try {
			
			return self::Settings()->getAllCommentsByVCD($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the statistics object.
	 *
	 * @return statisticsObj
	 */
	public static function getStatsObj() {
		try {
			
			return self::Settings()->getStatsObj();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
		
	/**
	 * Get statistics by a specified user ID.  Returns array of 3 statistics objects.
	 *
	 * @param int $user_id | The User ID that the statistics belong to
	 * @return array
	 */
	public static function getUserStatistics($user_id) {
		try {
			
			return self::Settings()->getUserStatistics($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Add a new metadatatype object to the database, returns the same object with populated ID.
	 *
	 * @param metadataTypeObj $obj
	 * @return metadataTypeObj
	 */
	public static function addMetadataType(metadataTypeObj $obj) { 
		try {
		
			return self::Settings()->addMetaDataType($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
		
	
	/**
	 * Add new medata object to VCD-db.  Param can either be metadata object or an array of metadata objects.
	 *
	 * @param mixed $arrObj
	 */
	public static function addMetadata($arrObj) {
		try {
			
			self::Settings()->addMetadata($arrObj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update a specific metadata.
	 *
	 * @param metadataObj $obj
	 */
	public static function updateMetadata(metadataObj $obj) {
		try {
			
			self::Settings()->updateMetadata($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Delete a user defined metadata type
	 *
	 * @param int $type_id | The Id of the metadata type
	 */
	public static function deleteMetaDataType($type_id) {
		try {
			
			self::Settings()->deleteMetaDataType($type_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a specific metadata.
	 *
	 * @param int $metadata_id | The ID of the metadata to delete
	 */
	public static function deleteMetadata($metadata_id) {
		try {
			
			self::Settings()->deleteMetadata($metadata_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get metadata based on param conditions.  Returns array of metadata objects.
	 * The $record_id param is required but $user_id is optional and the $metadata_name param.
	 *
	 * @param int $record_id | The ID of the movie that the metadata belongs to
	 * @param int $user_id | The Owner ID of the metadata entries.
	 * @param string $metadata_name | The name of the metadata to filter by
	 * @param int $mediatype_id | MediaType ID of movieObj.  This forces deeper check.
	 * @return array
	 */
	public static function getMetadata($record_id, $user_id, $metadata_name, $mediatype_id = null) {
		try {
			
			return self::Settings()->getMetadata($record_id, $user_id, $metadata_name, $mediatype_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get metadataObj by ID
	 *
	 * @param int $metadata_id | The ID of the metadata to get
	 * @return metadataObj | The metadata object that is returned
	 */
	public static function getMetadataById($metadata_id) {
		try {
			
			return self::Settings()->getMetadataById($metadata_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all record ID's (movie ID's) that exist with association to a specific metadata name and User ID.
	 * Returns array of integers (Record ID's)
	 *
	 * @param int $user_id | The Owner ID of the metadata
	 * @param string $metadata_name | The metadata type name
	 * @return array
	 */
	public static function getRecordIDsByMetadata($user_id, $metadata_name) {
		try {
			
			return self::Settings()->getRecordIDsByMetadata($user_id, $metadata_name);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all known metadatatypes from database. If $user_id is provided, only metadatatypes created by that
	 * user_id will be returned. Function returns array of metadataTypeObjects.
	 *
	 * @param int $user_id | The user_id to filter metadatatypes to, null = no filter
	 * @return array
	 */
	public static function getMetadataTypes($user_id) {
		try {
			
			return self::Settings()->getMetadataTypes($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
}

/**
 * Provide the Web UI access to the Cover Services.  All errors that occur beneath this layer
 * are catched here in and displayed in the Web UI.  Since the errors are re-thrown in the 
 * Cover business class, the webservices can now handle its own exception logic and deal with 
 * Exceptions and throw them as soap_fault instead of getting prepared javascript messages 
 * that were originally intended for the Web UI.
 *
 */
class CoverServices extends VCDServices {
	
	
	/**
	 * Get all cover types in VCD-db, returns array of coverType objects
	 *
	 * @return array
	 */
	public static function getAllCoverTypes() {
		try {
			
			return self::CDcover()->getAllCoverTypes();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add a new coverType object
	 *
	 * @param cdcoverTypeObj $obj
	 */
	public static function addCoverType(cdcoverTypeObj $obj) {
		try {
			
			self::CDcover()->addCoverType($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a specific cover type object
	 *
	 * @param int $type_id | The ID of the cover type object to delete
	 */
	public static function deleteCoverType($type_id) {
		try {
			
			self::CDcover()->deleteCoverType($type_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all coverTypes used by this specific movie, returns array of coverType objects
	 *
	 * @param int $mediatype_id | The mediaType ID of the movie
	 * @return array
	 */
	public static function getAllCoverTypesForVcd($mediatype_id) {
		try {
			
			return self::CDcover()->getAllCoverTypesForVcd($mediatype_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get coverType object by ID
	 *
	 * @param int $covertype_id | The ID of the coverType object
	 * @return cdcoverTypeObj
	 */
	public static function getCoverTypeById($covertype_id) {
		try {
			
			return self::CDcover()->getCoverTypeById($covertype_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get coverType object by Name
	 *
	 * @param string $name | The name of the coverType object
	 * @return cdcoverTypeObj
	 */
	public static function getCoverTypeByName($name) {
		try {
			
			return self::CDcover()->getCoverTypeByName($name);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update specific coverType object
	 *
	 * @param cdcoverTypeObj $obj
	 */
	public static function updateCoverType(cdcoverTypeObj $obj) {
		try {
			
			self::CDcover()->updateCoverType($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * The a specific cover by ID
	 *
	 * @param int $cover_id | 
	 * @return cdcoverObj
	 */
	public static function getCoverById($cover_id) {
		try {
			
			return self::CDcover()->getCoverById($cover_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get all Covers stored in VCD-db.  Returns array of cdcoverObjects
	 *
	 * @return array | Array of cdcoverObjects
	 */
	public static function getAllCovers() {
		try {
			
			return self::CDcover()->getAllCovers();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get all cover objects that belong to a specified movie, returns array of cdcover objects
	 *
	 * @param int $vcd_id | The ID of the movie that owns the covers
	 * @return array
	 */
	public static function getAllCoversForVcd($vcd_id) {
		try {
			
			return self::CDcover()->getAllCoversForVcd($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add a new cover to VCD-db
	 *
	 * @param cdcoverObj $obj
	 */
	public static function addCover(cdcoverObj $obj) {
		try {
			
			self::CDcover()->addCover($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a specific cover from VCD-db
	 *
	 * @param int $cover_id
	 */
	public static function deleteCover($cover_id) {
		try {
			
			self::CDcover()->deleteCover($cover_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update cover object
	 *
	 * @param cdcoverObj $obj
	 */
	public static function updateCover(cdcoverObj $obj) {
		try {
			
			self::CDcover()->updateCover($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all cdcoverType objects associated with incoming mediaType objects.
	 * Parameter should contain array of mediaType objects, 
	 * returns an array of cdcoverType objects if none are found
	 *
	 * @param array $mediaTypeObjArr | Array of mediaType objects
	 * @return array
	 */
	public static function getAllowedCoversForVcd($mediaTypeObjArr) {
		try {
			
			return self::CDcover()->getAllowedCoversForVcd($mediaTypeObjArr);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Assign a new coverType object to a specified mediaType.
	 *
	 * @param int $mediaTypeID | The media type ID to use for assignment
	 * @param array $coverTypeIDArr | Array of integers representing coverType ID's
	 */
	public static function addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr) {
		try {
			
			self::CDcover()->addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get All coverType associated with the specified mediaType ID, returns array of cdcoverType objects.
	 *
	 * @param int $mediaType_id | The ID of the mediaType object
	 * @return array
	 */
	public static function getCDcoverTypesOnMediaType($mediaType_id) {
		try {
			
			return self::CDcover()->getCDcoverTypesOnMediaType($mediaType_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all thumbnails that the specified user has created in VCD-db, returns array of cdcover objects.
	 * And the cdcover objects are all of type 'Thumbnail'
	 *
	 * @param int $user_id | The Owner ID of the thumbnails
	 * @return array
	 */
	public static function getAllThumbnailsForXMLExport($user_id) {
		try {
			
			return self::CDcover()->getAllThumbnailsForXMLExport($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Move all CD-covers in VCD-db from database to harddrive
	 *
	 * @return int | The number of affected covers
	 */
	public static function moveCoversToDisk() {
		try {
			
			return self::CDcover()->moveCovers(true);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Move all CD-covers in VCD-db from harddrive to database
	 *
	 * @return int | The number of affected covers
	 */
	public static function moveCoversToDatabase() {
		try {
			
			return self::CDcover()->moveCovers(false);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
}

/**
 * Provide the Web UI access to the Pornstar Services.  All errors that occur beneath this layer
 * are catched here in and displayed in the Web UI.  Since the errors are re-thrown in the 
 * Pornstar business class, the webservices can now handle its own exception logic and deal with 
 * Exceptions and throw them as soap_fault instead of getting prepared javascript messages 
 * that were originally intended for the Web UI.
 *
 */
class PornstarServices extends VCDServices {
	
	
	/**
	 * Get all pornstars in VCD-db, returns array of pornstar objects
	 *
	 * @return array
	 */
	public static function getAllPornstars() {
		try {
			
			return self::Pornstar()->getAllPornstars();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a pornstar object by ID
	 *
	 * @param int $pornstar_id | The ID of the pornstar object
	 * @return pornstarObj
	 */
	public static function getPornstarByID($pornstar_id) {
		try {
			
			return self::Pornstar()->getPornstarByID($pornstar_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
		
	/**
	 * Get a pornstar object by the pornstar Name
	 *
	 * @param string $name | The Name of the pornstar
	 * @return pornstarObj
	 */
	public static function getPornstarByName($name) {
		try {
			
			return self::Pornstar()->getPornstarByName($name);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all pornstars that have been added to the cast list of a specific movie,
	 * returns array of pornstar objects.
	 *
	 * @param int $movie_id | The ID of the movie
	 * @return array
	 */
	public static function getPornstarsByMovieID($movie_id) {
		try {
			
			return self::Pornstar()->getPornstarsByMovieID($movie_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add a new pornstar to VCD-db.  Returns the same object with the ID populated.
	 *
	 * @param pornstarObj $pornstarObj
	 * @return pornstarObj
	 */
	public static function addPornstar(pornstarObj $obj) {
		try {
			
			return self::Pornstar()->addPornstar($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add pornstar to the cast list of a specific movie.
	 *
	 * @param int $pornstar_id | The ID of the pornstar
	 * @param int $movie_id | The ID of the movie
	 */
	public static function addPornstarToMovie($pornstar_id, $movie_id) {
		try {
			
			self::Pornstar()->addPornstarToMovie($pornstar_id, $movie_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete pornstar from the cast list of a movie.
	 *
	 * @param int $pornstar_id | The ID of the pornstar
	 * @param int $movie_id | The ID of the movie
	 */
	public static function deletePornstarFromMovie($pornstar_id, $movie_id) {
		try {
			
			self::Pornstar()->deletePornstarFromMovie($pornstar_id, $movie_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete pornstar from VCD-db, return true if operation secceds otherwise false.
	 *
	 * @param int $pornstar_id | The Id of the pornstar
	 * @return bool
	 */
	public static function deletePornstar($pornstar_id) {
		try {
			
			return self::Pornstar()->deletePornstar($pornstar_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update the specified pornstar object.
	 *
	 * @param pornstarObj $pornstar
	 */
	public static function updatePornstar(pornstarObj $obj) {
		try {
			
			self::Pornstar()->updatePornstar($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the alphabet entries of the first letter of pornstar names.
	 * Returns array of chars.
	 *
	 * @param bool $active_only | Filter by active pornstars of get all the list
	 * @return array
	 */
	public static function getPornstarsAlphabet($active_only) {
		try {
			
			return self::Pornstar()->getPornstarsAlphabet($active_only);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get pornstars that start with the specified letter in the alphabet.
	 * Returns array of pornstar objects.
	 *
	 * @param char $letter | A single letter to filter by
	 * @param bool $active_only | Filter by active pornstars of get all the list
	 * @return array
	 */
	public static function getPornstarsByLetter($letter, $active_only) {
		try {
			
			return self::Pornstar()->getPornstarsByLetter($letter, $active_only);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get all adult studios.  Returns array of studio objects.
	 *
	 * @return array
	 */
	public static function getAllStudios() {
		try {
			
			return self::Pornstar()->getAllStudios();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a specific studio object by ID.
	 *
	 * @param int $studio_id | The ID of the studio object
	 * @return studioObj
	 */
	public static function getStudioByID($studio_id) {
		try {
			
			return self::Pornstar()->getStudioByID($studio_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a specific studio object by studio name.
	 *
	 * @param string $studio_name | The name of the studio
	 * @return studioObj
	 */
	public static function getStudioByName($studio_name) {
		try {
			
			return self::Pornstar()->getStudioByName($studio_name);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the studio object that has been assigned to the specified movie.
	 *
	 * @param int $vcd_id | The ID of the movie to lookup.
	 * @return studioObj
	 */
	public static function getStudioByMovieID($vcd_id) {
		try {
			
			return self::Pornstar()->getStudioByMovieID($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a list of all the studios that are at least assigned to 1 movie.
	 * Returns array of studio objects.
	 *
	 * @return array
	 */
	public static function getStudiosInUse() {
		try {
			
			return self::Pornstar()->getStudiosInUse();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Assign movie to studio object.
	 *
	 * @param int $studio_id | The ID of the studio object
	 * @param int $vcd_id | The ID of the movie object.
	 */
	public static function addMovieToStudio($studio_id, $vcd_id) {
		try {
			
			self::Pornstar()->addMovieToStudio($studio_id, $vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Remove studio assignment from a movie.
	 *
	 * @param int $vcd_id | The ID of the movie to release the studio assignment
	 */
	public static function deleteMovieFromStudio($vcd_id) {
		try {
			
			self::Pornstar()->deleteMovieFromStudio($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new studio object to VCD-db
	 *
	 * @param studioObj $obj
	 */
	public static function addStudio(studioObj $obj) {
		try {
			
			self::Pornstar()->addStudio($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a studio object from VCD-db
	 *
	 * @param int $studio_id | The ID of the studio object to delete
	 */
	public static function deleteStudio($studio_id) {
		try {
			
			self::Pornstar()->deleteStudio($studio_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all pornacategories, returns array of porncategory objects.
	 *
	 * @return array
	 */
	public static function getSubCategories() {
		try {
			
			return self::Pornstar()->getSubCategories();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get a specific porncategory object by ID.
	 *
	 * @param int $category_id | The ID of the porncategory object
	 * @return porncategoryObj
	 */
	public static function getSubCategoryByID($category_id) {
		try {
			
			return self::Pornstar()->getSubCategoryByID($category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all porncategories that have been assigned to a specific movie.
	 * Returns array of porncategory objects.
	 *
	 * @param int $vcd_id | The ID of the movie to get the categories for
	 * @return array
	 */
	public static function getSubCategoriesByMovieID($vcd_id) {
		try {
			
			return self::Pornstar()->getSubCategoriesByMovieID($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all porncategory objects that are at least used once by any movie.
	 * Returns array of porncategory objects.
	 *
	 * @return array
	 */
	public static function getSubCategoriesInUse() {
		try {
			
			return self::Pornstar()->getSubCategoriesInUse();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Searches in the names for the current adult categories, if a name is found in the incoming list
	 * the porncategory match is pushed to the result array.  The return array contains porncategory objects.
	 * This function is mainly used by adult fetch-site scripts to verify the categories assign on the feched movie.
	 *
	 * @param array $arrCategoryNames | Array of category name to check
	 * @return array
	 */
	public static function getValidCategories($arrCategoryNames) {
		try {
			
			return self::Pornstar()->getValidCategories($arrCategoryNames);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Assign movie to a specific adult category.
	 *
	 * @param int $vcd_id | The Movie ID
	 * @param int $category_id | The ID of the adult category object
	 */
	public static function addCategoryToMovie($vcd_id, $category_id) {
		try {
			
			self::Pornstar()->addCategoryToMovie($vcd_id, $category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Remove all adult categories from the specific movie object.
	 *
	 * @param int $vcd_id | The ID of the movie to release all adult category assignments.
	 */
	public static function deleteMovieFromCategories($vcd_id) {
		try {
			
			self::Pornstar()->deleteMovieFromCategories($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add new porncategory object to VCD-db
	 *
	 * @param porncategoryObj $obj
	 */
	public static function addAdultCategory(porncategoryObj $obj) {
		try {
			
			self::Pornstar()->addAdultCategory($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Delete a specific adult category object from VCD-db.
	 *
	 * @param int $category_id | The ID of the category object to delete.
	 */
	public static function deleteAdultCategory($category_id) {
		try {
			
			self::Pornstar()->deleteAdultCategory($category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
}

/**
 * Provide the Web UI access to the Movie Services.  All errors that occur beneath this layer
 * are catched here in and displayed in the Web UI.  Since the errors are re-thrown in the 
 * Movie business class, the webservices can now handle its own exception logic and deal with 
 * Exceptions and throw them as soap_fault instead of getting prepared javascript messages 
 * that were originally intended for the Web UI.
 *
 */
class MovieServices extends VCDServices {
	
		
	/**
	 * Get movie by ID
	 *
	 * @param int $movie_id
	 * @return vcdObj
	 */
	public static function getVcdByID($movie_id) {
		try {
			
			return self::Movie()->getVcdByID($movie_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Create a new movie object, returns the ID of the newly created movie object.
	 *
	 * @param vcdObj $obj
	 * @return int
	 */
	public static function addVcd(vcdObj $obj) {
		try {
			
			return self::Movie()->addVcd($obj);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update movie
	 *
	 * @param vcdObj $obj
	 */
	public static function updateVcd(vcdObj $obj) {
		try {
			
			self::Movie()->updateVcd($obj);
			
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Update instance of a movie object, updated the media type and the cd count.
	 *
	 * @param int $vcd_id | The ID of the vcd object
	 * @param int $new_mediaid | The old media type object ID
	 * @param int $old_mediaid | The new media type object ID
	 * @param int $new_numcds | The new number of cd count
	 * @param int $oldnumcds | The old number of cd count
	 */
	public static function updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds) {
		try {
			
			self::Movie()->updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Remove user from the owner list of a specific movie.
	 * If $mode is set to 'full' all records about that movie is deleted.
	 * Should not be called unless the specified user_id is the only owner of the movie.  
	 * If $mode is set to 'single', the record linking to the user is the only thing that will be deleted.  
	 * Returns true on success otherwise false.
	 *
	 * @param int $vcd_id | The vcd object ID
	 * @param int $media_id | The media type ID to remove
	 * @param string $mode | can be 'full' or 'single'
	 * @param int $user_id | The Owner ID of the vcd object
	 * @return bool
	 */
	public static function deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id = -1) {
		try {
			
			return self::Movie()->deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that belong to a certain category ID.  The $start and $end are used as pager variables.
	 * The $user_id param is optional. Returns array of vcd objects.
	 *
	 * @param int $category_id | The category ID to filter by
	 * @param int $start | The start row of the recordset to get
	 * @param int $end | The end row of the the recordset to get
	 * @param int $user_id | Filter by specific owner ID
	 * @return array
	 */
	public static function getVcdByCategory($category_id, $start=0, $end=0, $user_id = -1) {
		try {
			
			return self::Movie()->getVcdByCategory($category_id, $start, $end, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that belong to a certain category ID.  The $start and $end are used as pager variables.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id | The category ID to filter by
	 * @param int $start | The start row of the recordset to get
	 * @param int $end | The end row of the the recordset to get
	 * @param int $user_id | Filter by specific owner ID
	 * @return array
	 */
	public static function getVcdByCategoryFiltered($category_id, $start=0, $end=0, $user_id) {
		try {
			
			return self::Movie()->getVcdByCategoryFiltered($category_id, $start, $end, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that belong to a specific owner ID.  Param $simple tells if the vcd object shall be fully populated.
	 * Returns array of vcd objects.
	 *
	 * @param int $user_id | The Owner ID to get movies by
	 * @param bool $simple | Get partially populated vcd objects or fully populated
	 * @return array
	 */
	public static function getAllVcdByUserId($user_id, $simple = true) {
		try {
			
			return self::Movie()->getAllVcdByUserId($user_id, $simple);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get list of users vcd objects, ordered by creation date DESC.
	 * Returns array of vcd objects.
	 *
	 * @param int $user_id | The Owner ID of the vcd objects
	 * @param int $count | Number of results to get
	 * @param bool $simple | Get partially populated vcd objects or fully populated
	 * @return array
	 */
	public static function getLatestVcdsByUserID($user_id, $count, $simple = true) {
		try {
			
			return self::Movie()->getLatestVcdsByUserID($user_id, $count, $simple);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all vcd objects for lists creations.  Returns array of vcd objects.
	 *
	 * @param int $excluded_userid | The ID of the owner to exclude
	 * @return array
	 */
	public static function getAllVcdForList($excluded_userid) {
		try {
			
			return self::Movie()->getAllVcdForList($excluded_userid);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get specific movies by ID's.  Returns array of vcd objects.
	 *
	 * @param array $arrIDs | array of integers representing movie ID's
	 * @return array
	 */
	public static function getVcdForListByIds($arrIDs) {
		try {
			
			return self::Movie()->getVcdForListByIds($arrIDs);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Add a new instance of a vcd object to user.
	 *
	 * @param int $user_id | The User ID to link the movie to
	 * @param int $vcd_id | The vcd object ID to link to user
	 * @param int $mediatype | The ID of the mediaType object
	 * @param int $cds | The number of CD's this copy uses
	 */
	public static function addVcdToUser($user_id, $vcd_id, $mediatype, $cds) {
		try {
			
			self::Movie()->addVcdToUser($user_id, $vcd_id, $mediatype, $cds);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get count of all vcd objects that belong to a specified category.
	 * Returns the number of vcd objects that match the given criteria.
	 *
	 * @param int $category_id | The ID of the mediatype to filter by
	 * @param int $user_id | The Owner ID
	 * @return int
	 */
	public static function getCategoryCount($category_id, $user_id = -1) {
		try {
			
			return self::Movie()->getCategoryCount($category_id, false, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the number of movies for selected category after user filter has been applied.
	 *
	 * @param int $category_id | The ID of the mediatype to filter by
	 * @param int $user_id | The Owner ID
	 * @return int
	 */
	public static function getCategoryCountFiltered($category_id, $user_id) {
		try {
			
			return self::Movie()->getCategoryCountFiltered($category_id, $user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the Top Ten list of latest movies.
	 * $category_id can be used to filter results to specified category.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id | The ID of the mediatype to filter by
	 * @param array $arrFilter | array of category id's to exclude
	 * @return array
	 */
	public static function getTopTenList($category_id = 0, $arrFilter = null) {
		try {
			
			return self::Movie()->getTopTenList($category_id, $arrFilter);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Search the database. Returns array of vcd Objects.
	 * Param $method defines the search type. Search type can be 'title', 'actor' or 'director'
	 *
	 * @param string $keyword | The keyword to search by
	 * @param string $method | The search method to use can be one of the following [title, actor, director]
	 * @return array
	 */
	public static function search($keyword, $method) {
		try {
			
			return self::Movie()->search($keyword, $method);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Perform advanced search, where filters can be applied.  All params except $title are optional.
	 * Returns array of vcd objects.
	 *
	 * @param string $title | The search keyword
	 * @param int $category | The ID of the movieCategory object to filter by
	 * @param int $year | The Year of production to filter by
	 * @param int $mediatype | The ID of the mediaType object to filter by
	 * @param int $owner | The Owner ID to filter by
	 * @param float $imdbgrade | The minimum IMDB grade to use a filter
	 * @return array
	 */
	public static function advancedSearch($title = null, $category = null, $year = null, $mediatype = null,
								   $owner = null, $imdbgrade = null) {
		try {
			
			return self::Movie()->advancedSearch($title, $category, $year, $mediatype, $owner, $imdbgrade);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Perform a cross join of movies by the logged-in user and some other Owner ID.
	 * Param $method represents the join action, returns array of vcd objects.
	 * 1 = Movies I own but user not
	 * 2 = Movies user owns but i dont
	 * 3 = Movies we both own
	 *
	 * @param int $user_id | The Other Owner ID to perform cross join with
	 * @param int $media_id | The mediatype ID to limit results by
	 * @param int $category_id | The category type ID to limit results
	 * @param int $method | The Join method to use, 1,2 or 3
	 * @return array
	 */
	public static function crossJoin($user_id, $media_id, $category_id, $method) {
		try {
			
			return self::Movie()->crossJoin($user_id, $media_id, $category_id, $method);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
 	 * Get all vcd objects by User ID for printview.
	 * $list_type can be 'all', 'movies', 'tv', text or 'blue'
	 * Returns array of vcd objects.
	 *
	 * @param int $user_id | The Owner ID of the movie objects
	 * @param string $list_type | The list type [all,movies,tv,text,blue]
	 * @return array
	 */
	public static function getPrintViewList($user_id, $list_type) {
		try {
			
			return self::Movie()->getPrintViewList($user_id, $list_type);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Find a random vcd object, params $category_id and $use_seenlist are optional.
	 *
	 * @param int $category_id | The movie category ID to limit results by
	 * @param bool $use_seenlist | Filter search result only to unseen movies.
	 * @return vcdObj
	 */
	public static function getRandomMovie($category_id, $use_seenlist = false) {
		try {
			
			return self::Movie()->getRandomMovie($category_id, $use_seenlist);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get similiar movies as an array of vcd objects.
	 * Movies in same category as the one specified in the $vcd_id param will be returned.
	 *
	 * @param int $vcd_id | The ID of the vcd object to search by
	 * @return array
	 */
	public static function getSimilarMovies($vcd_id) {
		try {
			
			return self::Movie()->getSimilarMovies($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get the number of movies that user owns
	 *
	 * @param itn $user_id | The Owner ID to seek by
	 * @return int
	 */
	public static function getMovieCount($user_id) {
		try {
			
			return self::Movie()->getMovieCount($user_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get vcd objects that are marked adult, and that are in a specific adult category.
	 * Returns array of vcd objects.
	 *
	 * @param int $category_id | The ID of the porncategory object to filter by
	 * @return array
	 */
	public static function getVcdByAdultCategory($category_id) {
		try {
			
			return self::Movie()->getVcdByAdultCategory($category_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Get all vcd objects that are linked to a specific adult studio.
	 * Returns array of vcd objects.
	 *
	 * @param int $studio_id | The ID of the studio object to filter by
	 * @return array
	 */
	public static function getVcdByAdultStudio($studio_id) {
		try {
			
			return self::Movie()->getVcdByAdultStudio($studio_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Set the screenshot flag on a specified movie object.
	 *
	 * @param int $vcd_id | The ID of the vcd object
	 */
	public static function markVcdWithScreenshots($vcd_id) {
		try {
			
			self::Movie()->markVcdWithScreenshots($vcd_id);;
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	/**
	 * Check if the movie has some screenshots.  Returns true if screenshots exist, otherwise false.
	 *
	 * @param int $vcd_id | The ID of the movie object
	 * @return bool
	 */
	public static function getScreenshots($vcd_id) {
		try {
			
			return self::Movie()->getScreenshots($vcd_id);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Get list of duplicate movies in the database
	 *
	 * @return array | Returns array of duplicate movie data
	 */
	public static function getDuplicationList() {
		try {
			
			return self::Movie()->getDuplicationList();
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
	
	/**
	 * Merge movies to fix duplicate conflicts
	 *
	 * @param int $masterID | The movie ID that conflicts will be merged to
	 * @param array $arrToBeMerged | Array of movie id's to merge to the $masterID ID
	 * @return bool | Returns true on success otherwise false
	 */
	public static function mergeMovies($masterID, $arrToBeMerged) {
		try {
			
			return self::Movie()->mergeMovies($masterID, $arrToBeMerged);
			
		} catch (Exception $ex) {
			parent::handleError($ex);
		}
	}
	
}


/**
 * Provide access to the business classes to its inheritors .
 *
 */
abstract class VCDServices {
	
	private static $forwardError = false;
	protected static $isWebserviceCall = false;
			
	/**
	 * Disable the Web UI error handling, useful for Ajax and SOAP calls
	 *
	 */
	public static function disableErrorHandler() {
		self::$forwardError = true;
	}
	
	/**
	 * Re-enable the Web UI error handler after disableErrorHandler() has been used.
	 *
	 */
	public static function enableErrorHander() {
		self::$forwardError = false;
	}
	
	/**
	 * Handle the error, rethrow it for Ajax and SOAP calls or display it for the Web UI
	 *
	 * @param Exception $ex
	 */
	protected static function handleError(Exception $ex) {
		if (self::$forwardError) {
			throw $ex;
		} else {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Get an instance of the vcd_movie class
	 *
	 * @return vcd_movie
	 */
	protected static function Movie() {
		if (self::usingProxy()) {
			return VCDClassFactory::getInstance('SoapMovieProxy');
		} else {
			return VCDClassFactory::getInstance('vcd_movie');	
		}
		
	}

	/**
	 * Get an instance of the vcd_user class
	 *
	 * @return vcd_user
	 */
	protected static function User() {
		if (self::usingProxy()) {
			return VCDClassFactory::getInstance('SoapUserProxy');
		} else {
			return VCDClassFactory::getInstance('vcd_user');
		}
	}
	
	/**
	 * Get an instance of the vcd_pornstar class
	 *
	 * @return vcd_pornstar
	 */
	protected static function Pornstar() {
		
		if (self::usingProxy()) {
			return VCDClassFactory::getInstance('SoapPornstarProxy');
		} else {
			return VCDClassFactory::getInstance('vcd_pornstar');
		}
	}
	
	/**
	 * Get an instance of the CDcover class
	 *
	 * @return vcd_cdcover
	 */
	protected static function CDcover() {
		if (self::usingProxy()) {
			return VCDClassFactory::getInstance('SoapCoverProxy');
		} else {
			return VCDClassFactory::getInstance('vcd_cdcover');	
		}
	}
	
	/**
	 * Get an instance of the vcd_settings class
	 *
	 * @return vcd_settings
	 */
	protected static function Settings() {
		if (self::usingProxy()) {
			return VCDClassFactory::getInstance('SoapSettingsProxy');	
		} else {
			return VCDClassFactory::getInstance('vcd_settings');	
		}
	}
	
	protected static function usingProxy() {
		if (!defined('VCDDB_USEPROXY')) {
			return false;
		} else {
			return (bool)(VCDDB_USEPROXY == 1 && !self::$isWebserviceCall);	
		}
	}
	
}

?>