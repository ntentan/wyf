import Mustache from "mustache"

/**
 * Renders lists of items for the CRUD listing of the WYF framework.
 */
class List {
    
    #template
    #container
    #url
    #requestHeaders
    
    /**
     * Creates a new instance of the list.
     */
    constructor(container, template) { 
        this.#container = container
        this.#requestHeaders = new Headers()
        this.#requestHeaders.append('Accept', 'application/json')
        this.#url = this.#container.getAttribute('wyf-data-path')
        this.#template = template
        console.log(this.#template)
        Mustache.parse(this.#template)
    }
    
    /**
     * Request the data from the controller.
     */
    run() {
        fetch(this.#url, {
            method: "GET",
            headers: this.#requestHeaders
        })
        .then(response => response.json())
        .then(response => this.#container.innerHTML = Mustache.render(this.#template, {'items': response}))
    }
}

export function renderList() {
    const list = new List(document.querySelector('#wyf-item-list'), document.querySelector('#wyf-list-item').innerHTML)
    list.run()
}
