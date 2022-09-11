
/**
 * Handles the admin page functionality.
 * 
 */

'use strict';

const { __, _x, _n, _nx } = wp.i18n;
 
// when DOM is ready
document.addEventListener( 'DOMContentLoaded', function () {

	const MIUsage = monsterinsights_usage_admin_object;

	const getData = async ( action ) => {

		const URL = `${ MIUsage.ajax_url }?action=${ action }&nonce=${ MIUsage.nonce }&admin=true`;

		const request = await fetch( URL, {
			method: "GET",
		});

		if ( request.status !== 200 ) {
			throw new Error( __( 'Something went wrong. Could not fetch new data.', 'monsterinsights-usage' ) );
		}
		return request.json();
	};

	const refreshDataButton = document.getElementById('date-refresh-refresh-button');
	refreshDataButton.addEventListener( 'click', function () {

		const theButton = this;

		const labelLoading = theButton.getAttribute( 'data-label-loading' );
		const labelDone    = theButton.getAttribute( 'data-label-done' );
		const textElm      = theButton.querySelector( '.date-refresh-refresh-button-text' );

		textElm.innerHTML = labelLoading;
		theButton.setAttribute( 'disabled', 'disabled' );

		getData( 'monsterinsights_usage_api_data_admin' ).then( ( response ) => {
			
			textElm.innerHTML = labelDone;
			theButton.removeAttribute( 'disabled' );

			document.querySelector( '.last-updated-text-wrapper' ).innerHTML = response.last_update_text;

			let markup = `${ Object.keys( response.data.rows ).map( row => `<tr>${ Object.keys( response.data.rows[row] ).map( col => `<td>${ response.data.rows[row][col] }</td>`).join( '' ) }</tr>` ).join( '' )
			}`;

			document.querySelector( 'table#mi-usage-table tbody' ).innerHTML = markup;

		} ).catch( ( error ) => {
			alert( error );
		} );
		

	});

}, false);
