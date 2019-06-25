<template>
    <button class="btn btn-block default" id="driver-picker-button">
        Open Google Drive
    </button>
</template>

<script>
    export default {
        data() {
            return {
                apiKey: null,
                appId: '427475754895',
                clientId: '427475754895-ojse61ui7cp31h9e58jjo61kmu484a23.apps.googleusercontent.com',
                scopes: ['https://www.googleapis.com/auth/drive.appdata'],

                pickerApiLoaded: false,
                oauthToken: null
            }
        },
        mounted() {
            this.loadPicker();
        },
        methods: {
            loadPicker() {
                gapi.load('auth', {'callback': this.onAuthApiLoad});
                gapi.load('picker', {'callback': this.onPickerApiLoad});
            },
            onAuthApiLoad() {
                window.gapi.auth.authorize(
                    {
                        'client_id': this.clientId,
                        'scope': this.scopes,
                        'immediate': false
                    },
                    this.handleAuthResult());
            },
            onPickerApiLoad() {
                this.pickerApiLoaded = true;
                this.createPicker();
            },
            handleAuthResult(result) {
                if (result && !result.error) {
                    this.oauthToken = result.access_token;
                    console.log(result)
                    this.createPicker();
                }
            },
            createPicker() {
                //if (this.pickerApiLoaded && this.oauthToken) {
                    let selectView = new google.picker.VIEW(google.picker.ViewId.DOCS);
                    let uploadView = new google.picker.DocsUploadView();

                    let picker = new google.picker.PickerBuilder()
                        .addView(selectView)
                        .addView(uploadView)
                        .setAppId(this.appId)
                        .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
                        .setOAuthToken(this.oauthToken)
                        .setCallback(pickerCallback)
                        .setButtonEl(document.getElementById('driver-picker-button'))
                        .build();
                    picker.setVisible();
                //}
            },
            pickerCallback(data) {
                if (data.action == google.picker.Action.PICKED) {
                    data.docs.forEach(function (file) {
                        console.log('The user selected: ' + file);
                        console.log(Object.getOwnPropertyNames(file));
                    }, this);
                }
            }
        }
    }
</script>