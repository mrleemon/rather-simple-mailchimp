(window.webpackJsonp_rather_simple_mailchimp=window.webpackJsonp_rather_simple_mailchimp||[]).push([[1],{7:function(e,t,r){}}]),function(e){function t(t){for(var n,o,a=t[0],c=t[1],u=t[2],s=0,m=[];s<a.length;s++)o=a[s],Object.prototype.hasOwnProperty.call(i,o)&&i[o]&&m.push(i[o][0]),i[o]=0;for(n in c)Object.prototype.hasOwnProperty.call(c,n)&&(e[n]=c[n]);for(p&&p(t);m.length;)m.shift()();return l.push.apply(l,u||[]),r()}function r(){for(var e,t=0;t<l.length;t++){for(var r=l[t],n=!0,a=1;a<r.length;a++){var c=r[a];0!==i[c]&&(n=!1)}n&&(l.splice(t--,1),e=o(o.s=r[0]))}return e}var n={},i={0:0},l=[];function o(t){if(n[t])return n[t].exports;var r=n[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,o),r.l=!0,r.exports}o.m=e,o.c=n,o.d=function(e,t,r){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,t){if(1&t&&(e=o(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(o.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)o.d(r,n,function(t){return e[t]}.bind(null,n));return r},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="";var a=window.webpackJsonp_rather_simple_mailchimp=window.webpackJsonp_rather_simple_mailchimp||[],c=a.push.bind(a);a.push=t,a=a.slice();for(var u=0;u<a.length;u++)t(a[u]);var p=c;l.push([6,1]),r()}([function(e,t){e.exports=window.wp.element},function(e,t){e.exports=window.wp.i18n},function(e,t){e.exports=window.wp.components},function(e,t){e.exports=window.wp.blockEditor},function(e,t){e.exports=window.wp.blocks},function(e,t){e.exports=window.wp.serverSideRender},function(e,t,r){"use strict";r.r(t);var n=r(0),i=r(1),l=r(2),o=r(3),a=r(4),c=r(5),u=r.n(c),p=(r(7),r(8),{title:Object(i.__)("Mailchimp","rather-simple-mailchimp"),description:Object(i.__)("A Mailchimp form.","rather-simple-mailchimp"),icon:"email",category:"embed",keywords:[Object(i.__)("email"),Object(i.__)("newsletter")],supports:{html:!1,multiple:!1},attributes:{url:{type:"string",default:""},u:{type:"string",default:""},id:{type:"string",default:""},firstName:{type:"boolean",default:!1},lastName:{type:"boolean",default:!1}},edit:function(e){var t=e.attributes;return Object(n.createElement)(n.Fragment,null,Object(n.createElement)(o.InspectorControls,null,Object(n.createElement)(l.PanelBody,{title:Object(i.__)("Mailchimp Settings","rather-simple-mailchimp")},Object(n.createElement)(l.TextControl,{label:Object(i.__)("URL","rather-simple-mailchimp"),type:"url",value:t.url,onChange:function(t){e.setAttributes({url:t})}}),Object(n.createElement)(l.TextControl,{label:Object(i.__)("U","rather-simple-mailchimp"),type:"text",value:t.u,onChange:function(t){e.setAttributes({u:t})}}),Object(n.createElement)(l.TextControl,{label:Object(i.__)("ID","rather-simple-mailchimp"),type:"text",value:t.id,onChange:function(t){e.setAttributes({id:t})}}),t.url&&t.u&&t.id&&Object(n.createElement)(l.ToggleControl,{label:Object(i.__)("Show First Name","rather-simple-mailchimp"),checked:!!t.firstName,onChange:function(){e.setAttributes({firstName:!e.attributes.firstName})}}),t.url&&t.u&&t.id&&Object(n.createElement)(l.ToggleControl,{label:Object(i.__)("Show Last Name","rather-simple-mailchimp"),checked:!!t.lastName,onChange:function(){e.setAttributes({lastName:!e.attributes.lastName})}}))),Object(n.createElement)(l.Disabled,null,Object(n.createElement)(u.a,{block:"occ/mailchimp",attributes:t,className:e.className})))},save:function(){return null}});Object(a.registerBlockType)("occ/mailchimp",p)},,function(e,t,r){}]);