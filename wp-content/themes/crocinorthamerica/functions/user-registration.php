<?php

// Custom Registration Message
function reg_message($message) {
    if (strpos($message, 'Register') !== FALSE) {
        $newMessage = "Fill out the following form to register for this site.";
        return '<p class="message register">' . $newMessage . '</p>';
    }
    else {
        return $message;
    }
}
add_action('login_message', 'reg_message');

//Custom Confirmation Message
function registration_confirmation( $errors, $redirect_to ) {

	if( strpos($_SERVER['REQUEST_URI'],'checkemail=registered') !== false ) {

		$errors->remove( 'registered');

		$errors->add( 'registered', sprintf( __( 'Your registration request has been successfully sent. You will receive a confirmation email soon. Thank you!' ), wp_login_url( ) ), 'message' );
		
        return $errors;

	} else {
		return $errors;
	}
}
add_filter( 'wp_login_errors', 'registration_confirmation', 10, 2 );

//Additional Fields
function user_registration_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
    $company = ( ! empty( $_POST['company'] ) ) ? trim( $_POST['company'] ) : '';
    $phone = ( ! empty( $_POST['phone'] ) ) ? trim( $_POST['phone'] ) : '';
    $city = ( ! empty( $_POST['city'] ) ) ? trim( $_POST['city'] ) : '';
    $state = ( ! empty( $_POST['state'] ) ) ? trim( $_POST['state'] ) : '';
    ?>
    <p>
        <label for="first_name"><?php _e( 'First Name', 'crocinorthamerica' ) ?><br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
    </p>

    <p>
        <label for="last_name"><?php _e( 'Last Name', 'crocinorthamerica' ) ?><br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
    </p>

    <p>
        <label for="company"><?php _e( 'Company', 'crocinorthamerica' ) ?><br />
            <input type="text" name="company" id="company" class="input" value="<?php echo esc_attr( wp_unslash( $company ) ); ?>" size="25" /></label>
    </p>

    <p>
        <label for="phone"><?php _e( 'Phone', 'crocinorthamerica' ) ?><br />
            <input type="tel" name="phone" id="phone" class="input" value="<?php echo esc_attr( wp_unslash( $phone ) ); ?>" size="25" /></label>
    </p>

    <p>
        <label for="city"><?php _e( 'City', 'crocinorthamerica' ) ?><br />
            <input type="text" name="city" id="city" class="input" value="<?php echo esc_attr( wp_unslash( $company ) ); ?>" size="25" /></label>
    </p>

    <p>
        <label for="state"><?php _e( 'State', 'crocinorthamerica' ) ?><br />
            <input type="text" name="state" id="state" class="input" value="<?php echo esc_attr( wp_unslash( $company ) ); ?>" size="25" /></label>
    </p>

    <?php
}
add_action( 'register_form', 'user_registration_form' );

//Validation
function user_registration_form_validation( $errors, $sanitized_user_login, $user_email ) {

    if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
        $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must enter your first name.', 'crocinorthamerica' ) );
    }
    if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
        $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: You must enter your last name.', 'crocinorthamerica' ) );
    }
    if ( empty( $_POST['company'] ) || ! empty( $_POST['company'] ) && trim( $_POST['company'] ) == '' ) {
        $errors->add( 'company_error', __( '<strong>ERROR</strong>: You must enter your company name.', 'crocinorthamerica' ) );
    }
    if ( empty( $_POST['phone'] ) || ! empty( $_POST['phone'] ) && trim( $_POST['phone'] ) == '' ) {
        $errors->add( 'phone_error', __( '<strong>ERROR</strong>: You must enter your phone number.', 'crocinorthamerica' ) );
    }
    if ( empty( $_POST['city'] ) || ! empty( $_POST['city'] ) && trim( $_POST['city'] ) == '' ) {
        $errors->add( 'city_error', __( '<strong>ERROR</strong>: You must enter your city.', 'crocinorthamerica' ) );
    }
    if ( empty( $_POST['state'] ) || ! empty( $_POST['state'] ) && trim( $_POST['state'] ) == '' ) {
        $errors->add( 'state_error', __( '<strong>ERROR</strong>: You must enter your state.', 'crocinorthamerica' ) );
    }
    return $errors;
}
add_filter( 'registration_errors', 'user_registration_form_validation', 10, 3 );

//User Meta
function user_registration_meta( $user_id ) {
    if ( ! empty( $_POST['first_name'] ) ) {
        update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
        update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
        update_user_meta( $user_id, 'company', trim( $_POST['company'] ) );
        update_user_meta( $user_id, 'phone', trim( $_POST['phone'] ) );
        update_user_meta( $user_id, 'city', trim( $_POST['city'] ) );
        update_user_meta( $user_id, 'state', trim( $_POST['state'] ) );
    }
}
add_action( 'user_register', 'user_registration_meta' );

//Admin Dashboard
function admin_registration_form( $operation ) {
	if ( 'add-new-user' !== $operation ) {
		return;
	}

	$company = ( ! empty( $_POST['company'] ) ) ? trim( $_POST['company'] ) : '';
    $phone = ( ! empty( $_POST['phone'] ) ) ? trim( $_POST['phone'] ) : '';
    $city = ( ! empty( $_POST['city'] ) ) ? trim( $_POST['city'] ) : '';
    $state = ( ! empty( $_POST['state'] ) ) ? trim( $_POST['state'] ) : '';

	?>
	<h3><?php esc_html_e( 'Personal Information', 'crocinorthamerica' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="company"><?php esc_html_e( 'Company', 'crocinorthamerica' ); ?></label> <span class="description"><?php esc_html_e( '(required)', 'crocinorthamerica' ); ?></span></th>
			<td>
				<input type="text" id="company" name="company" value="<?php echo esc_attr( $company ); ?>" class="regular-text"/>
			</td>
            <th><label for="phone"><?php esc_html_e( 'Phone', 'crocinorthamerica' ); ?></label> <span class="description"><?php esc_html_e( '(required)', 'crocinorthamerica' ); ?></span></th>
			<td>
				<input type="tel" id="phone" name="phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text"/>
			</td>
            <th><label for="city"><?php esc_html_e( 'City', 'crocinorthamerica' ); ?></label> <span class="description"><?php esc_html_e( '(required)', 'crocinorthamerica' ); ?></span></th>
			<td>
				<input type="text" id="city" name="city" value="<?php echo esc_attr( $city ); ?>" class="regular-text"/>
			</td>
            <th><label for="state"><?php esc_html_e( 'State', 'crocinorthamerica' ); ?></label> <span class="description"><?php esc_html_e( '(required)', 'crocinorthamerica' ); ?></span></th>
			<td>
				<input type="text" id="state" name="state" value="<?php echo esc_attr( $state ); ?>" class="regular-text"/>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'user_new_form', 'admin_registration_form' );

//Admin Dashboard Validation
function user_profile_update_errors( $errors, $update, $user ) {
	if ( $update ) {
		return;
	}

	if ( empty( $_POST['company'] ) ) {
		$errors->add( 'company', __( '<strong>ERROR</strong>: Please enter company name.', 'crocinorthamerica' ) );
	}

    if ( empty( $_POST['phone'] ) ) {
		$errors->add( 'phone', __( '<strong>ERROR</strong>: Please enter phone number.', 'crocinorthamerica' ) );
	}

    if ( empty( $_POST['city'] ) ) {
		$errors->add( 'city', __( '<strong>ERROR</strong>: Please enter city.', 'crocinorthamerica' ) );
	}

    if ( empty( $_POST['state'] ) ) {
		$errors->add( 'state', __( '<strong>ERROR</strong>: Please enter state.', 'crocinorthamerica' ) );
	}

}
add_action( 'user_profile_update_errors', 'user_profile_update_errors', 10, 3 );

add_action( 'edit_user_created_user', 'user_registration_meta' );

//Display Custom Fields
function display_custom_fields( $user ) {
	?>
	<h3><?php esc_html_e( 'Personal Information', 'crocinorthamerica' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="company"><?php esc_html_e( 'Company', 'crocinorthamerica' ); ?></label></th>
			<td><?php echo esc_html( get_the_author_meta( 'company', $user->ID ) ); ?></td>
		</tr>
        <tr>
            <th><label for="phone"><?php esc_html_e( 'Phone Number', 'crocinorthamerica' ); ?></label></th>
			<td><?php echo esc_html( get_the_author_meta( 'phone', $user->ID ) ); ?></td>
        </tr>
        <tr>
            <th><label for="city"><?php esc_html_e( 'City', 'crocinorthamerica' ); ?></label></th>
			<td><?php echo esc_html( get_the_author_meta( 'city', $user->ID ) ); ?></td>
        </tr>
        <tr>
            <th><label for="state"><?php esc_html_e( 'State', 'crocinorthamerica' ); ?></label></th>
			<td><?php echo esc_html( get_the_author_meta( 'state', $user->ID ) ); ?></td>
        </tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'display_custom_fields' );
add_action( 'edit_user_profile', 'display_custom_fields' );