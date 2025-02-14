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
		attributes: { id, firstName, lastName, placeholder },
		setAttributes,
	} = props;

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
						label={__('List ID', 'rather-simple-mailchimp')}
						type="text"
						value={id}
						onChange={setID}
					/>
					{id && (
						<ToggleControl
							label={__(
								'Show "First Name" field',
								'rather-simple-mailchimp'
							)}
							checked={!!firstName}
							onChange={toggleFirstName}
						/>
					)}
					{id && (
						<ToggleControl
							label={__(
								'Show "Last Name" field',
								'rather-simple-mailchimp'
							)}
							checked={!!lastName}
							onChange={toggleLastName}
						/>
					)}
					{id && (
						<ToggleControl
							label={__(
								'Show placeholder',
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
						block="occ/rather-simple-mailchimp"
						attributes={props.attributes}
					/>
				</Disabled>
			</div>
		</>
	);

}

export default Edit;
