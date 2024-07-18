/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	Disabled,
	PanelBody,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import {
	InspectorControls,
	useBlockProps
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

const Edit = (props) => {

	const blockProps = useBlockProps();
	const {
		attributes: { url, u, id, firstName, lastName, placeholder },
		setAttributes,
	} = props;

	const setURL = (value) => {
		setAttributes({ url: value });
	};

	const setU = (value) => {
		setAttributes({ u: value });
	};

	const setID = (value) => {
		setAttributes({ id: value });
	};

	const toggleFirstName = () => {
		setAttributes({ firstName: !props.attributes.firstName });
	};

	const toggleLastName = () => {
		setAttributes({ lastName: !props.attributes.lastName });
	};

	const togglePlaceholder = () => {
		setAttributes({ placeholder: !props.attributes.placeholder });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__(
						'Mailchimp Settings',
						'rather-simple-mailchimp'
					)}
				>
					<TextControl
						label={__('URL', 'rather-simple-mailchimp')}
						type="url"
						value={url}
						onChange={setURL}
					/>
					<TextControl
						label={__('U', 'rather-simple-mailchimp')}
						type="text"
						value={u}
						onChange={setU}
					/>
					<TextControl
						label={__('ID', 'rather-simple-mailchimp')}
						type="text"
						value={id}
						onChange={setID}
					/>
					{url && u && id && (
						<ToggleControl
							label={__(
								'Show First Name',
								'rather-simple-mailchimp'
							)}
							checked={!!firstName}
							onChange={toggleFirstName}
						/>
					)}
					{url && u && id && (
						<ToggleControl
							label={__(
								'Show Last Name',
								'rather-simple-mailchimp'
							)}
							checked={!!lastName}
							onChange={toggleLastName}
						/>
					)}
					{url && u && id && (
						<ToggleControl
							label={__(
								'Show Placeholder',
								'rather-simple-mailchimp'
							)}
							checked={!!placeholder}
							onChange={togglePlaceholder}
						/>
					)}
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<Disabled>
					<ServerSideRender
						block="occ/mailchimp"
						attributes={props.attributes}
					/>
				</Disabled>
			</div>
		</>
	);

}

export default Edit;
