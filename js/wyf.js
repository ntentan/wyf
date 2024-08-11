import {renderList} from "./lists"

document.addEventListener('load', () => {
    const wyfMode = document.querySelector('body').getAttribute('wyf-state')
    
    if (wyfMode == 'wyf-listing') {
        renderList()
    }
})
