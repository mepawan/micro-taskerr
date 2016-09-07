<?php
/**
 * User Profile Metaboxes
 *
 * @package Taskerr\Profile\Metaboxes
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Taskerr-adapted APP_User_Meta_Box class
 */
class TR_User_Meta_Box extends APP_User_Meta_Box {

    public function __construct( $id = '', $title = '', $args = array() ){
		parent::__construct( $id, $title, $args );
	}

	public function table( $rows, $formdata ) {

		if ( is_admin() ) {
			return parent::table( $rows, $formdata );
		}

		$output = '';
		foreach ( $rows as $row ) {
			$output .= $this->table_row( $row, $formdata );
		}
		return $output;
	}

	public function table_row( $row, $formdata ) {

		if ( is_admin() ) {
			return parent::table_row( $row, $formdata );
		}

		$name = $row['name'];
		// Wrap description in span tag
		if ( isset( $row['desc'] ) )
			$row['desc'] = $this->wrap_desc( $row['desc'] );

		if ( isset( $row['title'] ) )
			$row['title'] = html( 'span', array( 'class' => 'form-field-title' ), $row['title'] );

		if ( 'checkbox' === $row['type'] && isset( $row['value'] ) ) {
			foreach ( $row['value'] as $option => $value ) {
				$value = html( 'label', array( 'for' => $option ) );
			}
		} else {
			$row['title'] = html( 'label', array( 'for' => $name ), $row['title'] );
		}

		// Get input html
		$input = scbForms::input( $row, $formdata );
		// Remove unnecessary label wrapper
		if ( 'checkbox' !== $row['type'] ) {
			$input = str_replace( array( '<label>', '</label>' ), '', $input );
			$input = html( 'label', array( 'for' => $name ), $row['title'] . $input );
		} else {
			$input = $row['title'] . $input;
		}

		// Wrap into form row
		return html( 'div', array( 'class' => 'form-field' ), $input );
	}
}

/**
 * User notifications metabox
 */
class TR_User_Notifications_Meta_Box extends TR_User_Meta_Box {

    public function __construct(){
        parent::__construct( 'notifications', __( 'Email Notifications Settings', APP_TD ), array( 'templates' => 'edit-profile.php' ) );
    }

    public function form_fields() {
		$types = array();

		$email_types = array(
			'new_service_author'       => __( 'You have created a new service', APP_TD ),
			'service_approval'         => __( 'Your Service listing has been approved', APP_TD ),
			'service_expired'          => __( 'Your Service listing has expired', APP_TD ),
			'new_review'               => __( 'You have sent a new review', APP_TD ),
			'task_created_provider'    => __( 'A user has ordered your service', APP_TD ),
			'task_created_buyer'       => __( 'You have ordered a new service', APP_TD ),
			'task_paid_provider'       => __( 'You have marked a task as Paid', APP_TD ),
			'task_paid_buyer'          => __( 'Service provider has marked a task as Paid', APP_TD ),
			'task_unpaid_provider'     => __( 'You have marked a task as Unpaid', APP_TD ),
			'task_unpaid_buyer'        => __( 'Service provider has marked a task as Unpaid', APP_TD ),
			'task_complete_provider'   => __( 'You have marked a task as Completed', APP_TD ),
			'task_complete_buyer'      => __( 'Service provider has marked a task as Completed', APP_TD ),
			'task_uncomplete_provider' => __( 'You have marked a task as Uncompleted', APP_TD ),
			'task_uncomplete_buyer'    => __( 'Service provider has marked a task as Uncompleted', APP_TD ),
			'task_confirm_provider'    => __( 'User has marked a task as Confirmed', APP_TD ),
			'task_confirm_buyer'       => __( 'You have marked a task as Confirmed', APP_TD ),
			'task_unconfirm_provider'  => __( 'User has marked a task as Unconfirmed', APP_TD ),
			'task_unconfirm_buyer'     => __( 'You have marked a task as Unconfirmed', APP_TD ),
		);

        return array(
            array(
                'title' => __( 'Receive email notifications:', APP_TD ),
                'type'  => 'checkbox',
                'name'  => 'notifications',
				'value' => $email_types,
				'checked' => array_keys( $email_types ),
                'desc'  => __( 'You can enable or disable email notifications for the following actions.', APP_TD ),
            ),
        );
    }
}

/**
 * Registers user contact methods
 *
 * @param  array $user_contacts Pre-defined contact methods
 * @return array Extended contact methods
 */
function tr_user_contact_methods( $user_contacts ){
	// Add user contact methods
	$allowed_networks = _tr_allowed_networks_user();

	foreach ( $allowed_networks as $type ) {
		$user_contacts[ $type ] = APP_Social_Networks::get_title($type);
	}

	return $user_contacts;
}
add_filter('user_contactmethods', 'tr_user_contact_methods');