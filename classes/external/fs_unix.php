<?php
/*
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2004 Bharat Mediratta
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * $Id$
 */
?>
<?php
function fs_copy($source, $dest) {
	$umask = umask(0113);
	$results = copy($source, $dest);
	umask($umask);
}

function fs_exec($cmd, &$results, &$status, $debugfile="") {
	if (!empty($debugfile)) {
		$cmd = "($cmd) 2>$debugfile";
	} 
	return exec($cmd, $results, $status);
}

function fs_tempdir() {
	return export_filename(getenv("TEMP"));
}

function fs_file_exists($filename) {
	return @file_exists($filename);
}

function fs_is_link($filename) {
	/* if the link is broken it will spew a warning, so ignore it */
	return @is_link($filename);
}

function fs_filesize($filename) {
	return filesize($filename);
}

function fs_fopen($filename, $mode, $use_include_path=0) {
	return fopen($filename, $mode, $use_include_path);
}

function fs_is_dir($filename) {
	return @is_dir($filename);
}

function fs_is_file($filename) {
	return @is_file($filename);
}

function fs_is_readable($filename) {
	return @is_readable($filename);
}

function fs_opendir($path) {
	return opendir($path);
}

function fs_rename($oldname, $newname) {
	return rename($oldname, $newname);
}

function fs_stat($filename) {
	return stat($filename);
}

function fs_unlink($filename) {
	return unlink($filename);
}

function fs_is_executable($filename) {
	return is_executable($filename);
}

function fs_import_filename($filename, $for_exec=1) {
	if ($for_exec) {
		$filename = escapeshellarg ($filename); // Might as well use the function PHP provides!
	}
	
	return $filename;
}

function fs_export_filename($filename) {
	return $filename;
}

function fs_executable($filename) {
	return $filename;
}

function fs_mkdir($filename, $perms) {
	$umask = umask(0);

	/*
	 * PHP 4.2.0 on Unix (specifically FreeBSD) has a bug where mkdir
	 * causes a seg fault if you specify modes.
	 *
	 * See: http://bugs.php.net/bug.php?id=16905
	 *
	 * We can't reliably determine the OS, so let's just turn off the
	 * permissions for any Unix implementation.
	 */
	if (!strcmp(phpversion(), "4.2.0")) {
	    $results = mkdir(fs_import_filename($filename, 0));
	} else {
	    $results = mkdir(fs_import_filename($filename, 0), $perms);
	}
	
	umask($umask);
	return $results;
}
?>
