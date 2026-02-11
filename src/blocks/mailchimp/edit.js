/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	__experimentalToolsPanel as ToolsPanel,
	__experimentalToolsPanelItem as ToolsPanelItem,
	Disabled,
	PanelBody,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import {
	DimensionControl,
	InspectorControls,
	useBlockProps
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { useState } from 'react';

const Edit = (props) => {

	const {
		attributes: { id, firstName, lastName, placeholder, width },
		setAttributes,
		clientId
	} = props;

	const blockProps = useBlockProps({
		style: {
			width: undefined,
			maxWidth: width,
		},
	});

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

	const setWidth = (value) => {
		setAttributes({ width: value });
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
			<InspectorControls group="dimensions">
				<ToolsPanelItem
					hasValue={() => !!width}
					label={__('Max. Width', 'rather-simple-mailchimp')}
					onDeselect={() => setWidth(undefined)}
					resetAllFilter={() => setWidth(undefined)}
					isShownByDefault
					panelId={clientId}
				>
					<DimensionControl
						label={__('Max. Width', 'rather-simple-mailchimp')}
						onChange={setWidth}
						value={width}
					/>
				</ToolsPanelItem>
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
