(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{3:function(e,t,r){}}]),function(e){function t(t){for(var n,a,o=t[0],c=t[1],u=t[2],p=0,m=[];p<o.length;p++)a=o[p],Object.prototype.hasOwnProperty.call(l,a)&&l[a]&&m.push(l[a][0]),l[a]=0;for(n in c)Object.prototype.hasOwnProperty.call(c,n)&&(e[n]=c[n]);for(s&&s(t);m.length;)m.shift()();return i.push.apply(i,u||[]),r()}function r(){for(var e,t=0;t<i.length;t++){for(var r=i[t],n=!0,o=1;o<r.length;o++){var c=r[o];0!==l[c]&&(n=!1)}n&&(i.splice(t--,1),e=a(a.s=r[0]))}return e}var n={},l={0:0},i=[];function a(t){if(n[t])return n[t].exports;var r=n[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,a),r.l=!0,r.exports}a.m=e,a.c=n,a.d=function(e,t,r){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(a.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)a.d(r,n,function(t){return e[t]}.bind(null,n));return r},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="";var o=window.webpackJsonp=window.webpackJsonp||[],c=o.push.bind(o);o.push=t,o=o.slice();for(var u=0;u<o.length;u++)t(o[u]);var s=c;i.push([1,1]),r()}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t,r){"use strict";r.r(t),r.d(t,"name",(function(){return b})),r.d(t,"settings",(function(){return h}));var n=r(0),l=(r(2),r(3),wp.i18n.__),i=wp.element.Fragment,a=wp.components,o=a.Disabled,c=a.PanelBody,u=(a.Placeholder,a.TextControl),s=a.ToggleControl,p=wp.blockEditor.InspectorControls,m=wp.blocks.registerBlockType,f=wp.serverSideRender,b="occ/mailchimp",h={title:l("Mailchimp","rather-simple-mailchimp"),description:l("A Mailchimp form.","rather-simple-mailchimp"),icon:"email",category:"embed",keywords:[l("email"),l("newsletter")],supports:{html:!1,multiple:!1},attributes:{url:{type:"string",default:""},u:{type:"string",default:""},id:{type:"string",default:""},firstName:{type:"boolean",default:!1},lastName:{type:"boolean",default:!1}},edit:function(e){var t=e.attributes;return Object(n.createElement)(i,null,Object(n.createElement)(p,null,Object(n.createElement)(c,{title:l("Mailchimp Settings","rather-simple-mailchimp")},Object(n.createElement)(u,{label:l("URL","rather-simple-mailchimp"),type:"url",value:t.url,onChange:function(t){e.setAttributes({url:t})}}),Object(n.createElement)(u,{label:l("U","rather-simple-mailchimp"),type:"text",value:t.u,onChange:function(t){e.setAttributes({u:t})}}),Object(n.createElement)(u,{label:l("ID","rather-simple-mailchimp"),type:"text",value:t.id,onChange:function(t){e.setAttributes({id:t})}}),t.url&&t.u&&t.id&&Object(n.createElement)(s,{label:l("Show First Name","rather-simple-mailchimp"),checked:!!t.firstName,onChange:function(){e.setAttributes({firstName:!e.attributes.firstName})}}),t.url&&t.u&&t.id&&Object(n.createElement)(s,{label:l("Show Last Name","rather-simple-mailchimp"),checked:!!t.lastName,onChange:function(){e.setAttributes({lastName:!e.attributes.lastName})}}))),Object(n.createElement)(o,null,Object(n.createElement)(f,{block:"occ/mailchimp",attributes:t,className:e.className})))},save:function(){return null}};m(b,h)},function(e,t,r){}]);