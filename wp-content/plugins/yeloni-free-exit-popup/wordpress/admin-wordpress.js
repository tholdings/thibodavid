//this file has functions to read from the wp-config setting textbox

window.defineAdminYetience = function() {
    yetience.loadApp(yetience.path + "/admin-interface", function() {
        //console.log('Scripts and styles loaded in ' + (Date.now() - autience_initiated_at) + ' milliseconds')
        angular.element(document).ready(function() {
            angular.bootstrap(document, ['yetienceApp'])

            document.getElementById('footer-thankyou').innerHTML += " Thank you for using <a href='http://www.yeloni.com' target='_blank'>Yeloni.</a>";
        })
    })

    yetience.platform = 'wordpress'

    yetience.website_saved = false
    yetience.readFromPlatform = function() {
        var encoded_setup = yetience.encoded_setup //document.getElementById('yetience_setup').value
        if (encoded_setup && encoded_setup.length > 1) {
            //decode and return
            var setup = decodeURIComponent(window.atob(encoded_setup))
            yetience.website_saved = true
            return JSON.parse(setup)
        } else {
            return {
                widgets: []
            }
        }
    }


    yetience.saveToPlatform = function(setup, message, label, disable_undo) {
        //if (!yetience.isCyclic(setup)) {
        document.getElementById('yetience_setup').value = window.btoa(encodeURIComponent(JSON.stringify(setup)))

        document.getElementById('autience-save-message').innerHTML = message
        if (!disable_undo) {
            document.getElementById('autience-undo-message').style.display = 'block'
        } else {
            document.getElementById('autience-undo-message').style.display = 'none'
        }
        document.getElementById('yetience_submit_button').getElementsByTagName('input')[0].value = label
        yetience.readyToSave()
            //}

    }

    yetience.readyToSave = function() {
        document.getElementById("yetience_submit_button").style.display = 'block'

        //scroll to top
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }

    yetience.isCyclic = function(obj) {
        var seenObjects = [];

        function detect(obj) {
            if (obj && typeof obj === 'object') {
                if (seenObjects.indexOf(obj) !== -1) {
                    return true;
                }
                seenObjects.push(obj);
                for (var key in obj) {
                    if (obj.hasOwnProperty(key) && detect(obj[key])) {
                        console.log(obj, 'cycle at ' + key);
                        return true;
                    }
                }
            }
            return false;
        }

        return detect(obj);
    }
}