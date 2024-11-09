<?php
require_once 'HTTP/Request2.php';

$request = new HTTP_Request2();
$request->setUrl('https://ped3kl.api.infobip.com/sms/2/text/advanced');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Authorization' => 'App b5b425ff9ecae7eee945ab99f46ced95-d23f6bc8-b275-49ce-a019-e219f8d2e272',
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
));
$request->setBody('{"messages":[{"destinations":[{"to":"639665534768"}],"from":"447491163443","text":"Emergency alert sent. Please respond immediately!"}]}');

try {
    $response = $request->send();
    if ($response->getStatus() == 200) {
        echo json_encode(['status' => 'success', 'message' => 'SOS alert sent!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase()]);
    }
} catch (HTTP_Request2_Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
