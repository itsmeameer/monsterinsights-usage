
/**
 * Handles the AJAX request for the shortcode.
 * 
 */

'use strict';

const { __, _x, _n, _nx } = wp.i18n;

// when DOM is ready
document.addEventListener( 'DOMContentLoaded', function () {

	const MIUsage = monsterinsights_usage_shortcode_object;

	// async function to get the data from the server
	const getData = async ( action ) => {

		const URL = `${ MIUsage.ajax_url }?action=${ action }&nonce=${ MIUsage.nonce }`;

		const request = await fetch( URL, {
			method: "GET",
		});

		if ( request.status !== 200 ) {
			throw new Error( __( 'Something went wrong. Could not fetch new data.', 'monsterinsights-usage' ) );
		}
		return request.json();
	};

	// Generate rows for table after checking if the respective col is set to be show or not
	const generateRow = ( row, setting, element ) => {
		let key = 0;
		let markup = '';

		for ( let col in row ) {
			if ( setting[key] === 'true' ) {
				markup += `<${ element }>${ row[col] }</${ element }>`;
			}
			key++;
		}

		if ( markup !== '' ) {
			return `<tr>${ markup }</tr>`;
		}

		return markup;
	}

	const shortcodes_elm = document.querySelectorAll( '.mi-usage-table-shortcode' );

	if ( shortcodes_elm.length > 0 ) {

		getData( 'monsterinsights_usage_api_data' ).then( ( response ) => {
			
			// Looping over all the shortcodes.
			shortcodes_elm.forEach( element => {

				const show_title = element.getAttribute( 'data-show-title' );

				const colSettings = [
					element.getAttribute( 'data-show-col-id' ),
					element.getAttribute( 'data-show-col-first-name' ),
					element.getAttribute( 'data-show-col-last-name' ),
					element.getAttribute( 'data-show-col-email' ),
					element.getAttribute( 'data-show-col-date' ),
				];

				let markup = '';

				markup += ( 'true' === show_title ) ? `<h3 class="monsterinsights-usage-title">${ response.title }</h3>` : '';

				// Check if any of the columns are set to be shown.
				if ( colSettings.every( ( setting ) => setting === 'false' ) ) {
					element.innerHTML = markup;
					return;
				}

				// Generate table.
				markup += `
				<table>
					<thead>
						${ generateRow( response.data.headers, colSettings, 'th' ) }
					</thead>
					<tbody>
						${ Object.keys( response.data.rows ).map( row => generateRow( response.data.rows[row], colSettings, 'td' ) ).join( '' ) }
					</tbody
				</table>`;

				// Add HTML to the element.
				element.innerHTML = markup;

			});

		} ).catch( ( error ) => {
			console.log( error );
		} );

	}

}, false);
