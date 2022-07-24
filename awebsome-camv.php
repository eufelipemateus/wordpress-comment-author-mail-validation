<?php
/**
 * Awebsome! Comment Author Mail Validation
 * Main file, frontend related
 * 
 * @package awebsome_camv
 * @author Capt. WordPress - Awebsome! <cpt.wp@awebsome.com>
 */
/*
	Plugin Name: Awebsome! Comment Author Mail Validation
	Plugin URI: http://plugins.awebsome.com
	Description: Adds a new option to the "Before a comment appears" fieldset Discussion subsection, that will add a mail validation to every comment before publishing.
	Version: 2.1
	Author: Capt. WordPress - Awebsome! <cpt.wp@awebsome.com>
	Author URI: http://awebsome.com/services/wordpress
	License: GPLv2
*/
/**
 * Require the admin UI file
 */
require_once('aws-camv-adm.php');

/**
 * Overrides the comment_post action to add some custom functionality
 * 
 * @param int $comment_ID
 * @uses get_comment To retrieve comment data given a comment ID or comment object
 * @uses aws_camv_generate_token To generate a unique token for the comment validation
 * @uses aws_camv_generate_validation_uri To generate the comment validation URI
 * @uses aws_camv_send_mail To send the validation email to comment author
 * @return int $comment_ID
 * 
 * @todo Add mail customization options (headers, content-type, attachments, html...)
 * @todo Integrate deeper with other validation methods
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_override_comment_post( $comment_ID )
{
	// Runs only if the comment author was not a registered user
	if ( !is_user_logged_in() )
	{
		// Get the comment data, generate a token and a validation URI
		$cmt 			= get_comment( $comment_ID );
		$nonce 			= wp_create_nonce( md5( $cmt->comment_author . $cmt->comment_author_email . $cmt->comment_date ) );
		//$token 			= aws_camv_generate_token( $cmt->comment_author, $cmt->comment_author_email, $cmt->comment_date );
		$validation_uri = aws_camv_generate_validation_uri( $cmt->comment_post_ID, $comment_ID, $nonce );
		
		// Set the mail info
		$subject   = get_bloginfo('name') .' | '. $cmt->comment_author .': '. __('Your comment requires validation', 'awebsome-camv');
		$mail_cont = __('Please, follow this link to validate and publish your comment: ', 'awebsome-camv') . $validation_uri;
		
		// Send the mail
		@wp_mail( $cmt->comment_author_email, $subject, $mail_cont );
	}
	
	return $comment_ID;
}

/**
 * Prevents comment auto approve
 * 
 * @param string $approved
 * @param array $commentdata
 * @return mixed Is the approval status (0|1|'spam')
 * 
 * @todo Integrate deeper with other validation methods
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_override_pre_comment_approved( $approved )
{
	// Leave SPAM and registered users alone
	return ( $approved === 'spam' || is_user_logged_in() ) ? $approved : 0;
}

/**
 * Returns the mail validation URL
 * 
 * @param int $comment_post_ID
 * @param int $comment_ID
 * @param string $token
 * @uses get_permalink
 * @uses strpos
 * @return string The one use form token
 * 
 * @todo Pretty permalinks URL integration (...permalink/nonce/cid)
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_generate_validation_uri( $comment_post_ID, $comment_ID, $nonce )
{
	// Go easy!
	$postlink 			= get_permalink( $comment_post_ID );
	$validation_params  = ( strpos($postlink, '?') === false ) ? '?' : '&'; // checks for '?' in $postlink
	$validation_params .= '_wpnonce='. $nonce .'&cid='. $comment_ID;
	$anchor 			= '#comment-'. $comment_ID;
	
	return $postlink . $validation_params . $anchor;
}

/**
 * Intercepts the validation variables in the URI and approves the comment
 * 
 * @uses get_comment To retrieve comment data given a comment ID or comment object
 * @uses aws_camv_generate_token To rebuild the token with the comment DB data
 * @uses wp_set_comment_status To change comment_status and update post_comments_number
 * @uses wp_die {@see http://codex.wordpress.org/Function_Reference/wp_die}
 * @uses wp_verify_nonce To verify one use time nonce
 * @return string $approved
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_intercept_validation()
{
	// if plugin option enabled
	if( get_option('aws_camv_onoff') !== '' )
	{
		// if we get the 2 vars while on frontend we've catched the validation
		if ( isset($_GET['_wpnonce']) && isset($_GET['cid']) )
		{
			// Go easy!
			$nonce 		= $_GET['_wpnonce'];
			$comment_ID = $_GET['cid'];
			
			// Get the comment data
			$cmt	 	= get_comment( $comment_ID );
			
			// Validate tokens
			if ( wp_verify_nonce( $nonce, md5( $cmt->comment_author. $cmt->comment_author_email. $cmt->comment_date ) ) )
			{
				// Check comment status to avoid spammers
				if ( wp_get_comment_status( $comment_ID ) !== 'spam' )
				{
					// Approve the comment and updates comment count
					if( wp_set_comment_status( $comment_ID, 'approve' ) )
					{
						wp_redirect( get_permalink( $cmt->comment_post_ID ) .'#comment-'. $comment_ID );
						exit;
					}
				}
				else wp_die('<h1>'. __('We\'re sorry, but your comment was flagged as SPAM. :(', 'awebsome-camv') .'</h1>');
			}
			else wp_die('<h1>'. __('There was an error validating your data...', 'awebsome-camv') .'</h1>');
		}
	}
}

/**
 * Removes the plugin options on plugin deactivation
 * 
 * @uses delete_option To delete the plugin created settings and options
 * 
 * @since awebsome_camv 1.0
 */
function aws_camv_uninstall ()
{
	delete_option('aws_camv_onoff');
}

// Add comment publishing related hooks
add_filter('pre_comment_approved', 'aws_camv_override_pre_comment_approved');
add_action('comment_post', 'aws_camv_override_comment_post');

// Add frontend mail link validation interception
add_action('init', 'aws_camv_intercept_validation');

// Add deactivation hooks
register_deactivation_hook( __FILE__, 'aws_camv_uninstall' );
?>