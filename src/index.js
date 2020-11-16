/**
 * WordPress dependencies
 */

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { Disabled, PanelBody, Placeholder, TextControl, ToggleControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const {	registerBlockType } = wp.blocks;
const ServerSideRender = wp.serverSideRender;

//import './editor.scss';
import './style.scss';

export const name = 'occ/mailchimp';

export const settings = {
	title: __( 'Mailchimp', 'rather-simple-mailchimp' ),
	description: __( 'A Mailchimp form.', 'rather-simple-mailchimp' ),
	icon: 'email',
	category: 'embed',
	keywords: [ __( 'email' ), __( 'newsletter' ) ],
	attributes: {
        url: {
            type: 'string',
            default: '',
        },
        u: {
            type: 'string',
            default: '',
        },
        id: {
            type: 'string',
            default: '',
        },
        firstName: {
            type: 'boolean',
            default: false,
        },
        lastName: {
            type: 'boolean',
            default: false,
        },
    },

	edit: props => {
        const attributes = props.attributes;

        const setID = value => {
            props.setAttributes( { id: value } );
        };

        const setURL = value => {
            props.setAttributes( { url: value } );
        };

        const setU = value => {
            props.setAttributes( { u: value } );
        };

        const toggleFirstName = () => {
            props.setAttributes( { firstName: ! props.attributes.firstName } );
        };

        const toggleLastName = () => {
            props.setAttributes( { lastName: ! props.attributes.lastName } );
        };

        return (
            <Fragment>
                <InspectorControls>
                    <PanelBody title={ __( 'Mailchimp Settings', 'rather-simple-mailchimp' ) }>
                        <TextControl
                            label={ __( 'URL', 'rather-simple-mailchimp' ) }
                            type='url'
                            value={ attributes.url }
                            onChange={ setURL }
                        />
                        <TextControl
                            label={ __( 'U', 'rather-simple-mailchimp' ) }
                            type='text'
                            value={ attributes.u }
                            onChange={ setU }
                        />
                        <TextControl
                            label={ __( 'ID', 'rather-simple-mailchimp' ) }
                            type='text'
                            value={ attributes.id }
                            onChange={ setID }
                        />
                        { attributes.url && attributes.u && attributes.id && (
                            <ToggleControl
                                label={ __( 'Show First Name', 'rather-simple-mailchimp' ) }
                                checked={ !! attributes.firstName }
                                onChange={ toggleFirstName }
                            />
                        )}
                        { attributes.url && attributes.u && attributes.id && (
                            <ToggleControl
                            label={ __( 'Show Last Name', 'rather-simple-mailchimp' ) }
                            checked={ !! attributes.lastName }
                            onChange={ toggleLastName }
                            />
                        )}
                    </PanelBody>
                </InspectorControls>
                <Disabled>
                    <ServerSideRender
                        block='occ/mailchimp'
                        attributes={ attributes }
                        className={ props.className }
                    />
                </Disabled>
            </Fragment>
        );

        /*return (
            <Fragment>
                <InspectorControls>
                    <PanelBody title={ __( 'Mailchimp Settings', 'rather-simple-mailchimp' ) }>
                        <TextControl
                            label={ __( 'URL', 'rather-simple-mailchimp' ) }
                            type='url'
                            value={ attributes.url }
                            onChange={ setURL }
                        />
                        <TextControl
                            label={ __( 'U', 'rather-simple-mailchimp' ) }
                            type='text'
                            value={ attributes.u }
                            onChange={ setU }
                        />
                        <TextControl
                            label={ __( 'ID', 'rather-simple-mailchimp' ) }
                            type='text'
                            value={ attributes.id }
                            onChange={ setID }
                        />
                        { attributes.url && attributes.u && attributes.id && (
                            <ToggleControl
                                label={ __( 'Show First Name', 'rather-simple-mailchimp' ) }
                                checked={ !! attributes.firstName }
                                onChange={ toggleFirstName }
                            />
                        )}
                        { attributes.url && attributes.u && attributes.id && (
                            <ToggleControl
                            label={ __( 'Show Last Name', 'rather-simple-mailchimp' ) }
                            checked={ !! attributes.lastName }
                            onChange={ toggleLastName }
                            />
                        )}
                    </PanelBody>
                </InspectorControls>
                <div className={ props.className }>
                    { attributes.url && attributes.u && attributes.id ? (
                        <div id="mc_embed_signup">
                            <form action={ attributes.url  + "/subscribe/post?u=" + attributes.u  + "&id=" + attributes.id } method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" className="validate" target="_blank" noValidate>
                                <div id="mc_embed_signup_scroll">
                                    <div style={{position: 'absolute', left: '-5000px'}}>
                                        <input name={ "b_" + attributes.u + "_" + attributes.id } tabIndex={-1} value="" type="text" />
                                    </div>
                                    { attributes.firstName && (
                                        <div className="mc-field-group">
                                            <label htmlFor="mce-FNAME">{ __( 'First Name', 'rather-simple-mailchimp' ) }<span className="required">*</span></label>
                                            <input value="" name="FNAME" className="required" id="mce-FNAME" type="text" disabled />
                                        </div>
                                    )}
                                    { attributes.lastName && (
                                        <div className="mc-field-group">
                                            <label htmlFor="mce-LNAME">{ __( 'Last Name', 'rather-simple-mailchimp' ) }<span className="required">*</span></label>
                                            <input value="" name="LNAME" className="required" id="mce-LNAME" type="text" disabled />
                                        </div>
                                    )}
                                    <div className="mc-field-group">
                                        <label htmlFor="mce-EMAIL">{ __( 'Email', 'rather-simple-mailchimp' ) }<span className="required">*</span></label>
                                        <input value="" name="EMAIL" className="required email" id="mce-EMAIL" type="email" disabled />
                                    </div>
                                    <div className="mc-submit-button">
                                        <input value={ __( 'Subscribe', 'rather-simple-mailchimp' ) } name="subscribe" id="mc-embedded-subscribe" className="button" type="submit" disabled />
                                    </div>
                                    <div className="mc-privacy-policy"></div>
                                    <div id="mce-responses" className="clear">
                                        <div className="response" id="mce-error-response" style={{display: 'none'}} />
                                        <div className="response" id="mce-success-response" style={{display: 'none'}} />
                                    </div>
                                </div>
                            </form>
                            <script src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
                        </div>
                    ) : (
                        <Placeholder
                            key='rather-simple-mailchimp-block'
                            icon='email'
                            label={ __( 'Rather Simple Mailchimp', 'rather-simple-mailchimp' ) }
                            className={ props.className }
                            instructions={ __( 'Set up your Mailchimp form filling the fields on the sidebar.', 'rather-simple-mailchimp' ) }>
                        </Placeholder>
                    )}
                </div>
            </Fragment>
        );*/

    },

    save: () => {
        return null
    }

    /*save: props => {
        const attributes = props.attributes;

		return (
			<div className={ props.className }>
                { attributes.url && attributes.u && attributes.id && (
                    <div id="mc_embed_signup">
                        <form action={ attributes.url  + "/subscribe/post?u=" + attributes.u  + "&id=" + attributes.id } method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" className="validate" target="_blank" noValidate>
                            <div id="mc_embed_signup_scroll">
                                <div style={{position: 'absolute', left: '-5000px'}}>
                                    <input name={ "b_" + attributes.u + "_" + attributes.id } tabIndex={-1} value="" type="text" />
                                </div>
                                { attributes.firstName && (
                                    <div className="mc-field-group">
                                        <label htmlFor="mce-FNAME">{ __( 'First Name', 'rather-simple-mailchimp' ) }<span className="required">*</span></label>
                                        <input value="" name="FNAME" className="required" id="mce-FNAME" type="text" />
                                    </div>
                                )}
                                { attributes.lastName && (
                                    <div className="mc-field-group">
                                        <label htmlFor="mce-LNAME">{ __( 'Last Name', 'rather-simple-mailchimp' ) }<span className="required">*</span></label>
                                        <input value="" name="LNAME" className="required" id="mce-LNAME" type="text" />
                                    </div>
                                )}
                                <div className="mc-field-group">
                                    <label htmlFor="mce-EMAIL">{ __( 'Email', 'rather-simple-mailchimp' ) }<span className="required">*</span></label>
                                    <input value="" name="EMAIL" className="required email" id="mce-EMAIL" type="email" />
                                </div>
                                <div className="mc-submit-button">
                                    <input value={ __( 'Subscribe', 'rather-simple-mailchimp' ) } name="subscribe" id="mc-embedded-subscribe" className="button" type="submit" />
                                </div>
                                <div className="mc-privacy-policy"></div>
                                <div id="mce-responses" className="clear">
                                    <div className="response" id="mce-error-response" style={{display: 'none'}} />
                                    <div className="response" id="mce-success-response" style={{display: 'none'}} />
                                </div>
                            </div>
                        </form>
                        <script src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
                    </div>
                )}
			</div>
		);
	}*/

};

registerBlockType( name, settings );
