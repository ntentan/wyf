import {renderList} from "./lists"

window.addEventListener('load', () => {
    const wyfMode = document.querySelector('body').getAttribute('wyf-mode')
    console.log(wyfMode);
    if (wyfMode == 'list') {
        renderList()
    }
})
