import Mustache from "mustache"

/**
 * Renders lists of items for the CRUD listing of the WYF framework.
 */
class List {
    
    #template
    #container
    #url
    #listHeaders
    
    /**
     * Creates a new instance of the list.
     */
    constructor(url, container) {
        this.#container = container
        this.#url = url
        this.#listHeaders = new Headers()
        this.#listHeaders.append('Accepts', 'application/json')
    }
    
    /**
     * Request the data from the controller.
     */
    run() {
        fetch(this.#url, {
            method: "GET",
            headers: this.#listHeaders
        })
        .then(response => response.json())
        .then(response => console.log(response))
    }
    
}

export function renderList() {
    const list = new List('./', document.querySelector('wyf-item-list'))
    list.run()
}
