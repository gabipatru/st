// main app for javascript mvc support

Controllers = {};

function addAction(controllerName, actionName, object) {
    // sanity checks
    if (typeof controllerName !== 'string' || controllerName.length === 0) {
        return false;
    }
    if (typeof actionName !== 'string' || actionName.length === 0) {
        return false;
    }
    if (typeof object !== 'object' && typeof object !== 'function') {
        return false;
    }
    
    // check if the controller exists
    if (typeof Controllers[controllerName] === 'undefined') {
        Controllers[controllerName] = {};
    }
    
    // add the controller
    Controllers[controllerName][actionName] = object;
}

$( 'document' ).ready( function() {
    if (Controllers[CONTROLLER_NAME] === undefined || Controllers[CONTROLLER_NAME] === null) {
        return;
    }

    // check for precontroller
    if (typeof Controllers[CONTROLLER_NAME]._precontroller === 'function') {
        Controllers[CONTROLLER_NAME]._precontroller();
    }

    // check for section prehook
    if (typeof Controllers[CONTROLLER_NAME]._prehook === 'function') {
        Controllers[CONTROLLER_NAME]._prehook();
    }
    
    if (Controllers[CONTROLLER_NAME][ACTION_NAME] === undefined || typeof Controllers[CONTROLLER_NAME][ACTION_NAME] === null) {
        return;
    }
    
    // check for consructor
    if (typeof Controllers[CONTROLLER_NAME][ACTION_NAME]._construct === 'function') {
        Controllers[CONTROLLER_NAME][ACTION_NAME]._construct();
    }
    
    // run main js part
    if (typeof Controllers[CONTROLLER_NAME][ACTION_NAME].run === 'function') {
        Controllers[CONTROLLER_NAME][ACTION_NAME].run();
    }
    
    // check for destructor
    if (typeof Controllers[CONTROLLER_NAME][ACTION_NAME]._destruct === 'function') {
        Controllers[CONTROLLER_NAME][ACTION_NAME]._destruct();
    }
    
});
