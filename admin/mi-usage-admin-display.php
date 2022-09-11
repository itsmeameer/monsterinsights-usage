<?php
/**
 * The view for the wp-admin dashboard.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/admin
 */

// Get the data that is stored without making a api call.
$data = $api->get_data( false, 'default' );

// Get last updated on date, formatted.
$last_updated_on = $api->last_updated_on( 'default' );

?>
<div class="wrap">
	<div class="admin-page-content-wrapper">

		<header>
			<h1><?php echo $mi_usage->get_plugin_name(); ?></h1>
			<p><?php esc_html_e( 'This is a simple page that shows the data taken from the API.', MI_USAGE_TEXT_DOMAIN ); ?></p>
		</header>

		<section class="date-refresh-wrapper">

			<p class="last-updated-text-wrapper">
				<?php
				if ( $last_updated_on ) {
					echo sprintf( __( 'Data was last updated on %s', MI_USAGE_TEXT_DOMAIN ), $last_updated_on );
				} else {
					esc_html_e( 'Data has not been pulled form the API yet. Please click the "Refresh Data" button to fetch the data.', MI_USAGE_TEXT_DOMAIN );
				}
				?>
			</p>

			<table id="mi-usage-table" class="wp-list-table widefat fixed striped table-view-list">
				<thead>
					<tr>
						<?php foreach ( $api->get_default_headers() as $header ) { ?>
							<td><?php echo $header; ?></td>
						<?php } ?>
					</tr>
				</thead>

				<tbody id="the-list">
					<?php if ( $data && ! empty( $data ) ) {
						foreach ( $data['data']['rows'] as $row ) {
							?>
							<tr>
								<?php foreach ( $row as $column ) { ?>
									<td><?php echo $column; ?></td>
								<?php } ?>
							</tr>
							<?php
						}
					} else { ?>
						<tr class="no-items">
							<td class="colspanchange error-space" colspan="5"><?php esc_html_e( 'It looks like the data has not been fetched from the API yet, click the "Refresh" button to fetch data.', MI_USAGE_TEXT_DOMAIN ); ?></td>
						</tr>
					<?php } ?>
				</tbody>

				<tfoot>
					<tr>
						<?php foreach ( $api->get_default_headers() as $header ) { ?>
							<td><?php echo $header; ?></td>
						<?php } ?>
					</tr>
				</tfoot>

			</table>

			<div class="date-refresh-container">
				<button id="date-refresh-refresh-button" data-label-loading="<?php esc_html_e( 'Refreshing...', MI_USAGE_TEXT_DOMAIN ); ?>" data-label-done="<?php esc_html_e( 'Refresh Data', MI_USAGE_TEXT_DOMAIN ); ?>">
					<span class="dashicons dashicons-update"></span>
					<span class="date-refresh-refresh-button-text"><?php esc_html_e( 'Refresh Data', MI_USAGE_TEXT_DOMAIN ); ?></span>
				</button>
			</div>

		</section>

	</div>
</div>
