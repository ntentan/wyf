import Mustache from "mustache"

/**
 * Renders lists of items for the WYF framework.
 */
class List {
    
    #template
    #container
    #url
    
    /**
     * Creates a new instance of the constructor.
     */
    constructor(url, container, template) {
        this.#container = container
        this.#template = template
        this.#url = url
    }
    
    start() {
        fetch(this.#url)
    }
    
}

export function renderList() {
    new List().start()
}
