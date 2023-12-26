const credentials = {
    apiKey: 'e502aaa57fb0f962fde9e4304c7ef3e6ad50655301b9ea607fe5036a20928e4a',
    username: 'sandbox'
}

const AfricasTalking = require('africastalking')(credentials)

const airtime = AfricasTalking.AIRTIME

const options = {
    recipients: [{
        phoneNumber: "+250781255340",
        currencyCode: "FRW",
        amount: 100
    }]
};

airtime.send(options)
    .then( response => {
        console.log(response);
    })
    .catch( error => {
        console.log(error);
    });