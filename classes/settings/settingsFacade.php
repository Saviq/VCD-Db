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
 * @subpackage Settings
 * @version $Id$
 */
 
?>
<? 
require_once(dirname(__FILE__).'/settings.php');
require_once(dirname(__FILE__).'/settingsSQL.php');

interface ISettings {
	
	/* Applications settings */
	public function getAllSettings();
	public function getSettingsByKey($key);
	public function getSettingsByID($settings_id);
	public function addSettings($settingsObj);
	public function updateSettings(settingsObj $obj);
	public function deleteSettings($settings_id);
	
	/* Source sites */
	public function getSourceSites();
	public function getSourceSiteByID($source_id);
	public function getSourceSiteByAlias($strAlias);
	public function addSourceSite(sourceSiteObj $obj);
	public function deleteSourceSite($source_id);
	public function updateSourceSite(sourceSiteObj $obj);
	
	/* Media types */
	public function getAllMediatypes();
	public function getMediaTypeByID($media_id);
	public function addMediaType(mediaTypeObj $obj);
	public function deleteMediaType($mediatype_id);
	public function updateMediaType(mediaTypeObj $obj);
	public function getMediaTypesOnCD($vcd_id);
	public function getMediaTypesInUseByUserID($user_id);
	public function getMediaCountByCategoryAndUserID($user_id, $category_id);
	public function getMediaTypeByName($media_name);
	
	/* Movie categories */
	public function getAllMovieCategories();
	public function getMovieCategoriesInUse();
	public function getMovieCategoryByID($category_id);
	public function addMovieCategory(movieCategoryObj $obj);
	public function deleteMovieCategory($category_id);
	public function updateMovieCategory(movieCategoryObj $obj);
	public function getCategoryIDByName($category_name);
	public function getCategoriesInUseByUserID($user_id);
	
	/* Borrowers */
	public function getBorrowerByID($borrower_id);
	public function getBorrowersByUserID($user_id);
	public function addBorrower(borrowerObj $obj);
	public function updateBorrower(borrowerObj $obj);
	public function deleteBorrower(borrowerObj $obj);
	
	/* Loan system */
	public function loanCDs($borrower_id, $arrMovieIDs);
	public function loanReturn($loan_id);
	public function getLoans($user_id, $show_returned);
	public function getLoansByBorrowerID($user_id, $borrower_id, $show_returned = false);
	
	/* Notification */
	public function notifyOfNewEntry(vcdObj $obj);
	
	/*  Rss Feeds */
	public function addRssfeed(rssObj $obj);
	public function getRssfeed($feed_id);
	public function getRssFeedsByUserId($user_id); 
	public function delFeed($feed_id);
	public function updateRssfeed(rssObj $obj);
	
	/* Wishlist */
	public function addToWishList($vcd_id, $user_id);
	public function getWishList($user_id);
	public function isOnWishList($vcd_id);
	public function removeFromWishList($vcd_id, $user_id);
	public function isPublicWishLists($user_id);
	
	/* Comments */
	public function addComment(commentObj $obj);
	public function deleteComment($comment_id);
	public function getCommentByID($comment_id);
	public function getAllCommentsByUserID($user_id);
	public function getAllCommentsByVCD($vcd_id);
	
	/* Statistics Obj */
	public function getStatsObj();
	public function getUserStatistics($user_id);
	
	/* Metadata objects */
	public function addMetadata($arrObj);
	public function updateMetadata(metadataObj $obj);
	public function deleteMetadata($metadata_id);
	public function getMetadata($record_id, $user_id, $metadata_name);
	public function getRecordIDsByMetadata($user_id, $metadata_name);
}
?>