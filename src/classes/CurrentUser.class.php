<?php
/**
 * This file implements the class CurrentUser.
 * 
 * PHP versions 4 and 5
 *
 * LICENSE:
 * 
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow-v2
 */

/**
 * CurrentUser
 *
 * Stores the information of the currently logged user.
 * Implements login and logout function.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow-v2
 */

class CurrentUser
{
	///	Current user account
	public static $account;
	
	/// Bool : true if current user is an admin
	public static $admin;
	
	/// Current path requested by the user
	public static $path;
	
	/// Current type of stuff requested by user (Page / Zip / Image)
	public static $action = "Page";
	
	
	/**
	 * Retrieves info for the current user account
	 *
	 * @author Thibaud Rohmer
	 */
	public function init(){
		
		/// Set action (needed for page layout)
		if(isset($_GET['t'])){
			
			$possible_actions = array('Page','Img','Thb','Zip');
			
			CurrentUser::$action=$_GET['t'];
			if(!in_array(CurrentUser::$action,$possible_actions)){
				CurrentUser::$action = "Page";
			}
			
		}
		 
		/// Set path
		if(isset($_GET['f']))
			CurrentUser::$path = File::r2a($_GET['f']);
		else
			CurrentUser::$path=Settings::$photos_dir;
		
		
		
		if(!isset(CurrentUser::$account)){
			if(!isset($_SESSION['login']))
				throw new Exception('No user is logged');
			else
				CurrentUser::$account	=	new Account($_SESSION['login']);
		}
	}
	
	/**
	 * Log the user in
	 *
	 * @param string $login User login
	 * @param string $password User password
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function login($login,$password){
		
		CurrentUser::$admin	=	false;
		
		$acc =	new Account($login);
		
		// Check password
		if(sha1($password) == $acc->password){
			$_SESSION['login']		=	$login;
			CurrentUser::$account	=	$acc;
		}else{
			// Wrong password
			throw Exception("Wrong password.");			
		}
		if(in_array('root',$account))
			CurrentUser::$admin = true;
	}
	
	/**
	 * Log the user out
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function logout(){
		unset($_SESSION);
	}
	

	
	
}
?>