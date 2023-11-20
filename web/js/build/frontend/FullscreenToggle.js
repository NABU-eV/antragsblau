define(["require","exports"],(function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.FullscreenToggle=void 0;t.FullscreenToggle=class{constructor(e){this.$element=e,this.vueElement=null,this.vueWidget=null,this.element=e[0],this.element.getAttribute("data-vue-element")?(this.vueElement=this.element.getAttribute("data-vue-element"),this.holderElement=this.createFullscreenVueHolder()):this.holderElement=document.querySelector(".well"),this.element.addEventListener("click",this.toggleFullScreeen.bind(this)),["fullscreenchange","webkitfullscreenchange","mozfullscreenchange","msfullscreenchange"].forEach((e=>document.addEventListener(e,this.onFullscreenChange.bind(this),!1)))}requestFullscreen(){this.vueElement&&document.querySelector("body").append(this.holderElement);let e=this.holderElement;e.requestFullscreen?e.requestFullscreen():e.webkitRequestFullscreen?e.webkitRequestFullscreen():e.mozRequestFullScreen?e.mozRequestFullScreen():e.msRequestFullscreen&&e.msRequestFullscreen(),this.vueElement&&this.initVueElement()}exitFullscreen(){let e=document;e.exitFullscreen?e.exitFullscreen():e.webkitExitFullscreen?e.webkitExitFullscreen():e.mozCancelFullScreen?e.mozCancelFullScreen():e.msExitFullscreen&&e.msExitFullscreen()}isFullscreen(){let e=document;return!!(e.fullscreenElement||e.webkitFullscreenElement||e.mozFullScreenElement||e.msFullscreenElement)}toggleFullScreeen(){this.isFullscreen()?this.exitFullscreen():this.requestFullscreen()}onFullscreenChange(){if(!this.isFullscreen()&&this.vueElement){const e=this.vueWidget.currIMotion?this.vueWidget.currIMotion.url_html:null;this.destroyVueElement(),this.holderElement.remove(),e&&window.location.href!==e&&(window.location.href=e)}}createFullscreenVueHolder(){const e=document.createElement("div"),t=document.createElement("div");return e.append(t),e}initVueElement(){const e=this;let t="<"+this.vueElement+' :initdata="initdata" @close="close" @changed="changed"></'+this.vueElement+">",l={};this.element.getAttribute("data-vue-initdata")&&(l=JSON.parse(this.element.getAttribute("data-vue-initdata"))),this.vueWidget=Vue.createApp({template:t,data:()=>({initdata:l,currIMotion:null}),methods:{close:function(t){e.isFullscreen()?e.exitFullscreen():(e.destroyVueElement(),e.holderElement.remove()),t&&t!==window.location.href&&(window.location.href=t)},changed:function(e){this.currIMotion=e}},beforeUnmount(){},created(){}}),this.vueWidget.config.compilerOptions.whitespace="condense",window.__initVueComponents(this.vueWidget,"fullscreen"),this.vueWidget.mount(this.holderElement.firstChild)}destroyVueElement(){this.vueWidget.unmount()}}}));
//# sourceMappingURL=FullscreenToggle.js.map
