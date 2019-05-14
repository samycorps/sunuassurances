// const base_url = 'http://localhost:8001';
// const call_agent_urls = {
//     "register" : base_url+'/api/callagent',
//     "login": base_url+'/api/uservalidate',
// };

// const call_centre_urls = {
//     "callform" : base_url+'/api/callcenter'
// };

// const base_url = 'http://localhost:8000/api';
const base_url = 'http://sunuassurancesnigeria.com/portal/api';
const api_urls = {
    "register" : `${base_url}/users`,
    "registerProfile": `${base_url}/profiles`,
    "login": `${base_url}/uservalidate`,
    "autoRegValidation": `${base_url}/autoreg`,
    "colours": `${base_url}/colours`,
    "vehiclebodies": `${base_url}/vehiclebodies`,
    "covertypes": `${base_url}/covertypes`,
    "getcovertypes": `${base_url}/getcovertypes`,
    "sectors": `${base_url}/sectors`,
    "states": `${base_url}/states`,
    "riskclasses": `${base_url}/riskclasses`,
    "banks": `${base_url}/banks`,
    "companybanks": `${base_url}/companybanks`,
    "locations": `${base_url}/locations`,
    "titles": `${base_url}/titles`,
    "occupations": `${base_url}/occupations`,
    "vehiclemodels": `${base_url}/vehiclemodels`,
    "getpolicy": `${base_url}/getpolicy`,
    "renewpolicy": `${base_url}/renewpolicy`,
    "getAdditionalPolicy": `${base_url}/getadditionalpolicy`,
    "renewPolicy": `${base_url}/renewpolicy`,
    "enquirypolicynumber": `${base_url}/enquirypolicynumber`,
    "vehicletransactiondetails": `${base_url}/vehicletransactiondetails`,
    "vehicletransactionpayment": `${base_url}/vehicletransactionpayment`,
    "vehicletransactionpolicy": `${base_url}/vehicletransactionpolicy`,
    "cities": `${base_url}/cities`,
    "states": `${base_url}/states`,
    "agentClientList": `${base_url}/agentclientlist`,
    "profileList": `${base_url}/profilelist`,
    "individualPolicyList": `${base_url}/individualpolicylist`,
    "getTransactionDetails": `${base_url}/gettransactiondetails`,
    "gettransactiondetailsbyprofile": `${base_url}/gettransactiondetailsbyprofile`,
};

const paystack = {
    "key" : 'pk_test_d4767bbd667ebe3cd8503f3f45c0766dda0f4bb9'
};
