/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	Path,
	SVG,
	Disabled,
	PanelBody,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import metadata from './block.json';

import './editor.scss';
import './style.scss';

const { name } = metadata;

const settings = {

	icon: {
		src: <SVG viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
			<Path fill="currentColor" d="M16.279 11.506c.132-.016.257-.018.373 0c.066-.154.078-.419.019-.708c-.09-.429-.211-.688-.461-.646c-.251.04-.261.35-.17.779c.05.24.14.446.239.575zm-2.149.339c.18.078.29.129.331.086c.029-.028.021-.084-.022-.154a1.05 1.05 0 0 0-.464-.371a1.26 1.26 0 0 0-1.228.146c-.119.088-.232.209-.218.283c.007.023.023.042.065.05c.099.011.444-.164.843-.188c.282-.02.513.068.693.148zm-.361.205c-.232.037-.361.113-.443.187c-.071.062-.113.128-.113.177l.018.042l.037.014c.053 0 .171-.046.171-.046c.324-.115.539-.102.752-.078c.117.014.172.02.198-.02c.007-.012.018-.035-.007-.074c-.056-.091-.291-.24-.613-.202zm1.784.756c.159.078.333.046.39-.069c.059-.115-.024-.272-.183-.349c-.158-.079-.333-.049-.39.066c-.057.115.026.274.183.352zm1.018-.891c-.128-.002-.234.138-.238.316c-.003.177.1.321.229.322c.129.002.235-.139.238-.315s-.099-.32-.229-.323zm-8.644 3.183c-.032-.04-.085-.029-.136-.015c-.036.007-.076.017-.119.016a.265.265 0 0 1-.221-.111c-.059-.09-.056-.225.01-.378l.03-.069c.104-.231.275-.619.082-.988a.88.88 0 0 0-.671-.488a.861.861 0 0 0-.739.267c-.284.313-.327.741-.273.893c.021.056.053.071.075.074c.048.007.119-.029.164-.15l.014-.038c.02-.064.057-.184.118-.278a.518.518 0 0 1 .717-.15c.2.131.275.375.19.608c-.044.121-.115.351-.1.54c.032.383.27.537.48.556c.206.007.35-.108.387-.193c.021-.053.003-.084-.008-.096z"/>
			<Path fill="currentColor" d="M19.821 14.397c-.009-.029-.061-.216-.13-.44l-.144-.384c.281-.423.286-.799.249-1.013a1.284 1.284 0 0 0-.372-.724c-.222-.232-.677-.472-1.315-.651l-.335-.093c-.002-.015-.018-.79-.031-1.123c-.011-.24-.031-.616-.148-.986c-.14-.502-.381-.938-.684-1.221c.835-.864 1.355-1.817 1.354-2.634c-.003-1.571-1.933-2.049-4.312-1.063l-.503.214c-.002-.002-.911-.894-.924-.905c-2.714-2.366-11.192 7.06-8.48 9.349l.593.501a2.916 2.916 0 0 0-.166 1.345c.065.631.389 1.234.915 1.701c.5.442 1.159.724 1.796.723c1.055 2.432 3.465 3.922 6.291 4.007c3.032.09 5.576-1.333 6.644-3.889c.069-.179.365-.987.365-1.7c-.001-.718-.406-1.015-.663-1.014zM7.416 16.309a1.38 1.38 0 0 1-.28.021c-.916-.026-1.905-.85-2.003-1.827c-.109-1.08.443-1.912 1.421-2.108c.116-.025.258-.038.41-.031c.548.032 1.354.452 1.539 1.645c.164 1.055-.096 2.132-1.087 2.3zm-1.021-4.562a2.325 2.325 0 0 0-1.473.94c-.197-.164-.562-.48-.626-.604c-.524-.994.571-2.928 1.337-4.02c1.889-2.698 4.851-4.739 6.223-4.371c.222.064.96.921.96.921s-1.37.759-2.642 1.819c-1.711 1.32-3.006 3.236-3.779 5.315zm9.611 4.158a.05.05 0 0 0 .03-.054a.05.05 0 0 0-.056-.045s-1.434.212-2.789-.283c.147-.479.541-.308 1.134-.259a8.287 8.287 0 0 0 2.735-.296c.613-.177 1.419-.524 2.045-1.018c.212.465.286.975.286.975s.163-.029.3.055c.13.08.224.245.16.671c-.133.798-.471 1.445-1.042 2.041a4.259 4.259 0 0 1-1.249.934a5.337 5.337 0 0 1-.814.346c-2.149.701-4.349-.07-5.058-1.727a2.761 2.761 0 0 1-.142-.392c-.302-1.092-.046-2.4.755-3.226v-.001c.051-.052.102-.113.102-.191c0-.064-.042-.133-.077-.183c-.28-.406-1.253-1.099-1.057-2.44c.139-.964.982-1.642 1.768-1.602l.2.012c.34.02.637.063.917.076c.47.019.891-.049 1.391-.465c.169-.142.304-.263.532-.301c.024-.006.084-.025.203-.021a.681.681 0 0 1 .343.109c.4.266.457.912.479 1.385c.012.269.045.922.055 1.108c.026.428.139.489.365.563c.129.044.248.074.423.125c.529.147.845.3 1.043.493a.637.637 0 0 1 .188.372c.065.457-.353 1.021-1.455 1.533c-1.206.559-2.669.701-3.679.588l-.354-.04c-.81-.108-1.269.936-.784 1.651c.313.461 1.164.761 2.017.761c1.953.002 3.455-.832 4.015-1.554l.044-.063c.026-.042.005-.063-.03-.041c-.455.312-2.483 1.552-4.651 1.18c0 0-.264-.044-.504-.138c-.19-.072-.591-.258-.639-.668c1.747.543 2.85.031 2.85.03zm-2.773-.327zM9.886 8.053c.672-.776 1.499-1.452 2.241-1.83c.025-.014.052.015.038.038a2.125 2.125 0 0 0-.208.508c-.006.027.023.049.046.032c.462-.314 1.264-.651 1.968-.693a.03.03 0 0 1 .021.055a1.66 1.66 0 0 0-.31.311c-.014.02-.001.049.024.049c.494.003 1.191.175 1.644.43c.03.018.008.077-.025.069c-.688-.157-1.811-.277-2.979.008c-1.044.254-1.84.646-2.419 1.069c-.03.02-.065-.019-.041-.046z"/>
			</SVG>,
		foreground: '#ff8a00'
	},

	edit: ( props ) => {
		const blockProps = useBlockProps();
		const {
			attributes: { url, u, id, firstName, lastName, placeholder },
			setAttributes,
		} = props;

		const setURL = ( value ) => {
			setAttributes( { url: value } );
		};

		const setU = ( value ) => {
			setAttributes( { u: value } );
		};

		const setID = ( value ) => {
			setAttributes( { id: value } );
		};

		const toggleFirstName = () => {
			setAttributes( { firstName: ! props.attributes.firstName } );
		};

		const toggleLastName = () => {
			setAttributes( { lastName: ! props.attributes.lastName } );
		};

		const togglePlaceholder = () => {
			setAttributes( { placeholder: ! props.attributes.placeholder } );
		};

		return (
			<>
				<InspectorControls>
					<PanelBody
						title={ __(
							'Mailchimp Settings',
							'rather-simple-mailchimp'
						) }
					>
						<TextControl
							label={ __( 'URL', 'rather-simple-mailchimp' ) }
							type="url"
							value={ url }
							onChange={ setURL }
						/>
						<TextControl
							label={ __( 'U', 'rather-simple-mailchimp' ) }
							type="text"
							value={ u }
							onChange={ setU }
						/>
						<TextControl
							label={ __( 'ID', 'rather-simple-mailchimp' ) }
							type="text"
							value={ id }
							onChange={ setID }
						/>
						{ url && u && id && (
							<ToggleControl
								label={ __(
									'Show First Name',
									'rather-simple-mailchimp'
								) }
								checked={ !! firstName }
								onChange={ toggleFirstName }
							/>
						) }
						{ url && u && id && (
							<ToggleControl
								label={ __(
									'Show Last Name',
									'rather-simple-mailchimp'
								) }
								checked={ !! lastName }
								onChange={ toggleLastName }
							/>
						) }
						{ url && u && id && (
							<ToggleControl
								label={ __(
									'Show Placeholder',
									'rather-simple-mailchimp'
								) }
								checked={ !! placeholder }
								onChange={ togglePlaceholder }
							/>
						) }
					</PanelBody>
				</InspectorControls>
				<div { ...blockProps }>
					<Disabled>
						<ServerSideRender
							block="occ/mailchimp"
							attributes={ props.attributes }
						/>
					</Disabled>
				</div>
			</>
		);
	},

};

registerBlockType( name, settings );
