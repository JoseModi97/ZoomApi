<?php

namespace app\components\zoom;

use yii\base\Component;
use yii\httpclient\Client;

class ZoomComponent extends Component
{
    public $apiKey;
    public $apiSecret;
    public $oauthTokenUrl = 'https://zoom.us/oauth/token'; // Or your specific OAuth endpoint
    public $apiBaseUrl = 'https://api.zoom.us/v2/';

    private $_httpClient;

    public function init()
    {
        parent::init();

        if (empty($this->apiKey) || empty($this->apiSecret)) {
            // In a real application, you might throw an exception or log a warning
            // For now, we'll allow it for setup purposes
            \Yii::warning('Zoom API Key or Secret is not configured. Please add them to your configuration.', __METHOD__);
        }

        $this->_httpClient = new Client([
            'baseUrl' => $this->apiBaseUrl,
            'requestConfig' => [
                'format' => Client::FORMAT_JSON
            ],
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
    }

    /**
     * Placeholder for getting an OAuth access token.
     * In a real scenario, you would implement the OAuth 2.0 flow here.
     * @return string|null The access token or null if authentication fails.
     */
    protected function getAccessToken()
    {
        // This is a simplified placeholder.
        // A real implementation would involve:
        // 1. Checking if a valid token already exists (e.g., in cache or session).
        // 2. If not, requesting a new token from $this->oauthTokenUrl using apiKey and apiSecret.
        //    (The exact grant type will depend on your Zoom app setup - server-to-server OAuth or standard OAuth)
        // 3. Storing the new token and its expiry time.

        if (empty($this->apiKey) || empty($this->apiSecret)) {
            \Yii::error('Cannot obtain access token without API Key and Secret.', __METHOD__);
            return null;
        }

        // Simulate returning a token for now, replace with actual OAuth call
        // For Server-to-Server OAuth, you'd typically make a POST request to the token URL
        // with grant_type=account_credentials and your account_id.
        // For standard OAuth, it's a more complex flow.
        // Refer to ZOOM_OAUTH_SETUP_GUIDE.md for more details.

        \Yii::info('Simulating access token retrieval. Implement actual OAuth flow.', __METHOD__);
        return 'dummy-placeholder-access-token';
    }

    /**
     * Makes a request to the Zoom API.
     * @param string $endpoint The API endpoint (e.g., 'users', 'meetings/{meetingId}').
     * @param string $method The HTTP method (e.g., 'GET', 'POST', 'PATCH', 'DELETE').
     * @param array $data The data to send with the request (for POST, PUT, PATCH).
     * @param array $options Additional options for the HTTP client.
     * @return array|false The API response or false on failure.
     */
    public function makeRequest($endpoint, $method = 'GET', $data = [], $options = [])
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            \Yii::error('Failed to get Zoom access token.', __METHOD__);
            return false;
        }

        $request = $this->_httpClient->createRequest()
            ->setMethod($method)
            ->setUrl($endpoint)
            ->addHeaders(['Authorization' => 'Bearer ' . $accessToken]);

        if (!empty($data) && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
            $request->setData($data);
        } elseif (!empty($data) && strtoupper($method) === 'GET') {
            $request->setUrl([$endpoint] + $data); // Append data as query params for GET
        }

        if (!empty($options)) {
            $request->addOptions($options);
        }

        try {
            $response = $request->send();
            if ($response->isOk) {
                return $response->data;
            } else {
                \Yii::error("Zoom API request failed: {$response->getStatusCode()} - " . $response->content, __METHOD__);
                return false;
            }
        } catch (\yii\httpclient\Exception $e) {
            \Yii::error("Zoom API request exception: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * Dynamically creates and returns service instances.
     * @param string $name Service name (e.g., 'meetings', 'users')
     * @return object|null The service instance or null if not found.
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        $serviceClassBase = __NAMESPACE__ . '\\services\\';
        $className = $serviceClassBase . ucfirst($name);

        if (class_exists($className)) {
            // Check if it implements ZoomServiceInterface
            if (!in_array(ZoomServiceInterface::class, class_implements($className))) {
                 \Yii::error("Service class {$className} does not implement ZoomServiceInterface.", __METHOD__);
                 return null;
            }
            // Pass this ZoomComponent instance to the service
            return new $className(['zoom' => $this]);
        }

        try {
            return parent::__get($name);
        } catch (\yii\base\UnknownPropertyException $e) {
            \Yii::error("Zoom service or property '{$name}' not found.", __METHOD__);
            throw $e; // Re-throw if it's truly an unknown property
        }
    }
}
