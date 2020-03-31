export default class {

    /**
     * To Create Instance
     * @constructor
     * @param {object} $errors - Number of Error Object
     */
    constructor($errors) {
        this.$errors = $errors;
    }

    /**
     * To Check object has Error
     * @param {string} key - The name of key or field
     * @return {object}
     */
    has(key) {
        return this.$errors.hasOwnProperty(key);
    }

    /**
     * To get specific key object
     * @param {string} key - The name of key or field
     * @return {object}
     */
    get(key) {
        if (this.has(key)) return this.$errors[key];
    }

    /**
     * To first value of specific key object
     * @param {string} key - The name of key or field
     * @return {object}
     */
    first(key) {
        if (this.has(key) && Array.isArray(this.$errors[key])) return this.$errors[key][0];
    }

    /**
     * To return whole errors from object
     * @return {object}
     */
    all() {
        return this.$errors;
    }
}
