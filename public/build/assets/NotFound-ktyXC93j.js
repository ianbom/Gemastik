import{r,j as t}from"./app-DqvPgPLX.js";/**
 * @remix-run/router v1.23.0
 *
 * Copyright (c) Remix Software Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE.md file in the root directory of this source tree.
 *
 * @license MIT
 */var n;(function(e){e.Pop="POP",e.Push="PUSH",e.Replace="REPLACE"})(n||(n={}));function i(e,c){throw new Error(c)}var o;(function(e){e.data="data",e.deferred="deferred",e.redirect="redirect",e.error="error"})(o||(o={}));const s=["post","put","patch","delete"];new Set(s);const u=["get",...s];new Set(u);/**
 * React Router v6.30.1
 *
 * Copyright (c) Remix Software Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE.md file in the root directory of this source tree.
 *
 * @license MIT
 */const a=r.createContext(null);function l(){return r.useContext(a)!=null}function d(){return l()||i(),r.useContext(a).location}new Promise(()=>{});const m=()=>{const e=d();return r.useEffect(()=>{console.error("404 Error: User attempted to access non-existent route:",e.pathname)},[e.pathname]),t.jsx("div",{className:"flex items-center justify-center min-h-screen bg-gray-100",children:t.jsxs("div",{className:"text-center",children:[t.jsx("h1",{className:"mb-4 text-4xl font-bold",children:"404"}),t.jsx("p",{className:"mb-4 text-xl text-gray-600",children:"Oops! Page not found"}),t.jsx("a",{href:"/",className:"text-blue-500 underline hover:text-blue-700",children:"Return to Home"})]})})};export{m as default};
