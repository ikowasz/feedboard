import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    connect() {
        console.log("Hello from PostController", this.element);
        console.log(this.element.dataset)
    }
}