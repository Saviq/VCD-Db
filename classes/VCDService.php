<?php
/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
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

/**
 * Provide the Web UI access to the User Services.  All errors that occur beneath this layer
 * are catched here in and displayed in the Web UI.  Since the errors are re-thrown in the 
 * User business class, the webservices can now handle its own exception logic and deal with 
 * Exceptions and throw them as soap_fault instead of getting prepared javascript messages 
 * that were originally intended for the Web UI.
 *
 */
class UserServices extends VCDServices {
	
	// Deny instantiation of the class 
	private function __construct() {}
	
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			
			return self::getPropertyById($property_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
	
	// Deny instantiation of the class 
	private function __construct() {}
	
	/**
	 * Get all Settings objects in VCD-db.  Returns array of Settings objects.
	 *
	 * @return array
	 */
	public static function getAllSettings() {
		try {
			
			return self::Settings()->getAllSettings();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Get specific settings object by key.
	 *
	 * @param string $key | The settings key that identifies the object
	 * @return settingsObj
	 */
	public static function getSettingsByKey($key) {
		try {
			
			return self::Settings()->getSettingsByKey($key);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
		}
	}
	
	/**
	 * Update a specific mediaType object in VCD-db.
	 *
	 * @param medaTypeObj $mediaTypeObj
	 */
	public static function updateMediaType(medaTypeObj $mediaTypeObj) {
		try {
			
			self::Settings()->updateMediaType($mediaTypeObj);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			VCDException::display($ex);
		}
	}
	
	/**
	 * Get collection of mediatype objects that user uses in all his movies.
	 * Return array of mediaType objects.
	 *
	 * @param int $user_id | The User ID to seek mediaType objects by.
	 * @return array
	 */
	public static function getMediaTypesInUseByUserID($user_id) {
		try {
			
			return self::Settings()->getMediaTypesInUseByUserID($user_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/**
	 * Get the movie count of all movies belonging to a specific category.
	 *
	 * @param int $user_id | The User ID of the user to get results from
	 * @param int $category_id | The ID of the category to filter by
	 * @return int
	 */
	public static function getMediaCountByCategoryAndUserID($user_id, $category_id) {
		try {
			
			return self::Settings()->getMediaCountByCategoryAndUserID($user_id, $category_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
		}
	}
	
	/**
	 * Get the ID of a specific movieCategory object by using its name as identifier.
	 *
	 * @param string $name | The name of the moviecategory object to seek by
	 * @return int | Returns the ID of the movieCategory object
	 */
	public static function getCategoryIDByName($name) {
		try {
			
			return self::Settings()->getCategoryIDByName($name);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
		}
	}
	
	/**
	 * Get metadata based on param conditions.  Returns array of metadata objects.
	 * The $record_id param is required but $user_id is optional and the $metadata_name param.
	 *
	 * @param int $record_id | The ID of the movie that the metadata belongs to
	 * @param int $user_id | The Owner ID of the metadata entries.
	 * @param string $metadata_name | The name of the metadata to filter by
	 * @return array
	 */
	public static function getMetadata($record_id, $user_id, $metadata_name) {
		try {
			
			return self::Settings()->getMetadata($record_id, $user_id, $metadata_name);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			VCDException::display($ex);
		}
	}
	
	
}

class CoverServices extends VCDServices {
	
	// Deny instantiation of the class 
	private function __construct() {}
	
	public static function getAllCoverTypes() {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addCoverType($cdcoverTypeObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteCoverType($type_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllCoverTypesForVcd($mediatype_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getCoverTypeById($covertype_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getCoverTypeByName($covertype_name) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function updateCoverType($cdcoverTypeObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getCoverById($cover_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllCoversForVcd($vcd_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addCover($cdcoverObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteCover($cover_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function updateCover($cdcoverObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllowedCoversForVcd($mediaTypeObjArr) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addCoverTypesToMedia($mediaTypeID, $coverTypeIDArr) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getCDcoverTypesOnMediaType($mediaType_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllThumbnailsForXMLExport($user_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
}

class PornstarServices extends VCDServices {
	
	// Deny instantiation of the class 
	private function __construct() {}
	
	public static function getAllPornstars() {
		try {
			
			return self::Pornstar()->getAllPornstars();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getPornstarByID($pornstar_id) {
		try {
			
			return self::Pornstar()->getPornstarByID($pornstar_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
		
	public static function getPornstarByName($name) {
		try {
			
			return self::Pornstar()->getPornstarByName($name);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getPornstarsByMovieID($movie_id) {
		try {
			
			return self::Pornstar()->getPornstarsByMovieID($movie_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
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
			VCDException::display($ex);
		}
	}
	
	
	/**
	 * Get all adult studios.  Returns array of studio objects.
	 *
	 */
	public static function getAllStudios() {
		try {
			
			return self::Pornstar()->getAllStudios();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getStudioByID($studio_id) {
		try {
			
			return self::Pornstar()->getStudioByID($studio_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getStudioByName($studio_name) {
		try {
			
			return self::Pornstar()->getStudioByName($studio_name);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getStudioByMovieID($vcd_id) {
		try {
			
			return self::Pornstar()->getStudioByMovieID($vcd_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getStudiosInUse() {
		try {
			
			return self::Pornstar()->getStudiosInUse();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addMovieToStudio($studio_id, $vcd_id) {
		try {
			
			self::Pornstar()->addMovieToStudio($studio_id, $vcd_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteMovieFromStudio($vcd_id) {
		try {
			
			self::Pornstar()->deleteMovieFromStudio($vcd_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addStudio(studioObj $obj) {
		try {
			
			self::Pornstar()->addStudio($obj);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteStudio($studio_id) {
		try {
			
			self::Pornstar()->deleteStudio($studio_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/* Subcategories */
	public static function getSubCategories() {
		try {
			
			return self::Pornstar()->getSubCategories();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getSubCategoryByID($category_id) {
		try {
			
			return self::Pornstar()->getSubCategoryByID($category_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getSubCategoriesByMovieID($vcd_id) {
		try {
			
			return self::Pornstar()->getSubCategoriesByMovieID($vcd_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getSubCategoriesInUse() {
		try {
			
			return self::Pornstar()->getSubCategoriesInUse();
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getValidCategories($arrCategoryNames) {
		try {
			
			return self::Pornstar()->getValidCategories($arrCategoryNames);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addCategoryToMovie($vcd_id, $category_id) {
		try {
			
			self::Pornstar()->addCategoryToMovie($vcd_id, $category_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteMovieFromCategories($vcd_id) {
		try {
			
			self::Pornstar()->deleteMovieFromCategories($vcd_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addAdultCategory(porncategoryObj $obj) {
		try {
			
			self::Pornstar()->addAdultCategory($obj);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteAdultCategory($category_id) {
		try {
			
			self::Pornstar()->deleteAdultCategory($category_id);
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
}

class MovieServices extends VCDServices {
	
	// Deny instantiation of the class 
	private function __construct() {}
		
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
			VCDException::display($ex);
		}
	}
	
	
	public static function addVcd(vcdObj $vcdObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function updateVcd(vcdObj $vcdObj) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function updateVcdInstance($vcd_id, $new_mediaid, $old_mediaid, $new_numcds, $oldnumcds) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function deleteVcdFromUser($vcd_id, $media_id, $mode, $user_id = -1) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getVcdByCategory($category_id, $start=0, $end=0, $user_id = -1) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getVcdByCategoryFiltered($category_id, $start=0, $end=0, $user_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllVcdByUserId($user_id, $simple = true) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getLatestVcdsByUserID($user_id, $count, $simple = true) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getAllVcdForList($excluded_userid) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getVcdForListByIds($arrIDs) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function addVcdToUser($user_id, $vcd_id, $mediatype, $cds) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getCategoryCount($category_id, $isAdult = false, $user_id = -1) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getCategoryCountFiltered($category_id, $user_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getTopTenList($category_id = 0) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function search($keyword, $method) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function advancedSearch($title = null, $category = null, $year = null, $mediatype = null,
								   $owner = null, $imdbgrade = null) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function crossJoin($user_id, $media_id, $category_id, $method) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getPrintViewList($user_id, $list_type) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getRandomMovie($category, $use_seenlist = false) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getSimilarMovies($vcd_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getMovieCount($user_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	/* Adult VCD functions */
	public static function getVcdByAdultCategory($category_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getVcdByAdultStudio($studio_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function markVcdWithScreenshots($vcd_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
	public static function getScreenshots($vcd_id) {
		try {
			
		} catch (Exception $ex) {
			VCDException::display($ex);
		}
	}
	
}


/**
 * Provide access to the business classes to its inheritors .
 *
 */
abstract class VCDServices {
	
	/**
	 * Get an instantce of the vcd_movie class
	 *
	 * @return vcd_movie
	 */
	protected static function Movie() {
		return VCDClassFactory::getInstance('vcd_movie');
	}

	/**
	 * Get an instance of the vcd_user class
	 *
	 * @return vcd_user
	 */
	protected static function User() {
		return VCDClassFactory::getInstance('vcd_user');
	}
	
	/**
	 * Get an instance of the vcd_pornstar class
	 *
	 * @return vcd_pornstar
	 */
	protected static function Pornstar() {
		return VCDClassFactory::getInstance('vcd_pornstar');
	}
	
	/**
	 * Get an instance of the CDcover class
	 *
	 * @return vcd_cdcover
	 */
	protected static function CDcover() {
		return VCDClassFactory::getInstance('vcd_cdcover');
	}
	
	/**
	 * Get an instance of the vcd_settings class
	 *
	 * @return vcd_settings
	 */
	protected static function Settings() {
		return VCDClassFactory::getInstance('vcd_settings');
	}
}




?>