<!-- #introduction (start) -->
<div id="introduction" class="mod-main">
    <div class="hd">
        <div class="title">
            <h2>Introduction</h2>
        </div>
    </div>
    <div class="bd">
        <h3>模組基本概念</h3>
        <ul>
            <li>模組是頁面上提供獨立功能的區塊。</li>
            <li>重覆使用性不是切模組的最高指導原則。</li>
            <li>外觀看起來獨立一塊的也不一定就該切模組。</li>
            <li>分頁 Pagination 是片段的小功能、只是模組的一小部分。模組的功能是完整的、而非片段的。</li>
        </ul>
        <h3>實作原則</h3>
        <ul>
            <li>每個模組有自己的 HTML、CSS、JavaScript、甚至自己的 Controller 檔案。</li>
            <li>一個新模組放到頁面上不能改變其他模組的結構、樣式、行為。</li>
            <li>一個既有模組被移除時，不能影響任何結構、樣式、行為。</li>
            <li>每個團隊內的前端工程師，在做 View/Controller 時不再以 Page 為單元開發、而是以 Module 為單元開發，把自己當成 "Modular Developer"。</li>
        </ul>
        <h3>好處</h3>
        <ul>
            <li>工程師可以專注於單一功能的開發。</li>
            <li>容易分工。</li>
            <li>模組可以獨立作業。</li>
            <li>模組便於單獨測試。</li>
            <li>每個人開發邏輯一致、花點時間了解概念都可以快速上手。</li>
        </ul>
        <h3>困難點</h3>
        <ul>
            <li>HTML 可以用 PHP include_once() 組合，問題不大，會影響些許效能。</li>
            <li>CSS 可以用 Namespace 的概念互相隔絕，問題不大。但是 Request 數量是個大問題。</li>
            <li>JavaScript 最棘手，除了跟 CSS 一樣有檔案數量的問題外，還有模組之間溝通的問題，透過全域變數或方法傳遞會沒辦法彼此獨立。</li>
            <li>檔案過多、分散不易尋找。</li>
        </ul>
        <h3>解決方式</h3>
        <ul>
            <li>CSS / JavaScript 數量的問題透過 Server 端的技術將多個檔案透過設定合併、縮小。(Deploy 到線上時不能動態組、考慮 CDN 或者是 Static 不會有 PHP、還有效能的問題。）</li>
            <li>JavaScript 跨模組傳遞則透過 Nicholas Zakas 推廣的 <a href="http://www.slideshare.net/nzakas/scalable-javascript-application-architecture" target="_blank">Scalable JavaScript Architecture</a>、透過 YUI 來實作。</li>
            <li>分散不易尋找的問題，可以透過撰寫程式、選擇模組，一次在 Editor 打開相關的 HTML, CSS, JavaScript。</li>
        </ul>
    </div>
</div>
<!-- #introduction (end) -->
